<?php
/**
 * Asks for a review on WordPress.org once the plugin has done something useful for the site, the same way as Photo
 * Gallery and Data Tables: a form received its first message from a visitor, a form went live on a published page,
 * PRO reported its own moment, the forms collected many messages or, as a fallback, two weeks of use. The card is
 * shown inside the plugin's own pages only, to the users allowed to use the plugin, and not again once answered.
 *
 * PRO (or any add-on) reports its moments with do_action('cfs_review_moment', $type, ['form' => $id]).
 *
 * The old review notice of the plugin (removed in 1.9) saved its answers in the plugin options: start_usage,
 * done_rate_msg and hide_rate_msg. A site that answered it is asked once more only after a real success.
 */
class cfsReview
{
  const KEY = 'cfs_review';
  const REVIEW_URL = 'https://wordpress.org/support/plugin/contact-form-by-supsystic/reviews/#new-post';
  const SUPPORT_URL = 'https://supsystic.com/contact-us/';
  const SHORTCODE = 'supsystic-form';

  // No card in the first days after install, whatever happens
  const MIN_DAYS = 3;
  // Without any moment the card comes after two weeks of use
  const FALLBACK_DAYS = 14;
  const FORMS_MILESTONE = 3;
  const MESSAGES_MILESTONE = 10;
  const SNOOZE_DAYS = 30;
  const MAX_SNOOZES = 3;

  /**
   * Moments in the order the card picks them: the first one recorded wins.
   * @var string[]
   */
  private $momentTypes = ['lead', 'live', 'subscribe'];

  /** @var array|null */
  private $state;

  /** @var array|null|false False until the card is built for the current request */
  private $card = false;

  public function init()
  {
    add_filter('do_shortcode_tag', [$this, 'onShortcodeOutput'], 10, 3);
    add_action('wp_after_insert_post', [$this, 'onPostSaved'], 10, 2);
    add_action('cfs_review_moment', [$this, 'recordMoment'], 10, 2);
    // A message sent through a form (the plugin's own filter of the success message)
    dispatcherCfs::addFilter('contactSuccessMsg', [$this, 'onContactSuccess'], 99, 2);

    if (is_admin()) {
      add_filter('plugin_row_meta', [$this, 'addPluginRowLink'], 10, 2);
      add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
      add_action('wp_ajax_cfs_review_respond', [$this, 'ajaxRespond']);
    }
  }

  /**
   * A form is rendered on a published page that a visitor (or the site owner) opens: the form is live.
   * @param string|false $output
   * @param string $tag
   * @param array|string $attr
   * @return string|false
   */
  public function onShortcodeOutput($output, $tag, $attr)
  {
    if ($tag !== self::SHORTCODE || !is_string($output) || $output === '' || $this->hasMoment('live')) {
      return $output;
    }
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST) || is_preview() || is_feed() || is_customize_preview()) {
      return $output;
    }
    $formId = $this->formIdOf($attr);
    if (!$formId || !is_singular() || !$this->isUserForm($formId)) {
      return $output;
    }
    $post = get_queried_object();
    if ($post instanceof WP_Post && $post->post_status === 'publish' && !post_password_required($post)) {
      $this->addMoment('live', ['form' => $formId, 'post' => (int) $post->ID]);
    }
    return $output;
  }

  /**
   * A post with a form shortcode is published: the form is live even before anyone opens the page.
   * @param int $postId
   * @param WP_Post $post
   */
  public function onPostSaved($postId, $post)
  {
    if (!$post instanceof WP_Post || $post->post_status !== 'publish' || $this->hasMoment('live')) {
      return;
    }
    if (wp_is_post_revision($postId) || wp_is_post_autosave($postId) || !is_post_type_viewable($post->post_type)) {
      return;
    }
    $content = (string) $post->post_content;
    if ($content === '' || strpos($content, self::SHORTCODE) === false) {
      return;
    }
    if (preg_match_all('/' . get_shortcode_regex([self::SHORTCODE]) . '/', $content, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $shortcode) {
        $formId = $this->formIdOf(shortcode_parse_atts($shortcode[3]));
        if ($formId && $this->isUserForm($formId)) {
          $this->addMoment('live', ['form' => $formId, 'post' => (int) $postId]);
          return;
        }
      }
    }
  }

  /**
   * The first message that a visitor (not someone who manages the site) sent through a form.
   * @param string $message
   * @param array $form
   * @return string
   */
  public function onContactSuccess($message, $form = [])
  {
    if (!$this->hasMoment('lead') && !current_user_can('manage_options') && is_array($form) && !empty($form['id']) && $this->isUserForm((int) $form['id'])) {
      $this->addMoment('lead', ['form' => (int) $form['id']]);
    }
    return $message;
  }

  /**
   * Handler of the "cfs_review_moment" action.
   * @param string $type One of the moment types (subscribe)
   * @param array $data ['form' => id]
   */
  public function recordMoment($type, $data = [])
  {
    if (!is_string($type) || in_array($type, ['live', 'lead'], true) || !in_array($type, $this->momentTypes, true) || $this->hasMoment($type)) {
      return;
    }
    $this->addMoment($type, ['form' => is_array($data) && isset($data['form']) ? (int) $data['form'] : 0]);
  }

  /**
   * The card for the current user on the current page, or null when there is nothing to show.
   * @return array|null
   */
  public function getCard()
  {
    if ($this->card !== false) {
      return $this->card;
    }
    $this->card = null;

    if (!frameCfs::_()->isAdminPlugPage() || !apply_filters('cfs_review_request', true)) {
      return null;
    }
    $tab = frameCfs::_()->getModule('options')->getActiveTab();
    if (!in_array($tab, ['overview', 'forms', 'forms_contacts', 'settings'], true)) {
      return null;
    }
    $userId = get_current_user_id();
    if (!$userId || !$this->currentUserCanUsePlugin()) {
      return null;
    }
    $answer = $this->getAnswer($userId);
    if ($answer['status'] === 'rated' || $answer['status'] === 'dismissed') {
      return null;
    }
    if ($answer['status'] === 'snoozed' && $answer['until'] > time()) {
      return null;
    }
    $state = $this->getState();
    if (time() - $state['since'] < self::MIN_DAYS * DAY_IN_SECONDS) {
      return null;
    }
    $moment = $this->pickMoment($state);
    if (!$moment) {
      return null;
    }
    $this->card = array_merge($moment, [
      'reviewUrl' => self::REVIEW_URL,
      'supportUrl' => self::SUPPORT_URL,
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('cfs_review'),
      'isOverview' => $tab === 'overview',
    ]);
    return $this->card;
  }

  public function enqueueAssets()
  {
    if (!$this->getCard()) {
      return;
    }
    $promo = frameCfs::_()->getModule('supsystic_promo');
    $ver = CFS_VERSION . '-review-2';
    wp_enqueue_style('cfs-review', $promo->getModPath() . 'css/admin.review.css', [], $ver);
    wp_enqueue_script('cfs-review', $promo->getModPath() . 'js/admin.review.js', [], $ver, true);
  }

  /**
   * The card, at the top of the content of the plugin page (action discountMsg of the page layout)
   */
  public function printCard()
  {
    $card = $this->getCard();
    if (!$card) {
      return;
    }
    // Every value is escaped by the template
    echo frameCfs::_()->getModule('supsystic_promo')->getView()->getReviewCard($card);
  }

  public function ajaxRespond()
  {
    check_ajax_referer('cfs_review', '_wpnonce');
    if (!$this->currentUserCanUsePlugin()) {
      wp_send_json_error(null, 403);
    }
    $answer = isset($_POST['answer']) ? sanitize_key(wp_unslash($_POST['answer'])) : '';
    if (!$this->saveAnswer(get_current_user_id(), $answer)) {
      wp_send_json_error(null, 400);
    }
    wp_send_json_success();
  }

  /**
   * Saves the answer of a user. "rate" and "done" end the requests; "later", "support" and "close" put them off for a
   * month, and the third time (or the first time on a site that answered the old review notice) for good.
   * @param int $userId
   * @param string $answer
   * @return bool
   */
  public function saveAnswer($userId, $answer)
  {
    $userId = (int) $userId;
    if (!$userId || !is_string($answer)) {
      return false;
    }
    $now = time();
    if ($answer === 'rate' || $answer === 'done') {
      update_user_meta($userId, self::KEY, ['status' => 'rated', 'answer' => $answer, 'time' => $now]);
      return true;
    }
    if ($answer === 'later' || $answer === 'support' || $answer === 'close') {
      $snoozes = $this->getAnswer($userId)['snoozes'] + 1;
      $state = $this->getState();
      $final = $snoozes >= self::MAX_SNOOZES || $state['legacy'];
      update_user_meta($userId, self::KEY, [
        'status' => $final ? 'dismissed' : 'snoozed',
        'answer' => $answer,
        'until' => $now + self::SNOOZE_DAYS * DAY_IN_SECONDS,
        'snoozes' => $snoozes,
        'time' => $now,
      ]);
      return true;
    }
    return false;
  }

  /**
   * "Rate us" link in the plugin's row on the Plugins page.
   * @param array $links
   * @param string $file
   * @return array
   */
  public function addPluginRowLink($links, $file)
  {
    if (!is_array($links) || $file !== plugin_basename(CFS_DIR . CFS_MAIN_FILE)) {
      return $links;
    }
    $links[] = sprintf(
      '<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s <span style="color:#ffb900;letter-spacing:1px;" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span></a>',
      esc_url(self::REVIEW_URL),
      esc_attr__('Rate Contact Form on WordPress.org', CFS_LANG_CODE),
      esc_html__('Rate us', CFS_LANG_CODE)
    );
    return $links;
  }

  /**
   * Site state: when the plugin started to be used, the recorded moments and whether the old review notice was answered.
   * @return array
   */
  public function getState()
  {
    if ($this->state !== null) {
      return $this->state;
    }
    $state = get_option(self::KEY);
    if (!is_array($state) || !isset($state['since']) || !is_int($state['since'])) {
      $state = $this->createState();
      update_option(self::KEY, $state, true);
    }
    $state['legacy'] = !empty($state['legacy']);
    $state['moments'] = isset($state['moments']) && is_array($state['moments']) ? $state['moments'] : [];
    $this->state = $state;
    return $this->state;
  }

  /**
   * @param int $userId
   * @return array ['status' => ''|'snoozed'|'rated'|'dismissed', 'until' => int, 'snoozes' => int]
   */
  public function getAnswer($userId)
  {
    $answer = get_user_meta((int) $userId, self::KEY, true);
    $answer = is_array($answer) ? $answer : [];
    return [
      'status' => isset($answer['status']) && is_string($answer['status']) ? $answer['status'] : '',
      'until' => isset($answer['until']) ? (int) $answer['until'] : 0,
      'snoozes' => isset($answer['snoozes']) ? max(0, (int) $answer['snoozes']) : 0,
    ];
  }

  /**
   * The same rule as the plugin's admin pages: the capability of the plugin menu.
   * @return bool
   */
  private function currentUserCanUsePlugin()
  {
    $menu = frameCfs::_()->getModule('adminmenu');
    return current_user_can($menu ? $menu->getMainCap() : 'manage_options');
  }

  /**
   * The first state of a site. Existing sites keep their history: the start is the first form (or the first use the
   * old review notice counted from), and an answered old notice is remembered.
   * @return array
   */
  private function createState()
  {
    global $wpdb;
    $now = time();
    $since = $now;
    $legacy = false;

    $opts = get_option(CFS_CODE . '_opts_data');
    if (is_array($opts)) {
      foreach (['done_rate_msg', 'hide_rate_msg'] as $optKey) {
        if (!empty($opts[$optKey]['value'])) {
          $legacy = true;
        }
      }
      if (!empty($opts['start_usage']['value']) && is_numeric($opts['start_usage']['value']) && (int) $opts['start_usage']['value'] > 0) {
        $since = min($since, (int) $opts['start_usage']['value']);
      }
    }
    $suppress = $wpdb->suppress_errors(true);
    $firstForm = $wpdb->get_var("SELECT MIN(date_created) FROM {$wpdb->prefix}cfs_forms WHERE original_id <> 0 AND ab_id = 0");
    $wpdb->suppress_errors($suppress);
    if ($firstForm && ($firstTime = strtotime($firstForm)) && $firstTime > 0) {
      $since = min($since, $firstTime);
    }
    return ['since' => (int) $since, 'legacy' => $legacy, 'moments' => []];
  }

  /**
   * @param array $state
   * @return array|null
   */
  private function pickMoment(array $state)
  {
    foreach ($this->momentTypes as $type) {
      if (empty($state['moments'][$type]) || !is_array($state['moments'][$type])) {
        continue;
      }
      $moment = $state['moments'][$type];
      $formId = isset($moment['form']) ? (int) $moment['form'] : 0;
      $form = $this->isUserForm($formId) ? $this->getFormLabel($formId) : null;
      if ($form === null) {
        // The form was deleted since then
        continue;
      }
      $form = trim($form) !== '' ? trim($form) : '#' . $formId;

      if ($type === 'lead') {
        return [
          'type' => 'lead',
          'eyebrow' => __('First message', CFS_LANG_CODE),
          /* translators: %s: form name */
          'title' => sprintf(__('“%s” got its first message from a visitor', CFS_LANG_CODE), $form),
          'text' => __('Your form is doing its job: people can reach you right from your site.', CFS_LANG_CODE),
          'link' => frameCfs::_()->getModule('options')->getTabUrl('forms_contacts'),
          'linkText' => __('Contacts', CFS_LANG_CODE),
        ];
      }
      if ($type === 'live') {
        $post = isset($moment['post']) ? get_post((int) $moment['post']) : null;
        if (!$post instanceof WP_Post || $post->post_status !== 'publish') {
          continue;
        }
        $postTitle = get_the_title($post);
        return [
          'type' => 'live',
          'eyebrow' => __('Form published', CFS_LANG_CODE),
          /* translators: 1: form name, 2: page title */
          'title' => $postTitle !== '' ? sprintf(__('“%1$s” is live on “%2$s”', CFS_LANG_CODE), $form, $postTitle) : sprintf(__('“%s” is live on your site', CFS_LANG_CODE), $form),
          'text' => __('Visitors can now send you messages right from this page.', CFS_LANG_CODE),
          'link' => get_permalink($post),
          'linkText' => __('View page', CFS_LANG_CODE),
        ];
      }
      return [
        'type' => 'subscribe',
        'eyebrow' => __('New subscriber', CFS_LANG_CODE),
        /* translators: %s: form name */
        'title' => sprintf(__('“%s” added its first subscriber to your mailing list', CFS_LANG_CODE), $form),
        'text' => __('Every message can grow your list now.', CFS_LANG_CODE),
      ];
    }

    // A site that answered the old review notice is asked once more only after a real success
    if ($state['legacy']) {
      return null;
    }
    $messages = $this->countMessages();
    if ($messages >= self::MESSAGES_MILESTONE) {
      return [
        'type' => 'messages',
        'eyebrow' => __('Milestone', CFS_LANG_CODE),
        /* translators: %d: number of messages */
        'title' => sprintf(__('Your forms have sent you %d messages', CFS_LANG_CODE), $messages),
        'text' => __('Nice work. Your visitors find it easy to reach you.', CFS_LANG_CODE),
      ];
    }
    $forms = $this->countForms();
    if ($forms >= self::FORMS_MILESTONE) {
      return [
        'type' => 'forms',
        'eyebrow' => __('Milestone', CFS_LANG_CODE),
        /* translators: %d: number of forms */
        'title' => sprintf(__('You have built %d forms with Contact Form', CFS_LANG_CODE), $forms),
        'text' => __('Nice work. Every page can have the form it needs.', CFS_LANG_CODE),
      ];
    }
    $days = (int) floor((time() - $state['since']) / DAY_IN_SECONDS);
    if ($forms >= 1 && $days >= self::FALLBACK_DAYS) {
      $weeks = (int) floor($days / 7);
      return [
        'type' => 'time',
        'eyebrow' => __('Thank you for using Contact Form', CFS_LANG_CODE),
        /* translators: %d: number of weeks */
        'title' => $weeks < 9 ? sprintf(__('Contact Form has been working on your site for %d weeks', CFS_LANG_CODE), $weeks) : __('Contact Form has been working on your site for a while now', CFS_LANG_CODE),
        'text' => __('We hope it makes talking to your visitors easy.', CFS_LANG_CODE),
      ];
    }
    return null;
  }

  /**
   * @param string $type
   * @return bool
   */
  private function hasMoment($type)
  {
    $state = $this->getState();
    return !empty($state['moments'][$type]);
  }

  /**
   * @param string $type
   * @param array $data
   */
  private function addMoment($type, array $data)
  {
    $state = $this->getState();
    $state['moments'][$type] = array_merge($data, ['time' => time()]);
    $this->state = $state;
    update_option(self::KEY, $state, true);
  }

  /**
   * @param array|string $attr Shortcode attributes
   * @return int
   */
  private function formIdOf($attr)
  {
    if (!is_array($attr)) {
      return 0;
    }
    if (!empty($attr['id']) && (int) $attr['id'] > 0) {
      return (int) $attr['id'];
    }
    // Some editors turn the space into %20: [supsystic-form id%20=5]
    if (isset($attr[0]) && is_string($attr[0]) && strpos($attr[0], '=') !== false) {
      $parts = explode('=', $attr[0]);
      return isset($parts[1]) ? max(0, (int) $parts[1]) : 0;
    }
    return 0;
  }

  /**
   * A form the site owner made (the templates are rows with original_id 0)
   * @param int $id
   * @return bool
   */
  private function isUserForm($id)
  {
    global $wpdb;
    $originalId = $wpdb->get_var($wpdb->prepare("SELECT original_id FROM {$wpdb->prefix}cfs_forms WHERE id = %d", (int) $id));
    return $originalId !== null && (int) $originalId !== 0;
  }

  /**
   * @param int $id
   * @return string|null Null when there is no such form
   */
  private function getFormLabel($id)
  {
    global $wpdb;
    if ($id < 1) {
      return null;
    }
    $label = $wpdb->get_var($wpdb->prepare("SELECT label FROM {$wpdb->prefix}cfs_forms WHERE id = %d", $id));
    return $label === null ? null : (string) $label;
  }

  /**
   * Forms made by the user (the templates are rows with original_id 0)
   * @return int
   */
  private function countForms()
  {
    global $wpdb;
    $suppress = $wpdb->suppress_errors(true);
    $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}cfs_forms WHERE original_id <> 0 AND ab_id = 0");
    $wpdb->suppress_errors($suppress);
    return $count;
  }

  /**
   * Successful sends of all forms (the "Actions" column of the forms list)
   * @return int
   */
  private function countMessages()
  {
    global $wpdb;
    $suppress = $wpdb->suppress_errors(true);
    $count = (int) $wpdb->get_var("SELECT SUM(actions) FROM {$wpdb->prefix}cfs_forms WHERE original_id <> 0");
    $wpdb->suppress_errors($suppress);
    return $count;
  }
}

<?php
class supsystic_promoCfs extends moduleCfs
{
  // CES, the Customer Engagement System: where the forms of Contact Form move on
  const CES_FILE = 'popup-by-supsystic/pps.php';
  const CES_IMPORT_VERSION = '2.1.0';
  // The offer of Contact Form users on CES Pro: 30% when Contact Form PRO works with an active license (isPro(): the PRO
  // modules are on), 15% otherwise - also when the PRO plugin is installed and active without a license.
  // No code and no discount while CES Pro runs with a working license.
  const CES_COUPON_PRO = 'CFPRO30';
  const CES_COUPON_PRO_DISCOUNT = 30;
  const CES_COUPON_FREE = 'CFFREE15';
  const CES_COUPON_FREE_DISCOUNT = 15;
  // User meta: 1 when the user folded the CES banner into a strip
  const CES_BANNER_FOLDED = 'cfs_ces_banner_folded';
  private $_mainLink = '';
  private $_assetsUrl = '';
  public function __construct($d)
  {
    parent::__construct($d);
    $this->getMainLink();
  }
  public function init()
  {
    parent::init();
    $this->registerDeactivationDialog();
    if (is_admin()) {
      add_action('init', [$this, 'checkWelcome']);
      // PRO feature windows of the free version (one window per PRO feature, opened by data-cfs-upsell="KEY")
      add_action('admin_enqueue_scripts', [$this, 'enqueueUpsellAssets']);
      add_action('admin_footer', [$this, 'printUpsellWindows']);
      add_action('admin_post_cfs_ces_activate', [$this, 'activateCes']);
      // The CES banner folds into a strip; the choice is kept for every user
      add_action('wp_ajax_cfs_ces_banner_fold', [$this, 'ajaxFoldCesBanner']);
    }
    $this->weLoveYou();
    // Review request by moments (a first message, a form on a published page...), as in Photo Gallery and Data Tables
    $this->getReview()->init();
    // The banner about the move to CES stands above everything, on every plugin page
    dispatcherCfs::addAction('discountMsg', [$this, 'printCesBanner'], 5);
    dispatcherCfs::addAction('discountMsg', [$this, 'printReviewCard']);
    dispatcherCfs::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    dispatcherCfs::addFilter('showTplsList', [$this, 'checkProTpls']);
    dispatcherCfs::addFilter('formsEditTabs', [$this, 'addEditTab']);
  }
  public function addEditTab($tabs)
  {
    if (!$this->isPro()) {
      $tabs['cfsFormConditionalLogic'] = [
        'title' => __('Conditional Logic', CFS_LANG_CODE),
        'content' => $this->getView()->getProTabCard('logic'),
        'fa_icon' => 'fa-flask',
        'sort_order' => 90,
      ];
    }
    return $tabs;
  }
  public function addAdminTab($tabs)
  {
    $tabs['overview'] = [
      'label' => __('Overview', CFS_LANG_CODE),
      'callback' => [$this, 'getOverviewTabContent'],
      'fa_icon' => 'fa-info',
      'sort_order' => 5,
    ];
    return $tabs;
  }
  public function addSubDestList($subDestList)
  {
    if (!$this->isPro()) {
      $subDestList = array_merge($subDestList, [
        'constantcontact' => ['label' => __('Constant Contact - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'campaignmonitor' => ['label' => __('Campaign Monitor - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'verticalresponse' => ['label' => __('Vertical Response - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'sendgrid' => ['label' => __('SendGrid - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'get_response' => ['label' => __('GetResponse - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'icontact' => ['label' => __('iContact - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'activecampaign' => ['label' => __('Active Campaign - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'mailrelay' => ['label' => __('Mailrelay - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'arpreach' => ['label' => __('arpReach - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'sgautorepondeur' => ['label' => __('SG Autorepondeur - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'benchmarkemail' => ['label' => __('Benchmark - PRO', CFS_LANG_CODE), 'require_confirm' => true],
        'infusionsoft' => ['label' => __('InfusionSoft - PRO', CFS_LANG_CODE), 'require_confirm' => false],
        'salesforce' => ['label' => __('SalesForce - Web-to-Lead - PRO', CFS_LANG_CODE), 'require_confirm' => false],
        'convertkit' => ['label' => __('ConvertKit - PRO', CFS_LANG_CODE), 'require_confirm' => false],
        'myemma' => ['label' => __('Emma - PRO', CFS_LANG_CODE), 'require_confirm' => false],
      ]);
    }
    return $subDestList;
  }
  public function getOverviewTabContent()
  {
    return $this->getView()->getOverviewTabContent();
  }
  public function showWelcomePage()
  {
    $this->getView()->showWelcomePage();
  }
  private function _preparePromoLink($link, $ref = '')
  {
    if (empty($ref)) {
      $ref = 'user';
    }
    return $link;
  }
  public function weLoveYou()
  {
    if (!$this->isPro()) {
      dispatcherCfs::addFilter('formsEditTabs', [$this, 'addUserExp'], 10, 2);
    }
  }
  public function showAdditionalmainAdminShowOnOptions($forms)
  {
    $this->getView()->showAdditionalmainAdminShowOnOptions($forms);
  }
  public function addUserExp($tabs, $forms)
  {
    $modPath = $this->getAssetsUrl();
    if (!frameCfs::_()->getModule('ab_testing')) {
      $tabs['cfsFormAbTesting'] = [
        'title' => __('Testing', CFS_LANG_CODE),
        'content' => $this->getView()->getProTabCard('abtesting'),
        'icon_content' => '<b>A/B</b>',
        'avoid_hide_icon' => true,
        'sort_order' => 60,
      ];
    }
    if (!frameCfs::_()->getModule('subscribe')) {
      $tabs['cfsFormSubscribe'] = [
        'title' => __('Subscribe', CFS_LANG_CODE),
        'content' => $this->getView()->getProTabCard('subscribe'),
        'fa_icon' => 'fa-users',
        'sort_order' => 50,
      ];
    }
    return $tabs;
  }
  public function addUserExpDesign($tabs)
  {
    $tabs['cfsFormLayeredForm'] = [
      'title' => __('Form Location', CFS_LANG_CODE),
      'content' => $this->getView()->getLayeredStylePromo(),
      'fa_icon' => 'fa-arrows',
      'sort_order' => 15,
    ];
    return $tabs;
  }
  /**
   * Public shell for private method
   */
  public function preparePromoLink($link, $ref = '')
  {
    return $this->_preparePromoLink($link, $ref);
  }
  public function getMainLink()
  {
    if (empty($this->_mainLink)) {
      $affiliateQueryString = '';
      $this->_mainLink = 'https://supsystic.com/plugins/contact-form-plugin/' . $affiliateQueryString;
    }
    return $this->_mainLink;
  }
  public function generateMainLink($params = '')
  {
    $mainLink = $this->getMainLink();
    if (!empty($params)) {
      return $mainLink . (strpos($mainLink, '?') ? '&' : '?') . $params;
    }
    return $mainLink;
  }
  public function isPro()
  {
    static $isPro;
    if (is_null($isPro)) {
      // license is always active with PRO - even if license key was not entered,
      $isPro = frameCfs::_()->getModule('add_fields') ? true : false;
    }
    return $isPro;
  }
  public function getAssetsUrl()
  {
    if (empty($this->_assetsUrl)) {
      $this->_assetsUrl = frameCfs::_()->getModule('forms')->getAssetsUrl() . 'promo/';
    }
    return $this->_assetsUrl;
  }
  public function checkWelcome()
  {
    $from = reqCfs::getVar('from', 'get');
    $pl = reqCfs::getVar('pl', 'get');
    if ($from == 'welcome-page' && $pl == CFS_CODE && frameCfs::_()->getModule('user')->isAdmin()) {
      $welcomeSent = (int) get_option(CFS_DB_PREF . 'welcome_sent');
      if (!$welcomeSent) {
        update_option(CFS_DB_PREF . 'welcome_sent', 1);
      }
    }
  }
  /**
   * @return cfsReview
   */
  public function getReview()
  {
    static $review;
    if (!$review) {
      require_once $this->getModDir() . 'classes' . DS . 'review.php';
      $review = new cfsReview();
    }
    return $review;
  }
  public function printReviewCard()
  {
    $this->getReview()->printCard();
  }
  /**
   * PRO features of the plugin for the upsell windows, the PRO plaques and the Overview. The medium is what the old
   * PRO labels already send as utm_medium (admin.upsell.js finds the feature by it).
   * @return array
   */
  public function getUpsellFeatures()
  {
    return [
      'logic' => [
        'ces' => ['tier' => 'free', 'text' => __('Conditional logic is free in CES forms: show, hide and change fields by the answers.', CFS_LANG_CODE)],
        'icon' => 'fa-code-fork',
        'chip' => __('Conditional logic', CFS_LANG_CODE),
        'medium' => 'conditional_logic',
        'title' => __('Show each visitor only the questions that matter', CFS_LANG_CODE),
        'text' => __('Fields appear, hide or change by the answers given above, so a long form stays short and clear.', CFS_LANG_CODE),
        'points' => [__('Show or hide any field by any answer', CFS_LANG_CODE), __('Several rules joined with AND or OR', CFS_LANG_CODE), __('Works with every field type', CFS_LANG_CODE), __('Two engines: classic rules and v2 with groups', CFS_LANG_CODE)],
        'uses' => [__('Quotes and orders', CFS_LANG_CODE), __('Surveys', CFS_LANG_CODE), __('Support requests', CFS_LANG_CODE)],
      ],
      'abtesting' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-flask',
        'chip' => __('A/B testing', CFS_LANG_CODE),
        'medium' => 'abtesting',
        'title' => __('Find the form that gets more messages', CFS_LANG_CODE),
        'text' => __('Run two or more versions of a form at once: each visitor sees one of them, and the plugin counts the results.', CFS_LANG_CODE),
        'points' => [__('Make test versions of a form in one click', CFS_LANG_CODE), __('Visitors see the versions in turn', CFS_LANG_CODE), __('Views, sends and conversion for every version', CFS_LANG_CODE), __('Keep the version that wins', CFS_LANG_CODE)],
        'uses' => [__('Landing pages', CFS_LANG_CODE), __('Lead generation', CFS_LANG_CODE), __('Marketing teams', CFS_LANG_CODE)],
      ],
      'subscribe' => [
        'ces' => ['tier' => 'free', 'text' => __('In CES every sender becomes a contact in its CRM with tags, and newsletters and automations go out from CES itself.', CFS_LANG_CODE)],
        'icon' => 'fa-users',
        'chip' => __('Email marketing', CFS_LANG_CODE),
        'medium' => 'subscribe',
        'title' => __('Grow your mailing list with every message', CFS_LANG_CODE),
        'text' => __('Send the people who fill in your form straight to your mailing service, with a Subscribe checkbox when you need their consent.', CFS_LANG_CODE),
        'points' => [__('Mailchimp, AWeber, MailPoet, Brevo, ActiveCampaign, GetResponse, ConvertKit and more', CFS_LANG_CODE), __('Pick the list for each form', CFS_LANG_CODE), __('A Subscribe checkbox field for consent', CFS_LANG_CODE), __('WordPress users as a list too', CFS_LANG_CODE)],
        'uses' => [__('Newsletters', CFS_LANG_CODE), __('Online shops', CFS_LANG_CODE), __('Bloggers', CFS_LANG_CODE)],
      ],
      'publish' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-thumb-tack',
        'chip' => __('Publish content', CFS_LANG_CODE),
        'medium' => 'publish_content',
        'title' => __('Turn form messages into posts', CFS_LANG_CODE),
        'text' => __('Visitors send a story, a review or an event, and the form creates a post from it.', CFS_LANG_CODE),
        'points' => [__('Posts, pages or any post type', CFS_LANG_CODE), __('Form fields become the title, the text and the excerpt', CFS_LANG_CODE), __('Categories and the post status you choose', CFS_LANG_CODE), __('A link to the new post in the email you get', CFS_LANG_CODE)],
        'uses' => [__('Guest posts', CFS_LANG_CODE), __('Reviews', CFS_LANG_CODE), __('Event listings', CFS_LANG_CODE)],
      ],
      'registration' => [
        'ces' => ['tier' => 'free', 'text' => __('CES has sign-up, login and password reset forms for WordPress users, free.', CFS_LANG_CODE)],
        'icon' => 'fa-user-plus',
        'chip' => __('User registration', CFS_LANG_CODE),
        'medium' => 'registration',
        'title' => __('Let visitors sign up with your own form', CFS_LANG_CODE),
        'text' => __('The form creates a WordPress user from the answers, with the role you choose.', CFS_LANG_CODE),
        'points' => [__('Fields become the login, the email and the name', CFS_LANG_CODE), __('Choose the role of new users', CFS_LANG_CODE), __('Your own design instead of the standard sign-up page', CFS_LANG_CODE)],
        'uses' => [__('Communities', CFS_LANG_CODE), __('Courses', CFS_LANG_CODE), __('Members areas', CFS_LANG_CODE)],
      ],
      'fbconvert' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-facebook',
        'chip' => __('Facebook Conversion', CFS_LANG_CODE),
        'medium' => 'fb_convert',
        'title' => __('Count your form leads in Facebook Ads', CFS_LANG_CODE),
        'text' => __('Every successful send reports a conversion to the Meta Pixel, so your ads see which campaigns bring messages.', CFS_LANG_CODE),
        'points' => [__('A conversion event on every successful send', CFS_LANG_CODE), __('Turn it on for the forms you need', CFS_LANG_CODE), __('Works with the redirect after sending', CFS_LANG_CODE)],
        'uses' => [__('Ad campaigns', CFS_LANG_CODE), __('Lead generation', CFS_LANG_CODE), __('Agencies', CFS_LANG_CODE)],
      ],
      'icons' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-star-o',
        'chip' => __('Field icons', CFS_LANG_CODE),
        'medium' => 'field_icon',
        'title' => __('Add icons to your fields', CFS_LANG_CODE),
        'text' => __('Pick an icon for any field from hundreds of icons and set its size and color.', CFS_LANG_CODE),
        'points' => [__('Hundreds of icons with a search', CFS_LANG_CODE), __('Five sizes and your own color', CFS_LANG_CODE), __('Colors of the selected stars of the Rating field', CFS_LANG_CODE)],
        'uses' => [__('Modern designs', CFS_LANG_CODE), __('Mobile forms', CFS_LANG_CODE), __('Brand sites', CFS_LANG_CODE)],
      ],
      'presets' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-magic',
        'chip' => __('Value presets', CFS_LANG_CODE),
        'medium' => 'value_preset',
        'title' => __('Learn more about every message', CFS_LANG_CODE),
        'text' => __('Fields can carry the visitor IP, the country, the page title or its address without asking for them.', CFS_LANG_CODE),
        'points' => [__('IP address and country of the visitor', CFS_LANG_CODE), __('Title and address of the page with the form', CFS_LANG_CODE), __('Use them in hidden fields', CFS_LANG_CODE)],
        'uses' => [__('Sales teams', CFS_LANG_CODE), __('Multi-page sites', CFS_LANG_CODE), __('Support', CFS_LANG_CODE)],
      ],
      'file' => [
        'ces' => ['tier' => 'pro', 'text' => __('File and multi-file upload fields are in CES Pro.', CFS_LANG_CODE)],
        'icon' => 'fa-upload',
        'chip' => __('File upload', CFS_LANG_CODE),
        'medium' => 'field_file',
        'title' => __('Receive files with your forms', CFS_LANG_CODE),
        'text' => __('Visitors attach documents, photos or archives, and the files come to your email and stay with the contact.', CFS_LANG_CODE),
        'points' => [__('Allowed file types and size limits', CFS_LANG_CODE), __('Several files in one field', CFS_LANG_CODE), __('Files attached to the email you get', CFS_LANG_CODE)],
        'uses' => [__('Job applications', CFS_LANG_CODE), __('Quotes', CFS_LANG_CODE), __('Support', CFS_LANG_CODE)],
      ],
      'rating' => [
        'ces' => ['tier' => 'pro', 'text' => __('Rating, URL, range and color fields are in CES Pro.', CFS_LANG_CODE)],
        'icon' => 'fa-star',
        'chip' => __('Rating field', CFS_LANG_CODE),
        'medium' => 'field_rating',
        'title' => __('Ask for a rating in stars', CFS_LANG_CODE),
        'text' => __('A star rating field in your colors, with the ratings in the form statistics.', CFS_LANG_CODE),
        'points' => [__('Stars in your own colors and size', CFS_LANG_CODE), __('Ratings in the statistics of the form', CFS_LANG_CODE), __('Works with any other fields', CFS_LANG_CODE)],
        'uses' => [__('Feedback', CFS_LANG_CODE), __('Restaurants and hotels', CFS_LANG_CODE), __('Courses', CFS_LANG_CODE)],
      ],
      'captcha' => [
        'ces' => ['tier' => 'free', 'text' => __('CES forms have a honeypot and CAPTCHA for free.', CFS_LANG_CODE)],
        'icon' => 'fa-shield',
        'chip' => __('Smart spam protection', CFS_LANG_CODE),
        'medium' => 'field_recaptcha_v3',
        'title' => __('Stop spam without puzzles', CFS_LANG_CODE),
        'text' => __('reCAPTCHA v3, hCaptcha and Cloudflare Turnstile check visitors in the background, people just send the form.', CFS_LANG_CODE),
        'points' => [__('Google reCAPTCHA v3', CFS_LANG_CODE), __('hCaptcha', CFS_LANG_CODE), __('Cloudflare Turnstile', CFS_LANG_CODE), __('No pictures to click for real people', CFS_LANG_CODE)],
        'uses' => [__('Busy sites', CFS_LANG_CODE), __('Shops', CFS_LANG_CODE), __('Any public form', CFS_LANG_CODE)],
      ],
      'wpfields' => [
        'ces' => ['tier' => 'pro', 'text' => __('Lists of posts, pages, categories, tags and WooCommerce products are fields of CES Pro.', CFS_LANG_CODE)],
        'icon' => 'fa-wordpress',
        'chip' => __('WordPress fields', CFS_LANG_CODE),
        'medium' => 'field_wptextarea',
        'title' => __('Use WordPress data in your forms', CFS_LANG_CODE),
        'text' => __('A rich text editor field, a list of your post categories and the attributes of WooCommerce products.', CFS_LANG_CODE),
        'points' => [__('WordPress Editor field', CFS_LANG_CODE), __('WordPress Categories field', CFS_LANG_CODE), __('WooCommerce product attribute field', CFS_LANG_CODE)],
        'uses' => [__('Guest posts', CFS_LANG_CODE), __('Shops', CFS_LANG_CODE), __('Directories', CFS_LANG_CODE)],
      ],
      'address' => [
        'ces' => ['tier' => 'pro', 'text' => __('Address search with Google Maps is a field of CES Pro.', CFS_LANG_CODE)],
        'icon' => 'fa-map-marker',
        'chip' => __('Address search', CFS_LANG_CODE),
        'medium' => 'field_address',
        'title' => __('Addresses that complete themselves', CFS_LANG_CODE),
        'text' => __('The visitor starts typing and picks the address from the suggestions, without typos.', CFS_LANG_CODE),
        'points' => [__('Suggestions while typing', CFS_LANG_CODE), __('Full and correct addresses in your messages', CFS_LANG_CODE), __('Works on phones too', CFS_LANG_CODE)],
        'uses' => [__('Deliveries', CFS_LANG_CODE), __('Bookings', CFS_LANG_CODE), __('Home services', CFS_LANG_CODE)],
      ],
      'access' => [
        'ces' => ['tier' => '', 'text' => ''],
        'icon' => 'fa-key',
        'chip' => __('Access for roles', CFS_LANG_CODE),
        'medium' => 'access_roles',
        'title' => __('Let your team manage the forms', CFS_LANG_CODE),
        'text' => __('Give editors, shop managers or any other role access to the plugin without making them administrators.', CFS_LANG_CODE),
        'points' => [__('Pick the roles in the settings', CFS_LANG_CODE), __('They see the forms, contacts and statistics', CFS_LANG_CODE), __('Administrators keep full access', CFS_LANG_CODE)],
        'uses' => [__('Agencies', CFS_LANG_CODE), __('Teams', CFS_LANG_CODE), __('Client sites', CFS_LANG_CODE)],
      ],
    ];
  }
  public function enqueueUpsellAssets()
  {
    if ($this->isPro() || !frameCfs::_()->isAdminPlugPage()) {
      return;
    }
    $ver = CFS_VERSION . '-up-1';
    wp_enqueue_style('cfs-upsell', $this->getModPath() . 'css/admin.upsell.css', [], $ver);
    wp_enqueue_script('cfs-upsell', $this->getModPath() . 'js/admin.upsell.js', [], $ver, true);
  }
  public function printUpsellWindows()
  {
    if ($this->isPro() || !frameCfs::_()->isAdminPlugPage()) {
      return;
    }
    echo $this->getView()->getUpsellWindows();
  }
  /**
   * A premium plaque in place of an "available in PRO" note: a short text and Read more, which opens the window of
   * the feature (the link leads to the PRO page when scripts do not run). Nothing with an active PRO.
   * @param string $key Feature of getUpsellFeatures()
   * @param string $text
   * @return string
   */
  public function getProNote($key, $text)
  {
    if ($this->isPro()) {
      return '';
    }
    $features = $this->getUpsellFeatures();
    $medium = isset($features[$key]) ? $features[$key]['medium'] : $key;
    return '<div class="cfs-pro-note"><span class="cfs-pro-note-icon"><i class="fa fa-diamond" aria-hidden="true"></i></span>' .
      '<span class="cfs-pro-note-text"><b class="cfs-pro-note-tag">PRO</b>' . esc_html($text) . '</span>' .
      '<a class="cfs-pro-note-more" href="' . esc_url($this->generateMainLink('utm_source=plugin&utm_medium=' . $medium . '&utm_campaign=forms')) . '" target="_blank" rel="noopener" data-cfs-upsell="' . esc_attr($key) . '">' .
      esc_html__('Read more', CFS_LANG_CODE) . '<i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>';
  }
  /**
   * Contact Form is a classic plugin now: its forms move on to CES (Customer Engagement System, popup-by-supsystic 2.x),
   * where the import page of CES moves them in one click. The state of CES on this site and the one button that leads
   * there: install, update, activate or open the move page.
   * @return array ['state' => install|update|activate|move|open|none, 'url', 'label', 'moved', 'total', 'cesPro']
   */
  public function getCesState()
  {
    static $state;
    if ($state !== null) {
      return $state;
    }
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    $installed = isset($plugins[self::CES_FILE]);
    $active = $installed && is_plugin_active(self::CES_FILE);
    $version = $installed ? (string) $plugins[self::CES_FILE]['Version'] : '';
    // The move page exists in CES 2.1.0+ (and in a CES copy that already has the import module)
    $hasImport =
      $installed && (version_compare($version, self::CES_IMPORT_VERSION, '>=') || is_dir(WP_PLUGIN_DIR . DS . 'popup-by-supsystic' . DS . 'modules' . DS . 'form' . DS . 'submodules' . DS . 'cf_import'));
    $total = $this->countUserForms();
    $moved = 0;
    $import = get_option('ppsgen_cf_import');
    if (is_array($import) && !empty($import['forms']) && is_array($import['forms'])) {
      $moved = count($import['forms']);
    }
    $moveUrl = admin_url('admin.php?page=ppsgen&tab=form_import_cf&from=cfs');
    $state = ['state' => 'none', 'url' => '', 'label' => '', 'moved' => min($moved, $total), 'total' => $total, 'cesPro' => $this->isCesProActive(), 'moveUrl' => $moveUrl];
    if (!$installed) {
      if (current_user_can('install_plugins')) {
        $state['state'] = 'install';
        $state['url'] = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=popup-by-supsystic'), 'install-plugin_popup-by-supsystic');
        $state['label'] = __('Install CES and move your forms', CFS_LANG_CODE);
      }
    } elseif (!$hasImport) {
      if (current_user_can('update_plugins')) {
        $state['state'] = 'update';
        $state['url'] = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . rawurlencode(self::CES_FILE)), 'upgrade-plugin_' . self::CES_FILE);
        $state['label'] = __('Update CES and move your forms', CFS_LANG_CODE);
      }
    } elseif (!$active) {
      if (current_user_can('activate_plugins')) {
        $state['state'] = 'activate';
        $state['url'] = wp_nonce_url(admin_url('admin-post.php?action=cfs_ces_activate'), 'cfs_ces_activate');
        $state['label'] = __('Activate CES and move your forms', CFS_LANG_CODE);
      }
    } elseif ($total > 0 && $state['moved'] >= $total) {
      $state['state'] = 'open';
      $state['url'] = $moveUrl;
      $state['label'] = __('Open your forms in CES', CFS_LANG_CODE);
    } else {
      $state['state'] = 'move';
      $state['url'] = $moveUrl;
      $state['label'] = __('Move your forms to CES', CFS_LANG_CODE);
    }
    return $state;
  }
  /**
   * Activates CES from the Contact Form pages and goes straight to the move page of CES
   */
  public function activateCes()
  {
    check_admin_referer('cfs_ces_activate');
    if (!current_user_can('activate_plugins')) {
      wp_die(esc_html__('Sorry, you are not allowed to activate plugins on this site.', CFS_LANG_CODE));
    }
    if (!function_exists('activate_plugin')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $res = activate_plugin(self::CES_FILE);
    if (is_wp_error($res)) {
      wp_die(esc_html($res->get_error_message()));
    }
    wp_safe_redirect(admin_url('admin.php?page=ppsgen&tab=form_import_cf&from=cfs'));
    exit;
  }
  /**
   * CES PRO with a working license: the CES Pro code (CFPRO30 / CFFREE15) is not shown then
   * @return bool
   */
  public function isCesProActive()
  {
    $active = false;
    if (function_exists('ppsgen_get_pro_license_info')) {
      $info = ppsgen_get_pro_license_info();
      $active = is_array($info) && isset($info['state']) && in_array($info['state'], ['active', 'grace'], true);
    }
    return (bool) apply_filters('cfs_ces_pro_active', $active);
  }
  /**
   * The CES Pro code of this site: CFPRO30 (30%) when Contact Form PRO works with an active license - the same check the
   * plugin turns its PRO features on by (isPro()) - and CFFREE15 (15%) otherwise, also with the PRO plugin active but no
   * license. Null while CES Pro has a working license.
   * @return array|null ['code' => string, 'discount' => int (percent), 'pro' => bool]
   */
  public function getCesCoupon()
  {
    if ($this->isCesProActive()) {
      return null;
    }
    $pro = $this->isPro();
    return [
      'code' => $pro ? self::CES_COUPON_PRO : self::CES_COUPON_FREE,
      'discount' => $pro ? self::CES_COUPON_PRO_DISCOUNT : self::CES_COUPON_FREE_DISCOUNT,
      'pro' => $pro,
    ];
  }
  /**
   * A price of the supsystic.com page with the discount of the code, rounded up to whole dollars (the rule of the
   * owner for every discounted price): '$99' with 30% -> '$70', '$749' with 15% -> '$637'. The one place it is computed.
   * @param string $price
   * @param int $discount Percent
   * @return string
   */
  public function getCesDiscountPrice($price, $discount)
  {
    $amount = (float) preg_replace('/[^0-9.]/', '', $price);
    // round() first, so a float like 850.0000001 does not become 851
    return '$' . number_format(ceil(round($amount * (100 - (int) $discount) / 100, 2)));
  }
  /**
   * The offer of Contact Form users on CES Pro: the discount and the code of the site (getCesCoupon()), with a copy
   * button and Buy. Empty with CES PRO.
   * @param string $context Where it is shown, for the utm and the look (banner, window, overview, pricing)
   * @return string
   */
  public function getCesCouponHtml($context = 'banner')
  {
    $coupon = $this->getCesCoupon();
    if (!$coupon) {
      return '';
    }
    $text = $coupon['pro']
      /* translators: %d: discount in percent */
      ? sprintf(__('Contact Form PRO users save %d%% on CES Pro with the code', CFS_LANG_CODE), $coupon['discount'])
      /* translators: %d: discount in percent */
      : sprintf(__('Contact Form users save %d%% on CES Pro with the code', CFS_LANG_CODE), $coupon['discount']);
    $buyUrl = 'https://supsystic.com/plugins/contact-form-plugin/?utm_source=plugin&utm_medium=' . rawurlencode($context) . '&utm_campaign=cf_to_ces#pricing';
    return '<div class="cfs-ces-coupon is-' . esc_attr($context) . ($context === 'banner' ? '' : ' is-light') . '">' .
      '<span class="cfs-ces-coupon-badge">-' . (int) $coupon['discount'] . '%</span>' .
      '<span class="cfs-ces-coupon-text">' . esc_html($text) . '</span>' .
      '<button type="button" class="cfs-ces-coupon-code" data-cfs-copy="' . esc_attr($coupon['code']) . '" data-copied="' . esc_attr__('Copied', CFS_LANG_CODE) . '" title="' . esc_attr__('Copy the code', CFS_LANG_CODE) . '">' .
      '<b>' . esc_html($coupon['code']) . '</b><i class="fa fa-copy" aria-hidden="true"></i><span class="cfs-ces-coupon-copied" aria-live="polite"></span></button>' .
      '<a class="cfs-ces-coupon-buy" href="' . esc_url($buyUrl) . '" target="_blank" rel="noopener">' . esc_html__('Buy CES Pro', CFS_LANG_CODE) . '<i class="fa fa-arrow-right" aria-hidden="true"></i></a>' .
      '</div>';
  }
  /**
   * Is the CES banner folded into a strip for the current user (user meta, set by the fold button)
   * @return bool
   */
  public function isCesBannerFolded()
  {
    $userId = get_current_user_id();
    return $userId ? (bool) get_user_meta($userId, self::CES_BANNER_FOLDED, true) : false;
  }
  /**
   * AJAX of the fold button of the CES banner: keeps the choice of the current user. The banner cannot be closed.
   */
  public function ajaxFoldCesBanner()
  {
    check_ajax_referer('cfs_ces_banner', 'nonce');
    $userId = get_current_user_id();
    if (!$userId) {
      wp_send_json_error();
    }
    $folded = !empty($_POST['folded']) && sanitize_text_field(wp_unslash($_POST['folded'])) === '1';
    update_user_meta($userId, self::CES_BANNER_FOLDED, $folded ? 1 : 0);
    wp_send_json_success(['folded' => $folded]);
  }
  /**
   * The banner at the top of every Contact Form page (Free and PRO): Contact Form now lives on in CES
   */
  public function printCesBanner()
  {
    if (!frameCfs::_()->isAdminPlugPage()) {
      return;
    }
    echo $this->getView()->getCesBanner($this->getCesState());
  }
  /**
   * Forms made by the site owner (templates and A/B versions are not counted)
   * @return int
   */
  public function countUserForms()
  {
    global $wpdb;
    $suppress = $wpdb->suppress_errors(true);
    $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}cfs_forms WHERE original_id <> 0 AND ab_id = 0");
    $wpdb->suppress_errors($suppress);
    return $count;
  }
  public function getContactLink()
  {
    return $this->getMainLink() . '#contact';
  }
  public function checkProTpls($list)
  {
    if (!$this->isPro()) {
      $imgsPath = frameCfs::_()->getModule('forms')->getAssetsUrl() . 'img/preview/';
      $promoList = [
        //array('label' => 'List Building Layered', 'img_preview' => 'list-building-layered.jpg', 'sort_order' => 18),
      ]; // No pro tpls for now
      foreach ($promoList as $i => $t) {
        $promoList[$i]['img_preview_url'] = $imgsPath . $promoList[$i]['img_preview'];
        $promoList[$i]['promo'] = strtolower(str_replace([' ', '!'], '', $t['label']));
        $promoList[$i]['promo_link'] = $this->generateMainLink('utm_source=plugin&utm_medium=' . $promoList[$i]['promo'] . '&utm_campaign=forms');
      }
      foreach ($list as $i => $t) {
        if (isset($t['is_pro']) && $t['is_pro']) {
          unset($list[$i]);
        }
      }
      $list = array_merge($list, $promoList);
    }
    return $list;
  }

  /**
   * Why the plugin is deactivated: the short dialog shared by the Supsystic plugins (deactivation-v1/deactivation.php
   * in this folder says what is sent and when). Nothing is sent unless the user sends the answer.
   */
  public function registerDeactivationDialog()
  {
    if (!is_admin()) {
      return;
    }
    require_once __DIR__ . '/deactivation-v1/deactivation.php';
    Supsystic_Deactivation_V1::register([
      'slug' => 'contact-form-by-supsystic',
      'name' => 'Contact Form',
      'file' => dirname(__DIR__, 2) . '/cfs.php',
      'tables' => ['cfs_modules', 'cfs_forms'],
      'pro' => [
        'slug' => 'contact-form-by-supsystic-pro',
        'name' => 'Contact Form PRO',
        'email' => function () {
          $license = class_exists('frameCfs') ? frameCfs::_()->getModule('license') : null;
          return $license ? (string) $license->getModel()->getEmail() : '';
        },
      ],
    ]);
  }
}

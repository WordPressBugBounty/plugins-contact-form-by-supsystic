<?php
class formsCfs extends moduleCfs
{
  private $_assetsUrl = '';
  private $_fieldTypes = [];

  public function init()
  {
    dispatcherCfs::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    add_shortcode(CFS_SHORTCODE, [$this, 'showForm']);
    add_shortcode(CFS_SHORTCODE_SUBMITTED, [$this, 'showFormSubmittedData']);
    // Add to admin bar new item
    add_action('admin_bar_menu', [$this, 'addAdminBarNewItem'], 300);
    add_action('wp_loaded', [$this, 'checkRemoveExpiredContacts']);
    dispatcherCfs::addFilter('formCss', [$this, 'addFormCss'], 10, 2);
    dispatcherCfs::addFilter('formsChangeTpl', [$this, 'formsChangeTpl'], 10, 2);
  }
  public function addFormCss($css, $form)
  {
    $css = str_replace('input[type="submit"]', 'input[type="submit"]:not([type="checkbox"]):not([type="radio"])', $css);
    $css = str_replace('input[type="reset"]', 'input[type="reset"]:not([type="checkbox"]):not([type="radio"])', $css);
    return $css;
  }
  public function formsChangeTpl($newTpl, $currentForm)
  {
    if ($newTpl['unique_id'] == 'uwi23o') {
      $newTpl['css'] .= '
#[SHELL_ID] .cfsFileList { color: {{adjust_brightness("[bg_color_1]", 109)}}; }';
    }
    return $newTpl;
  }
  public function addAdminTab($tabs)
  {
    $tabs[$this->getCode() . '_add_new'] = [
      'label' => __('Add New Form', CFS_LANG_CODE),
      'callback' => [$this, 'getAddNewTabContent'],
      'fa_icon' => 'fa-plus-circle',
      'sort_order' => 10,
      'add_bread' => $this->getCode(),
    ];
    $tabs[$this->getCode() . '_edit'] = [
      'label' => __('Edit', CFS_LANG_CODE),
      'callback' => [$this, 'getEditTabContent'],
      'sort_order' => 20,
      'child_of' => $this->getCode(),
      'hidden' => 1,
      'add_bread' => $this->getCode(),
    ];
    $tabs[$this->getCode()] = [
      'label' => __('Show All Forms', CFS_LANG_CODE),
      'callback' => [$this, 'getTabContent'],
      'fa_icon' => 'fa-list',
      'sort_order' => 20, //'is_main' => true,
    ];
    $tabs[$this->getCode() . '_contacts'] = [
      'label' => __('Contacts', CFS_LANG_CODE),
      'callback' => [$this, 'getContactsTabContent'],
      'fa_icon' => 'fa-users',
      'sort_order' => 25, //'is_main' => true,
    ];
    return $tabs;
  }
  public function getTabContent()
  {
    return $this->getView()->getTabContent();
  }
  public function getContactsTabContent()
  {
    $id = (int) reqCfs::getVar('id', 'get');
    return $this->getView()->getContactsTabContent($id);
  }
  public function getAddNewTabContent()
  {
    return $this->getView()->getAddNewTabContent();
  }
  public function getEditTabContent()
  {
    $id = (int) reqCfs::getVar('id', 'get');
    return $this->getView()->getEditTabContent($id);
  }
  public function getEditLink($id, $formsTab = '')
  {
    $link = frameCfs::_()
      ->getModule('options')
      ->getTabUrl($this->getCode() . '_edit');
    $link .= '&id=' . $id;
    if (!empty($formsTab)) {
      $link .= '#' . $formsTab;
    }
    return $link;
  }
  public function getAssetsUrl()
  {
    if (empty($this->_assetsUrl)) {
      $this->_assetsUrl = CFS_ASSETS_PATH . 'forms/';
    }
    return $this->_assetsUrl;
  }
  public function addAdminBarNewItem($wp_admin_bar)
  {
    $mainCap = frameCfs::_()->getModule('adminmenu')->getMainCap();
    if (!current_user_can($mainCap) || !$wp_admin_bar || !is_object($wp_admin_bar)) {
      return;
    }
    $wp_admin_bar->add_menu([
      'parent' => 'new-content',
      'id' => CFS_CODE . '-admin-bar-new-item',
      'title' => __('Form', CFS_LANG_CODE),
      'href' => frameCfs::_()
        ->getModule('options')
        ->getTabUrl($this->getCode() . '_add_new'),
    ]);
  }
  public function getFieldTypes()
  {
    if (empty($this->_fieldTypes)) {
      $this->_fieldTypes = dispatcherCfs::applyFilters('fieldTypes', [
        'text' => ['label' => __('Text', CFS_LANG_CODE), 'icon' => 'fa-font'],
        'email' => ['label' => __('Email', CFS_LANG_CODE), 'icon' => 'fa-envelope-o'],
        'selectbox' => ['label' => __('Select Box', CFS_LANG_CODE), 'icon' => 'fa-list-ul'],
        'selectlist' => ['label' => __('Select List', CFS_LANG_CODE), 'icon' => 'fa-th-list'],
        'textarea' => ['label' => __('Textarea', CFS_LANG_CODE), 'icon' => 'fa-font'],
        'wptextarea' => ['label' => __('WordPress Editor', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''],
        'wpcategories' => ['label' => __('WordPress Categories', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''],
        'wooattrs' => ['label' => __('Woo Product Attribute', CFS_LANG_CODE), 'icon' => 'fa-wordpress', 'pro' => ''],
        'radiobutton' => ['label' => __('Radiobutton', CFS_LANG_CODE), 'icon' => 'fa-dot-circle-o'],
        'radiobuttons' => ['label' => __('Radiobuttons List', CFS_LANG_CODE), 'icon' => 'fa-dot-circle-o'],
        'checkbox' => ['label' => __('Checkbox', CFS_LANG_CODE), 'icon' => 'fa-check-square-o'],
        'checkboxlist' => ['label' => __('Checkbox List', CFS_LANG_CODE), 'icon' => 'fa-check-square-o'],
        'checkboxsubscribe' => ['label' => __('Subscribe Checkbox', CFS_LANG_CODE), 'icon' => 'fa-user-plus', 'pro' => ''],
        'countryList' => ['label' => __('Country List', CFS_LANG_CODE), 'icon' => 'fa-globe'],
        'countryListMultiple' => ['label' => __('Country List Multiple', CFS_LANG_CODE), 'icon' => 'fa-globe'],

        'number' => ['label' => __('Number', CFS_LANG_CODE), 'icon' => 'fa-sort-numeric-asc'],

        'date' => ['label' => __('Date', CFS_LANG_CODE), 'icon' => 'fa-calendar'],
        'month' => ['label' => __('Month', CFS_LANG_CODE), 'icon' => 'fa-calendar'],
        'week' => ['label' => __('Week', CFS_LANG_CODE), 'icon' => 'fa-calendar'],
        'time' => ['label' => __('Time', CFS_LANG_CODE), 'icon' => 'fa-clock-o'],

        'color' => ['label' => __('Color', CFS_LANG_CODE), 'icon' => 'fa-paint-brush'],
        'range' => ['label' => __('Range', CFS_LANG_CODE), 'icon' => 'fa-magic'],
        'url' => ['label' => __('URL', CFS_LANG_CODE), 'icon' => 'fa-link'],

        'file' => ['label' => __('File Upload', CFS_LANG_CODE), 'icon' => 'fa-upload', 'pro' => ''],
        'rating' => ['label' => __('Rating', CFS_LANG_CODE), 'icon' => 'fa-star', 'pro' => ''],
        'recaptcha' => ['label' => __('reCaptcha', CFS_LANG_CODE), 'icon' => 'fa-unlock-alt'],
        'recaptcha_v3' => ['label' => __('reCaptcha v3', CFS_LANG_CODE), 'icon' => 'fa-unlock-alt', 'pro' => ''],
        'hcaptcha' => ['label' => __('hCaptcha', CFS_LANG_CODE), 'icon' => 'fa-shield', 'pro' => ''],
        'turnstile' => ['label' => __('Cloudflare Turnstile', CFS_LANG_CODE), 'icon' => 'fa-cloud', 'pro' => ''],

        'hidden' => ['label' => __('Hidden Field', CFS_LANG_CODE), 'icon' => 'fa-eye-slash'],
        'submit' => ['label' => __('Submit Button', CFS_LANG_CODE), 'icon' => 'fa-paper-plane-o'],
        'reset' => ['label' => __('Reset Button', CFS_LANG_CODE), 'icon' => 'fa-repeat'],

        'htmldelim' => ['label' => __('HTML / Text Delimiter', CFS_LANG_CODE), 'icon' => 'fa-code'],

        'googlemap' => ['label' => __('Google Map', CFS_LANG_CODE), 'icon' => 'fa-globe'],
        'address' => ['label' => __('Address Search', CFS_LANG_CODE), 'icon' => 'fa-map-marker', 'pro' => ''],
      ]);
      $isPro = frameCfs::_()->getModule('supsystic_promo')->isPro();
      foreach ($this->_fieldTypes as $code => $f) {
        if (isset($f['pro']) && !$isPro) {
          $this->_fieldTypes[$code]['pro'] = frameCfs::_()
            ->getModule('supsystic_promo')
            ->generateMainLink('utm_source=plugin&utm_medium=field_' . $code . '&utm_campaign=forms');
        }
      }
    }
    return $this->_fieldTypes;
  }
  public function getFieldTypeByCode($htmlCode)
  {
    $this->getFieldTypes();
    return isset($this->_fieldTypes[$htmlCode]) ? $this->_fieldTypes[$htmlCode] : false;
  }
  public function isFieldListSupported($htmlCode)
  {
    return $htmlCode && in_array($htmlCode, ['selectbox', 'selectlist', 'radiobuttons', 'checkboxlist']);
  }
  public function showForm($params)
  {
    $id = isset($params['id']) ? (int) $params['id'] : 0;
    if (!$id && isset($params[0]) && !empty($params[0])) {
      // For some reason - for some cases it convert space in shortcode - to %20 im this place
      $id = explode('=', $params[0]);
      $id = isset($id[1]) ? (int) $id[1] : 0;
    }
    if ($id) {
      $params['id'] = $id;
      return $this->getView()->showForm($params);
    }
  }
  public function getAssetsforPrevStr($form)
  {
    $stylesStr = '';
    $stylesStr .= '<style type="text/css">
				.cfsFormPreloadImg {
					width: 1px !important;
					height: 1px !important;
					position: absolute !important;
					top: -9999px !important;
					left: -9999px !important;
					opacity: 0 !important;
				}
			</style>';
    $stylesStr = dispatcherCfs::applyFilters('assetsForPrevStr', $stylesStr, $form);
    return $stylesStr;
  }
  public function showFormSubmittedData()
  {
    $fid = (int) reqCfs::getVar('fid');
    $cid = (int) reqCfs::getVar('cid');
    $hash = reqCfs::getVar('hash');
    if ($fid && $cid && $hash) {
      if ($hash == md5(AUTH_KEY . $fid . $cid)) {
        $form = $this->getModel()->supGetById($fid);
        $contact = $this->getModel('contacts')->supGetById($cid);
        if ($form && $contact) {
          return $this->getModel()->generateSendFormDataFull($contact['fields'], $form);
        }
      }
    }
    return '';
  }
  public function getListAvailableTerms()
  {
    return ['category', 'post_tag', 'products_categories', 'product_cat'];
  }
  public function checkRemoveExpiredContacts()
  {
    global $wpdb;
    $removeContacts = (int) frameCfs::_()->getModule('options')->get('remove_expire_contacts');
    if ($removeContacts && $removeContacts > 0) {
      $lastCheck = (int) frameCfs::_()->getModule('options')->get('expire_contacts_last_check');
      $time = time();
      $checkFreq = 5; // Each 5 hours
      if (!$lastCheck || ($time - $lastCheck) / (60 * 60) > $checkFreq) {
        $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}cfs_contacts WHERE DATEDIFF(CURRENT_DATE, date_created) > %s", $removeContacts));
        frameCfs::_()->getModule('options')->getModel()->save('expire_contacts_last_check', $time);
      }
    }
  }
}

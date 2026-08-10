<?php
class supsystic_promoCfs extends moduleCfs
{
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
    if (is_admin()) {
      add_action('init', [$this, 'checkWelcome']);
    }
    $this->weLoveYou();
    dispatcherCfs::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    dispatcherCfs::addFilter('showTplsList', [$this, 'checkProTpls']);
    dispatcherCfs::addFilter('formsEditTabs', [$this, 'addEditTab']);
  }
  public function addEditTab($tabs)
  {
    if (!$this->isPro()) {
      $tabs['cfsFormConditionalLogic'] = [
        'title' => __('Conditional Logic', CFS_LANG_CODE),
        'content' =>
          '
				<label>
				Conditional Logic <i title="A feature allows to show certain fields, depend on the value of some other fields. It provides a set of rules that apply to fields that dynamically change the form layout. It’s a great way to make complex forms more compact, and present the users with only the information they are interested in. <a href=\'https://supsystic.com/documentation/contact-form-publish-content/\' target=\'_blank\'>https://supsystic.com/documentation/contact-form-publish-content/</a>" class="fa fa-question supsystic-tooltip tooltipstered"></i>
				</label>
				<br>
				<a style="margin-top:15px;" href="' .
          $this->generateMainLink('utm_source=plugin&utm_medium=conditional_logic&utm_campaign=forms') .
          '" target="_blank"><img style="max-width:800px; width: 100%; height: auto;" src="' .
          CFS_ASSETS_PATH .
          'forms/promo/img/logic.png" /></a>',
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
        'content' =>
          '<a href="' .
          $this->generateMainLink('utm_source=plugin&utm_medium=abtesting&utm_campaign=forms') .
          '" target="_blank" class="button button-primary">' .
          __('Get PRO', CFS_LANG_CODE) .
          '</a><br /><a href="' .
          $this->generateMainLink('utm_source=plugin&utm_medium=abtesting&utm_campaign=forms') .
          '" target="_blank">' .
          '<img style="max-width: 100%;" src="' .
          CFS_ASSETS_PATH .
          'forms/promo/img/AB-testing-pro.jpg" />' .
          '</a>',
        'icon_content' => '<b>A/B</b>',
        'avoid_hide_icon' => true,
        'sort_order' => 60,
      ];
    }
    if (!frameCfs::_()->getModule('subscribe')) {
      $tabs['cfsFormSubscribe'] = [
        'title' => __('Subscribe', CFS_LANG_CODE),
        'content' =>
          '<a href="' .
          $this->generateMainLink('utm_source=plugin&utm_medium=subscribe&utm_campaign=forms') .
          '" target="_blank" class="button button-primary">' .
          __('Get PRO', CFS_LANG_CODE) .
          '</a><br /><a href="' .
          $this->generateMainLink('utm_source=plugin&utm_medium=subscribe&utm_campaign=forms') .
          '" target="_blank">' .
          '<img style="max-width: 100%;" src="' .
          CFS_ASSETS_PATH .
          'forms/promo/img/subscribe.png" />' .
          '</a>',
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
}

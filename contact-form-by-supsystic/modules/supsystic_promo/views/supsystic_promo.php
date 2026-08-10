<?php
class supsystic_promoViewCfs extends viewCfs
{
  public function showAdditionalmainAdminShowOnOptions($forms)
  {
    $this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=onexit&utm_campaign=forms'));
    parent::display('additionalmainAdminShowOnOptions');
  }
  public function getOverviewTabContent()
  {
    frameCfs::_()->getModule('templates')->loadJqueryUi();

    frameCfs::_()->getModule('templates')->loadSlimscroll();
    frameCfs::_()->addScript('admin.overview', $this->getModule()->getModPath() . 'js/admin.overview.js');
    frameCfs::_()->addStyle('admin.overview', $this->getModule()->getModPath() . 'css/admin.overview.css');
    $this->assign('mainLink', $this->getModule()->getMainLink());
    $this->assign('faqList', $this->getFaqList());
    $this->assign('serverSettings', $this->getServerSettings());
    return parent::getContent('overviewTabContent');
  }
  public function getFaqList()
  {
    return [
      __('Why Form by Supsystic is "must have" for your website?', CFS_LANG_CODE) => sprintf(
        __('Increase your sales by 500%% using Form by Supsystic! More subscribers = more sales! It\'s that simple!<br />More info you can find here <a target="_blank" href="%s">Form by Supsystic is "must have" for your website</a>', CFS_LANG_CODE),
        'http://supsystic.com/why-contact-form-by-supsystic-is-must-have-for-your-website/',
      ),
      __('What is A/B testing?', CFS_LANG_CODE) => sprintf(
        __(
          'A/B testing is one of the easiest ways to increase conversion rates and learn more about your audience!<br />A/B test in Form plugin involves testing two or more versions of a forms window - an A version (original) and a B versions (the variation) - with live traffic and measuring the effect each version has on your conversion rate.<br />To know more detail – click <a target="_blank" href="%s">here</a>',
          CFS_LANG_CODE,
        ),
        'http://supsystic.com/what-is-ab-testing/',
      ),
      __('How to enable subscription to Aweber?', CFS_LANG_CODE) => sprintf(
        __('In order to subscribe to Aweber you need to know unique list id of your aweber account - check this <a target="_blank" href="%s">page</a> for more details.', CFS_LANG_CODE),
        'http://supsystic.com/what-is-the-unique-list-id/',
      ),
      __('How to subscribe to MailChimp?', CFS_LANG_CODE) => __(
        'To subscribe to MailChimp you need enter your MailChimp API key and name of list for subscription. To find your MailChimp API key - follow the instructions below:<br />
				1. Login to your mailchimp account at http://mailchimp.com<br />
				2. From the left main menu, click on your Username, then select "Account" in the flyout menu.<br />
				3. From the account page select "Extras" -> "API Keys".<br />
				4. Your API Key will be listed in the table labeled "Your API Keys".<br />
				5. Copy / Paste your API key into "MailChimp API key" field in Form edit screen -> Subscribe section.',
        CFS_LANG_CODE,
      ),
      __('Where to find css code for the pop-up window?', CFS_LANG_CODE) => __(
        'With Form by Supsystic you can edit CSS style directly from the plugin. <br />
				In WordPress admin area -
go to Form by Supsystic -> choose a forms, what you need -> click Code tab. <br />
Here you can edit css style of the pop-up window.',
        CFS_LANG_CODE,
      ),
      __('How to get PRO version of plugin for FREE?', CFS_LANG_CODE) => sprintf(
        __(
          'You have an incredible opportunity to get PRO version for free. Make Translation of the plugin! It will be amazing if you take advantage of this offer!<br />
					More info you can find here <a target="_blank" href="%s">“Get PRO version of any plugin for FREE”</a>',
          CFS_LANG_CODE,
        ),
        'http://supsystic.com/get-pro-version-of-any-plugin-for-free/',
      ),
      __('Translation', CFS_LANG_CODE) => sprintf(
        __(
          'All available languages are provided with the Supsystic Form plugin. If your language isn’t available, your plugin will be in English by default.<br />
					Available Translations: English, French, German, Spanish, Russian<br />
					Translate or update a translation Form WordPress plugin in your language and get a Premium license for FREE. <a target="_blank" href="%s">Contact us.</a>',
          CFS_LANG_CODE,
        ),
        $this->getModule()->getContactLink(),
      ),
    ];
  }
  public function getMostFaqList()
  {
    return [
      __("Where's my subscribers?", CFS_LANG_CODE) => sprintf(
        __(
          "By default all subscribers add to the WordPress.
					To find your subscribers go to Users tab on the left navigation menu of WordPress admin area.
					Also available subscription to the Aweber, MailChimp, MailPoet <a href='%s' target='_blank'>and other</a>.
					If you want to add another subscription service - just <a href='%s' target='_blank'>contact us</a> and provide URL of the subscription service.",
          CFS_LANG_CODE,
        ),
        $this->getModule()->getMainLink() . '#subscribe-to-email-forms-settings',
        $this->getModule()->getContactLink(),
      ),
      __("Form doesn't appear on the website", CFS_LANG_CODE) => sprintf(
        __(
          "If you setup you're Form properly, and it still doesn't show on the page - there are can be conflict with your WordPress theme or other plugins. <a href='%s' target='_blank'>Contact us</a> with the URL of the webpage you add forms and screenshots / text of the error messages, if you have one - and we will help you resolve your issue.",
          CFS_LANG_CODE,
        ),
        $this->getModule()->getContactLink(),
      ),
    ];
  }
  public function getServerSettings()
  {
    global $wpdb;
    return [
      'Operating System' => ['value' => PHP_OS],
      'PHP Version' => ['value' => PHP_VERSION],
      'Server Software' => ['value' => $_SERVER['SERVER_SOFTWARE']],
      'MySQL' => ['value' => $wpdb->db_version()],
      'PHP Allow URL Fopen' => ['value' => ini_get('allow_url_fopen') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE)],
      'PHP Memory Limit' => ['value' => ini_get('memory_limit')],
      'PHP Max Post Size' => ['value' => ini_get('post_max_size')],
      'PHP Max Upload Filesize' => ['value' => ini_get('upload_max_filesize')],
      'PHP Max Script Execute Time' => ['value' => ini_get('max_execution_time')],
      'PHP EXIF Support' => ['value' => extension_loaded('exif') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE)],
      'PHP EXIF Version' => ['value' => phpversion('exif')],
      'PHP XML Support' => ['value' => extension_loaded('libxml') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE), 'error' => !extension_loaded('libxml')],
      'PHP CURL Support' => ['value' => extension_loaded('curl') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE), 'error' => !extension_loaded('curl')],
    ];
  }
  public function getLayeredStylePromo()
  {
    $this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=layered&utm_campaign=forms'));
    return parent::getContent('layeredStylePromo');
  }
  public function showWelcomePage()
  {
    frameCfs::_()->getModule('templates')->loadJqueryUi();
    frameCfs::_()->getModule('templates')->loadSupTablesUi();
    frameCfs::_()->addStyle('admin.welcome', $this->getModule()->getModPath() . 'css/admin.welcome.css');
    $createNewLink = frameCfs::_()->getModule('options')->getTabUrl('forms_add_new');
    $this->assign('createNewLink', $this->_makeWelcomeLink($createNewLink));
    $this->assign('faqList', $this->getMostFaqList());
    $this->assign('mainLink', $this->getModule()->getMainLink());
    parent::display('welcomePage');
  }
  private function _makeWelcomeLink($link)
  {
    return uriCfs::_(['baseUrl' => $link, 'from' => 'welcome-page', 'pl' => CFS_CODE]);
  }
}

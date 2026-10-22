<?php
#[\AllowDynamicProperties]
class frameCfs
{
  private $_modules = [];
  private $_tables = [];
  private $_allModules = [];
  /**
   * bool Uses to know if we are on one of the plugin pages
   */
  private $_inPlugin = false;
  /**
   * Array to hold all scripts and add them in one time in addScripts method
   */
  private $_scripts = [];
  private $_scriptsInitialized = false;
  private $_styles = [];
  private $_stylesInitialized = false;
  private $_useFootAssets = false;
  private $_nonceIsInitialized = false;

  private $_scriptsVars = [];
  private $_mod = '';
  private $_action = '';
  /**
   * PRO before 1.5.0 does not declare it's public and nonce exempt methods, @see _isLegacyPro()
   */
  private static $_legacyProPublicMethods = [
    'add_fields' => ['uploadFile', 'getFile', 'removeFile'],
    'wp_subscribe' => ['confirm'],
  ];
  private static $_legacyProNonceExemptMethods = [
    'campaignmonitor' => ['saveAccessToken'],
    'constantcontact' => ['saveAccessToken'],
    'verticalresponse' => ['saveAccessToken'],
    // Read only exports - old PRO opens them by links without nonce
    'ab_testing' => ['getListForTbl'],
    'wp_subscribe' => ['getWpCsvList'],
    'statistics' => ['getCsv'],
  ];
  private static $_legacyPro = null;
  /**
   * Object with result of executing non-ajax module request
   */
  private $_res = null;

  public function __construct()
  {
    $this->_res = toeCreateObjCfs('response', []);
  }
  public static function getInstance()
  {
    static $instance;
    if (!$instance) {
      $instance = new frameCfs();
    }
    return $instance;
  }
  public static function _()
  {
    return self::getInstance();
  }
  public function parseRoute()
  {
    // Check plugin
    $pl = reqCfs::getVar('pl');
    if ($pl == CFS_CODE) {
      $mod = reqCfs::getMode();
      if ($mod) {
        $this->_mod = $mod;
      }
      $action = reqCfs::getVar('action');
      if ($action && preg_match('/^[A-Za-z0-9_]+$/', $action)) {
        $this->_action = $action;
      }
    }
  }
  public function setMod($mod)
  {
    $this->_mod = $mod;
  }
  public function getMod()
  {
    return $this->_mod;
  }
  public function setAction($action)
  {
    $this->_action = $action;
  }
  public function getAction()
  {
    return $this->_action;
  }
  protected function _extractModules()
  {
    global $wpdb;
    $wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
    if (dbCfs::exist('cfs_modules')) {
      $activeModules = $wpdb->get_results("SELECT sup_m.*, sup_m_t.label as type_name FROM {$wpdb->prefix}cfs_modules as sup_m INNER JOIN {$wpdb->prefix}cfs_modules_type sup_m_t ON sup_m_t.id = sup_m.type_id ORDER BY id ASC", ARRAY_A);
      //$activeModules = $this->getTable('modules')
      //->innerJoin( $this->getTable('modules_type'), 'type_id' )
      //->orderBy('id ASC')
      //->get($this->getTable('modules')->alias(). '.*, '. $this->getTable('modules_type')->alias(). '.label as type_name');

      if ($activeModules) {
        foreach ($activeModules as $m) {
          $code = $m['code'];
          $moduleLocationDir = CFS_MODULES_DIR;
          if (!empty($m['ex_plug_dir'])) {
            $moduleLocationDir = utilsCfs::getExtModDir($m['ex_plug_dir']);
          }
          if (is_dir($moduleLocationDir . $code)) {
            $this->_allModules[$m['code']] = 1;
            if ((bool) $m['active']) {
              importClassCfs($code . strFirstUp(CFS_CODE), $moduleLocationDir . $code . DS . 'mod.php');
              $moduleClass = toeGetClassNameCfs($code);
              if (class_exists($moduleClass)) {
                $this->_modules[$code] = new $moduleClass($m);
                if (is_dir($moduleLocationDir . $code . DS . 'tables')) {
                  $this->_extractTables($moduleLocationDir . $code . DS . 'tables' . DS);
                }
              }
            }
          }
        }
      }
    }
    //$operationTime = microtime(true) - $startTime;
  }
  protected function _initModules()
  {
    if (!empty($this->_modules)) {
      foreach ($this->_modules as $mod) {
        $mod->init();
      }
    }
  }
  public function init()
  {
    reqCfs::init();
    add_action('init', [$this, '_delayedInit'], 5);
  }
  public function _delayedInit()
  {
    //$startTime = microtime(true);
    reqCfs::init();
    $this->_extractTables();
    $this->_extractModules();

    $this->_initModules();

    dispatcherCfs::doAction('afterModulesInit');

    modInstallerCfs::checkActivationMessages();

    $this->_execModules();

    $addAssetsAction = $this->usePackAssets() && !is_admin() ? 'wp_footer' : 'init';

    add_action($addAssetsAction, [$this, 'addScripts']);
    add_action($addAssetsAction, [$this, 'addStyles']);

    register_activation_hook(CFS_DIR . DS . CFS_MAIN_FILE, ['utilsCfs', 'activatePlugin']); //See classes/install.php file
    register_uninstall_hook(CFS_DIR . DS . CFS_MAIN_FILE, ['utilsCfs', 'deletePlugin']);
    register_deactivation_hook(CFS_DIR . DS . CFS_MAIN_FILE, ['utilsCfs', 'deactivatePlugin']);

    add_action('init', [$this, 'connectLang']);
    add_filter('do_not_defer', [$this, 'excludeDeferScripts']);
    add_filter('script_loader_tag', [$this, 'add_defer_attribute'], 10, 2);
    //$operationTime = microtime(true) - $startTime;
  }
  public function add_defer_attribute($tag, $handle)
  {
    // add script handles to the array below
    switch ($handle) {
      case 'google.recaptcha':
        return str_replace(' src', ' defer="defer" src', $tag);
        break;
      case 'google.recaptcha.frontend':
        return str_replace(' src', ' defer="defer" src', $tag);
        break;
    }
    return $tag;
  }
  public function excludeDeferScripts($do_not_defer)
  {
    //Need when WP deferred javaScript is activated
    global $wp_scripts;
    foreach ($wp_scripts->queue as $handle) {
      $do_not_defer[] = $handle;
    }
    return $do_not_defer;
  }
  public function connectLang()
  {
    load_plugin_textdomain(CFS_LANG_CODE, false, CFS_PLUG_NAME . '/languages/');
  }
  /**
   * Check permissions for action in controller by $code and made corresponding action
   * @param string $code Code of controller that need to be checked
   * @param string $action Action that need to be checked
   * @return bool true if ok, else - should exit from application
   */
  public function checkPermissions($code, $action)
  {
    if ($this->havePermissions($code, $action)) {
      return true;
    } else {
      exit(_e('You have no permissions to view this page', CFS_LANG_CODE));
    }
  }
  /**
   * Check permissions for action in controller by $code.
   * Access is denied by default: an action can be dispatched only if its controller declares it,
   * either as public (getPublicMethods() - such method must validate the request on its own),
   * or for some user level (getPermissions()). Every action that is not public requires
   * a valid plugin nonce too, except actions from getNonceExemptMethods().
   * @param string $code Code of controller that need to be checked
   * @param string $action Action that need to be checked
   * @return bool true if ok, else - false
   */
  public function havePermissions($code, $action)
  {
    $controller = $this->_getDispatchController($code, $action);
    if (!$controller) {
      return false;
    }
    $action = strtolower($action);
    if ($this->_methodListed($action, $this->_getPublicMethods($code, $controller))) {
      // Public methods can be additionally enrolled for the plugin nonce
      return !$this->_methodListed($action, $controller->getNoncedMethods()) || $this->verifyNonce();
    }
    $requiredLevels = $this->_getRequiredUserLevels($controller->getPermissions(), $action);
    if (empty($requiredLevels)) {
      // Action was not declared by controller - deny it
      return false;
    }
    $currentUserPosition = $this->getModule('user')->getCurrentUserPosition();
    // For multi-sites network admin role is undefined, let's do this here
    if (is_multisite() && is_admin() && is_super_admin()) {
      $currentUserPosition = CFS_ADMIN;
    }
    foreach ($requiredLevels as $levels) {
      if (!in_array($currentUserPosition, $levels, true)) {
        return false;
      }
    }
    if (!$this->_methodListed($action, $this->_getNonceExemptMethods($code, $controller)) && !$this->verifyNonce()) {
      return false;
    }
    return true;
  }
  /**
   * PRO add-on can be older than this plugin: before 1.5.0 it did not declare access for it's methods
   * @return bool
   */
  protected function _isLegacyPro()
  {
    if (self::$_legacyPro === null) {
      self::$_legacyPro = function_exists('getProPlugCodeCfs') && !defined('CFS_PRO_DECLARES_ACCESS');
    }
    return self::$_legacyPro;
  }
  protected function _getPublicMethods($code, $controller)
  {
    $methods = (array) $controller->getPublicMethods();
    if ($this->_isLegacyPro() && isset(self::$_legacyProPublicMethods[$code])) {
      $methods = array_merge($methods, self::$_legacyProPublicMethods[$code]);
    }
    return $methods;
  }
  protected function _getNonceExemptMethods($code, $controller)
  {
    $methods = (array) $controller->getNonceExemptMethods();
    if ($this->_isLegacyPro() && isset(self::$_legacyProNonceExemptMethods[$code])) {
      $methods = array_merge($methods, self::$_legacyProNonceExemptMethods[$code]);
    }
    return $methods;
  }
  /**
   * Check if action is allowed for any visitor, including not logged in users
   * @param string $code Code of controller that need to be checked
   * @param string $action Action that need to be checked
   * @return bool
   */
  public function isPublicAction($code, $action)
  {
    $controller = $this->_getDispatchController($code, $action);
    return $controller && $this->_methodListed(strtolower($action), $this->_getPublicMethods($code, $controller));
  }
  /**
   * Verify plugin nonce from current request
   * @return bool
   */
  public function verifyNonce()
  {
    $nonce = isset($_REQUEST['_wpnonce']) && is_string($_REQUEST['_wpnonce']) ? sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) : '';
    if (empty($nonce)) {
      return false;
    }
    return wp_verify_nonce($nonce, 'cfs_nonce') || wp_verify_nonce($nonce, 'cfs_nonce_frontend');
  }
  /**
   * Get controller of module, if action can be dispatched to it: it should be real public controller method,
   * not some name that will be routed through controllerCfs::__call() to it's model.
   * @param string $code Module code
   * @param string $action Action name
   * @return controllerCfs|null
   */
  protected function _getDispatchController($code, $action)
  {
    $mod = $this->getModule($code);
    $controller = $mod ? $mod->getController() : null;
    if (!$controller || empty($action) || !is_string($action) || !method_exists($controller, $action)) {
      return null;
    }
    $method = new ReflectionMethod($controller, $action);
    if (!$method->isPublic() || $method->isStatic()) {
      return null;
    }
    return $controller;
  }
  protected function _methodListed($action, $methods)
  {
    return !empty($methods) && is_array($methods) && in_array($action, array_map('strtolower', $methods), true);
  }
  /**
   * Get user levels required for action from controller permissions
   * @param array $permissions Result of controllerCfs::getPermissions()
   * @param string $action Action name in lower case
   * @return array List of conditions, each one - array of user levels, any of them pass condition. Empty if action is not declared.
   */
  protected function _getRequiredUserLevels($permissions, $action)
  {
    $res = [];
    if (empty($permissions) || !is_array($permissions)) {
      return $res;
    }
    if (isset($permissions[CFS_METHODS]) && is_array($permissions[CFS_METHODS])) {
      foreach ($permissions[CFS_METHODS] as $method => $levels) {
        if (strtolower($method) == $action) {
          $res[] = (array) $levels;
          break;
        }
      }
    }
    if (isset($permissions[CFS_USERLEVELS]) && is_array($permissions[CFS_USERLEVELS])) {
      foreach ($permissions[CFS_USERLEVELS] as $userlevel => $methods) {
        if ($this->_methodListed($action, (array) $methods)) {
          $res[] = [$userlevel];
          break;
        }
      }
    }
    return $res;
  }
  public function getRes()
  {
    return $this->_res;
  }
  public function execAfterWpInit()
  {
    $this->_doExec();
  }
  /**
   * Check if method for module require some special permission. We can detect users permissions only after wp init action was done.
   */
  protected function _execOnlyAfterWpInit()
  {
    $res = false;
    $mod = $this->getModule($this->_mod);
    $action = strtolower($this->_action);
    if ($mod) {
      $permissions = $mod->getController()->getPermissions();
      if (!empty($permissions)) {
        // Special permissions
        if (isset($permissions[CFS_METHODS]) && !empty($permissions[CFS_METHODS])) {
          foreach ($permissions[CFS_METHODS] as $method => $permissions) {
            // Make case-insensitive
            $permissions[CFS_METHODS][strtolower($method)] = $permissions;
          }
          if (array_key_exists($action, $permissions[CFS_METHODS])) {
            // Permission for this method exists
            $res = true;
          }
        }
        if (isset($permissions[CFS_USERLEVELS]) && !empty($permissions[CFS_USERLEVELS])) {
          foreach ($permissions[CFS_USERLEVELS] as $level => $methods) {
            if (!empty($methods)) {
              $res = true;
              break;
            }
          }
        }
      }
    }
    return $res;
  }
  protected function _execModules()
  {
    if ($this->_mod) {
      // If module exist and is active
      $mod = $this->getModule($this->_mod);
      if ($mod && !empty($this->_action)) {
        if ($this->_execOnlyAfterWpInit()) {
          add_action('init', [$this, 'execAfterWpInit']);
        } else {
          $this->_doExec();
        }
      }
    }
  }
  protected function _doExec()
  {
    $mod = $this->getModule($this->_mod);
    if ($mod && $this->checkPermissions($this->_mod, $this->_action)) {
      switch (reqCfs::getVar('reqType')) {
        case 'ajax':
          add_action('wp_ajax_' . $this->_action, [$mod->getController(), $this->_action]);
          // Not logged in visitors can reach only actions that controller declared as public
          if ($this->isPublicAction($this->_mod, $this->_action)) {
            add_action('wp_ajax_nopriv_' . $this->_action, [$mod->getController(), $this->_action]);
          }
          break;
        default:
          $this->_res = $mod->exec($this->_action);
          break;
      }
    }
  }
  protected function _extractTables($tablesDir = CFS_TABLES_DIR)
  {
    $mDirHandle = opendir($tablesDir);
    while (($file = readdir($mDirHandle)) !== false) {
      if (is_file($tablesDir . $file) && $file != '.' && $file != '..' && strpos($file, '.php')) {
        $this->_extractTable(str_replace('.php', '', $file), $tablesDir);
      }
    }
  }
  protected function _extractTable($tableName, $tablesDir = CFS_TABLES_DIR)
  {
    importClassCfs('noClassNameHere', $tablesDir . $tableName . '.php');
    $this->_tables[$tableName] = tableCfs::_($tableName);
  }
  /**
   * public alias for _extractTables method
   * @see _extractTables
   */
  public function extractTables($tablesDir)
  {
    if (!empty($tablesDir)) {
      $this->_extractTables($tablesDir);
    }
  }
  public function exec()
  {
    /**
         * @deprecated
         */
    /*if(!empty($this->_modules)) {
            foreach($this->_modules as $mod) {
                $mod->exec();
            }
        }*/
  }
  public function getTables()
  {
    return $this->_tables;
  }
  /**
   * Return table by name
   * @param string $tableName table name in database
   * @return object table
   * @example frameCfs::_()->getTable('products')->getAll()
   */
  public function getTable($tableName)
  {
    if (empty($this->_tables[$tableName])) {
      $this->_extractTable($tableName);
    }
    return $this->_tables[$tableName];
  }
  public function getModules($filter = [])
  {
    $res = [];
    if (empty($filter)) {
      $res = $this->_modules;
    } else {
      foreach ($this->_modules as $code => $mod) {
        if (isset($filter['type'])) {
          if (is_numeric($filter['type']) && $filter['type'] == $mod->getTypeID()) {
            $res[$code] = $mod;
          } elseif ($filter['type'] == $mod->getType()) {
            $res[$code] = $mod;
          }
        }
      }
    }
    return $res;
  }

  public function getModule($code)
  {
    return isset($this->_modules[$code]) ? $this->_modules[$code] : null;
  }
  public function inPlugin()
  {
    return $this->_inPlugin;
  }
  public function usePackAssets()
  {
    if (!$this->_useFootAssets && $this->getModule('options') && $this->getModule('options')->get('foot_assets')) {
      $this->_useFootAssets = true;
    }
    return $this->_useFootAssets;
  }
  /**
   * Push data to script array to use it all in addScripts method
   * @see wp_enqueue_script definition
   */

  public function addScript($handle, $src = '', $deps = [], $ver = false, $in_footer = false, $vars = [])
  {
    $src = empty($src) ? $src : uriCfs::_($src);
    if (!$ver) {
      $ver = CFS_VERSION;
    }
    if ($this->_scriptsInitialized) {
      wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    } else {
      $this->_scripts[] = [
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'in_footer' => $in_footer,
        'vars' => $vars,
      ];
    }
  }
  /**
   * Add all scripts from _scripts array to worcfsess
   */
  public function addScripts()
  {
    if (!empty($this->_scripts)) {
      foreach ($this->_scripts as $s) {
        wp_enqueue_script($s['handle'], $s['src'], $s['deps'], $s['ver'], $s['in_footer']);

        if ($s['vars'] || isset($this->_scriptsVars[$s['handle']])) {
          $vars = [];
          if ($s['vars']) {
            $vars = $s['vars'];
          }
          if ($this->_scriptsVars[$s['handle']]) {
            $vars = array_merge($vars, $this->_scriptsVars[$s['handle']]);
          }
          if ($vars) {
            foreach ($vars as $k => $v) {
              $v = is_array($v) ? $v : [$v];
              wp_localize_script($s['handle'], $k, $v);
            }
          }
        }
      }
    }
    $this->_scriptsInitialized = true;
  }
  public function addJSVar($script, $name, $val)
  {
    if ($this->_scriptsInitialized) {
      if ($this->_nonceIsInitialized && $script == 'nonceCfs') {
      } else {
        $val = is_array($val) ? $val : [$val];
        wp_localize_script($script, $name, $val);
      }
      if ($script == 'nonceCfs') {
        $this->_nonceIsInitialized = true;
      }
    } else {
      $this->_scriptsVars[$script][$name] = $val;
    }
  }

  public function addStyle($handle, $src = false, $deps = [], $ver = false, $media = 'all')
  {
    $src = empty($src) ? $src : uriCfs::_($src);
    if (!$ver) {
      $ver = CFS_VERSION;
    }
    if ($this->_stylesInitialized) {
      wp_enqueue_style($handle, $src, $deps, $ver, $media);
    } else {
      $this->_styles[] = [
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'media' => $media,
      ];
    }
  }
  public function addStyles()
  {
    if (!empty($this->_styles)) {
      foreach ($this->_styles as $s) {
        wp_enqueue_style($s['handle'], $s['src'], $s['deps'], $s['ver'], $s['media']);
      }
    }
    $this->_stylesInitialized = true;
  }
  //Very interesting thing going here.............
  public function loadPlugins()
  {
    require_once ABSPATH . 'wp-includes/pluggable.php';
  }
  public function loadWPSettings()
  {
    require_once ABSPATH . 'wp-settings.php';
  }
  public function loadLocale()
  {
    require_once ABSPATH . 'wp-includes/locale.php';
  }
  public function moduleActive($code)
  {
    return isset($this->_modules[$code]);
  }
  public function moduleExists($code)
  {
    if ($this->moduleActive($code)) {
      return true;
    }
    return isset($this->_allModules[$code]);
  }
  public function isTplEditor()
  {
    $tplEditor = reqCfs::getVar('tplEditor');
    return (bool) $tplEditor;
  }
  /**
   * This is custom method for each plugin and should be modified if you create copy from this instance.
   */
  public function isAdminPlugOptsPage()
  {
    $page = reqCfs::getVar('page');
    if (is_admin() && strpos((string) $page, frameCfs::_()->getModule('adminmenu')->getMainSlug()) !== false) {
      return true;
    }
    return false;
  }
  public function isAdminPlugPage()
  {
    if ($this->isAdminPlugOptsPage()) {
      return true;
    }
    return false;
  }
  public function licenseDeactivated()
  {
    return !$this->getModule('license') && $this->moduleExists('license');
  }
  public function savePluginActivationErrors()
  {
    update_option(CFS_CODE . '_plugin_activation_errors', ob_get_contents());
  }
  public function getActivationErrors()
  {
    return get_option(CFS_CODE . '_plugin_activation_errors');
  }
}

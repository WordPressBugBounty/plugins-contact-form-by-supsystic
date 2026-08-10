<?php
#[\AllowDynamicProperties]
class modInstallerCfs
{
  private static $_current = [];
  /**
   * Install new moduleCfs into plugin
   * @param string $module new moduleCfs data (@see classes/tables/modules.php)
   * @param string $path path to the main plugin file from what module is installed
   * @return bool true - if install success, else - false
   */
  public static function install($module, $path)
  {
    $exPlugDest = explode('plugins', $path);
    if (!empty($exPlugDest[1])) {
      $module['ex_plug_dir'] = str_replace(DS, '', $exPlugDest[1]);
    }
    $path = $path . DS . $module['code'];
    if (!empty($module) && !empty($path) && is_dir($path)) {
      if (self::isModule($path)) {
        $filesMoved = false;
        if (empty($module['ex_plug_dir'])) {
          $filesMoved = self::moveFiles($module['code'], $path);
        } else {
          $filesMoved = true;
        } //Those modules doesn't need to move their files
        if ($filesMoved) {
          if (frameCfs::_()->getTable('modules')->exists($module['code'], 'code')) {
            frameCfs::_()
              ->getTable('modules')
              ->delete(['code' => $module['code']]);
          }
          if ($module['code'] != 'license') {
            $module['active'] = 0;
          }
          global $wpdb;
          $tableName = $wpdb->prefix . 'cfs_modules';
          $res = $wpdb->insert($tableName, $module);
          self::_runModuleInstall($module);
          self::_installTables($module);
          return true;
        } else {
          errorsCfs::push(sprintf(__('Move files for %s failed'), $module['code']), errorsCfs::MOD_INSTALL);
        }
      } else {
        errorsCfs::push(sprintf(__('%s is not plugin module'), $module['code']), errorsCfs::MOD_INSTALL);
      }
    }
    return false;
  }
  protected static function _runModuleInstall($module, $action = 'install')
  {
    $moduleLocationDir = CFS_MODULES_DIR;
    if (!empty($module['ex_plug_dir'])) {
      $moduleLocationDir = utilsCfs::getPluginDir($module['ex_plug_dir']);
    }
    if (is_dir($moduleLocationDir . $module['code'])) {
      if (!class_exists($module['code'] . strFirstUp(CFS_CODE))) {
        importClassCfs($module['code'], $moduleLocationDir . $module['code'] . DS . 'mod.php');
      }
      $moduleClass = toeGetClassNameCfs($module['code']);
      $moduleObj = new $moduleClass($module);
      if ($moduleObj) {
        $moduleObj->$action();
      }
    }
  }
  /**
   * Check whether is or no module in given path
   * @param string $path path to the module
   * @return bool true if it is module, else - false
   */
  public static function isModule($path)
  {
    return true;
  }
  /**
   * Move files to plugin modules directory
   * @param string $code code for module
   * @param string $path path from what module will be moved
   * @return bool is success - true, else - false
   */
  public static function moveFiles($code, $path)
  {
    if (!is_dir(CFS_MODULES_DIR . $code)) {
      if (mkdir(CFS_MODULES_DIR . $code)) {
        utilsCfs::copyDirectories($path, CFS_MODULES_DIR . $code);
        return true;
      } else {
        errorsCfs::push(__('Can not create module directory. Try to set permission to ' . CFS_MODULES_DIR . ' directory 755 or 777', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
      }
    } else {
      return true;
    }
    return false;
  }
  private static function _getPluginLocations()
  {
    $locations = [];
    $plug = reqCfs::getVar('plugin');
    if (empty($plug)) {
      $plug = reqCfs::getVar('checked');
      $plug = $plug[0];
    }
    $locations['plugPath'] = plugin_basename(trim($plug));
    $locations['plugDir'] = dirname(WP_PLUGIN_DIR . DS . $locations['plugPath']);
    $locations['plugMainFile'] = WP_PLUGIN_DIR . DS . $locations['plugPath'];
    $locations['xmlPath'] = $locations['plugDir'] . DS . 'install.xml';
    $locations['extendModPath'] = $locations['plugDir'] . DS . 'install.php';
    return $locations;
  }
  private static function _getModulesFromXml($xmlPath)
  {
    if ($xml = utilsCfs::getXml($xmlPath)) {
      if (isset($xml->modules) && isset($xml->modules->mod)) {
        $modules = [];
        $xmlMods = $xml->modules->children();
        foreach ($xmlMods->mod as $mod) {
          $modules[] = $mod;
        }
        if (empty($modules)) {
          errorsCfs::push(__('No modules were found in XML file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        } else {
          return $modules;
        }
      } else {
        errorsCfs::push(__('Invalid XML file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
      }
    } else {
      errorsCfs::push(__('No XML file were found', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
    }
    return false;
  }
  private static function _getExtendModules($locations)
  {
    $modules = [];
    $isExtendModPath = file_exists($locations['extendModPath']);
    $modulesList = $isExtendModPath ? include $locations['extendModPath'] : self::_getModulesFromXml($locations['xmlPath']);
    if (!empty($modulesList)) {
      foreach ($modulesList as $mod) {
        $modData = $isExtendModPath ? $mod : utilsCfs::xmlNodeAttrsToArr($mod);
        array_push($modules, $modData);
      }
      if (empty($modules)) {
        errorsCfs::push(__('No modules were found in installation file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
      } else {
        return $modules;
      }
    } else {
      errorsCfs::push(__('No installation file were found', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
    }
    return false;
  }
  /**
   * Check whether modules is installed or not, if not and must be activated - install it
   * @param array $codes array with modules data to store in database
   * @param string $path path to plugin file where modules is stored (__FILE__ for example)
   * @return bool true if check ok, else - false
   */
  public static function check($extPlugName = '')
  {
    $locations = self::_getPluginLocations();
    if ($modules = self::_getExtendModules($locations)) {
      // Resolve "license" first: activate() below only lets any other module
      // in this extension come back on if a currently valid license exists,
      // so license itself must already be up to date by the time we get there.
      usort($modules, function ($a, $b) {
        $aCode = is_array($a) ? $a['code'] ?? '' : '';
        $bCode = is_array($b) ? $b['code'] ?? '' : '';
        return ($bCode === 'license' ? 1 : 0) - ($aCode === 'license' ? 1 : 0);
      });
      foreach ($modules as $m) {
        if (!empty($m)) {
          //If module Exists - just activate it, we can't check this using frameCfs::moduleExists because this will not work for multy-site WP
          $exist = frameCfs::_()->getTable('modules')->exists($m['code'], 'code');
          if ($exist /*frameCfs::_()->moduleExists($m['code'])*/) {
            self::activate($m);
          } else {
            //  if not - install it
            if (!self::install($m, $locations['plugDir'])) {
              errorsCfs::push(sprintf(__('Install %s failed'), $m['code']), errorsCfs::MOD_INSTALL);
            } else {
              self::activate($m);
            }
          }
        }
      }
    } else {
      errorsCfs::push(__('Error Activate module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
    }
    if (errorsCfs::haveErrors(errorsCfs::MOD_INSTALL)) {
      self::displayErrors();
      return false;
    }
    update_option(CFS_CODE . '_full_installed', 1);
    return true;
  }
  /**
   * Public alias for _getCheckRegPlugs()
   */
  /**
   * We will run this each time plugin start to check modules activation messages
   */
  public static function checkActivationMessages() {}
  /**
   * True only when this extension has a "license" module row and it is not
   * currently active -- i.e. when activate() below should withhold every
   * other module until a valid license re-enables them. Extensions that have
   * no license concept at all (no "license" row) are unaffected. Read
   * directly from the table (not via getModule('license'), which would
   * require that module to already be loaded in this request) so it reflects
   * any activation this same check() pass just performed.
   */
  private static function _licenseGateApplies()
  {
    // Query $wpdb directly rather than through dbCfs::get(), which is a stub
    // that always returns false (would make the gate apply permanently).
    global $wpdb;
    $active = $wpdb->get_var("SELECT active FROM {$wpdb->prefix}cfs_modules WHERE code = 'license'");
    return $active !== null && (int) $active !== 1;
  }
  /**
   * Deactivate module after deactivating external plugin
   */
  public static function deactivate()
  {
    $locations = self::_getPluginLocations();
    if ($modules = self::_getExtendModules($locations)) {
      foreach ($modules as $m) {
        if (frameCfs::_()->moduleActive($m['code'])) {
          //If module is active - then deacivate it

          global $wpdb;
          $tableName = $wpdb->prefix . 'cfs_modules';
          $id = frameCfs::_()->getModule($m['code'])->getID();
          $data = [
            'id' => $id,
            'active' => 0,
          ];
          $data_where = ['id' => $id];
          // $wpdb->update() returns 0 (falsy but not an error) when the row already
          // had active = 0 - only `false` means the query itself failed.
          $res = $wpdb->update($tableName, $data, $data_where);
          if ($res === false) {
            errorsCfs::push(__('Error Deactivation module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
          }
        }
      }
    }
    if (errorsCfs::haveErrors(errorsCfs::MOD_INSTALL)) {
      self::displayErrors(false);
      return false;
    }
    return true;
  }
  public static function activate($modDataArr)
  {
    if (!empty($modDataArr['code']) && !frameCfs::_()->moduleActive($modDataArr['code'])) {
      // Only "license" comes back automatically just because the extension
      // plugin itself was (re)activated. Every other of its modules must only
      // be reactivated once a currently valid license exists -- otherwise a
      // bare deactivate/reactivate of the plugin would silently re-enable
      // every paid feature regardless of license state.
      if ($modDataArr['code'] !== 'license' && self::_licenseGateApplies()) {
        return;
      }
      if (!frameCfs::_()->getModule('options')) {
        // 'options' is a core module of the base plugin; without it we can't
        // reach the modules table model at all. Bail instead of fataling on a
        // null method call.
        errorsCfs::push(__('Core "options" module is not active, cannot activate modules', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        return;
      }
      //If module is not active - then acivate it
      $res = frameCfs::_()
        ->getModule('options')
        ->getModel('modules')
        ->put([
          'code' => $modDataArr['code'],
          'active' => 1,
        ]);

      if (!$res) {
        errorsCfs::push(__('Error Activating module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
      } else {
        $dbModData = frameCfs::_()
          ->getModule('options')
          ->getModel('modules')
          ->get(['code' => $modDataArr['code']]);
        if (!empty($dbModData) && !empty($dbModData[0])) {
          $modDataArr['ex_plug_dir'] = $dbModData[0]['ex_plug_dir'];
        }
        self::_runModuleInstall($modDataArr, 'activate');
      }
    }
  }
  /**
   * Display all errors for module installer, must be used ONLY if You realy need it
   */
  public static function displayErrors($exit = true)
  {
    $errors = errorsCfs::get(errorsCfs::MOD_INSTALL);
    foreach ($errors as $e) {
      echo '<b style="color: red;">' . $e . '</b><br />';
    }
    if ($exit) {
      exit();
    }
  }
  public static function uninstall()
  {
    $locations = self::_getPluginLocations();
    $optionsModule = frameCfs::_()->getModule('options');
    if ($modules = self::_getExtendModules($locations)) {
      foreach ($modules as $m) {
        self::_uninstallTables($m);
        if ($optionsModule) {
          $optionsModule->getModel('modules')->delete(['code' => $m['code']]);
        }
        utilsCfs::deleteDir(CFS_MODULES_DIR . $m['code']);
      }
    }
  }
  protected static function _uninstallTables($module)
  {
    if (is_dir(CFS_MODULES_DIR . $module['code'] . DS . 'tables')) {
      $tableFiles = utilsCfs::getFilesList(CFS_MODULES_DIR . $module['code'] . DS . 'tables');
      if (!empty($tableNames)) {
        foreach ($tableFiles as $file) {
          $tableName = str_replace('.php', '', $file);
          if (frameCfs::_()->getTable($tableName)) {
            frameCfs::_()->getTable($tableName)->uninstall();
          }
        }
      }
    }
  }
  public static function _installTables($module, $action = 'install')
  {
    $modDir = empty($module['ex_plug_dir']) ? CFS_MODULES_DIR . $module['code'] . DS : utilsCfs::getPluginDir($module['ex_plug_dir']) . $module['code'] . DS;
    if (is_dir($modDir . 'tables')) {
      $tableFiles = utilsCfs::getFilesList($modDir . 'tables');
      if (!empty($tableFiles)) {
        frameCfs::_()->extractTables($modDir . 'tables' . DS);
        foreach ($tableFiles as $file) {
          $tableName = str_replace('.php', '', $file);
          if (frameCfs::_()->getTable($tableName)) {
            frameCfs::_()->getTable($tableName)->$action();
          }
        }
      }
    }
  }
}

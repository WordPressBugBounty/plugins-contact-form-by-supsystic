<?php
#[\AllowDynamicProperties]
class errorsCfs
{
  const FATAL = 'fatal';
  const MOD_INSTALL = 'mod_install';
  private static $errors = [];
  private static $haveErrors = false;

  public static $current = [];

  public static function push($error, $type = 'common')
  {
    if (!isset(self::$errors[$type])) {
      self::$errors[$type] = [];
    }
    if (is_array($error)) {
      self::$errors[$type] = array_merge(self::$errors[$type], $error);
    } else {
      self::$errors[$type][] = $error;
    }
    self::$haveErrors = true;

    if ($type == 'session') {
      self::setSession(self::$errors[$type]);
    }
  }
  public static function setSession($error)
  {
    $sesErrors = self::getSession();
    if (empty($sesErrors)) {
      $sesErrors = [];
    }
    if (is_array($error)) {
      $sesErrors = array_merge($sesErrors, $error);
    } else {
      $sesErrors[] = $error;
    }
    reqCfs::setVar('sesErrors', $sesErrors, 'session');
  }
  /**
   * Messages of our own redirects ("cfsErrors" in the address). Anyone can put anything there, so they are only shown in
   * the admin area, on the plugin pages, to the users who manage the plugin; nothing is printed on the site.
   */
  public static function init()
  {
    if (!is_admin()) {
      return;
    }
    $cfsErrors = reqCfs::getVar('cfsErrors');
    if (empty($cfsErrors)) {
      return;
    }
    foreach (is_array($cfsErrors) ? $cfsErrors : [$cfsErrors] as $error) {
      if (!is_string($error)) {
        continue;
      }
      $error = trim(stripslashes($error));
      if ($error !== '') {
        self::$current[] = $error;
      }
    }
    if (!empty(self::$current)) {
      add_action('admin_notices', ['errorsCfs', 'showAdminErrors']);
    }
  }
  public static function showAdminErrors()
  {
    if (empty(self::$current) || !frameCfs::_()->isAdminPlugPage()) {
      return;
    }
    $adminmenu = frameCfs::_()->getModule('adminmenu');
    if (!$adminmenu || !current_user_can($adminmenu->getMainCap())) {
      return;
    }
    $html = '';
    foreach (self::$current as $error) {
      $html .= '<div class="error"><p><strong style="font-size: 15px;">' . esc_html($error) . '</strong></p></div>';
    }
    echo $html;
  }
  public static function getSession()
  {
    return reqCfs::getVar('sesErrors', 'session');
  }
  public static function clearSession()
  {
    reqCfs::clearVar('sesErrors', 'session');
  }
  public static function get($type = '')
  {
    $res = [];
    if (!empty(self::$errors)) {
      if (empty($type)) {
        foreach (self::$errors as $e) {
          foreach ($e as $error) {
            $res[] = $error;
          }
        }
      } else {
        $res = self::$errors[$type];
      }
    }
    return $res;
  }
  public static function haveErrors($type = '')
  {
    if (empty($type)) {
      return self::$haveErrors;
    } else {
      return isset(self::$errors[$type]);
    }
  }
}

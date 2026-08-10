<?php
#[\AllowDynamicProperties]
class dispatcherCfs
{
  protected static $_pref = 'cfs_';

  public static function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1)
  {
    if (strpos($tag, 'cfs_') === false) {
      $tag = self::$_pref . $tag;
    }
    return add_action($tag, $function_to_add, $priority, $accepted_args);
  }
  public static function doAction($tag)
  {
    $allArgs = func_get_args();
    if (strpos($tag, 'cfs_') === false) {
      $tag = self::$_pref . $tag;
    }
    $numArgs = count($allArgs);
    if ($numArgs > 1) {
      $args = [$tag];
      for ($i = 1; $i < $numArgs; $i++) {
        $args[] = $allArgs[$i];
      }
      return call_user_func_array('do_action', $args);
    }
    return do_action($tag);
  }
  public static function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
  {
    if (strpos($tag, 'cfs_') === false) {
      $tag = self::$_pref . $tag;
    }
    return add_filter($tag, $function_to_add, $priority, $accepted_args);
  }
  public static function applyFilters($tag, $value)
  {
    $allArgs = func_get_args();
    if (strpos($tag, 'cfs_') === false) {
      $tag = self::$_pref . $tag;
    }
    $numArgs = count($allArgs);
    if ($numArgs > 2) {
      $args = [$tag];
      for ($i = 1; $i < $numArgs; $i++) {
        $args[] = $allArgs[$i];
      }
      return call_user_func_array('apply_filters', $args);
    } else {
      return apply_filters($tag, $value);
    }
  }
}

<?php
#[\AllowDynamicProperties]
class dateCfs
{
  public static function _($time = null)
  {
    if (is_null($time)) {
      $time = time();
    }
    return date(CFS_DATE_FORMAT_HIS, $time);
  }
}

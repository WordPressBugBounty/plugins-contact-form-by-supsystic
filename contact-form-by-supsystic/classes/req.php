<?php
#[\AllowDynamicProperties]
class reqCfs
{
  protected static $_requestData;
  protected static $_requestMethod;
  protected static $_allowedHtml;

  public static function init()
  {
    // Empty for now
  }
  public static function startSession()
  {
    if (!utilsCfs::isSessionStarted()) {
      session_start();
    }
  }
  /**
   * @param string $name key in variables array
   * @param string $from from where get result = "all", "input", "get"
   * @param mixed $default default value - will be returned if $name wasn't found
   * @return mixed value of a variable, if didn't found - $default (NULL by default)
   */

  public static function supStrRgbToHex($color)
  {
    preg_match_all('/\((.+?)\)/', $color, $matches);
    if (!empty($matches[1][0])) {
      $rgb = explode(',', $matches[1][0]);
      $size = count($rgb);
      if ($size == 3 || $size == 4) {
        if ($size == 4) {
          $alpha = array_pop($rgb);
          $alpha = floatval(trim($alpha));
          $alpha = ceil(($alpha * (255 * 100)) / 100);
          array_push($rgb, $alpha);
        }

        $result = '#';
        foreach ($rgb as $row) {
          $result .= str_pad(dechex(trim($row)), 2, '0', STR_PAD_LEFT);
        }

        return $result;
      }
    }

    return false;
  }
  public static function sanitizeString($str)
  {
    $str = str_replace(['{{', '&#123;&#123;', '\u007B\u007B', '{%', '&#37;', '}}', '&#125;&#125;', '\u007D\u007D', '%}'], '', $str);

    $allowedHtml = self::getAllowedHtml();
    if (!empty($str) && is_string($str)) {
      $str = htmlspecialchars_decode($str);

      $re = '/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/';
      $str = preg_replace_callback(
        $re,
        function ($m) {
          return self::supStrRgbToHex($m[0]);
        },
        $str,
      );

      $re = '/rgba\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\d*(?:\.\d+)?\)/';
      $str = preg_replace_callback(
        $re,
        function ($m) {
          return self::supStrRgbToHex($m[0]);
        },
        $str,
      );
      $str = wp_kses($str, $allowedHtml);
    }
    return $str;
  }
  public static function getAllowedHtml()
  {
    if (empty(self::$_allowedHtml)) {
      $allowedHtml = wp_kses_allowed_html();

      $newAllowedHtml = [
        'li' => ['style' => 1, 'class' => 1, 'id' => 1],
        'ul' => ['style' => 1, 'class' => 1, 'id' => 1],
        'ol' => ['style' => 1, 'class' => 1, 'id' => 1],
        'i' => ['style' => 1, 'class' => 1, 'id' => 1],
        'img' => ['src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'alt' => 1, 'border' => 1],
        'video' => ['src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'poster' => 1, 'autoplay' => 1, 'controls' => 1, 'crossorigin' => 1, 'autobuffer' => 1, 'buffered' => 1, 'played' => 1, 'loop' => 1, 'muted' => 1, 'preload' => 1],
        'track' => ['src' => 1, 'kind' => 1, 'label' => 1, 'srclang' => 1],
        'source' => ['src' => 1, 'type' => 1],
        'audio' => ['src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'autoplay' => 1, 'controls' => 1, 'crossorigin' => 1, 'loop' => 1, 'muted' => 1, 'preload' => 1],
        'iframe' => ['src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'title' => 1, 'allow' => 1, 'allowfullscreen' => 1, 'allowpaymentrequest' => 1, 'csp' => 1, 'height' => 1, 'loading' => 1, 'name' => 1, 'referrerpolicy' => 1, 'sandbox' => 1],
      ];

      $allowedDiv = [
        'div' => [
          'field_shell_styles' => 1,
          'field_shell_classes' => 1,
          'field_html' => 1,
          'field_id' => 1,
          'data-number' => 1,
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'title' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-enb-color' => 1,
          'data-enb-schedule' => 1,
          'data-schedule-from' => 1,
          'data-schedule-to' => 1,
          'data-enb-badge' => 1,
          'data-badge-badge_txt_color' => 1,
          'data-badge-badge_bg_color' => 1,
          'data-badge-badge_name' => 1,
          'data-badge-badge_pos' => 1,
          'data-old-number' => 1,
          'data-selected-number' => 1,
          'data-switch-type' => 1,
          'data-toggle-0' => 1,
          'data-toggle-1' => 1,
          'data-toggle-2' => 1,
          'data-toggle-3' => 1,
          'data-toggle-4' => 1,
          'data-toggle-5' => 1,
          'data-toggle-6' => 1,
          'data-toggle-7' => 1,
          'data-toggle-8' => 1,
          'data-toggle-9' => 1,
          'data-toggle-10' => 1,
        ],
        'small' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'span' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'pre' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'p' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'br' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'hr' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'hgroup' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h1' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h2' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h3' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h4' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h5' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'h6' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'ul' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'ol' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'li' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'dl' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'dt' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'dd' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'strong' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'em' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'b' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'i' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'u' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'img' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'a' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'link' => 1,
          'rel' => 1,
          'href' => 1,
          'target' => 1,
        ],
        'abbr' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'address' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'blockquote' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'area' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'audio' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'video' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'form' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'action' => 1,
          'target' => 1,
          'method' => 1,
        ],
        'fieldset' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'label' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'input' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'value' => 1,
          'type' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'name' => 1,
          'src' => 1,
          'border' => 1,
          'alt' => 1,
          'name' => 1,
          'maxlength' => 1,
        ],
        'textarea' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'caption' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'table' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'tbody' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'td' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'tfoot' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'th' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'thead' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'tr' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'iframe' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'select' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
        ],
        'option' => [
          'style' => 1,
          'title' => 1,
          'align' => 1,
          'class' => 1,
          'width' => 1,
          'height' => 1,
          'id' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'data-type' => 1,
          'data-el' => 1,
          'data-color' => 1,
          'data-icon' => 1,
          'data-bgcolor-elements' => 1,
          'data-bgcolor-to' => 1,
          'data-mce-style' => 1,
          'selected' => 1,
          'data-number' => 1,
          'value' => 1,
        ],
        'sup' => [],
        'sub' => [],
      ];

      $allowedHtml = array_merge($allowedHtml, $allowedDiv);
      self::$_allowedHtml = array_merge($allowedHtml, $newAllowedHtml);
    }
    return self::$_allowedHtml;
  }

  //  static public function sanitize_array( &$array, $parentArr = '' ) {
  //      $allowed = '<div><span><pre><p><br><hr><hgroup><h1><h2><h3><h4><h5><h6>
  //        <ul><ol><li><dl><dt><dd><strong><em><b><i><u>
  //        <img><a><abbr><address><blockquote><area><audio><video>
  //        <form><fieldset><label><input><textarea>
  //        <caption><table><tbody><td><tfoot><th><thead><tr>
  //        <iframe><select><option>';
  //      $keys = array('sub_txt_confirm_mail_message', 'msg', 'sub_txt_subscriber_mail_subject', 'sub_txt_subscriber_mail_message', 'sub_new_message', 'field_wrapper');
  //      foreach ($array as $key => &$value) {
  //        if (in_array($key, $keys, true)) { // third param true, because if its false when key = 0 => in_array = true
  //          if (!is_array($value)) {
  //           //  $value = strip_tags($value, $allowed);
  //           $value = self::sanitizeString($value);
  //            // $value = wp_kses_post($value);
  //          }
  //        } else {
  //          if( !is_array($value) )	{
  //           $isHtmlDelimValue = false;
  //           if (is_array($parentArr)) {
  //             foreach ($parentArr as $subArrKey => $subArrVal) {
  //               // if ($subArrVal == 'htmldelim' && $key == 'value') {
  //               if ($subArrKey == 'html' && $subArrVal == 'htmldelim' && $key == 'value') {
  //                 $isHtmlDelimValue = true;
  //                 break;
  //               }
  //             }
  //           }
  //           if ($key == 'html') {
  //             $isHtmlDelimValue = true;
  //           }
  //           if ($isHtmlDelimValue) {
  //             //$value = strip_tags($value, $allowed);
  //             $value = self::sanitizeString($value);
  //           } else {
  //             $value = wp_kses_post(sanitize_text_field($value));
  //           }
  //          } else {
  //           if( is_array($value) )	{
  //             self::sanitize_array($value, $value);
  //           } else {
  //             self::sanitize_array($value);
  //           }
  //          }
  //        }
  //      }
  //      return $array;
  //    }

  public static function sanitize_array(&$array, $parentKey = '')
  {
    // $allowed = '<div>,<span>,<pre>,<p>,<small>,<br>,<hr>,<hgroup>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,
    //   <ul>,<ol>,<li>,<dl>,<dt>,<dd>,<strong>,<em>,<b>,<i>,<u>,
    //   <img>,<a>,<abbr>,<address>,<blockquote>,<area>,<audio>,<video>,
    //   <form>,<fieldset>,<label>,<input>,<textarea>,
    //   <caption>,<table>,<tbody>,<td>,<tfoot>,<th>,<thead>,<tr>,
    //   <iframe>,<select>,<option>';
    foreach ($array as $key => &$value) {
      // $keys = array(
      //    'txt_item_html',
      //    'img_item_html',
      //    'icon_item_html',
      //    'new_cell_html',
      //    'new_column_html'
      // );
      // if ((in_array($parentKey, $keys) && $key == 'val') || $key == 'html') {
      //    $re = '/data-toggle-[0-9]+=\\\\"(.*?)\\\\"/m';
      //    $newValue = preg_replace_callback($re, function ($matches) {
      //       $patterns[0] = '/</';
      //       $patterns[1] = '/>/';
      //       $replacements[1] = '&lt;';
      //       $replacements[0] = '&gt;';
      //       $string = preg_replace($patterns, $replacements, $matches[0]);
      //       return $string;
      //    }
      //    , $value);
      //    $value = $newValue;
      //    $value = strip_tags($value, $allowed);
      //    $value = self::sanitizeString($value);
      // }
      // else {
      if (!is_array($value)) {
        $value = self::sanitizeString($value);
      } else {
        $parentKey = $key;
        self::sanitize_array($value, $parentKey);
      }
      //}
    }
    return $array;
  }

  public static function getVar($name, $from = 'all', $default = null)
  {
    $from = strtolower($from);
    if ($from == 'all') {
      if (isset($_GET[$name])) {
        $from = 'get';
      } elseif (isset($_POST[$name])) {
        $from = 'post';
      }
    }

    switch ($from) {
      case 'get':
        if (isset($_GET[$name])) {
          if (is_array($_GET[$name])) {
            return self::sanitize_array($_GET[$name]);
          } else {
            return sanitize_text_field($_GET[$name]);
          }
        }
        break;
      case 'post':
        if (isset($_POST[$name])) {
          if (is_array($_POST[$name])) {
            return self::sanitize_array($_POST[$name]);
          } else {
            return sanitize_text_field($_POST[$name]);
          }
        }
        break;
      case 'session':
        if (isset($_SESSION[$name])) {
          if (is_array($_SESSION[$name])) {
            return self::sanitize_array($_SESSION[$name]);
          } else {
            return sanitize_text_field($_SESSION[$name]);
          }
        }
        break;
      case 'server':
        if (isset($_SERVER[$name])) {
          if (is_array($_SERVER[$name])) {
            return self::sanitize_array($_SERVER[$name]);
          } else {
            return sanitize_text_field($_SERVER[$name]);
          }
        }
        break;
      case 'cookie':
        if (isset($_COOKIE[$name])) {
          $value = sanitize_text_field($_COOKIE[$name]);
          if (strpos($value, '_JSON:') === 0) {
            $value = explode('_JSON:', $value);
            $value = utilsCfs::jsonDecode(array_pop($value));
          }
          if (is_array($value)) {
            $value = self::sanitize_array($value);
            return $value;
          } elseif (is_string($value)) {
            $value = sanitize_text_field($value);
            return $value;
          }
        }
        break;
    }
    return $default;
  }
  public static function isEmpty($name, $from = 'all')
  {
    $val = self::getVar($name, $from);
    return empty($val);
  }
  public static function setVar($name, $val, $in = 'input', $params = [])
  {
    $in = strtolower($in);
    if (is_array($val)) {
      $val = self::sanitize_array($val);
    } else {
      $val = sanitize_text_field($val);
    }
    switch ($in) {
      case 'get':
        $_GET[$name] = $val;
        break;
      case 'post':
        $_POST[$name] = $val;
        break;
      case 'session':
        $_SESSION[$name] = $val;
        break;
      case 'cookie':
        $expire = isset($params['expire']) ? time() + $params['expire'] : 0;
        $path = isset($params['path']) ? $params['path'] : '/';
        if (is_array($val) || is_object($val)) {
          $saveVal = '_JSON:' . utilsCfs::jsonEncode($val);
        } else {
          $saveVal = $val;
        }
        setcookie($name, $saveVal, $expire, $path);
        break;
    }
  }
  public static function clearVar($name, $in = 'input', $params = [])
  {
    $in = strtolower($in);
    switch ($in) {
      case 'get':
        if (isset($_GET[$name])) {
          unset($_GET[$name]);
        }
        break;
      case 'post':
        if (isset($_POST[$name])) {
          unset($_POST[$name]);
        }
        break;
      case 'session':
        if (isset($_SESSION[$name])) {
          unset($_SESSION[$name]);
        }
        break;
      case 'cookie':
        $path = isset($params['path']) ? $params['path'] : '/';
        setcookie($name, '', time() - 3600, $path);
        break;
    }
  }
  public static function get($what)
  {
    $what = strtolower($what);
    switch ($what) {
      case 'get':
        if (is_array($_GET)) {
          return self::sanitize_array($_GET);
        } else {
          return sanitize_text_field($_GET);
        }
        break;
      case 'post':
        if (is_array($_POST)) {
          return self::sanitize_array($_POST);
        } else {
          return sanitize_text_field($_POST);
        }
        break;
      case 'session':
        if (is_array($_SESSION)) {
          return self::sanitize_array($_SESSION);
        } else {
          return sanitize_text_field($_SESSION);
        }
        break;
    }
    return null;
  }
  public static function getMethod()
  {
    if (!self::$_requestMethod) {
      self::$_requestMethod = strtoupper(self::getVar('method', 'all', $_SERVER['REQUEST_METHOD']));
    }
    return self::$_requestMethod;
  }
  public static function getAdminPage()
  {
    $pagePath = self::getVar('page');
    if (!empty($pagePath) && strpos($pagePath, '/') !== false) {
      $pagePath = explode('/', $pagePath);
      return str_replace('.php', '', $pagePath[count($pagePath) - 1]);
    }
    return false;
  }
  public static function getRequestUri()
  {
    return $_SERVER['REQUEST_URI'];
  }
  public static function getMode()
  {
    $mod = '';
    if (!($mod = self::getVar('mod'))) {
      //Frontend usage
      $mod = self::getVar('page');
    } //Admin usage
    return $mod;
  }
}

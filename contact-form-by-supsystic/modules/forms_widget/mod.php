<?php
class forms_widgetCfs extends moduleCfs
{
  public function init()
  {
    parent::init();
    // The modules start on "init" (priority 5), after WordPress ran "widgets_init" (init, priority 1): the widget was
    // never registered then, and widgets placed in sidebars printed nothing. Registered now, with its placed instances
    if (did_action('widgets_init')) {
      $this->registerWidget();
      global $wp_widget_factory;
      if (isset($wp_widget_factory->widgets['formsWidgetWpCfs']) && $wp_widget_factory->widgets['formsWidgetWpCfs'] instanceof WP_Widget) {
        $wp_widget_factory->widgets['formsWidgetWpCfs']->_register();
      }
    } else {
      add_action('widgets_init', [$this, 'registerWidget']);
    }
    add_filter('widget_types_to_hide_from_legacy_widget_block', [$this, 'hideFromLegacyBlock']);
  }
  /**
   * While CES runs in its CES mode, new forms are CES forms (block "CES Form", widget "CES Form by Supsystic"): this
   * widget is not offered in the block widget editor. Widgets placed before keep working and stay editable there;
   * the classic widget screen (Classic Widgets plugin) lists it as before.
   */
  public function hideFromLegacyBlock($types)
  {
    $types = is_array($types) ? $types : [];
    if (function_exists('ppsgen_is_ces_mode') && ppsgen_is_ces_mode()) {
      $types[] = 'formswidgetwpcfs';
    }
    return $types;
  }
  public function registerWidget()
  {
    return register_widget('formsWidgetWpCfs');
  }
}
/**
 * Forms Widget class
 */
class formsWidgetWpCfs extends WP_Widget
{
  public function __construct()
  {
    $widgetOps = [
      'classname' => 'formsWidgetWpCfs',
      'description' => __('Shows a form made in Contact Form (Classic).', CFS_LANG_CODE),
    ];
    $control_ops = [
      'id_base' => 'formsWidgetWpCfs',
    ];
    // id_base "formswidgetwpcfs" (WordPress lowercases it) stays: widgets placed before keep their settings
    parent::__construct('formsWidgetWpCfs', __('Contact Form (Classic) by Supsystic', CFS_LANG_CODE), $widgetOps);
  }
  public function widget($args, $instance)
  {
    frameCfs::_()->getModule('forms_widget')->getView()->displayWidget($args, $instance);
  }
  public function update($new_instance, $old_instance)
  {
    return $new_instance;
  }
  public function form($instance)
  {
    frameCfs::_()->getModule('forms_widget')->getView()->displayForm($instance, $this);
  }
}

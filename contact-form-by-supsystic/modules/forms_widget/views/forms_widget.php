<?php

class forms_widgetViewCfs extends viewCfs
{
  public function displayForm($data, $widget)
  {
    $formsList = [];
    global $wpdb;
    $forms = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cfs_forms WHERE original_id != 0 AND ab_id = 0", ARRAY_A);
    if ($forms) {
      foreach ($forms as $f) {
        $formsList[$f['id']] = $f['label'];
      }
    }
    $this->assign('formsList', $formsList);
    $this->assign('createFormUrl', frameCfs::_()->getModule('options')->getTabUrl('forms_add_new'));
    $this->assign('data', $data);
    $this->assign('widget', $widget);
    parent::display('formsWidgetForm');
  }
  public function displayWidget($args, $instance)
  {
    $title = empty($instance['title']) ? '' : $instance['title'];

    echo $args['before_widget'];
    if ($title) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    if (isset($instance['id']) && !empty($instance['id'])) {
      echo frameCfs::_()
        ->getModule('forms')
        ->getView()
        ->showForm(['id' => $instance['id']]);
    }

    echo $args['after_widget'];
  }
}
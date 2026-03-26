<?php
class statisticsCfs extends moduleCfs
{
  private $_types = [];
  public function getTypes()
  {
    if (empty($this->_types)) {
      $this->_types = [
        'show' => ['id' => 1, 'label' => __('Displayed', CFS_LANG_CODE)],
        'submit' => ['id' => 2, 'label' => __('Submitted', CFS_LANG_CODE)],
        'submit_success' => ['id' => 3, 'label' => __('Submitted Success', CFS_LANG_CODE)],
        'submit_error' => ['id' => 4, 'label' => __('Submitted Fail', CFS_LANG_CODE)],
      ];
    }
    return $this->_types;
  }
  public function getTypeIdByCode($code)
  {
    $this->getTypes();
    return isset($this->_types[$code]) ? $this->_types[$code]['id'] : false;
  }
}

<?php
class optionsModelCfs extends modelCfs
{
  private $_values = [];
  private $_valuesLoaded = false;
  private static $_restrictedOptionValues = [
    'mail_send_engine' => ['wp_mail', 'smtp', 'sendmail'],
    'smtp_secure' => ['', 'ssl', 'tls'],
  ];

  public function get($optKey)
  {
    $this->_loadOptValues();
    return isset($this->_values[$optKey]) ? $this->_values[$optKey]['value'] : false;
  }
  public function isEmpty($optKey)
  {
    $value = $this->get($optKey);
    return $value === false;
  }
  public function save($optKey, $val, $ignoreDbUpdate = false)
  {
    if (isset(self::$_restrictedOptionValues[$optKey]) && !in_array($val, self::$_restrictedOptionValues[$optKey], true)) {
      return;
    }
    if (!$this->_isValidOptValue($optKey, $val)) {
      return;
    }
    $this->_loadOptValues();
    if (!isset($this->_values[$optKey]) || $this->_values[$optKey]['value'] !== $val) {
      if (isset($this->_values[$optKey]) || !isset($this->_values[$optKey]['value'])) {
        $this->_values[$optKey] = [];
      }
      $this->_values[$optKey]['value'] = $val;
      $this->_values[$optKey]['changed_on'] = time();
      if (!$ignoreDbUpdate) {
        $this->_updateOptsInDb();
      }
    }
  }
  public function getAll()
  {
    $this->_loadOptValues();
    return $this->_values;
  }
  /**
   * Pass throught refferer - to not lose memory for copy of same opts array
   */
  public function fillInValues(&$options)
  {
    $this->_loadOptValues();
    foreach ($options as $cKey => $cData) {
      foreach ($cData['opts'] as $optKey => $optData) {
        $value = 0;
        $changedOn = 0;
        // Retrive value from saved options
        if (isset($this->_values[$optKey])) {
          $value = $this->_values[$optKey]['value'];
          $changedOn = isset($this->_values[$optKey]['changed_on']) ? $this->_values[$optKey]['changed_on'] : '';
        } elseif (isset($optData['def'])) {
          // If there were no saved data - set it as default
          $value = $optData['def'];
        }
        $options[$cKey]['opts'][$optKey]['value'] = $value;
        $options[$cKey]['opts'][$optKey]['changed_on'] = $changedOn;
        if (!isset($this->_values[$optKey]['value'])) {
          $this->_values[$optKey]['value'] = $value;
        }
      }
    }
  }
  public function saveGroup($d = [])
  {
    if (isset($d['opt_values']) && is_array($d['opt_values']) && !empty($d['opt_values'])) {
      if (isset($d['opt_values']['sendmail_path']) && !$this->_isValidOptValue('sendmail_path', $d['opt_values']['sendmail_path'])) {
        // Field is always submitted with mail options, but matters only for Sendmail engine
        if (isset($d['opt_values']['mail_send_engine']) && $d['opt_values']['mail_send_engine'] === 'sendmail') {
          $this->pushError(__('Invalid Sendmail Path: it should be absolute path to sendmail binary, for example /usr/sbin/sendmail', CFS_LANG_CODE), 'opt_values[sendmail_path]');
          return false;
        }
        unset($d['opt_values']['sendmail_path']);
      }
      dispatcherCfs::doAction('beforeSaveOpts', $d);
      foreach ($d['opt_values'] as $code => $val) {
        $this->save($code, $val, true);
      }
      $this->_updateOptsInDb();
      return true;
    } else {
      $this->pushError(__('Empty data to save option', CFS_LANG_CODE));
    }
    return false;
  }
  private function _isValidOptValue($optKey, $val)
  {
    switch ($optKey) {
      case 'sendmail_path':
        return utilsCfs::isValidSendmailPath($val);
    }
    return true;
  }
  private function _updateOptsInDb()
  {
    update_option(CFS_CODE . '_opts_data', $this->_values);
  }
  private function _loadOptValues()
  {
    if (!$this->_valuesLoaded) {
      $this->_values = get_option(CFS_CODE . '_opts_data');
      if (empty($this->_values)) {
        $this->_values = [];
      }
      $this->_valuesLoaded = true;
    }
  }
}

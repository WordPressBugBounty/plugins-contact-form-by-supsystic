<?php
$title = CFS_WP_PLUGIN_NAME; ?>
<html>
    <head>
        <title><?php _e($title); ?></title>
    </head>
    <body>
<div style="position: fixed; margin-left: 40%; margin-right: auto; text-align: center; background-color: #fdf5ce; padding: 10px; margin-top: 10%;">
    <div><?php _e($title); ?></div>
    <?php echo htmlCfs::formStart('deactivatePlugin', ['action' => $this->REQUEST_URI, 'method' => $this->REQUEST_METHOD]); ?>
    <?php
    $formData = [];
    switch ($this->REQUEST_METHOD) {
      case 'GET':
        $formData = $this->GET;
        break;
      case 'POST':
        $formData = $this->POST;
        break;
    }
    foreach ($formData as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $subKey => $subVal) {
          echo htmlCfs::hidden($key . '[' . $subKey . ']', ['value' => $subVal]);
        }
      } else {
        echo htmlCfs::hidden($key, ['value' => $val]);
      }
    }
    ?>
        <table width="100%">
            <tr>
                <td><?php _e('Delete Plugin Data (options, setup data, database tables, etc.)', CFS_LANG_CODE); ?>:</td>
                <td><?php echo htmlCfs::radiobuttons('deleteOptions', ['options' => ['No', 'Yes']]); ?></td>
            </tr>
        </table>
    <?php echo htmlCfs::submit('toeGo', ['value' => __('Done', CFS_LANG_CODE)]); ?>
    <?php echo htmlCfs::formEnd(); ?>
    </div>
</body>
</html>
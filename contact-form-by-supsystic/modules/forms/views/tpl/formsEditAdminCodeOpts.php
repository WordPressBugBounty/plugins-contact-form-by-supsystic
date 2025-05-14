<?php 
	$fieldWrapper = $this->form['params']['tpl']['field_wrapper'];
	$fieldWrapper = str_replace("[field_shell_classes]","field_shell_classes", $fieldWrapper);
	$fieldWrapper = str_replace("[field_shell_styles]","field_shell_styles", $fieldWrapper);
	$fieldWrapper = reqCfs::sanitizeString($fieldWrapper);
?>
<p class="alert alert-danger">
	<?php //printf(__('Edit this ONLY if you know basics of HTML, CSS and have been acquainted with the rules of template editing described <a target="_blank" href="%s">here</a>', CFS_LANG_CODE), 'http://supsystic.com/edit-form-html-css-code/')?>
	<?php _e('Edit this ONLY if you know basics of HTML and CSS', CFS_LANG_CODE)?>
</p>
<fieldset>
	<legend><?php _e('Field Wrapper')?></legend>
	<?php echo htmlCfs::textarea('params[tpl][field_wrapper]', array('value' => $fieldWrapper, 'attrs' => 'id="cfsFormFieldWrapperEditor"'))?>
</fieldset>
<fieldset>
	<legend><?php _e('CSS code')?></legend>
	<?php echo htmlCfs::textarea('css', array('value' => reqCfs::sanitizeString($this->form['css']), 'attrs' => 'id="cfsFormCssEditor"'))?>
</fieldset>
<fieldset>
	<legend><?php _e('HTML code')?></legend>
	<?php echo htmlCfs::textarea('html', array('value' => reqCfs::sanitizeString($this->form['html']), 'attrs' => 'id="cfsFormHtmlEditor"'))?>
</fieldset>

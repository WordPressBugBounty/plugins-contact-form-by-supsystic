<?php
// Settings page in the Data Tables / Photo Gallery style: a page head and cards. The fields are printed exactly as
// before (same names, values, ids and data-connect rows), only grouped into cards.
$cfsSettingsCards = [
  'mail' => [
    'icon' => 'fa-paper-plane',
    'title' => __('Sending emails', CFS_LANG_CODE),
    'desc' => __('How your site sends the emails of your forms.', CFS_LANG_CODE),
    'opts' => ['mail_send_engine', 'smtp_host', 'smtp_login', 'smtp_pass', 'smtp_port', 'smtp_secure', 'sendmail_path', 'disable_email_html_type'],
  ],
  'protect' => [
    'icon' => 'fa-shield',
    'title' => __('Protection and access', CFS_LANG_CODE),
    'desc' => __('Spam protection and who can work with the plugin.', CFS_LANG_CODE),
    'opts' => ['enable_submit_ip_antispam', 'access_roles'],
  ],
  'editor' => [
    'icon' => 'fa-sliders',
    'title' => __('Editor and speed', CFS_LANG_CODE),
    'desc' => __('How the form editor saves and how the plugin files load on your pages.', CFS_LANG_CODE),
    'opts' => ['disable_autosave', 'foot_assets'],
  ],
  'data' => [
    'icon' => 'fa-database',
    'title' => __('Submissions data', CFS_LANG_CODE),
    'desc' => __('How long the contacts sent through your forms are kept on your site.', CFS_LANG_CODE),
    'opts' => ['remove_expire_contacts'],
  ],
];
$cfsSettingsCardOf = [];
foreach ($cfsSettingsCards as $cfsCardKey => $cfsCard) {
  foreach ($cfsCard['opts'] as $cfsOptKey) {
    $cfsSettingsCardOf[$cfsOptKey] = $cfsCardKey;
  }
}
// Options added by other modules (optionsDefine filter) get a card of their own
$cfsSettingsCards['more'] = [
  'icon' => 'fa-cog',
  'title' => __('More settings', CFS_LANG_CODE),
  'desc' => '',
  'opts' => [],
];
$cfsSettingsRows = [];
foreach ($this->options as $optCatKey => $optCatData) {
  if (empty($optCatData['opts'])) {
    continue;
  }
  foreach ($optCatData['opts'] as $optKey => $opt) {
    if (empty($opt['html'])) {
      continue;
    }
    $cfsSettingsRows[isset($cfsSettingsCardOf[$optKey]) ? $cfsSettingsCardOf[$optKey] : 'more'][$optKey] = $opt;
  }
}
// Rows keep the order of the card lists
foreach ($cfsSettingsCards as $cfsCardKey => $cfsCard) {
  if (!empty($cfsCard['opts']) && !empty($cfsSettingsRows[$cfsCardKey])) {
    $cfsOrdered = [];
    foreach ($cfsCard['opts'] as $cfsOptKey) {
      if (isset($cfsSettingsRows[$cfsCardKey][$cfsOptKey])) {
        $cfsOrdered[$cfsOptKey] = $cfsSettingsRows[$cfsCardKey][$cfsOptKey];
      }
    }
    $cfsSettingsRows[$cfsCardKey] = $cfsOrdered + $cfsSettingsRows[$cfsCardKey];
  }
}
$cfsSettingsColumns = [['mail'], ['protect', 'editor', 'data', 'more']];
?>
<section class="supsystic-bar cfs-settings-bar">
	<header class="cfs-page-head">
		<span class="cfs-page-head-icon"><i class="fa fa-cogs" aria-hidden="true"></i></span>
		<div>
			<h2><?php _e('Settings', CFS_LANG_CODE); ?></h2>
			<p><?php _e('These options apply to all your forms.', CFS_LANG_CODE); ?></p>
		</div>
		<ul class="supsystic-bar-controls cfs-page-head-actions">
			<li title="<?php _e('Save all options'); ?>">
				<button class="button button-primary cfs-ui-main" id="cfsSettingsSaveBtn" data-toolbar-button>
					<i class="fa fa-fw fa-save"></i>
					<?php _e('Save', CFS_LANG_CODE); ?>
				</button>
			</li>
		</ul>
	</header>
</section>
<section>
	<form id="cfsSettingsForm" class="cfsInputsWithDescrForm cfs-settings-form">
		<div class="supsystic-item supsystic-panel">
			<div id="containerWrapper" class="cfs-settings-grid">
				<?php foreach ($cfsSettingsColumns as $cfsColumn) { ?>
				<div class="cfs-settings-col">
					<?php foreach ($cfsColumn as $cfsCardKey) { ?>
						<?php if (empty($cfsSettingsRows[$cfsCardKey])) {
          continue;
        } ?>
						<?php $cfsCard = $cfsSettingsCards[$cfsCardKey]; ?>
					<div class="cfs-card cfs-settings-card" data-cfs-card="<?php echo esc_attr($cfsCardKey); ?>">
						<div class="cfs-card-head">
							<span class="cfs-card-icon"><i class="fa <?php echo esc_attr($cfsCard['icon']); ?>" aria-hidden="true"></i></span>
							<div>
								<h3><?php echo esc_html($cfsCard['title']); ?></h3>
								<?php if (!empty($cfsCard['desc'])) { ?>
									<p><?php echo esc_html($cfsCard['desc']); ?></p>
								<?php } ?>
							</div>
						</div>
						<table class="form-table">
							<?php foreach ($cfsSettingsRows[$cfsCardKey] as $optKey => $opt) { ?>
								<?php
        $htmlType = $opt['html'];
        $htmlOpts = ['value' => empty($opt['value']) ? '' : $opt['value'], 'attrs' => 'data-optkey="' . $optKey . '"'];
        if (in_array($htmlType, ['selectbox', 'selectlist']) && isset($opt['options'])) {
          if (is_callable($opt['options'])) {
            $htmlOpts['options'] = call_user_func($opt['options']);
          } elseif (is_array($opt['options'])) {
            $htmlOpts['options'] = $opt['options'];
          }
        }
        if (isset($opt['pro']) && !empty($opt['pro'])) {
          $htmlOpts['attrs'] .= ' class="cfsProOpt"';
        }
        ?>
								<tr
									<?php if (isset($opt['connect']) && $opt['connect']) { ?>
										data-connect="<?php echo $opt['connect']; ?>" style="display: none;"
									<?php } ?>
								>
									<th scope="row">
										<span class="cfs-set-label"><?php echo $opt['label']; ?></span>
										<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html($opt['desc']); ?>"></i>
										<?php if (isset($opt['pro']) && !empty($opt['pro'])) { ?>
											<span class="cfsProOptMiniLabel">
												<a href="<?php echo $opt['pro']; ?>" target="_blank" data-cfs-upsell="<?php echo esc_attr($optKey); ?>">
													<?php _e('PRO option', CFS_LANG_CODE); ?>
												</a>
											</span>
										<?php } ?>
										<?php if (!empty($opt['changed_on'])) { ?>
											<span class="description">
												<?php $opt['value'] ? printf(__('Turned On %s', CFS_LANG_CODE), dateCfs::_($opt['changed_on'])) : printf(__('Turned Off %s', CFS_LANG_CODE), dateCfs::_($opt['changed_on'])); ?>
											</span>
										<?php } ?>
									</th>
									<td>
										<?php echo htmlCfs::$htmlType('opt_values[' . $optKey . ']', $htmlOpts); ?>
										<div id="cfsFormOptDetails_<?php echo $optKey; ?>" class="cfsOptDetailsShell">
										<?php if (isset($opt['add_sub_opts']) && !empty($opt['add_sub_opts'])) {
            if (is_string($opt['add_sub_opts'])) {
              echo $opt['add_sub_opts'];
            } elseif (is_callable($opt['add_sub_opts'])) {
              echo call_user_func_array($opt['add_sub_opts'], [$this->options]);
            }
          } ?>
										</div>
									</td>
								</tr>
							<?php } ?>
						</table>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php echo htmlCfs::hidden('mod', ['value' => 'options']); ?>
		<?php echo htmlCfs::defaultNonceForAdminPanel(); ?>
		<?php echo htmlCfs::hidden('action', ['value' => 'saveGroup']); ?>
	</form>
</section>

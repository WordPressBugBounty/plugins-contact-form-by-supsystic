<?php
/**
 * Small looped pictures of the PRO features for the upsell windows: CSS mocks of a form (admin.upsell.css), no images.
 * Plain words only, they are a picture.
 */
switch ($this->demoKey) {
  case 'logic': ?>
<div class="cfs-mk">
	<div class="cfs-mk-t"><?php esc_html_e('Need a quote?', CFS_LANG_CODE); ?></div>
	<div class="cfs-mk-choice"><span class="is-yes"><?php esc_html_e('Yes', CFS_LANG_CODE); ?></span><span class="is-no"><?php esc_html_e('No', CFS_LANG_CODE); ?></span></div>
	<div class="cfs-mk-reveal">
		<div class="cfs-mk-f"><em><?php esc_html_e('Company', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-f"><em><?php esc_html_e('Budget', CFS_LANG_CODE); ?></em></div>
	</div>
	<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'abtesting': ?>
<div class="cfs-mk-ab">
	<div class="cfs-mk cfs-mk-sm"><b class="cfs-mk-ab-tag">A</b><div class="cfs-mk-f"></div><div class="cfs-mk-f"></div><div class="cfs-mk-b"></div><div class="cfs-mk-bar"><i style="--cfs-w: 38%"></i></div><small>3.8%</small></div>
	<div class="cfs-mk cfs-mk-sm is-win"><b class="cfs-mk-ab-tag">B</b><div class="cfs-mk-f"></div><div class="cfs-mk-b is-alt"></div><div class="cfs-mk-bar"><i style="--cfs-w: 72%"></i></div><small>7.2%</small><span class="cfs-mk-win"><i class="fa fa-trophy"></i> <?php esc_html_e('Winner', CFS_LANG_CODE); ?></span></div>
</div>
<?php break;
  case 'subscribe': ?>
<div class="cfs-mk-flow">
	<div class="cfs-mk cfs-mk-sm">
		<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-check"><i></i><?php esc_html_e('Subscribe to news', CFS_LANG_CODE); ?></div>
		<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
	</div>
	<i class="fa fa-long-arrow-right cfs-mk-arrow"></i>
	<div class="cfs-mk-list is-cycle">
		<span>Mailchimp</span><span>AWeber</span><span>MailPoet</span><span>Brevo</span>
	</div>
</div>
<?php break;
  case 'publish': ?>
<div class="cfs-mk-flow">
	<div class="cfs-mk cfs-mk-sm">
		<div class="cfs-mk-f"><em><?php esc_html_e('Title', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-f is-tall"><em><?php esc_html_e('Your story', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
	</div>
	<i class="fa fa-long-arrow-right cfs-mk-arrow"></i>
	<div class="cfs-mk-post">
		<div class="cfs-mk-post-img"></div>
		<b></b><i></i><i></i>
		<span class="cfs-mk-pill"><i class="fa fa-check"></i> <?php esc_html_e('Post published', CFS_LANG_CODE); ?></span>
	</div>
</div>
<?php break;
  case 'registration': ?>
<div class="cfs-mk-flow">
	<div class="cfs-mk cfs-mk-sm">
		<div class="cfs-mk-t"><?php esc_html_e('Sign up', CFS_LANG_CODE); ?></div>
		<div class="cfs-mk-f"><em><?php esc_html_e('Name', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-b"><?php esc_html_e('Create account', CFS_LANG_CODE); ?></div>
	</div>
	<i class="fa fa-long-arrow-right cfs-mk-arrow"></i>
	<div class="cfs-mk-user">
		<span class="cfs-mk-avatar"><i class="fa fa-user"></i></span>
		<b><?php esc_html_e('New user', CFS_LANG_CODE); ?></b>
		<span class="cfs-mk-pill"><?php esc_html_e('Subscriber', CFS_LANG_CODE); ?></span>
	</div>
</div>
<?php break;
  case 'fbconvert': ?>
<div class="cfs-mk-flow">
	<div class="cfs-mk cfs-mk-sm">
		<div class="cfs-mk-f"></div>
		<div class="cfs-mk-f"></div>
		<div class="cfs-mk-b is-press"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
	</div>
	<i class="fa fa-long-arrow-right cfs-mk-arrow"></i>
	<div class="cfs-mk-chart">
		<span class="cfs-mk-pill is-fb"><i class="fa fa-facebook"></i> <?php esc_html_e('Lead', CFS_LANG_CODE); ?> +1</span>
		<div class="cfs-mk-bars"><i style="--cfs-h: 30%"></i><i style="--cfs-h: 45%"></i><i style="--cfs-h: 38%"></i><i style="--cfs-h: 62%"></i><i class="is-grow" style="--cfs-h: 88%"></i></div>
	</div>
</div>
<?php break;
  case 'icons': ?>
<div class="cfs-mk">
	<div class="cfs-mk-f has-icon"><i class="fa fa-user"></i><em><?php esc_html_e('Your name', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-f has-icon"><i class="fa fa-envelope"></i><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-f has-icon"><i class="fa fa-phone"></i><em><?php esc_html_e('Phone', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-f has-icon is-tall"><i class="fa fa-comment"></i><em><?php esc_html_e('Message', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-b"><i class="fa fa-paper-plane"></i> <?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'presets': ?>
<div class="cfs-mk-flow">
	<div class="cfs-mk cfs-mk-sm">
		<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-f is-tall"><em><?php esc_html_e('Message', CFS_LANG_CODE); ?></em></div>
		<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
	</div>
	<i class="fa fa-long-arrow-right cfs-mk-arrow"></i>
	<div class="cfs-mk-mail">
		<b><i class="fa fa-envelope-o"></i> <?php esc_html_e('New message', CFS_LANG_CODE); ?></b>
		<span class="is-1"><?php esc_html_e('IP', CFS_LANG_CODE); ?>: 203.0.113.7</span>
		<span class="is-2"><?php esc_html_e('Country', CFS_LANG_CODE); ?>: DE</span>
		<span class="is-3"><?php esc_html_e('Page', CFS_LANG_CODE); ?>: /pricing/</span>
	</div>
</div>
<?php break;
  case 'file': ?>
<div class="cfs-mk">
	<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-drop"><i class="fa fa-cloud-upload"></i><span><?php esc_html_e('Drop files here', CFS_LANG_CODE); ?></span></div>
	<div class="cfs-mk-file"><i class="fa fa-file-pdf-o"></i><span>brief.pdf</span><b><i></i></b></div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'rating': ?>
<div class="cfs-mk">
	<div class="cfs-mk-t"><?php esc_html_e('How did we do?', CFS_LANG_CODE); ?></div>
	<div class="cfs-mk-stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
	<div class="cfs-mk-f is-tall"><em><?php esc_html_e('Your review', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'captcha': ?>
<div class="cfs-mk">
	<div class="cfs-mk-f"><em><?php esc_html_e('Email', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-f is-tall"><em><?php esc_html_e('Message', CFS_LANG_CODE); ?></em></div>
	<div class="cfs-mk-human"><span class="cfs-mk-spin"></span><i class="fa fa-check"></i><em class="is-wait"><?php esc_html_e('Checking…', CFS_LANG_CODE); ?></em><em class="is-ok"><?php esc_html_e('Success!', CFS_LANG_CODE); ?></em><i class="fa fa-shield cfs-mk-shield"></i></div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'wpfields': ?>
<div class="cfs-mk">
	<div class="cfs-mk-editor"><span><b>B</b><i>I</i><u>U</u><i class="fa fa-list-ul"></i><i class="fa fa-link"></i></span><div></div></div>
	<div class="cfs-mk-chips is-cycle"><span><?php esc_html_e('News', CFS_LANG_CODE); ?></span><span><?php esc_html_e('Events', CFS_LANG_CODE); ?></span><span><?php esc_html_e('Tips', CFS_LANG_CODE); ?></span></div>
	<div class="cfs-mk-f is-select"><em><?php esc_html_e('Color', CFS_LANG_CODE); ?>: <?php esc_html_e('Blue', CFS_LANG_CODE); ?></em><i class="fa fa-angle-down"></i></div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'address': ?>
<div class="cfs-mk">
	<div class="cfs-mk-f has-icon is-type"><i class="fa fa-map-marker"></i><em>221B Baker</em></div>
	<div class="cfs-mk-suggest">
		<span><i class="fa fa-map-marker"></i> 221B Baker Street, London</span>
		<span><i class="fa fa-map-marker"></i> 221 Baker Avenue, Concord</span>
		<span><i class="fa fa-map-marker"></i> 221 Baker Road, Leeds</span>
	</div>
	<div class="cfs-mk-b"><?php esc_html_e('Send', CFS_LANG_CODE); ?></div>
</div>
<?php break;
  case 'access': ?>
<div class="cfs-mk cfs-mk-roles">
	<div class="cfs-mk-t"><?php esc_html_e('Who can manage forms', CFS_LANG_CODE); ?></div>
	<div class="cfs-mk-role is-on"><i class="fa fa-user-secret"></i><span><?php esc_html_e('Administrator', CFS_LANG_CODE); ?></span><b></b></div>
	<div class="cfs-mk-role is-1"><i class="fa fa-pencil"></i><span><?php esc_html_e('Editor', CFS_LANG_CODE); ?></span><b></b></div>
	<div class="cfs-mk-role is-2"><i class="fa fa-user"></i><span><?php esc_html_e('Shop manager', CFS_LANG_CODE); ?></span><b></b></div>
	<div class="cfs-mk-role"><i class="fa fa-user-o"></i><span><?php esc_html_e('Author', CFS_LANG_CODE); ?></span><b></b></div>
</div>
<?php break;
  default: ?>
<span class="cfs-up-icon"><i class="fa fa-diamond"></i></span>
<?php break;
}

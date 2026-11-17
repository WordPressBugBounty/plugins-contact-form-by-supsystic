<?php
/**
 * PRO feature windows of the free version, in the look of the Photo Gallery and Data Tables windows: one <dialog>,
 * and a <template id="cfs-up-KEY"> for each PRO feature that admin.upsell.js puts into it. Any element with
 * data-cfs-upsell="KEY" opens the window of its feature; the old "PRO option" labels and the old PRO popup find it
 * by the utm_medium of their link.
 */
$features = $this->features;
$ces = $this->ces;
$coupon = $this->couponHtml;
?>
<dialog class="cfs-up" data-cfs-up aria-labelledby="cfs-up-title">
	<button type="button" class="cfs-up-close" data-cfs-up-close aria-label="<?php echo esc_attr__('Close', CFS_LANG_CODE); ?>">&times;</button>
	<div class="cfs-up-body" data-cfs-up-body></div>
</dialog>
<?php foreach ($features as $key => $f) { ?>
<template id="cfs-up-<?php echo esc_attr($key); ?>">
	<div class="cfs-up-hero">
		<span class="cfs-up-eyebrow"><i class="fa fa-diamond" aria-hidden="true"></i><?php esc_html_e('PRO feature', CFS_LANG_CODE); ?></span>
		<div class="cfs-up-head">
			<div>
				<h2 id="cfs-up-title"><?php echo esc_html($f['title']); ?></h2>
				<p><?php echo esc_html($f['text']); ?></p>
			</div>
			<?php if (!empty($f['ces']['tier']) && !empty($ces['url'])) { ?>
				<a class="cfs-up-cta" href="<?php echo esc_url($ces['url']); ?>"><?php echo esc_html($ces['label']); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<?php } else { ?>
				<a class="cfs-up-cta" href="<?php echo esc_url($this->module->generateMainLink('utm_source=plugin&utm_medium=' . $f['medium'] . '&utm_campaign=forms')); ?>" target="_blank" rel="noopener"><?php esc_html_e('Get PRO', CFS_LANG_CODE); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<?php } ?>
		</div>
	</div>
	<div class="cfs-up-main">
		<div class="cfs-up-demo" aria-hidden="true">
			<div class="cfs-up-demo-bar"><i></i><i></i><i></i><span><?php esc_html_e('On your page', CFS_LANG_CODE); ?></span></div>
			<div class="cfs-up-stage cfs-up-stage-<?php echo esc_attr($key); ?>">
				<?php echo $this->module->getView()->getUpsellDemo($key); ?>
			</div>
		</div>
		<div class="cfs-up-info">
			<h3><?php esc_html_e('What you get', CFS_LANG_CODE); ?></h3>
			<ul>
				<?php foreach ($f['points'] as $point) { ?><li><?php echo esc_html($point); ?></li><?php } ?>
			</ul>
			<h3><?php esc_html_e('Great for', CFS_LANG_CODE); ?></h3>
			<div class="cfs-up-uses">
				<?php foreach ($f['uses'] as $use) { ?><span><?php echo esc_html($use); ?></span><?php } ?>
			</div>
		</div>
	</div>
	<div class="cfs-up-ces<?php echo empty($f['ces']['tier']) ? ' is-none' : ''; ?>">
		<span class="cfs-up-ces-logo" aria-hidden="true">CES</span>
		<div class="cfs-up-ces-text">
			<?php if (!empty($f['ces']['tier'])) { ?>
				<strong><?php esc_html_e('In CES', CFS_LANG_CODE); ?> <span class="cfs-up-ces-tier is-<?php echo esc_attr($f['ces']['tier']); ?>"><?php echo $f['ces']['tier'] === 'free' ? esc_html__('Free', CFS_LANG_CODE) : esc_html__('CES Pro', CFS_LANG_CODE); ?></span></strong>
				<span><?php echo esc_html($f['ces']['text']); ?></span>
			<?php } else { ?>
				<strong><?php esc_html_e('Not in CES yet', CFS_LANG_CODE); ?></strong>
				<span><?php esc_html_e('This feature stays in Contact Form PRO. Your forms can move to CES at any time, the Customer Engagement System with forms, popups, a CRM and automation.', CFS_LANG_CODE); ?></span>
			<?php } ?>
		</div>
		<?php if (empty($f['ces']['tier']) && !empty($ces['url'])) { ?>
			<a class="cfs-up-ces-link" href="<?php echo esc_url($ces['url']); ?>"><?php echo esc_html($ces['label']); ?></a>
		<?php } ?>
	</div>
	<?php if (!empty($f['ces']['tier'])) { echo $coupon; } ?>
	<div class="cfs-up-more">
		<span class="cfs-up-more-label"><?php esc_html_e('Also in PRO', CFS_LANG_CODE); ?></span>
		<?php foreach ($features as $otherKey => $other) { ?>
			<?php if ($otherKey !== $key) { ?>
				<button type="button" data-cfs-upsell="<?php echo esc_attr($otherKey); ?>"><i class="fa <?php echo esc_attr($other['icon']); ?>" aria-hidden="true"></i> <?php echo esc_html($other['chip']); ?></button>
			<?php } ?>
		<?php } ?>
	</div>
</template>
<?php } ?>

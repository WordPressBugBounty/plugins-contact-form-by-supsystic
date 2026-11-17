<?php
/**
 * The banner at the top of every Contact Form page (Free and PRO): Contact Form is a classic plugin now and its forms
 * move on to CES. One button by the state of CES (install, update, activate, move, open), the CES Pro offer and what stays.
 */
$ces = $this->cesState;
$isPro = $this->isPro;
$coupon = $this->couponHtml;
$overviewUrl = frameCfs::_()->getModule('options')->getTabUrl('overview');
$progress = $ces['total'] > 0 && $ces['moved'] > 0;
// Folded: a one-line strip with the mark, "Contact Form is now part of CES", the CES Pro code and the unfold button.
// The banner cannot be closed; the fold is kept for every user (user meta, supsystic_promoCfs::ajaxFoldCesBanner).
$folded = !empty($this->folded);
?>
<section class="cfs-ces-banner<?php echo $folded ? ' is-folded' : ''; ?>" data-cfs-ces-banner data-ajax-url="<?php echo esc_url($this->ajaxUrl); ?>" data-nonce="<?php echo esc_attr($this->foldNonce); ?>" aria-label="<?php echo esc_attr__('Contact Form is now part of CES', CFS_LANG_CODE); ?>">
	<div class="cfs-ces-banner-art" aria-hidden="true">
		<span class="cfs-ces-banner-node is-form"><i class="fa fa-wpforms"></i></span>
		<span class="cfs-ces-banner-node is-popup"><i class="fa fa-window-maximize"></i></span>
		<span class="cfs-ces-banner-node is-crm"><i class="fa fa-address-book-o"></i></span>
		<span class="cfs-ces-banner-node is-mail"><i class="fa fa-envelope-o"></i></span>
		<span class="cfs-ces-banner-core">CES</span>
	</div>
	<div class="cfs-ces-banner-body">
		<p class="cfs-ces-banner-eyebrow"><i class="fa fa-rocket" aria-hidden="true"></i><span><?php esc_html_e('Contact Form is now part of CES', CFS_LANG_CODE); ?></span></p>
		<h3 class="cfs-ces-banner-title"><?php esc_html_e('Forms, popups, a CRM and email automation in one free plugin', CFS_LANG_CODE); ?></h3>
		<p class="cfs-ces-banner-text">
			<?php if ($isPro) { ?>
				<?php esc_html_e('Move your forms to the Customer Engagement System in one click: every message becomes a contact you can follow up. Your Contact Form PRO keeps working as it is.', CFS_LANG_CODE); ?>
			<?php } else { ?>
				<?php esc_html_e('Move your forms to the Customer Engagement System in one click: every message becomes a contact you can follow up. Your forms here keep working as they are.', CFS_LANG_CODE); ?>
			<?php } ?>
		</p>
		<?php if ($progress) { ?>
			<div class="cfs-ces-banner-progress">
				<span class="cfs-ces-banner-bar"><span style="width: <?php echo (int) round(100 * $ces['moved'] / $ces['total']); ?>%"></span></span>
				<span><?php
				/* translators: 1: moved forms, 2: all forms */
				echo esc_html(sprintf(__('%1$d of %2$d forms moved to CES', CFS_LANG_CODE), $ces['moved'], $ces['total']));
				?></span>
			</div>
		<?php } ?>
		<div class="cfs-ces-banner-actions">
			<?php if (!empty($ces['url'])) { ?>
				<a class="cfs-ces-banner-cta" href="<?php echo esc_url($ces['url']); ?>"><?php echo esc_html($ces['label']); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<?php } ?>
			<a class="cfs-ces-banner-link" href="<?php echo esc_url($overviewUrl . '#cfs-ces'); ?>"><?php esc_html_e('What changes?', CFS_LANG_CODE); ?></a>
		</div>
		<?php echo $coupon; ?>
	</div>
	<button type="button" class="cfs-ces-banner-fold" data-cfs-ces-fold aria-expanded="<?php echo $folded ? 'false' : 'true'; ?>" title="<?php echo $folded ? esc_attr__('Show more', CFS_LANG_CODE) : esc_attr__('Show less', CFS_LANG_CODE); ?>" data-title-more="<?php echo esc_attr__('Show more', CFS_LANG_CODE); ?>" data-title-less="<?php echo esc_attr__('Show less', CFS_LANG_CODE); ?>">
		<i class="fa fa-angle-up" aria-hidden="true"></i>
	</button>
</section>

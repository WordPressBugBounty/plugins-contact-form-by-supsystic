<?php
$action = $this->action;
$readMoreUrl = $this->readMoreUrl;
$previewImgUrl = $this->previewImgUrl;
?>
<div id="cfsCesPromoOverlay" class="cfs-ces-promo-overlay">
	<div class="cfs-ces-promo-modal" role="dialog" aria-modal="true" aria-labelledby="cfsCesPromoTitle">
		<button type="button" class="cfs-ces-promo-close" id="cfsCesPromoClose" aria-label="<?php esc_attr_e('Close', CFS_LANG_CODE); ?>">&times;</button>
		<div class="cfs-ces-promo-grid">
			<div class="cfs-ces-promo-media">
				<a href="<?php echo esc_url($readMoreUrl); ?>" target="_blank" rel="noopener" class="cfs-ces-promo-preview cfs-ces-promo-remember">
					<img src="<?php echo esc_url($previewImgUrl); ?>" alt="<?php esc_attr_e('Customer Engagement System: popup, form, CRM, email and sale in one connected funnel', CFS_LANG_CODE); ?>" />
				</a>
				<ol class="cfs-ces-promo-steps">
					<li><span>1</span><?php esc_html_e('Capture', CFS_LANG_CODE); ?></li>
					<li><span>2</span><?php esc_html_e('Collect', CFS_LANG_CODE); ?></li>
					<li><span>3</span><?php esc_html_e('Organize', CFS_LANG_CODE); ?></li>
					<li><span>4</span><?php esc_html_e('Follow up', CFS_LANG_CODE); ?></li>
					<li><span>5</span><?php esc_html_e('Sell', CFS_LANG_CODE); ?></li>
				</ol>
			</div>
			<div class="cfs-ces-promo-content">
				<div class="cfs-ces-promo-eyebrow-row">
					<span class="cfs-ces-promo-eyebrow"><?php esc_html_e('Contact Form + CES', CFS_LANG_CODE); ?></span>
					<span class="cfs-ces-promo-badge"><?php esc_html_e('Free', CFS_LANG_CODE); ?></span>
				</div>
				<h2 id="cfsCesPromoTitle" class="cfs-ces-promo-title"><?php esc_html_e('Contact Form is now part of CES', CFS_LANG_CODE); ?></h2>
				<p class="cfs-ces-promo-subtitle">
					<?php esc_html_e('The Customer Engagement System by Supsystic is one free plugin where forms, popups, a CRM and email automation share one contact base. This is where Contact Form by Supsystic keeps growing.', CFS_LANG_CODE); ?>
				</p>
				<ul class="cfs-ces-promo-features">
					<li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e('A new form builder with conditional logic and 30+ field types', CFS_LANG_CODE); ?></li>
					<li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e('Every submission becomes a CRM contact with tags and history', CFS_LANG_CODE); ?></li>
					<li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e('Automatic follow-up emails and newsletters', CFS_LANG_CODE); ?></li>
					<li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e('Popups with exit-intent, scroll and click triggers', CFS_LANG_CODE); ?></li>
					<li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e('200+ templates for forms, popups and emails', CFS_LANG_CODE); ?></li>
				</ul>

				<p class="cfs-ces-promo-reassurance">
					<?php esc_html_e('Your Contact Form forms keep working exactly as they are – nothing is removed or changed. CES installs next to Contact Form, and you can rebuild your forms there whenever it suits you.', CFS_LANG_CODE); ?>
				</p>

				<div class="cfs-ces-promo-actions">
					<?php if (!empty($action)) { ?>
						<a href="<?php echo esc_url($action['url']); ?>" class="cfs-ces-promo-cta cfs-ces-promo-remember"><?php echo esc_html($action['label']); ?></a>
					<?php } ?>
					<a href="<?php echo esc_url($readMoreUrl); ?>" target="_blank" rel="noopener" class="cfs-ces-promo-learn-more cfs-ces-promo-remember"><?php esc_html_e('Read more', CFS_LANG_CODE); ?></a>
					<button type="button" class="cfs-ces-promo-dismiss" id="cfsCesPromoDismiss"><?php esc_html_e('Maybe later', CFS_LANG_CODE); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>

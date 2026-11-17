<?php
/**
 * Review request card (cfsReview): "Is it working well?" leads to the WordPress.org review form or to support.
 */
$card = $this->reviewCard;
?>
<section class="cfs-review<?php echo !empty($card['isOverview']) ? ' is-overview' : ''; ?>" data-cfs-review data-ajax-url="<?php echo esc_url($card['ajaxUrl']); ?>" data-nonce="<?php echo esc_attr($card['nonce']); ?>" aria-label="<?php echo esc_attr__('Rate Contact Form', CFS_LANG_CODE); ?>">
	<div class="cfs-review-art" aria-hidden="true">
		<svg class="cfs-review-icon" viewBox="0 0 48 48" width="48" height="48" focusable="false">
			<rect x="7" y="8" width="34" height="32" rx="5" fill="#fff" fill-opacity=".18" stroke="#fff" stroke-width="2.4"/>
			<path d="M13 17h22M13 24h22M13 31h12" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round"/>
		</svg>
		<span class="cfs-review-badge">
			<svg viewBox="0 0 16 16" width="14" height="14" focusable="false"><path d="M3.5 8.3l3 3 6-6.6" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</span>
		<svg class="cfs-review-spark is-one" viewBox="0 0 10 10" width="12" height="12" focusable="false"><path d="M5 0l1.2 3.8L10 5 6.2 6.2 5 10 3.8 6.2 0 5l3.8-1.2z" fill="#ffc53d"/></svg>
		<svg class="cfs-review-spark is-two" viewBox="0 0 10 10" width="9" height="9" focusable="false"><path d="M5 0l1.2 3.8L10 5 6.2 6.2 5 10 3.8 6.2 0 5l3.8-1.2z" fill="#4ae8ea"/></svg>
		<svg class="cfs-review-spark is-three" viewBox="0 0 10 10" width="7" height="7" focusable="false"><path d="M5 0l1.2 3.8L10 5 6.2 6.2 5 10 3.8 6.2 0 5l3.8-1.2z" fill="#ffc53d"/></svg>
	</div>

	<div class="cfs-review-body" aria-live="polite">
		<div class="cfs-review-step is-active" data-review-step="ask">
			<p class="cfs-review-eyebrow"><?php echo esc_html($card['eyebrow']); ?></p>
			<h3 class="cfs-review-title"><?php echo esc_html($card['title']); ?></h3>
			<p class="cfs-review-text">
				<?php echo esc_html($card['text']); ?>
				<?php if (!empty($card['link'])) { ?>
					<a class="cfs-review-inline-link" href="<?php echo esc_url($card['link']); ?>"<?php echo $card['type'] === 'live' ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($card['linkText']); ?> &rarr;</a>
				<?php } ?>
			</p>
			<div class="cfs-review-question"><?php esc_html_e('Is Contact Form working well for you?', CFS_LANG_CODE); ?></div>
			<div class="cfs-review-actions">
				<button type="button" class="cfs-review-btn is-primary" data-review-go="yes"><?php esc_html_e('Yes, it works great', CFS_LANG_CODE); ?></button>
				<button type="button" class="cfs-review-btn" data-review-go="no"><?php esc_html_e('Not really', CFS_LANG_CODE); ?></button>
			</div>
		</div>

		<div class="cfs-review-step" data-review-step="yes" hidden>
			<p class="cfs-review-eyebrow"><?php esc_html_e('Glad to hear it', CFS_LANG_CODE); ?></p>
			<h3 class="cfs-review-title"><?php esc_html_e('Would you rate Contact Form on WordPress.org?', CFS_LANG_CODE); ?></h3>
			<p class="cfs-review-text"><?php esc_html_e('It takes about a minute. Your review helps other site owners find the plugin and keeps us improving it.', CFS_LANG_CODE); ?></p>
			<div class="cfs-review-stars" aria-hidden="true">
				<?php for ($i = 1; $i <= 5; $i++) { ?>
					<svg class="cfs-review-star" style="--cfs-star: <?php echo (int) $i; ?>" viewBox="0 0 24 24" width="26" height="26" focusable="false"><path d="M12 2.6l2.9 5.9 6.5.9-4.7 4.6 1.1 6.5L12 17.4l-5.8 3.1 1.1-6.5-4.7-4.6 6.5-.9z"/></svg>
				<?php } ?>
			</div>
			<div class="cfs-review-actions">
				<a class="cfs-review-btn is-primary" href="<?php echo esc_url($card['reviewUrl']); ?>" target="_blank" rel="noopener noreferrer" data-review-answer="rate">
					<?php esc_html_e('Write a review', CFS_LANG_CODE); ?>
					<svg viewBox="0 0 16 16" width="13" height="13" aria-hidden="true" focusable="false"><path d="M6 3h7v7M13 3L5 11" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
				<button type="button" class="cfs-review-btn" data-review-answer="later"><?php esc_html_e('Not now', CFS_LANG_CODE); ?></button>
				<button type="button" class="cfs-review-link" data-review-answer="done"><?php esc_html_e('I already did', CFS_LANG_CODE); ?></button>
			</div>
		</div>

		<div class="cfs-review-step" data-review-step="no" hidden>
			<p class="cfs-review-eyebrow"><?php esc_html_e('We are here to help', CFS_LANG_CODE); ?></p>
			<h3 class="cfs-review-title"><?php esc_html_e('Sorry it is not working the way you need', CFS_LANG_CODE); ?></h3>
			<p class="cfs-review-text"><?php esc_html_e('Tell us what went wrong and our support team will help you sort it out.', CFS_LANG_CODE); ?></p>
			<div class="cfs-review-actions">
				<a class="cfs-review-btn is-primary" href="<?php echo esc_url($card['supportUrl']); ?>" target="_blank" rel="noopener noreferrer" data-review-answer="support"><?php esc_html_e('Contact support', CFS_LANG_CODE); ?></a>
				<button type="button" class="cfs-review-btn" data-review-answer="later"><?php esc_html_e('Not now', CFS_LANG_CODE); ?></button>
				<a class="cfs-review-link" href="<?php echo esc_url($card['reviewUrl']); ?>" target="_blank" rel="noopener noreferrer" data-review-answer="rate"><?php esc_html_e('Leave a review anyway', CFS_LANG_CODE); ?></a>
			</div>
		</div>

		<div class="cfs-review-step" data-review-step="thanks" hidden>
			<p class="cfs-review-eyebrow"><?php esc_html_e('Thank you', CFS_LANG_CODE); ?></p>
			<h3 class="cfs-review-title"><?php esc_html_e('You made our day', CFS_LANG_CODE); ?></h3>
			<p class="cfs-review-text"><?php esc_html_e('Every review helps the whole Supsystic team keep making Contact Form better.', CFS_LANG_CODE); ?></p>
		</div>

		<div class="cfs-review-step" data-review-step="support" hidden>
			<p class="cfs-review-eyebrow"><?php esc_html_e('Thank you', CFS_LANG_CODE); ?></p>
			<h3 class="cfs-review-title"><?php esc_html_e('We will get it sorted', CFS_LANG_CODE); ?></h3>
			<p class="cfs-review-text"><?php esc_html_e('Our support page is open in a new tab. Describe the problem and we will get back to you.', CFS_LANG_CODE); ?></p>
		</div>
	</div>

	<button type="button" class="cfs-review-close" data-review-answer="close" aria-label="<?php echo esc_attr__('Dismiss', CFS_LANG_CODE); ?>">
		<svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true" focusable="false"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
	</button>
</section>

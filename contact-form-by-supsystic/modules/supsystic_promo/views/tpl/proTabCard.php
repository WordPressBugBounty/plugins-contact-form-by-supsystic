<?php
/**
 * A PRO tab of the form editor in the free version: the feature, its picture, Read more and Get PRO.
 */
$card = $this->card;
$key = $this->cardKey;
$demo = $this->module->getView()->getUpsellDemo($key);
?>
<div class="cfs-pro-tab">
	<div class="cfs-pro-tab-info">
		<span class="cfs-pro-tab-eyebrow"><i class="fa fa-diamond" aria-hidden="true"></i><?php esc_html_e('PRO feature', CFS_LANG_CODE); ?></span>
		<h3><?php echo esc_html($card['title']); ?></h3>
		<p><?php echo esc_html($card['text']); ?></p>
		<ul>
			<?php foreach ($card['points'] as $point) { ?><li><?php echo esc_html($point); ?></li><?php } ?>
		</ul>
		<div class="cfs-pro-tab-actions">
			<a class="cfs-pro-tab-cta" href="<?php echo esc_url($this->module->generateMainLink('utm_source=plugin&utm_medium=' . $card['medium'] . '&utm_campaign=forms')); ?>" target="_blank" rel="noopener"><?php esc_html_e('Get PRO', CFS_LANG_CODE); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<a class="cfs-pro-tab-more" href="<?php echo esc_url($this->module->generateMainLink('utm_source=plugin&utm_medium=' . $card['medium'] . '&utm_campaign=forms')); ?>" target="_blank" rel="noopener" data-cfs-upsell="<?php echo esc_attr($key); ?>"><?php esc_html_e('Read more', CFS_LANG_CODE); ?></a>
		</div>
	</div>
	<div class="cfs-up-demo cfs-pro-tab-demo" aria-hidden="true">
		<div class="cfs-up-demo-bar"><i></i><i></i><i></i><span><?php esc_html_e('On your page', CFS_LANG_CODE); ?></span></div>
		<div class="cfs-up-stage cfs-up-stage-<?php echo esc_attr($key); ?>"><?php echo $demo; ?></div>
	</div>
</div>

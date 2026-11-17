<?php
/**
 * Overview page in the Data Tables / Photo Gallery style: hero, what the plugin does, the Free vs PRO comparison
 * (only without PRO), feature suites, PRO panel, videos, FAQ, server settings and a sidebar.
 */
$isPro = $this->isPro;
$promo = $this->module;
$options = frameCfs::_()->getModule('options');
$proUrl = $promo->generateMainLink('utm_source=plugin&utm_medium=overview&utm_campaign=forms');
$createUrl = $options->getTabUrl('forms_add_new');
$formsUrl = $options->getTabUrl('forms');
$contactsUrl = $options->getTabUrl('forms_contacts');
$settingsUrl = $options->getTabUrl('settings');
$imgUrl = $promo->getModPath() . 'img/';
$ces = $promo->getCesState();
$cesCoupon = $promo->getCesCouponHtml('overview');
// FREE / PRO tags only while PRO is closed
$tag = function ($pro) use ($isPro) {
  if ($isPro) {
    return '';
  }
  return $pro ? '<span class="cfs-overview-tag cfs-overview-tag-pro">PRO</span>' : '<span class="cfs-overview-tag cfs-overview-tag-free">FREE</span>';
};
$suites = [
  'build' => [
    'icon' => 'fa-th-large',
    'pill' => 'Build',
    'title' => 'A form for every page in a few minutes.',
    'text' => 'Start from a ready template, drag fields into place and see the form exactly as visitors will see it.',
    'items' => [
      ['fa-clone', 'Ready templates', 'Base Contact, Simple White, Neon, Spearmint, Support Service, holiday designs and more, every one fully editable.', false],
      ['fa-arrows', 'Drag-and-drop builder', 'Put fields side by side or on their own row, mark them required, add placeholders and labels.', false],
      ['fa-list', '25+ field types', 'Text, email, number, selects, radio buttons, checkboxes, country lists, date and time pickers, color, range, Google Map and more.', false],
      ['fa-paint-brush', 'Design without code', 'Width in pixels or per cent, backgrounds and colors for the form, the fields and the buttons, plus a CSS and HTML editor.', false],
      ['fa-upload', 'File upload and rating', 'Let visitors attach files and rate you in stars, with the ratings in the statistics.', true],
      ['fa-star-o', 'Field icons and presets', 'Icons inside the fields and hidden values like the visitor country, IP or the page the form was sent from.', true],
    ],
    'upgrade' => ['Unlock every field', 'File upload, Rating, Address search, WordPress and WooCommerce fields, field icons and value presets.', 'build_suite'],
  ],
  'send' => [
    'icon' => 'fa-paper-plane',
    'pill' => 'After Send',
    'title' => 'Every message reaches you, and visitors know it.',
    'text' => 'Choose who gets the email, what visitors see after sending and where the message is kept.',
    'items' => [
      ['fa-envelope-o', 'Email notifications', 'Your own subject, sender, Reply-To and message with the form data; copies to several addresses.', false],
      ['fa-check-circle', 'Success message or redirect', 'A thank-you message in your color, a redirect to any page and hiding the form after sending.', false],
      ['fa-server', 'Reliable delivery', 'Send through WordPress mail, your SMTP server or Sendmail, and check it with a test email.', false],
      ['fa-users', 'Contacts and CSV export', 'Keep every message on your site and export the contacts to CSV in one click.', false],
      ['fa-line-chart', 'Form statistics', 'Views, sends, successes and errors by hour, day, week or month, also as CSV.', false],
      ['fa-thumb-tack', 'Posts and sign-ups from forms', 'Turn messages into posts and register WordPress users right from your form.', true],
    ],
    'upgrade' => ['Do more with every message', 'Publish posts, register users and report conversions to Facebook Ads from your forms.', 'send_suite'],
  ],
  'grow' => [
    'icon' => 'fa-rocket',
    'pill' => 'Grow',
    'title' => 'Turn visitors into leads and subscribers.',
    'text' => 'Ask only what matters, test what works best and send new contacts straight to your mailing list.',
    'items' => [
      ['fa-code-fork', 'Conditional logic', 'Fields appear and hide by the answers above, so long forms stay short and clear.', true],
      ['fa-flask', 'A/B testing', 'Run versions of a form at once and keep the one that gets more messages.', true],
      ['fa-envelope', 'Email marketing', 'Mailchimp, AWeber, MailPoet, Brevo, ActiveCampaign, GetResponse, ConvertKit and 15+ more services.', true],
      ['fa-shield', 'Spam protection', 'Google reCAPTCHA and blocking repeated sends from one IP; reCAPTCHA v3, hCaptcha and Turnstile in PRO.', false],
      ['fa-facebook', 'Facebook Conversion', 'Every successful send counts as a conversion for your Facebook Ads.', true],
      ['fa-key', 'Access for your team', 'Let editors or any other role manage the forms without making them administrators.', true],
    ],
    'upgrade' => ['Unlock the growth toolkit', 'Conditional logic, A/B testing, 15+ email marketing services, smart spam protection and access for roles.', 'grow_suite'],
  ],
];
?>
<div class="cfs-overview">
	<section class="cfs-overview-hero">
		<div class="cfs-overview-hero-copy">
			<div class="cfs-overview-kicker">
				<img src="<?php echo esc_url($imgUrl . 'plugin-icon.png'); ?>" alt="">
				<span>Contact Form by Supsystic · now part of CES</span>
			</div>
			<h1>Forms now live in CES: forms, popups, CRM and automation in one.</h1>
			<p>Contact Form by Supsystic is a classic plugin now. Its forms move on to the Customer Engagement System — one free plugin where forms, popups, a CRM and email automation share one contact base. Move your forms in one click; everything here keeps working.</p>
			<div class="cfs-overview-actions">
				<?php if (!empty($ces['url'])) { ?>
					<a class="button button-primary button-hero cfs-ui-main" href="<?php echo esc_url($ces['url']); ?>"><i class="fa fa-rocket"></i> <?php echo esc_html($ces['label']); ?></a>
				<?php } ?>
				<a class="button button-secondary button-hero" href="<?php echo esc_url($formsUrl); ?>"><i class="fa fa-list"></i> Your forms</a>
			</div>
			<?php echo $cesCoupon; ?>
			<div class="cfs-overview-trust">
				<span><i class="fa fa-check"></i> Your forms keep working</span>
				<span><i class="fa fa-check"></i> Move in one click</span>
				<span><i class="fa fa-check"></i> CES is free on WordPress.org</span>
			</div>
		</div>
		<div class="cfs-overview-hero-media is-form" aria-hidden="true">
			<div class="cfs-ov-form">
				<div class="cfs-ov-form-head"><span class="cfs-ov-form-icon"><i class="fa fa-paper-plane"></i></span><div><b>Contact us</b><small>We reply within a day</small></div></div>
				<div class="cfs-ov-form-row"><span><em>First name</em></span><span><em>Last name</em></span></div>
				<span class="cfs-ov-form-field"><i class="fa fa-envelope-o"></i><em>you@example.com</em></span>
				<span class="cfs-ov-form-field is-select"><em>Question about…</em><i class="fa fa-angle-down"></i></span>
				<span class="cfs-ov-form-field is-tall"><em>Your message</em></span>
				<span class="cfs-ov-form-btn">Send message</span>
				<span class="cfs-ov-form-toast"><i class="fa fa-check-circle"></i> Thank you for contacting us!</span>
			</div>
			<div class="cfs-ov-mail">
				<b><i class="fa fa-address-book-o"></i> New contact</b>
				<small>anna@example.com</small>
				<span class="cfs-ov-tags"><em>contact-form</em><em>lead</em></span>
			</div>
			<div class="cfs-ov-stat">
				<small>Automation</small><b><i class="fa fa-paper-plane"></i> Welcome email</b>
				<span class="cfs-ov-flow"><i></i><i></i><i></i></span>
			</div>
		</div>
	</section>

	<section id="cfs-ces" class="cfs-overview-ces">
		<div class="cfs-overview-section-heading">
			<div><span class="cfs-overview-pill">Contact Form → CES</span><h2>What changes when you move to CES</h2></div>
			<p>Nothing is removed: you choose when to move, and you can keep using Contact Form as long as you like.</p>
		</div>
		<div class="cfs-overview-classic">
			<article><i class="fa fa-check-circle"></i><div><h3>Your forms keep working</h3><p>Forms, shortcodes, widgets and popups with Contact Form stay on your pages exactly as they are.</p></div></article>
			<article><i class="fa fa-inbox"></i><div><h3>Your messages stay here</h3><p>The Contacts list, CSV export and statistics of Contact Form keep all your messages.</p></div></article>
			<article><i class="fa fa-key"></i><div><h3><?php echo $isPro ? 'Your PRO license keeps working' : 'Contact Form PRO keeps working'; ?></h3><p><?php echo $isPro ? 'Your Contact Form PRO features, the License tab and updates work as before. CES Pro is a separate product.' : 'Owners of a Contact Form PRO license keep its features and updates. CES Pro is a separate product.'; ?></p></div></article>
		</div>
		<div class="cfs-overview-vs">
			<div class="cfs-overview-vs-row is-head"><span></span><strong>Contact Form (classic)</strong><strong class="is-ces">CES</strong></div>
			<?php
   $cfsVs = [
     ['Form builder', 'Drag-and-drop builder, ready templates', 'New builder, 30+ field types, 200+ templates for forms, popups and emails'],
     ['Every message', 'Email to you, the Contacts list and CSV', 'Email to you, an auto-reply, and a CRM contact with tags and history'],
     ['Conditional logic', 'In Contact Form PRO', 'Free'],
     ['Follow-up', 'By hand from your inbox', 'Automations and newsletters to your contacts'],
     ['Popups', 'With the separate PopUp plugin', 'Built in, with exit-intent, scroll and click triggers'],
     ['Spam protection', 'reCAPTCHA and blocking repeated sends', 'Honeypot and CAPTCHA'],
     ['Development', 'Classic plugin: fixes and security updates', 'Where new features arrive'],
   ];
   foreach ($cfsVs as $cfsRow) { ?>
			<div class="cfs-overview-vs-row"><span><?php echo esc_html($cfsRow[0]); ?></span><em><?php echo esc_html($cfsRow[1]); ?></em><em class="is-ces"><i class="fa fa-check"></i><?php echo esc_html($cfsRow[2]); ?></em></div>
			<?php } ?>
		</div>
		<div class="cfs-overview-ces-cta">
			<?php if (!empty($ces['url'])) { ?>
				<a class="button button-primary button-hero cfs-ui-main" href="<?php echo esc_url($ces['url']); ?>"><i class="fa fa-rocket"></i> <?php echo esc_html($ces['label']); ?></a>
			<?php } ?>
			<?php if ($ces['total'] > 0 && $ces['moved'] > 0) { ?>
				<span class="cfs-overview-ces-count"><?php
				/* translators: 1: forms moved, 2: all forms */
				echo esc_html(sprintf(__('%1$d of %2$d forms moved to CES', CFS_LANG_CODE), $ces['moved'], $ces['total']));
				?></span>
			<?php } ?>
		</div>
	</section>

	<section class="cfs-overview-wide">
		<?php echo $this->cesOffer; ?>
	</section>

	<section class="cfs-overview-layout">
		<div class="cfs-overview-main">
			<?php foreach ($suites as $key => $suite) { ?>
			<section id="cfs-<?php echo esc_attr($key); ?>-suite" class="cfs-overview-suite is-<?php echo esc_attr($key); ?>-suite">
				<div class="cfs-overview-suite-head">
					<div class="cfs-overview-suite-icon"><i class="fa <?php echo esc_attr($suite['icon']); ?>"></i></div>
					<div><span class="cfs-overview-pill"><?php echo esc_html($suite['pill']); ?></span><h2><?php echo esc_html($suite['title']); ?></h2><p><?php echo esc_html($suite['text']); ?></p></div>
				</div>
				<div class="cfs-overview-capability-grid">
					<?php foreach ($suite['items'] as $item) { ?>
						<article><i class="fa <?php echo esc_attr($item[0]); ?>"></i><h3><?php echo esc_html($item[1]); ?></h3><p><?php echo esc_html($item[2]); ?></p><?php echo $tag($item[3]); ?></article>
					<?php } ?>
				</div>
			</section>
			<?php } ?>

			<section class="cfs-overview-pro-panel is-ces">
				<div>
					<span class="cfs-overview-pill">Customer Engagement System</span>
					<h2>One plugin for every way visitors reach you.</h2>
					<p>CES puts forms, popups, a CRM and email automation on one contact base. Move your Contact Form forms in one click and keep growing from there.</p>
				</div>
				<div class="cfs-overview-pro-list">
					<span><i class="fa fa-check"></i> Forms with conditional logic for free</span>
					<span><i class="fa fa-check"></i> Every message becomes a CRM contact</span>
					<span><i class="fa fa-check"></i> Auto-replies, automations and newsletters</span>
					<span><i class="fa fa-check"></i> Popups with smart triggers</span>
					<span><i class="fa fa-check"></i> 200+ templates</span>
					<span><i class="fa fa-check"></i> Your forms moved in one click</span>
				</div>
				<?php if (!empty($ces['url'])) { ?>
					<a class="button button-primary button-hero cfs-overview-upgrade-cta" href="<?php echo esc_url($ces['url']); ?>"><i class="fa fa-rocket"></i> <?php echo esc_html($ces['label']); ?></a>
				<?php } ?>
			</section>

			<section id="cfs-overview-videos" class="cfs-overview-video-grid">
				<article class="cfs-overview-card">
					<div class="cfs-overview-card-head"><div><span class="cfs-overview-pill">Learn</span><h2>Video tutorial</h2></div></div>
					<div class="cfs-overview-video-frame"><iframe title="Contact Form tutorial" src="https://www.youtube.com/embed/_GvD8fZryzY" frameborder="0" allowfullscreen loading="lazy"></iframe></div>
				</article>
				<article class="cfs-overview-card">
					<div class="cfs-overview-card-head"><div><span class="cfs-overview-pill">Discover</span><h2>Supsystic overview</h2></div></div>
					<div class="cfs-overview-video-frame"><iframe title="Supsystic overview" src="https://www.youtube.com/embed/dKd_9g6JzfU" frameborder="0" allowfullscreen loading="lazy"></iframe></div>
				</article>
			</section>

			<section id="cfs-overview-faq" class="cfs-overview-card cfs-overview-faq">
				<div class="cfs-overview-card-head"><div><span class="cfs-overview-pill">FAQ</span><h2>Questions and answers</h2></div><p>The most common questions about forms, subscriptions and PRO.</p></div>
				<?php foreach ($this->faqList as $title => $desc) { ?>
					<details class="cfs-overview-faq-item">
						<summary><?php echo esc_html($title); ?></summary>
						<div class="cfs-overview-faq-text"><?php echo wp_kses_post($desc); ?></div>
					</details>
				<?php } ?>
				<a class="button" href="https://supsystic.com/docs/contact-form/?utm_source=plugin&amp;utm_medium=faq&amp;utm_campaign=contact-form" target="_blank" rel="noopener"><i class="fa fa-book"></i> Check all FAQs</a>
			</section>

			<section id="cfs-overview-server" class="cfs-overview-card">
				<div class="cfs-overview-card-head"><div><span class="cfs-overview-pill">Diagnostics</span><h2>Server settings</h2></div><p>Useful environment details for troubleshooting.</p></div>
				<div class="cfs-overview-server-grid">
					<?php foreach ($this->serverSettings as $title => $element) { ?>
						<div class="cfs-overview-server-item<?php echo !empty($element['error']) ? ' is-error' : ''; ?>"><span><?php echo esc_html(trim($title)); ?></span><strong><?php echo esc_html(trim((string) $element['value'])); ?></strong></div>
					<?php } ?>
				</div>
			</section>
		</div>

		<aside class="cfs-overview-sidebar">
			<section class="cfs-overview-card cfs-overview-resource-card">
				<span class="cfs-overview-pill">Resources</span><h2>Useful links</h2>
				<a class="cfs-overview-resource-link" href="#cfs-ces"><i class="fa fa-rocket"></i> Contact Form and CES</a>
				<a class="cfs-overview-resource-link" href="<?php echo esc_url($createUrl); ?>"><i class="fa fa-plus-circle"></i> Create a new form</a>
				<a class="cfs-overview-resource-link" href="<?php echo esc_url($formsUrl); ?>"><i class="fa fa-list"></i> Manage forms</a>
				<a class="cfs-overview-resource-link" href="<?php echo esc_url($contactsUrl); ?>"><i class="fa fa-users"></i> Contacts</a>
				<a class="cfs-overview-resource-link" href="<?php echo esc_url($settingsUrl); ?>"><i class="fa fa-cog"></i> Settings</a>
				<a class="cfs-overview-resource-link" href="#cfs-overview-videos"><i class="fa fa-play"></i> Video tutorials</a>
				<a class="cfs-overview-resource-link" href="#cfs-overview-faq"><i class="fa fa-question-circle"></i> Questions and answers</a>
				<a class="cfs-overview-resource-link" href="#cfs-overview-server"><i class="fa fa-server"></i> Server settings</a>
				<a class="cfs-overview-resource-link" href="https://supsystic.com/docs/contact-form/?utm_source=plugin&amp;utm_medium=overview&amp;utm_campaign=contact-form" target="_blank" rel="noopener"><i class="fa fa-book"></i> Documentation</a>
				<a class="cfs-overview-resource-link" href="#cfs-compare"><i class="fa fa-balance-scale"></i> CES Free and CES Pro</a>
			</section>
			<section class="cfs-overview-card cfs-overview-outcome-card">
				<span class="cfs-overview-pill">Popular uses</span><h2>One plugin, many forms</h2>
				<ul><li>Contact and feedback forms</li><li>Quote and order requests</li><li>Support tickets</li><li>Newsletter sign-ups</li><li>Job applications with files</li><li>Event registrations</li><li>Guest posts and reviews</li><li>Surveys with ratings</li></ul>
			</section>
			<section id="cfs-overview-support" class="cfs-overview-card cfs-overview-support-card">
				<span class="cfs-overview-pill">Support</span><h2>Need help with a form?</h2><p>Send us your question, issue or feature request. The Supsystic support team will help you set up the right form.</p>
				<a class="button button-primary cfs-ui-main" href="https://supsystic.com/contact-us/" target="_blank" rel="noopener"><i class="fa fa-life-ring"></i> Contact support</a>
			</section>
		</aside>
	</section>
</div>

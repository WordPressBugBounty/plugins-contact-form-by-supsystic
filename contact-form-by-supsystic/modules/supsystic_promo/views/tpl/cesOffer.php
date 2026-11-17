<?php
/**
 * CES Free vs CES Pro and CES Pro prices from the supsystic.com page of Contact Form (the forms of Contact Form now
 * live in CES, the Customer Engagement System). Copied from the live page: when its prices change, rebuild this file.
 * $ces: the CES action of supsystic_promoCfs::getCesState(), $couponHtml: the offer with the CES Pro code of the site
 * (CFPRO30 when Contact Form PRO works with an active license, CFFREE15 otherwise; empty with CES Pro).
 * With a code every price of the page is shown struck through next to the price with the discount (rounded to cents as
 * on the site), every card names the code, and every Buy button puts its own license (pid) in the cart with the code.
 */
$ces = $this->ces;
$couponHtml = $this->couponHtml;
$promo = frameCfs::_()->getModule('supsystic_promo');
$coupon = $promo->getCesCoupon();
ob_start();
?>
<div class="cfs-pro-offer cfs-ces-offer">
<section class="cfMtxSection" id="cfs-compare">
<div class="cfMtxTop"><div class="cfMtxSectionInner">
<div class="cfSectionLabel">CES Free vs CES Pro</div><h2 class="cfMtxSectionTitle">Everything in the Box &mdash; and Exactly What Pro Adds</h2>
<div class="cfMtxSectionSubtitle">The forms module of the Customer Engagement System: 35+ capabilities, compared line by line.</div>
<div class="cfMtxScore">
<div class="cfMtxPlan" style="--cf-ring-off:95.10"><div class="cfMtxPlanTop"><div class="cfMtxRing"><svg viewBox="0 0 92 92" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle class="cfMtxRingTrack" cx="46" cy="46" r="40"></circle><circle class="cfMtxRingFill" cx="46" cy="46" r="40"></circle></svg><div class="cfMtxRingLabel"><span class="cfMtxRingNum">20+</span><span class="cfMtxRingOf">in Free</span></div></div><div><div class="cfMtxPlanName">CES Free</div><div class="cfMtxPlanPrice">$0 <span>forever</span></div></div></div>
<div class="cfMtxPlanText">Every form type, unlimited forms and submissions, 10+ field types, conditional logic, built-in spam protection, admin notifications, auto-replies, tags on submit and custom CSS.</div>
<?php if (!empty($ces['url'])) { ?><a href="<?php echo esc_url($ces['url']); ?>" class="cfBtnGhost"><?php echo esc_html($ces['label']); ?></a><?php } ?></div>
<div class="cfMtxPlan cfMtxPlanPro"><div class="cfMtxPlanTop"><div class="cfMtxRing"><svg viewBox="0 0 92 92" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle class="cfMtxRingTrack" cx="46" cy="46" r="40"></circle><circle class="cfMtxRingFill" cx="46" cy="46" r="40"></circle></svg><div class="cfMtxRingLabel"><span class="cfMtxRingNum">35+</span><span class="cfMtxRingOf">in Pro</span></div></div><div><div class="cfMtxPlanName">CES Pro</div><div class="cfMtxPlanPrice">from $99 <span>per year</span></div><div class="cfMtxPlanPriceAlt"><span>lifetime from</span><b>$749</b></div></div></div>
<div class="cfMtxPlanText">20+ more field types, masks and regex validation, file uploads, email verification, custom JavaScript and premium support.</div>
<a href="#cfs-pricing" class="cfBtnPrimary">View License Options</a></div>
</div>
</div></div>
<div class="cfMtxBottom"><div class="cfMtxSectionInner">
<div class="cfMtxTableWrap">
<div class="cfMtxControls"><div class="cfMtxControlsTitle">Full feature comparison</div>
<div class="cfMtxFilters" role="group" aria-label="Filter the comparison"><button type="button" class="cfMtxFilter cfMtxFilterActive" data-cf-tier-filter="all">All</button><button type="button" class="cfMtxFilter" data-cf-tier-filter="free">In Free</button><button type="button" class="cfMtxFilter" data-cf-tier-filter="pro">Pro only</button></div></div>
<div class="cfMtxTable">
<div class="cfMtxRow cfMtxHeadRow"><div class="cfMtxCell cfMtxCellFeature">Capability</div><div class="cfMtxCell cfMtxHeadPlan"><strong>CES Free</strong><span class="cfMtxHeadPlanSub">wordpress.org</span></div><div class="cfMtxCell cfMtxHeadPlan cfMtxHeadPro"><strong>CES Pro</strong><span class="cfMtxHeadPlanSub">from $99/yr</span><span class="cfMtxHeadPlanAlt">lifetime from $749</span></div></div>
<div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></span>Form types</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:100%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Simple (message + auto-reply)</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Create contact</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Identify contact (update by email)</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">WordPress login / logout</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">WordPress signup</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Password reset</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></span>Fields</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:22%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Text, password, textarea, number, email, phone</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Checkbox, radio, select, hidden</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">File and multi-file upload</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Date, time, month, week</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">URL, range, color, rating</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Address search (Google Maps)</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Languages and countries lists</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Posts, pages, categories, tags lists</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">WooCommerce categories, tags, attributes, products</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9m0 0l-4.5 4.5M12 12l4.5 4.5M7.5 16.5v4.5m9-4.5v4.5"/></svg></span>Logic & validation</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:33%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Required fields, placeholders, labels</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Conditional logic</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Input masks</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Regex validation patterns</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Min / max length and value</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Email verification before contact creation</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></span>Protection & delivery</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:83%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Honeypot</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">CAPTCHA</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Admin notification email</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Auto-reply from template</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Send via SMTP / PHP mail</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Send via 10+ Pro providers</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg></span>CRM & automation</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:100%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Tags on submit</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Custom-field values from answers</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Start an automation on submit</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Submissions list and export</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Submission stats per form</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxGroup" data-cf-group><div class="cfMtxGroupName"><span class="cfMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg></span>Placement & code</div><div class="cfMtxGroupMeter"><span class="cfMtxGroupBar"><span class="cfMtxGroupBarFill" style="width:60%"></span></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Shortcode and block</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Inside a CES popup</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="free"><div class="cfMtxCell cfMtxCellFeature">Custom CSS</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Custom JavaScript</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div><div class="cfMtxRow" data-cf-tier="pro"><div class="cfMtxCell cfMtxCellFeature">Premium support</div><div class="cfMtxCell cfMtxCellFree"><span class="cfMtxNo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg></span></div><div class="cfMtxCell cfMtxCellPro"><span class="cfMtxYes"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span></div></div>
<div class="cfMtxRow cfMtxFootRow"><div class="cfMtxCell cfMtxCellFeature"></div><div class="cfMtxCell cfMtxCellFree"><?php if (!empty($ces['url'])) { ?><a href="<?php echo esc_url($ces['url']); ?>" class="cfMtxFootFree"><?php echo esc_html($ces['label']); ?></a><?php } ?></div><div class="cfMtxCell cfMtxCellPro"><a href="#cfs-pricing" class="cfBtnPrimary">Get Pro &rarr;</a></div></div>
</div>
</div>
<div class="cfMtxNote">Every Pro license unlocks <b>every capability</b> across Popup, Forms, CRM and Email &mdash; tiers differ only in how many sites they cover.<br>Pro is a superset of Free: nothing you build on the free version has to be rebuilt after upgrading.</div>
</div></div>
</section>
<section class="cfPrsSection" id="cfs-pricing">
<div class="cfPrsSectionInner">
<style>
.supPricing{max-width:1100px;margin:0 auto;font-family:Montserrat,Arial,sans-serif;color:#1f2a44}
.supPricing,.supPricing *{box-sizing:border-box}

.sptHead{text-align:center;margin:0 0 22px}
.supPricing .sptEyebrow{display:flex;align-items:center;justify-content:center;gap:14px;margin:0 0 14px;font-size:12px;line-height:1;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#0d838d}
.supPricing .sptEyebrow::before,.supPricing .sptEyebrow::after{content:"";flex:0 0 48px;height:2px;border-radius:2px}
.supPricing .sptEyebrow::before{background:linear-gradient(90deg,rgba(82,186,197,0),#52bac5)}
.supPricing .sptEyebrow::after{background:linear-gradient(90deg,#52bac5,rgba(82,186,197,0))}
.supPricing .sptTitle{font-family:inherit;font-size:clamp(26px,3.2vw,44px);line-height:1.15;font-weight:800;letter-spacing:-0.025em;color:#1f2a44;margin:0 0 10px;padding:0;text-align:center;text-transform:none}
.sptSubtitle{font-size:15px;line-height:1.5;color:#6b7385;margin:0}
.sptTabs{display:flex;justify-content:center;gap:10px;margin:0 auto}
.supPricing .sptTab{appearance:none;-webkit-appearance:none;display:flex;align-items:center;gap:14px;flex:0 1 178px;min-width:0;margin:0;padding:10px 16px;background:#fff;border:1px solid #dfe3ea;border-radius:8px;box-shadow:none;cursor:pointer;font:inherit;text-align:left;text-transform:none;letter-spacing:normal;line-height:1.25;color:#1f2a44;transition:border-color .15s,background-color .15s}
.supPricing .sptTab:hover{border-color:#b9c3d3}
.supPricing .sptTab:focus-visible{outline:2px solid #2B72C6;outline-offset:2px}
.supPricing .sptTab.is-active{background:#eef8ec;border:2px solid #4A8D22;padding:9px 15px}
.sptTabIcon{flex:0 0 30px;width:30px;height:30px;color:#2B72C6}
.sptTabIcon svg{display:block;width:100%;height:100%}
.sptTab.is-active .sptTabIcon{color:#3f7f1d}
.sptTabName{display:block;font-size:14px;font-weight:700;color:#1f2a44}
.sptTabSites{display:block;font-size:12px;font-weight:400;color:#6b7385;margin-top:2px}
.sptTab.is-active .sptTabSites{color:#3f7f1d}
.sptHint{text-align:center;font-size:13px;color:#6b7385;margin:12px 0 18px}

.sptPanel[hidden]{display:none}
.sptGrid{display:flex;justify-content:center;align-items:stretch;gap:18px}
.sptCard{flex:0 1 calc((100% - 36px) / 3);display:flex;flex-direction:column;background:#f5f7fb;border:1px solid #e3e7ee;border-radius:8px;box-shadow:0 2px 10px rgba(31,42,68,.06);text-align:center;overflow:hidden}
.sptCardHead{padding:8px 10px;font-size:19px;font-weight:700;letter-spacing:.3px;color:#fff}
.sptTermYear .sptCardHead,.sptTermYear .sptBtn{background:#2B72C6}
.sptTermThree .sptCardHead,.sptTermThree .sptBtn{background:#4A8D22}
.sptTermLife .sptCardHead,.sptTermLife .sptBtn{background:#E58B07}
.sptPrice{flex:1 0 auto;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;padding:16px 14px 14px;background:#fff}
.sptBadge{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;margin-bottom:4px;border-radius:5px;background:#e2f3df;color:#2f6f18;font-size:12px;font-weight:600;line-height:1.3}
.sptBadge svg{width:13px;height:13px;flex:0 0 13px}
.sptRegular{font-size:13px;color:#6b7385;line-height:1.3}
.sptRegularOld{position:relative;display:inline-block;margin:0 2px 0 4px;font-size:22px;font-weight:700;color:#8a94a6}
.sptRegularOld::after{content:"";position:absolute;left:-3px;right:-3px;top:52%;height:2px;background:#e0443e;transform:rotate(-8deg)}
.sptAmountRow{display:flex;align-items:baseline;justify-content:center;gap:8px;line-height:1}
.sptAmount{position:relative;font-size:46px;font-weight:700;color:#111827;letter-spacing:-.5px}
.sptCur{font-size:24px;font-weight:600;vertical-align:top;position:relative;top:4px;margin-right:2px;color:#111827}
.sptPer{font-size:18px;font-weight:400;color:#6b7385}
.sptNote{font-size:13px;color:#6b7385;line-height:1.35}
.sptFeatures{margin:0;padding:10px 12% 6px;text-align:left}
.sptFeature{display:flex;align-items:center;gap:12px;padding:6px 4px;font-size:14px;font-weight:400;color:#1f2a44;border-bottom:1px solid #e1e5ec}
.sptFeature:last-child{border-bottom:0}
.sptFeature svg{flex:0 0 16px;width:16px;height:16px;color:#3a8a1c}
.sptCta{padding:8px 14px 12px}
.supPricing a.sptBtn,.supPricing a.sptBtn:hover,.supPricing a.sptBtn:focus{display:block;width:84%;margin:0 auto;padding:8px 10px;border-radius:6px;color:#fff;font-size:17px;font-weight:700;letter-spacing:.3px;text-decoration:none;line-height:1.3}
.supPricing a.sptBtn:hover{filter:brightness(1.07)}
.sptGuarantee{margin-top:6px;font-size:11.5px;color:#6b7385}
.sptFooterNote{text-align:center;font-size:14px;color:#56708f;margin:18px 0 0}

@media (max-width:860px){
  .sptGrid{flex-direction:column;align-items:center}
  .sptCard{flex:none;width:100%;max-width:420px}
}
@media (max-width:600px){
  .sptTabs{gap:6px}
  .supPricing .sptTab{flex:1 1 0;flex-direction:column;gap:6px;padding:10px 6px;text-align:center}
  .supPricing .sptTab.is-active{padding:9px 5px}
  .sptTabIcon{flex-basis:26px;width:26px;height:26px}
  .sptTabName{font-size:13px}
  .sptTabSites{font-size:11px}
}
</style>
    <div class="supPricing" id="sptPricing1">

  <div class="sptHead">
    <div class="sptEyebrow">CES Pro licensing</div>
    <h2 class="sptTitle">Choose your licence and get started</h2>
    <p class="sptSubtitle">Flexible options for individuals, businesses and agencies.</p>
  </div>

  <div class="sptTabs" role="tablist" aria-label="Licence">
        <button type="button" class="sptTab is-active" role="tab"
      id="sptPricing1-tab-single"
      aria-controls="sptPricing1-panel-single"
      aria-selected="true"
      data-licence="single">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg></span>
      <span>
        <span class="sptTabName">Personal</span>
        <span class="sptTabSites">1 site</span>
      </span>
    </button>
        <button type="button" class="sptTab" role="tab"
      id="sptPricing1-tab-dev"
      aria-controls="sptPricing1-panel-dev"
      aria-selected="false"
      data-licence="dev">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><circle cx="4.8" cy="10" r="2.3"/><circle cx="19.2" cy="10" r="2.3"/><path d="M6.5 20c0-3.3 2.5-5.5 5.5-5.5s5.5 2.2 5.5 5.5"/><path d="M1.2 19c0-2.4 1.6-4 3.8-4"/><path d="M22.8 19c0-2.4-1.6-4-3.8-4"/></svg></span>
      <span>
        <span class="sptTabName">Multi-site</span>
        <span class="sptTabSites">Up to 5 sites</span>
      </span>
    </button>
        <button type="button" class="sptTab" role="tab"
      id="sptPricing1-tab-enterprise"
      aria-controls="sptPricing1-panel-enterprise"
      aria-selected="false"
      data-licence="enterprise">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="3" width="10.5" height="18" rx="1"/><path d="M14 9h5.5a1 1 0 0 1 1 1v11H14"/><path d="M6.5 7h1.5M10 7h1.5M6.5 11h1.5M10 11h1.5M6.5 15h1.5M10 15h1.5M16.5 13h1.5M16.5 17h1.5"/><path d="M1.5 21h21"/></svg></span>
      <span>
        <span class="sptTabName">Agency</span>
        <span class="sptTabSites">Unlimited sites</span>
      </span>
    </button>
      </div>
  <p class="sptHint">Choose your licence first, then your term.</p>

    <div class="sptPanel" role="tabpanel" data-licence="single"
    id="sptPricing1-panel-single"
    aria-labelledby="sptPricing1-tab-single">
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 12.6V4a2 2 0 0 1 2-2h8.6a2 2 0 0 1 1.4.6l8.4 8.4a2 2 0 0 1 0 2.8l-8.6 8.6a2 2 0 0 1-2.8 0L2.6 14A2 2 0 0 1 2 12.6zM7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>Introductory price</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>99</span>
            <span class="sptPer">/ year</span>
          </div>
          <div class="sptNote">Renews at $79/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>1 Site License</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30155">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10v2h4v3a5 5 0 0 1-5 5h-.4A5 5 0 0 1 13 14.9V18h3v2.5H8V18h3v-3.1A5 5 0 0 1 8.4 12H8a5 5 0 0 1-5-5V4h4V2zM5 6v1a3 3 0 0 0 2 2.8V6H5zm12 0v3.8A3 3 0 0 0 19 7V6h-2z"/></svg>Best long-term value</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>249</span>
            <span class="sptPer">/ 3 years</span>
          </div>
          <div class="sptNote">That's only $83/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>1 Site License</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30162">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      
    </div>
  </div>
    <div class="sptPanel" role="tabpanel" data-licence="dev"
    id="sptPricing1-panel-dev"
    aria-labelledby="sptPricing1-tab-dev" hidden>
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 12.6V4a2 2 0 0 1 2-2h8.6a2 2 0 0 1 1.4.6l8.4 8.4a2 2 0 0 1 0 2.8l-8.6 8.6a2 2 0 0 1-2.8 0L2.6 14A2 2 0 0 1 2 12.6zM7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>Introductory price</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>199</span>
            <span class="sptPer">/ year</span>
          </div>
          <div class="sptNote">Renews at $159/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30159">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10v2h4v3a5 5 0 0 1-5 5h-.4A5 5 0 0 1 13 14.9V18h3v2.5H8V18h3v-3.1A5 5 0 0 1 8.4 12H8a5 5 0 0 1-5-5V4h4V2zM5 6v1a3 3 0 0 0 2 2.8V6H5zm12 0v3.8A3 3 0 0 0 19 7V6h-2z"/></svg>Best long-term value</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>499</span>
            <span class="sptPer">/ 3 years</span>
          </div>
          <div class="sptNote">That's only $166/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30163">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

            <div class="sptCard sptTermLife">
        <div class="sptCardHead">Lifetime</div>
        <div class="sptPrice">
          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>749</span>
          </div>
          <div class="sptNote">Pay once, use forever</div>
        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30166">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>
      
    </div>
  </div>
    <div class="sptPanel" role="tabpanel" data-licence="enterprise"
    id="sptPricing1-panel-enterprise"
    aria-labelledby="sptPricing1-tab-enterprise" hidden>
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 12.6V4a2 2 0 0 1 2-2h8.6a2 2 0 0 1 1.4.6l8.4 8.4a2 2 0 0 1 0 2.8l-8.6 8.6a2 2 0 0 1-2.8 0L2.6 14A2 2 0 0 1 2 12.6zM7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>Introductory price</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>399</span>
            <span class="sptPer">/ year</span>
          </div>
          <div class="sptNote">Renews at $329/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30160">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10v2h4v3a5 5 0 0 1-5 5h-.4A5 5 0 0 1 13 14.9V18h3v2.5H8V18h3v-3.1A5 5 0 0 1 8.4 12H8a5 5 0 0 1-5-5V4h4V2zM5 6v1a3 3 0 0 0 2 2.8V6H5zm12 0v3.8A3 3 0 0 0 19 7V6h-2z"/></svg>Best long-term value</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>999</span>
            <span class="sptPer">/ 3 years</span>
          </div>
          <div class="sptNote">That's only $333/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30164">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

            <div class="sptCard sptTermLife">
        <div class="sptCardHead">Lifetime</div>
        <div class="sptPrice">
          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>1,499</span>
          </div>
          <div class="sptNote">Pay once, use forever</div>
        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&#038;addQty=1&#038;goto=checkout&#038;lang=en&#038;action=addToCart&#038;pid=30167">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>
      
    </div>
  </div>
  
  <?php echo $couponHtml; ?><p class="sptFooterNote">Lock in the introductory price &ndash; renewals never cost more than you paid.</p></div>

  <div class="cfPrsSectionSubtitle2">Choose 3-year or lifetime options for even greater savings.</div>
<div class="cfPrsIncluded"><div class="cfPrsIncludedIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div><div><div class="cfPrsIncludedTitle">Every tier unlocks all four modules</div><div class="cfPrsIncludedText">There is no per-module upsell. A Pro license switches on the Pro triggers, 20+ Pro field types, masks and uploads, 10+ mail providers and premium support &mdash; on every site the license covers.</div></div></div>
</div>
</section>
</div>
<?php
$offerHtml = ob_get_clean();
$utm = '&#038;utm_source=plugin&#038;utm_medium=overview_prices&#038;utm_campaign=cf_to_ces';
if ($coupon) {
  $deal = '−' . (int) $coupon['discount'] . '% · ' . $coupon['code'];
  $price = function ($value) use ($promo, $coupon) {
    return $promo->getCesDiscountPrice($value, $coupon['discount']);
  };
  // Plans of the comparison: "from $99 per year" and "lifetime from $749"
  $offerHtml = preg_replace_callback('/<div class="cfMtxPlanPrice">from (\$[\d,.]+) <span>per year<\/span><\/div><div class="cfMtxPlanPriceAlt"><span>lifetime from<\/span><b>(\$[\d,.]+)<\/b><\/div>/', function ($m) use ($price, $deal) {
    return '<div class="cfMtxPlanPrice">from <s>' . $m[1] . '</s> ' . esc_html($price($m[1])) . ' <span>per year</span></div>'
      . '<div class="cfMtxPlanPriceAlt"><span>lifetime from</span><s>' . $m[2] . '</s><b>' . esc_html($price($m[2])) . '</b></div>'
      . '<em class="cfMtxPlanDeal">' . esc_html($deal) . '</em>';
  }, $offerHtml);
  $offerHtml = preg_replace_callback('/<span class="cfMtxHeadPlanSub">from (\$[\d,.]+)\/yr<\/span><span class="cfMtxHeadPlanAlt">lifetime from (\$[\d,.]+)<\/span>/', function ($m) use ($price) {
    return '<span class="cfMtxHeadPlanSub">from ' . esc_html($price($m[1])) . '/yr</span><span class="cfMtxHeadPlanAlt">lifetime from ' . esc_html($price($m[2])) . '</span>';
  }, $offerHtml);
  // Every license card
  $parts = explode('<div class="sptCard ', $offerHtml);
  foreach ($parts as $i => $part) {
    if ($i === 0) {
      continue;
    }
    if (!preg_match('/<span class="sptAmount"><span class="sptCur">\$<\/span>([\d,.]+)<\/span>/', $part, $m)) {
      continue;
    }
    $was = '$' . $m[1];
    // A callback, not a replacement string: "$99" there would be read as the group $9
    $part = preg_replace_callback('/<div class="sptAmountRow">\s*<span class="sptAmount"><span class="sptCur">\$<\/span>[\d,.]+<\/span>/', function () use ($deal, $was, $price) {
      return '<span class="sptDeal">' . esc_html($deal) . '</span>'
        . '<div class="sptAmountRow"><s class="sptWas">' . esc_html($was) . '</s><span class="sptAmount is-deal"><span class="sptCur">$</span>' . esc_html(substr($price($was), 1)) . '</span>';
    }, $part, 1);
    // "That's only $83/year" of a 3-year license: a third of the price with the discount
    $part = preg_replace_callback('/That(?:&#039;|\x27)s only \$[\d,.]+\/year/', function () use ($promo, $coupon, $was) {
      $third = (float) preg_replace('/[^0-9.]/', '', $was) / 3;
      return "That's only " . $promo->getCesDiscountPrice((string) $third, $coupon['discount']) . '/year';
    }, $part, 1);
    $part = preg_replace('/(pid=\d+)"/', '$1&#038;coupon=' . rawurlencode($coupon['code']) . $utm . '"', $part, 1);
    $part = str_replace('<div class="sptGuarantee">', '<div class="sptDealNote">' . esc_html(sprintf('−%d%% with %s at checkout', $coupon['discount'], $coupon['code'])) . '</div><div class="sptGuarantee">', $part);
    $parts[$i] = $part;
  }
  $offerHtml = implode('<div class="sptCard ', $parts);
} else {
  $offerHtml = preg_replace('/(pid=\d+)"/', '$1' . $utm . '"', $offerHtml);
}
echo $offerHtml;

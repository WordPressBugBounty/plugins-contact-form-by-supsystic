/**
 * PRO feature windows of the free version. Any element with data-cfs-upsell="<feature>" opens the window of that
 * feature (templates printed by supsystic_promoCfs::printUpsellWindows). The old "PRO option" labels and the old PRO
 * popup of the plugin (#cfsOptInProWnd) find their feature by the utm_medium of their link, so every PRO lock of the
 * plugin opens its own window; a lock without a window keeps its old behaviour.
 */
(function () {
  'use strict';

  // utm_medium of the old PRO links => feature window
  var MEDIUM = {
    conditional_logic: 'logic',
    abtesting: 'abtesting',
    subscribe: 'subscribe',
    publish_content: 'publish',
    registration: 'registration',
    fb_convert: 'fbconvert',
    field_icon: 'icons',
    value_preset: 'presets',
    access_roles: 'access',
    field_file: 'file',
    field_rating: 'rating',
    field_recaptcha_v3: 'captcha',
    field_hcaptcha: 'captcha',
    field_turnstile: 'captcha',
    field_wptextarea: 'wpfields',
    field_wpcategories: 'wpfields',
    field_wooattrs: 'wpfields',
    field_checkboxsubscribe: 'subscribe',
    field_address: 'address',
  };

  var dialog = null,
    body = null;

  function setup() {
    if (dialog) {
      return true;
    }
    dialog = document.querySelector('[data-cfs-up]');
    body = dialog ? dialog.querySelector('[data-cfs-up-body]') : null;
    if (!dialog || !body || typeof dialog.showModal !== 'function') {
      dialog = null;
      return false;
    }
    dialog.addEventListener('click', function (event) {
      // A click on the dimmed backdrop lands on the dialog itself
      if (event.target === dialog || event.target.closest('[data-cfs-up-close]')) {
        dialog.close();
      }
    });
    return true;
  }

  function keyFromHref(href) {
    var match = /[?&]utm_medium=([^&#]+)/.exec(href || '');
    if (!match) {
      return null;
    }
    return MEDIUM[match[1]] || (document.getElementById('cfs-up-' + match[1]) ? match[1] : null);
  }

  /**
   * @param {string} key The feature
   * @return {boolean} Whether the window opened
   */
  function open(key) {
    var template = key ? document.getElementById('cfs-up-' + key) : null;
    if (!template || !setup()) {
      return false;
    }
    body.textContent = '';
    body.appendChild(template.content.cloneNode(true));
    if (!dialog.open) {
      dialog.showModal();
    }
    body.scrollTop = 0;
    var cta = body.querySelector('.cfs-up-cta');
    if (cta) {
      cta.focus({ preventScroll: true });
    }
    return true;
  }

  document.addEventListener(
    'click',
    function (event) {
      if (!event.target.closest) {
        return;
      }
      var trigger = event.target.closest('[data-cfs-upsell]'),
        key = trigger ? trigger.getAttribute('data-cfs-upsell') : null;
      if (!trigger) {
        var label = event.target.closest('.cfsProOptMiniLabel a');
        key = label ? keyFromHref(label.getAttribute('href')) : null;
      }
      if (key && open(key)) {
        event.preventDefault();
        event.stopImmediatePropagation();
      }
    },
    true
  );

  // The old PRO popup: the plugin fills it from the row of the PRO option (cfsFillInMainPromoWnd) and opens it. When
  // that row names a feature with a window, the window opens instead.
  function hookOldPopup() {
    var $ = window.jQuery;
    if (!$ || typeof window.cfsFillInMainPromoWnd !== 'function' || window.cfsFillInMainPromoWnd.cfsUpsell) {
      return;
    }
    var pending = null,
      fill = window.cfsFillInMainPromoWnd;
    window.cfsFillInMainPromoWnd = function ($shell) {
      pending = $shell && $shell.find ? keyFromHref($shell.find('.cfsProOptMiniLabel a').attr('href')) : null;
      return fill.apply(this, arguments);
    };
    window.cfsFillInMainPromoWnd.cfsUpsell = true;
    $(document).on('dialogopen', '#cfsOptInProWnd', function () {
      var key = pending;
      pending = null;
      if (key && document.getElementById('cfs-up-' + key)) {
        $(this).dialog('close');
        open(key);
      }
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hookOldPopup);
  } else {
    hookOldPopup();
  }

  window.CfsUpsell = { open: open };
})();

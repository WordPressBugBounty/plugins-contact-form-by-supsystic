(function ($) {
  'use strict';

  // Contact Form is now part of CES: the invitation shows on the plugin's pages until it is closed,
  // then stays hidden for a week in this browser.
  var STORAGE_KEY = 'cfsCesPromoLastClosed';
  var HIDE_FOR = 7 * 24 * 60 * 60 * 1000;

  function getLastClosed() {
    try {
      return parseInt(window.localStorage.getItem(STORAGE_KEY), 10) || 0;
    } catch (e) {
      return 0;
    }
  }

  function rememberClosed() {
    try {
      window.localStorage.setItem(STORAGE_KEY, String(Date.now()));
    } catch (e) {}
  }

  function shouldShow() {
    return Date.now() - getLastClosed() >= HIDE_FOR;
  }

  $(function () {
    var $overlay = $('#cfsCesPromoOverlay');
    if (!$overlay.length) {
      return;
    }

    function openPopup() {
      $overlay.addClass('is-open');
    }

    function hidePopup() {
      $overlay.removeClass('is-open');
    }

    function closePopup() {
      hidePopup();
      rememberClosed();
    }

    $overlay.on('click', function (e) {
      if (e.target === this) {
        closePopup();
      }
    });
    $('#cfsCesPromoClose, #cfsCesPromoDismiss').on('click', closePopup);
    // Install / Read more also count as an answer: the invitation comes back in a week
    $overlay.on('click', '.cfs-ces-promo-remember', rememberClosed);
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && $overlay.hasClass('is-open')) {
        closePopup();
      }
    });

    if (shouldShow()) {
      setTimeout(openPopup, 400);
    }
  });
})(jQuery);

/**
 * Design of the plugin pages. When an old jQuery UI dialog opens it is centered under the admin bar, and the buttons
 * that only close it (Cancel, Close, No) are marked plain, so the action button is the only dark one. It runs a tick
 * later: the plugin's own handler of the same event rewrites the classes of the buttons first.
 */
(function ($) {
  'use strict';

  var plain = /^(cancel|close|no|back|отмена|закрыть|нет|назад)$/i;

  $(document).on('dialogopen', function (event) {
    var $dialog = $(event.target).closest('.ui-dialog');
    if (!$dialog.length) {
      return;
    }
    $dialog.addClass('cfs-dlg-center');
    setTimeout(function () {
      var $buttons = $dialog.find('.ui-dialog-buttonset button');
      $buttons.each(function () {
        var $button = $(this);
        $button.toggleClass('cfs-dlg-plain', plain.test($.trim($button.text())));
      });
      // The first button that is not Cancel / Close is the action of the dialog
      $buttons.removeClass('cfs-dlg-main').not('.cfs-dlg-plain').first().addClass('cfs-dlg-main');
    }, 0);
  });

  // While the head bar sticks to the top (the plugin's sticky script makes it fixed) it keeps the width of the content
  // column, so the actions on its right stay on screen
  function fitStickyBar() {
    var $bar = $('.supsystic-breadcrumbs.supsystic-sticky-active'),
      $column = $('.supsystic-plugin .supsystic-container:first');
    if ($bar.length && $column.length) {
      $bar.outerWidth($column.outerWidth());
    }
  }
  $(document).on('startSticky', '.supsystic-breadcrumbs', fitStickyBar);
  $(document).on('stopSticky', '.supsystic-breadcrumbs', function () {
    $(this).css('width', '');
  });
  $(window).on('resize', fitStickyBar);

  // The CES Pro code (CFPRO30 / CFFREE15): a click copies it
  $(document).on('click', '[data-cfs-copy]', function (event) {
    event.preventDefault();
    var $button = $(this),
      code = String($button.data('cfs-copy')),
      done = function () {
        $button.find('.cfs-ces-coupon-copied').text($button.data('copied') || 'Copied');
        $button.addClass('is-copied');
        setTimeout(function () {
          $button.removeClass('is-copied');
        }, 1600);
      };
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(code).then(done, function () {});
      return;
    }
    var $tmp = $('<textarea readonly>').val(code).css({ position: 'fixed', left: '-9999px' }).appendTo('body');
    $tmp.get(0).select();
    try {
      document.execCommand('copy');
      done();
    } catch (e) {}
    $tmp.remove();
  });

  // The CES banner folds into a one-line strip and cannot be closed. The page comes folded or not from the server
  // (user meta of the current user); the button switches it and saves the choice.
  function setFolded($banner, folded) {
    var $button = $banner.find('[data-cfs-ces-fold]');
    $banner.toggleClass('is-folded', folded);
    $button.attr('aria-expanded', folded ? 'false' : 'true').attr('title', $button.data(folded ? 'title-more' : 'title-less'));
  }
  $(function () {
    var $banner = $('[data-cfs-ces-banner]');
    if (!$banner.length) {
      return;
    }
    $banner.on('click', '[data-cfs-ces-fold]', function () {
      var next = !$banner.hasClass('is-folded');
      setFolded($banner, next);
      $.post($banner.data('ajax-url'), { action: 'cfs_ces_banner_fold', nonce: $banner.data('nonce'), folded: next ? '1' : '0' });
    });
  });

  // The main action of a screen is navy, other buttons stay plain even when their markup says primary
  $(function () {
    // On/off settings of the Settings page are switches
    $('.cfs-settings-form .form-table input[type="checkbox"]').addClass('cfs-sw');
    $('#cfsSettingsSaveBtn, .cfsFormSaveBtn, #cfsCreateFormFrm button.button-primary, #cfsLicenseForm button.button-primary').addClass('cfs-ui-main');
  });
})(jQuery);

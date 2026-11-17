/**
 * Form editor: the tabs become one bar of group buttons with a search that finds any setting in every tab, each tab is
 * a card and the on/off checkboxes look like switches. Only the presentation changes: no field is moved, renamed or
 * rebuilt, and the tabs keep switching through their own links, so the editor scripts and the saved values work as
 * before.
 */
(function ($) {
  'use strict';

  function translate(text) {
    return window.cfsSettingsUi && window.cfsSettingsUi[text] ? window.cfsSettingsUi[text] : text;
  }

  // The rows the page itself hides (other field types, switched-off options) stay hidden while searching
  function shown(element, stop) {
    for (var node = element; node && node !== stop; node = node.parentElement) {
      if (node.hidden || (node.style && node.style.display === 'none' && !node.classList.contains('cfsTabContent'))) {
        return false;
      }
    }
    return true;
  }

  // On/off settings become switches. Checkbox lists (several choices of one setting) keep their boxes.
  function markSwitches(root) {
    $(root)
      .find('.form-table input[type="checkbox"], .cfsTabContent > label > input[type="checkbox"], .cfsFormOptRow > label > input[type="checkbox"]')
      .each(function () {
        var $box = $(this),
          $cell = $box.closest('td');
        if ($box.hasClass('cfs-sw') || $box.closest('ul, ol, .cfsListItems, .chosen-container').length) {
          return;
        }
        if ($cell.length && $cell.find('input[type="checkbox"]').length > 3) {
          return;
        }
        $box.addClass('cfs-sw');
      });
  }

  function init() {
    var $wrap = $('#cfsFormEditTabs'),
      form = document.getElementById('cfsFormEditForm');
    if (!$wrap.length || !form || $wrap.hasClass('cfs-ed-ui')) {
      return;
    }
    $wrap.addClass('cfs-ed-ui');

    var $nav = $wrap.find('.nav-tab-wrapper:first'),
      panels = Array.prototype.filter.call(form.children, function (child) {
        return child.classList.contains('cfsTabContent');
      });

    // Every tab button shows its icon and its name; the old script that hides one of them on narrow screens is
    // overruled in the stylesheet, the bar wraps instead
    $nav.find('a.nav-tab').each(function () {
      var $tab = $(this),
        panel = document.getElementById(($tab.attr('href') || '').replace('#', ''));
      if (panel) {
        panel.setAttribute('data-cfs-title', $.trim($tab.find('.cfsFormTabTitle').text() || $tab.text()));
      }
    });

    var $search = $('<label class="cfs-ed-search"><i class="fa fa-search" aria-hidden="true"></i><input type="search" autocomplete="off"></label>'),
      input = $search.find('input').get(0);
    input.placeholder = translate('Search settings');
    input.setAttribute('aria-label', translate('Search settings'));
    $nav.append($search);

    var empty = document.createElement('div');
    empty.className = 'cfs-ed-empty';
    empty.hidden = true;
    empty.textContent = translate('No settings match your search.');
    form.insertBefore(empty, form.firstChild);

    markSwitches(form);
    // Rows added later (copies of the submit blocks, PRO tabs filled by their scripts)
    if (window.MutationObserver) {
      var switchTimer = null;
      new MutationObserver(function () {
        window.clearTimeout(switchTimer);
        switchTimer = window.setTimeout(function () {
          markSwitches(form);
        }, 80);
      }).observe(form, { childList: true, subtree: true });
    }

    function isHit(row) {
      return !row.classList.contains('cfs-row-miss') && !!row.querySelector('th');
    }
    // Blocks without a single found row (buttons, notes, wrappers of other tables) are hidden while searching; a block
    // with hits is opened further, so only its found rows stay
    function prune(element, query) {
      Array.prototype.forEach.call(element.children, function (child) {
        if (child.tagName === 'TR' || child.tagName === 'TBODY' || child.tagName === 'THEAD') {
          return;
        }
        var hits = query ? Array.prototype.filter.call(child.querySelectorAll('tr'), isHit).length : 1;
        child.classList.toggle('cfs-chunk-miss', !!query && hits === 0 && !child.classList.contains('cfs-ed-keep'));
        if (query && hits && child.tagName !== 'TABLE') {
          prune(child, query);
        }
        if (!query) {
          Array.prototype.forEach.call(child.querySelectorAll('.cfs-chunk-miss'), function (node) {
            node.classList.remove('cfs-chunk-miss');
          });
        }
      });
    }

    function filter(query) {
      query = $.trim(query).toLowerCase();
      $wrap.toggleClass('is-searching', query !== '');
      var found = 0;
      panels.forEach(function (panel) {
        var hits = 0;
        Array.prototype.forEach.call(panel.querySelectorAll('tr'), function (row) {
          var label = row.querySelector('th');
          var match = !query || (label && label.textContent.toLowerCase().indexOf(query) !== -1);
          row.classList.toggle('cfs-row-miss', !!query && !match);
          if (match && query && label && shown(row, panel)) {
            hits++;
          }
        });
        panel.classList.toggle('cfs-panel-miss', query !== '' && hits === 0);
        prune(panel, query);
        found += hits;
      });
      empty.hidden = !query || found > 0;
    }

    var timer = null;
    input.addEventListener('input', function () {
      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        filter(input.value);
      }, 120);
    });
    input.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        input.value = '';
        filter('');
      }
      // The search sits inside the form: Enter must not submit it
      if (event.key === 'Enter') {
        event.preventDefault();
      }
    });
    // A click on a tab ends the search and shows that tab as before
    $nav.on('click', 'a.nav-tab', function () {
      if (input.value) {
        input.value = '';
        filter('');
      }
    });
    document.addEventListener('keydown', function (event) {
      var tag = event.target && event.target.tagName;
      if (event.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT' && !event.target.isContentEditable) {
        event.preventDefault();
        input.focus();
      }
    });
  }


  /**
   * Free version: when the form keeps settings of PRO features (a mailing service, posts, user sign-ups, conditional
   * logic), the free editor has no fields for them and a save would clear them. Before such a save a window says what
   * would be lost; "Save anyway" saves as before. Autosave does not save until the person has answered.
   */
  function proGuard() {
    var ui = window.cfsSettingsUi || {};
    if (ui.isPro !== '0' || typeof window.cfsSaveForm !== 'function' || typeof window.cfsForm === 'undefined') {
      return;
    }
    var params = (window.cfsForm && window.cfsForm.params) || {},
      tpl = params.tpl || {},
      lost = [];
    function on(value) {
      return value !== undefined && value !== null && value !== '' && value !== '0' && value !== 0 && value !== false;
    }
    function hasRules(rules) {
      if (!rules || typeof rules !== 'object') {
        return false;
      }
      return Object.keys(rules).some(function (k) {
        var rule = rules[k] || {},
          conds = rule.cond || {},
          logic = rule.logic || {};
        return (
          Object.keys(conds).some(function (c) {
            var cond = conds[c] || {};
            return on(cond.field) || on(cond.url_eq_to) || (cond.user_country && [].concat(cond.user_country).some(on)) || (cond.user_lang && [].concat(cond.user_lang).some(on));
          }) ||
          Object.keys(logic).some(function (l) {
            var act = logic[l] || {};
            return (act.fields && [].concat(act.fields).some(on)) || on(act.redirect_url) || on(act.sendto);
          })
        );
      });
    }
    if (on(tpl.enb_subscribe)) lost.push(ui['Subscriptions to your mailing service']);
    if (on(tpl.enb_publish)) lost.push(ui['Posts published from the form']);
    if (on(tpl.enb_reg)) lost.push(ui['WordPress user registration']);
    if (hasRules(params.cl) || hasRules(params.cl2)) lost.push(ui['Conditional logic rules']);
    if (!lost.length) {
      return;
    }
    var save = window.cfsSaveForm,
      confirmed = false,
      waiting = false,
      declined = false,
      lastIntent = 0;
    // A save the person asked for: the Save button or Enter in a field (autosave clicks the button from a script)
    document.addEventListener('click', function (event) {
      if (event.isTrusted && event.target.closest && event.target.closest('.cfsFormSaveBtn')) {
        lastIntent = Date.now();
      }
    }, true);
    document.addEventListener('keydown', function (event) {
      if (event.isTrusted && event.key === 'Enter' && event.target.closest && event.target.closest('#cfsFormEditForm')) {
        lastIntent = Date.now();
      }
    }, true);
    var dialog = document.createElement('dialog');
    dialog.className = 'cfs-up cfs-guard';
    dialog.innerHTML =
      '<div class="cfs-guard-body"><span class="cfs-guard-icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>' +
      '<div><h2></h2><p class="cfs-guard-text"></p><ul></ul><p class="cfs-guard-note"></p></div></div>' +
      '<div class="cfs-guard-actions"><a class="cfs-guard-ces" href=""></a><span class="cfs-guard-space"></span>' +
      '<button type="button" class="button cfs-guard-keep"></button><button type="button" class="button cfs-guard-save"></button></div>';
    dialog.querySelector('h2').textContent = ui['Save without the PRO settings?'];
    dialog.querySelector('.cfs-guard-text').textContent = ui['This form has settings of PRO features. Contact Form PRO is not active now, so saving the form here clears them:'];
    dialog.querySelector('.cfs-guard-note').textContent = ui['Activate your Contact Form PRO license to keep them, or move the form to CES.'];
    var list = dialog.querySelector('ul');
    lost.forEach(function (text) {
      var li = document.createElement('li');
      li.textContent = text;
      list.appendChild(li);
    });
    var cesLink = dialog.querySelector('.cfs-guard-ces');
    if (ui.cesUrl) {
      cesLink.href = ui.cesUrl;
      cesLink.textContent = ui.cesLabel;
    } else {
      cesLink.remove();
    }
    dialog.querySelector('.cfs-guard-keep').textContent = ui['Keep editing'];
    dialog.querySelector('.cfs-guard-save').textContent = ui['Save anyway'];
    document.body.appendChild(dialog);
    dialog.querySelector('.cfs-guard-keep').addEventListener('click', function () {
      waiting = false;
      declined = true;
      dialog.close();
    });
    dialog.querySelector('.cfs-guard-save').addEventListener('click', function () {
      confirmed = true;
      waiting = false;
      dialog.close();
      save();
    });
    dialog.addEventListener('cancel', function () {
      waiting = false;
      declined = true;
    });
    window.cfsSaveForm = function () {
      if (confirmed) {
        return save.apply(this, arguments);
      }
      var asked = Date.now() - lastIntent < 1500;
      if (!asked && (waiting || declined)) {
        return; // autosave waits for the answer and does not save after "Keep editing"
      }
      if (typeof dialog.showModal === 'function' && !dialog.open) {
        waiting = true;
        dialog.showModal();
      }
    };
  }

  $(function () {
    // After the editor scripts have set up their tabs
    window.setTimeout(init, 0);
    window.setTimeout(proGuard, 0);
  });
})(jQuery);

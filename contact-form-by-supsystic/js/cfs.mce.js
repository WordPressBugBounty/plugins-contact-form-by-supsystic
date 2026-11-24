/**
 * Classic editor (TinyMCE) button "Insert a Contact Form (Classic)": a dialog with the list of forms, Insert puts
 * [supsystic-form id="…"] at the cursor. Data: editor setting "cfs_classic_form" (modules/forms/mod.php). Same look as
 * the other Supsystic buttons (plugins/.claude/fix/shared/classic-editor-buttons.md): a dashicon, Supsystic cyan on hover.
 */
(function () {
  'use strict';
  if (!window.tinymce || !window.tinymce.PluginManager) {
    return;
  }

  function addStyle() {
    if (document.getElementById('cfs-mce-style')) {
      return;
    }
    var css =
      '.mce-toolbar .mce-btn.mce-supsystic-btn:hover i.mce-ico,.mce-toolbar .mce-btn.mce-supsystic-btn:focus i.mce-ico{color:#4ae8ea}' +
      '.mce-toolbar .mce-btn.mce-supsystic-btn i.mce-ico{transition:color .2s ease}' +
      '.cfs-mce-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:160000}' +
      '.cfs-mce-modal{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:440px;max-width:calc(100vw - 32px);background:#fff;' +
      'box-shadow:0 5px 15px rgba(0,0,0,.3);z-index:160001;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;font-size:13px;color:#1d2327;border-radius:4px;overflow:hidden}' +
      '.cfs-mce-head{display:flex;align-items:center;justify-content:space-between;padding:0 0 0 16px;border-bottom:1px solid #dcdcde;height:50px}' +
      '.cfs-mce-head h2{margin:0;font-size:16px;font-weight:600;line-height:1.3;display:flex;align-items:center;gap:8px}' +
      '.cfs-mce-head h2 .dashicons{color:#4ae8ea}' +
      '.cfs-mce-x{width:50px;height:50px;border:0;background:none;cursor:pointer;color:#646970;font-size:20px;line-height:50px}' +
      '.cfs-mce-x:hover,.cfs-mce-x:focus{color:#135e96}' +
      '.cfs-mce-body{padding:16px}.cfs-mce-body label{display:block;font-weight:600;margin:0 0 6px}' +
      '.cfs-mce-body select,.cfs-mce-body input[type=search]{width:100%;max-width:none;box-sizing:border-box;min-height:32px}' +
      '.cfs-mce-body input[type=search]{margin:0 0 8px}.cfs-mce-row{margin:0 0 14px}.cfs-mce-empty{margin:0 0 10px;color:#646970}' +
      '.cfs-mce-foot{display:flex;justify-content:flex-end;gap:8px;padding:12px 16px;border-top:1px solid #dcdcde;background:#f6f7f7}' +
      // Own look for the fields and buttons: page builders (Elementor) restyle inputs and buttons in their editor
      '.cfs-mce-modal select,.cfs-mce-modal input[type=search]{background:#fff!important;color:#1d2327!important;border:1px solid #8c8f94!important;border-radius:4px!important;padding:0 8px!important;height:32px!important;font-size:13px!important;box-shadow:none!important}' +
      '.cfs-mce-modal select:focus,.cfs-mce-modal input[type=search]:focus{border-color:#2271b1!important;box-shadow:0 0 0 1px #2271b1!important;outline:0!important}' +
      '.cfs-mce-modal input[type=radio]{accent-color:#2271b1}.cfs-mce-modal a{color:#2271b1}' +
      '.cfs-mce-modal .button{display:inline-flex;align-items:center;min-height:32px;padding:0 12px!important;font-size:13px!important;line-height:2.3!important;border-radius:3px!important;cursor:pointer;background:#f6f7f7!important;color:#2271b1!important;border:1px solid #2271b1!important;box-shadow:none!important;text-transform:none!important}' +
      '.cfs-mce-modal .button-primary{background:#2271b1!important;color:#fff!important}.cfs-mce-modal .button:hover{background:#f0f0f1!important;color:#135e96!important}.cfs-mce-modal .button-primary:hover{background:#135e96!important;color:#fff!important}';
    var s = document.createElement('style');
    s.id = 'cfs-mce-style';
    s.appendChild(document.createTextNode(css));
    document.head.appendChild(s);
  }

  function esc(v) {
    return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function openDialog(editor, d) {
    var items = d.items || [];
    var bookmark = editor.selection ? editor.selection.getBookmark(2, true) : null;
    var body = items.length
      ? (items.length > 15 ? '<input type="search" class="cfs-mce-search" placeholder="' + esc(d.search) + '" aria-label="' + esc(d.search) + '">' : '') +
        '<div class="cfs-mce-row"><label for="cfs-mce-select">' + esc(d.label) + '</label><select id="cfs-mce-select">' +
        items.map(function (o) {
          return '<option value="' + o.id + '">' + esc(o.name + ' (#' + o.id + ')') + '</option>';
        }).join('') +
        '</select></div>'
      : '<p class="cfs-mce-empty">' + esc(d.empty) + '</p>';
    if (d.create) {
      body += '<a href="' + esc(d.createUrl) + '" target="_blank" rel="noopener">' + esc(d.create) + '</a>';
    }
    var wrap = document.createElement('div');
    wrap.innerHTML =
      '<div class="cfs-mce-backdrop"></div><div class="cfs-mce-modal" role="dialog" aria-modal="true" aria-labelledby="cfs-mce-title">' +
      '<div class="cfs-mce-head"><h2 id="cfs-mce-title"><span class="dashicons dashicons-feedback" aria-hidden="true"></span>' + esc(d.title) + '</h2>' +
      '<button type="button" class="cfs-mce-x" aria-label="' + esc(d.close) + '"><span class="dashicons dashicons-no-alt" aria-hidden="true"></span></button></div>' +
      '<div class="cfs-mce-body">' + body + '</div><div class="cfs-mce-foot"><button type="button" class="button cfs-mce-cancel">' + esc(d.cancel) + '</button>' +
      (items.length ? '<button type="button" class="button button-primary cfs-mce-insert">' + esc(d.insert) + '</button>' : '') + '</div></div>';
    document.body.appendChild(wrap);
    var select = wrap.querySelector('#cfs-mce-select');
    var search = wrap.querySelector('.cfs-mce-search');
    function close() {
      document.removeEventListener('keydown', onKey, true);
      if (wrap.parentNode) {
        wrap.parentNode.removeChild(wrap);
      }
      editor.focus();
    }
    function insert() {
      if (!select || !select.value) {
        return;
      }
      close();
      if (bookmark) {
        editor.selection.moveToBookmark(bookmark);
      }
      editor.insertContent('[supsystic-form id="' + select.value + '"]');
    }
    function onKey(e) {
      if (e.key === 'Escape') {
        e.preventDefault();
        e.stopPropagation();
        close();
      } else if (e.key === 'Enter' && e.target && e.target.tagName !== 'A' && !/button/i.test(e.target.tagName)) {
        e.preventDefault();
        insert();
      }
    }
    document.addEventListener('keydown', onKey, true);
    wrap.querySelector('.cfs-mce-backdrop').addEventListener('click', close);
    wrap.querySelector('.cfs-mce-x').addEventListener('click', close);
    wrap.querySelector('.cfs-mce-cancel').addEventListener('click', close);
    var ins = wrap.querySelector('.cfs-mce-insert');
    if (ins) {
      ins.addEventListener('click', insert);
    }
    if (search) {
      search.addEventListener('input', function () {
        var q = search.value.toLowerCase();
        var first = null;
        Array.prototype.forEach.call(select.options, function (o) {
          var show = o.text.toLowerCase().indexOf(q) !== -1;
          o.hidden = !show;
          if (show && !first) {
            first = o;
          }
        });
        if (first && select.selectedOptions[0] && select.selectedOptions[0].hidden) {
          select.value = first.value;
        }
      });
    }
    (search || select || wrap.querySelector('.cfs-mce-cancel')).focus();
  }

  window.tinymce.PluginManager.add('supsysticClassicForm', function (editor) {
    var raw = editor.getParam('cfs_classic_form');
    var d = raw;
    if (typeof raw === 'string') {
      try {
        d = JSON.parse(raw);
      } catch (e) {
        d = null;
      }
    }
    if (!d) {
      return;
    }
    addStyle();
    editor.addCommand('supsysticClassicForm', function () {
      openDialog(editor, d);
    });
    editor.addButton('supsysticClassicForm', { tooltip: d.title, icon: 'dashicon dashicons-feedback', classes: 'supsystic-btn', cmd: 'supsysticClassicForm' });
  });
})();

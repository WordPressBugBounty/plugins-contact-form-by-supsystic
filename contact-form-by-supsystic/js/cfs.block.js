/**
 * Gutenberg block "Contact Form (Classic)" (modules/forms/builder.php): pick a form, see it live in a small iframe
 * (only the form's files, nothing is sent), Edit / Create links in the sidebar. On the site the block prints the form
 * shortcode. Offered unless CES runs in its CES mode; placed blocks keep rendering and stay editable.
 * Data: cfsBuilderBlock.
 */
(function (wp) {
  'use strict';
  var data = window.cfsBuilderBlock;
  if (!wp || !wp.blocks || !wp.element || !data) {
    return;
  }
  var el = wp.element.createElement;
  var useRef = wp.element.useRef;
  var useEffect = wp.element.useEffect;
  var be = wp.blockEditor;
  var C = wp.components;
  var t = data.i18n || {};
  var icon = data.icon
    ? el('img', { src: data.icon, alt: '', width: 24, height: 24, draggable: false, style: { width: 24, height: 24, pointerEvents: 'none' } })
    : 'feedback';

  function find(id) {
    for (var i = 0; i < data.forms.length; i++) {
      if (String(data.forms[i].id) === String(id)) {
        return data.forms[i];
      }
    }
    return null;
  }

  function options() {
    return [{ value: '', label: t.select }].concat(
      data.forms.map(function (f) {
        return { value: String(f.id), label: f.name + ' (#' + f.id + ')' };
      })
    );
  }

  function previewSrc(id) {
    var url = data.previewUrl;
    // previewUrl carries cfs_builder_preview=0: put the form id in
    return url.replace(/([?&]cfs_builder_preview=)\d+/, '$1' + encodeURIComponent(id));
  }

  // The preview page sizes its iframe itself when it can reach it; in a cross-origin isolated editor it posts its height
  function Preview(props) {
    var ref = useRef(null);
    useEffect(
      function () {
        var frame = ref.current;
        if (!frame) {
          return undefined;
        }
        var win = frame.ownerDocument && frame.ownerDocument.defaultView;
        if (!win) {
          return undefined;
        }
        var onMessage = function (e) {
          var d = e.data;
          if (!d || d.cfsPreview !== 'height' || e.source !== frame.contentWindow) {
            return;
          }
          frame.style.height = Math.max(40, Math.min(4000, parseInt(d.height, 10) || 0)) + 'px';
        };
        win.addEventListener('message', onMessage);
        return function () {
          win.removeEventListener('message', onMessage);
        };
      },
      [props.id]
    );
    return el('div', { style: { position: 'relative' } }, [
      el('iframe', {
        key: 'f' + props.id,
        ref: ref,
        className: 'cfs-builder-preview-frame',
        src: previewSrc(props.id),
        title: t.previewTitle,
        style: { display: 'block', width: '100%', height: '120px', border: 0, background: 'transparent' },
      }),
      // While the block is not selected, a click selects it instead of landing inside the form
      props.selected ? null : el('div', { key: 'cover', style: { position: 'absolute', inset: 0, cursor: 'pointer' } }),
    ]);
  }

  wp.blocks.registerBlockType('supsystic/contact-form-classic', {
    apiVersion: 3,
    title: data.title,
    description: data.description,
    icon: icon,
    category: 'supsystic',
    keywords: ['contact', 'form', 'classic', 'supsystic'],
    attributes: { formId: { type: 'string', default: '' } },
    supports: { html: false, inserter: !!data.offered },
    edit: function (props) {
      var a = props.attributes;
      var item = a.formId ? find(a.formId) : null;
      var blockProps = be.useBlockProps({ className: 'cfs-block cfs-block-contact-form-classic' });
      var pick = function (v) {
        props.setAttributes({ formId: v || '' });
      };
      var panel = [el(C.SelectControl, { key: 'pick', label: t.form, value: item ? String(item.id) : '', options: options(), onChange: pick, __nextHasNoMarginBottom: true })];
      if (data.canManage) {
        if (item) {
          panel.push(el('p', { key: 'edit' }, el(C.ExternalLink, { href: data.editUrl + item.id }, t.edit)));
        }
        panel.push(el('p', { key: 'new' }, el(C.ExternalLink, { href: data.createUrl }, t.create)));
      }
      var inspector = el(be.InspectorControls, { key: 'inspector' }, el(C.PanelBody, { title: data.title, initialOpen: true }, panel));
      if (!item) {
        return el('div', blockProps, [
          inspector,
          el(
            C.Placeholder,
            { key: 'ph', icon: icon, label: data.title, instructions: a.formId ? t.missing : data.forms.length ? t.choose : t.none },
            data.forms.length
              ? el(C.SelectControl, { label: t.form, hideLabelFromVision: true, value: '', options: options(), onChange: pick, __nextHasNoMarginBottom: true })
              : data.canManage
                ? el(C.ExternalLink, { href: data.createUrl }, t.create)
                : null
          ),
        ]);
      }
      return el('div', blockProps, [inspector, el(Preview, { key: 'preview', id: item.id, selected: !!props.isSelected })]);
    },
    save: function () {
      return null;
    },
  });

  // The "Supsystic" category shows the Supsystic logo (several Supsystic plugins doing it is harmless)
  if (wp.blocks.updateCategory && data.icon) {
    wp.blocks.updateCategory('supsystic', { icon: icon });
  }
})(window.wp);

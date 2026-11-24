<?php
/**
 * "Contact Form (Classic)" in the page builders, the same way as CES and the other Supsystic plugins
 * (plugins/.claude/fix/shared/gutenberg-supsystic-category.md, builder-blocks-branding.md):
 * - Gutenberg block "supsystic/contact-form-classic" in the shared "Supsystic" category: a live preview of the form in a
 *   small iframe (only this form's files, nothing is sent, no statistics), the form and the Edit / Create links in the
 *   sidebar; on the site it prints the form shortcode [supsystic-form id=N];
 * - Elementor widget "Contact Form (Classic)" in the "Supsystic" category, with the same live preview in the editor.
 * They are offered unless CES runs in its CES mode (new forms are CES forms there: "CES Form"); blocks and widgets
 * placed before keep rendering and stay editable in every mode.
 */
class cfsBuilderBlocks
{
  const BLOCK = 'supsystic/contact-form-classic';
  const PREVIEW = 'cfs_builder_preview';
  const SCRIPT = 'cfs-builder-block';
  private static $elementorRegistered = false;

  public static function register()
  {
    add_action('init', [__CLASS__, 'registerBlock'], 20);
    if (function_exists('get_default_block_categories')) {
      add_filter('block_categories_all', [__CLASS__, 'supsysticBlockCategory'], 5);
    } else {
      add_filter('block_categories', [__CLASS__, 'supsysticBlockCategory'], 5);
    }
    add_action('enqueue_block_editor_assets', [__CLASS__, 'blockEditorData']);
    // The preview page is a front-end request (the form loads its front-end files as on the site)
    add_action('template_redirect', [__CLASS__, 'maybePreview'], 0);
    if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.5.0', '<')) {
      add_action('elementor/widgets/widgets_registered', [__CLASS__, 'registerElementorWidget']);
    } else {
      add_action('elementor/widgets/register', [__CLASS__, 'registerElementorWidget']);
    }
    add_action('elementor/elements/categories_registered', [__CLASS__, 'elementorCategory'], 5);
    add_action('elementor/editor/after_enqueue_styles', [__CLASS__, 'elementorIconStyle']);
  }

  /** New blocks and widgets are offered unless CES runs in its CES mode */
  public static function offered()
  {
    return !(function_exists('ppsgen_is_ces_mode') && ppsgen_is_ces_mode());
  }

  /** The site's forms (not the templates, not A/B variants), newest first: id and name */
  public static function forms()
  {
    global $wpdb;
    $out = [];
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name from the prefix
    foreach ((array) $wpdb->get_results("SELECT id, label FROM {$wpdb->prefix}cfs_forms WHERE original_id != 0 AND ab_id = 0 ORDER BY id DESC", ARRAY_A) as $f) {
      $name = trim(wp_strip_all_tags((string) $f['label']));
      $out[] = ['id' => (int) $f['id'], 'name' => $name !== '' ? $name : '#' . (int) $f['id']];
    }
    return $out;
  }

  public static function formExists($id)
  {
    foreach (self::forms() as $f) {
      if ($f['id'] === (int) $id) {
        return true;
      }
    }
    return false;
  }

  public static function canManage()
  {
    $menu = class_exists('frameCfs') ? frameCfs::_()->getModule('adminmenu') : null;
    return current_user_can($menu ? $menu->getMainCap() : 'manage_options');
  }

  public static function editUrl()
  {
    return frameCfs::_()->getModule('options')->getTabUrl('forms_edit') . '&id=';
  }

  public static function createUrl()
  {
    return frameCfs::_()->getModule('options')->getTabUrl('forms_add_new');
  }

  public static function render($id)
  {
    $id = (int) $id;
    return $id > 0 && shortcode_exists(CFS_SHORTCODE) ? (string) do_shortcode('[' . CFS_SHORTCODE . ' id=' . $id . ']') : '';
  }

  /** Adds the "Supsystic" category first, unless another Supsystic plugin added it already */
  public static function supsysticBlockCategory($categories)
  {
    foreach ((array) $categories as $c) {
      if (isset($c['slug']) && $c['slug'] === 'supsystic') {
        return $categories;
      }
    }
    $categories = is_array($categories) ? $categories : [];
    array_unshift($categories, ['slug' => 'supsystic', 'title' => 'Supsystic', 'icon' => null]);
    return $categories;
  }

  /* ------------------------------------------------------------------ Gutenberg block */

  public static function registerBlock()
  {
    if (!function_exists('register_block_type') || \WP_Block_Type_Registry::get_instance()->is_registered(self::BLOCK)) {
      return;
    }
    $file = CFS_JS_DIR . 'cfs.block.js';
    wp_register_script(self::SCRIPT, CFS_JS_PATH . 'cfs.block.js', ['wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor'], is_file($file) ? (string) filemtime($file) : CFS_VERSION, true);
    register_block_type(self::BLOCK, [
      'api_version' => 3,
      'title' => __('Contact Form (Classic)', CFS_LANG_CODE),
      'category' => 'supsystic',
      'attributes' => ['formId' => ['type' => 'string', 'default' => '']],
      // Placed blocks keep rendering in every mode; new ones are offered unless CES runs in its CES mode
      'supports' => ['html' => false, 'inserter' => self::offered()],
      'render_callback' => function ($attributes) {
        return self::render((int) (isset($attributes['formId']) ? $attributes['formId'] : 0));
      },
    ]);
  }

  /** The block script loads where it is offered, or for a post that already has the block (it stays editable) */
  public static function blockEditorData()
  {
    if (!wp_script_is(self::SCRIPT, 'registered')) {
      return;
    }
    $post = get_post();
    if (!self::offered() && !($post && has_block(self::BLOCK, $post))) {
      return;
    }
    wp_enqueue_script(self::SCRIPT);
    wp_localize_script(self::SCRIPT, 'cfsBuilderBlock', [
      'offered' => self::offered(),
      'forms' => self::forms(),
      'title' => __('Contact Form (Classic)', CFS_LANG_CODE),
      'description' => __('Shows a form made in Contact Form (Classic), with a live preview.', CFS_LANG_CODE),
      'icon' => CFS_IMG_PATH . 'supsystic-adminbar.png',
      'previewUrl' => self::previewUrl(0),
      'canManage' => self::canManage(),
      'editUrl' => self::editUrl(),
      'createUrl' => self::createUrl(),
      'i18n' => [
        'form' => __('Form', CFS_LANG_CODE),
        'select' => __('— Select a form —', CFS_LANG_CODE),
        'choose' => __('Choose the form to show.', CFS_LANG_CODE),
        'none' => __('There are no forms yet.', CFS_LANG_CODE),
        'missing' => __('The selected form no longer exists. Choose another one.', CFS_LANG_CODE),
        'edit' => __('Edit this form in Contact Form', CFS_LANG_CODE),
        'create' => __('Create a form in Contact Form', CFS_LANG_CODE),
        'previewTitle' => __('Contact Form (Classic) preview', CFS_LANG_CODE),
      ],
    ]);
  }

  /* ------------------------------------------------------------------ preview page */

  public static function previewUrl($id)
  {
    return add_query_arg([self::PREVIEW => (int) $id, '_cfsnonce' => wp_create_nonce(self::PREVIEW)], home_url('/'));
  }

  /** The preview iframe with its size listener, for the Elementor editor (the block builds the same in JS) */
  public static function previewIframe($id)
  {
    $listener = '<script>(function () { if (window.cfsPreviewSizer) { return; } window.cfsPreviewSizer = 1;'
      . 'window.addEventListener("message", function (e) { var d = e.data; if (!d || d.cfsPreview !== "height") { return; }'
      . 'var fs = document.querySelectorAll("iframe.cfs-builder-preview-frame"); for (var i = 0; i < fs.length; i++) { if (fs[i].contentWindow === e.source) { fs[i].style.height = Math.max(40, Math.min(4000, parseInt(d.height, 10) || 0)) + "px"; } } });'
      . '})();</script>';
    return $listener . '<iframe class="cfs-builder-preview-frame" src="' . esc_url(self::previewUrl($id)) . '" title="' . esc_attr__('Contact Form (Classic) preview', CFS_LANG_CODE) . '" style="display:block;width:100%;height:120px;border:0;background:transparent;" loading="eager"></iframe>';
  }

  /**
   * /?cfs_builder_preview=N&_cfsnonce=…: a whole HTML page with the form as on the site, for the editors. Only the
   * form's own files (Contact Form and its PRO add-on, with their WordPress dependencies) are printed, nothing of the
   * theme or of other plugins; a submit is not sent and nothing goes into the statistics.
   */
  public static function maybePreview()
  {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- checked right below
    if (!isset($_GET[self::PREVIEW])) {
      return;
    }
    nocache_headers();
    header('Content-Type: text/html; charset=utf-8');
    header('X-Robots-Tag: noindex');
    // The block editor can be cross-origin isolated: an iframe in it needs a COEP of its own
    header('Cross-Origin-Embedder-Policy: credentialless');
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- checked here
    $nonce = isset($_GET['_cfsnonce']) ? sanitize_text_field(wp_unslash($_GET['_cfsnonce'])) : '';
    if (!current_user_can('edit_posts') || !wp_verify_nonce($nonce, self::PREVIEW)) {
      status_header(403);
      echo self::previewMessage(__('The preview needs you to be signed in. Reload the editor.', CFS_LANG_CODE)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      exit();
    }
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $id = absint($_GET[self::PREVIEW]);
    if (!$id || !self::formExists($id)) {
      echo self::previewMessage($id ? __('This form was not found.', CFS_LANG_CODE) : __('Choose the form to show.', CFS_LANG_CODE)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      exit();
    }
    show_admin_bar(false);
    $scripts = wp_scripts();
    $styles = wp_styles();
    $scriptsBefore = (array) $scripts->queue;
    $stylesBefore = (array) $styles->queue;
    $html = self::render($id);
    // With the "load assets in the footer" option the plugin queues its files at wp_footer: queue them now
    $frame = frameCfs::_();
    if (method_exists($frame, 'addScripts')) {
      $frame->addScripts();
      $frame->addStyles();
    }
    $own = function ($src) {
      $src = (string) $src;
      return strpos($src, '/contact-form-by-supsystic/') !== false || strpos($src, '/contact-form-by-supsystic-pro/') !== false;
    };
    $scriptHandles = [];
    foreach ((array) $scripts->queue as $handle) {
      $src = isset($scripts->registered[$handle]) ? $scripts->registered[$handle]->src : '';
      if (!in_array($handle, $scriptsBefore, true) || $own($src)) {
        $scriptHandles[] = $handle;
      }
    }
    $styleHandles = [];
    foreach ((array) $styles->queue as $handle) {
      $src = isset($styles->registered[$handle]) ? $styles->registered[$handle]->src : '';
      if (!in_array($handle, $stylesBefore, true) || $own($src)) {
        $styleHandles[] = $handle;
      }
    }
    ob_start();
    wp_print_styles($styleHandles);
    $head = (string) ob_get_clean();
    ob_start();
    wp_print_scripts($scriptHandles);
    $foot = (string) ob_get_clean();
    $note = esc_js(__('Preview — nothing was sent.', CFS_LANG_CODE));
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- the form's own shortcode output and WordPress-printed tags
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">';
    // Links open in a new tab instead of inside the editor
    echo '<base target="_blank">';
    echo $head;
    echo '<style>html,body{margin:0;padding:0;background:transparent;}body{padding:2px 0;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;}'
      . '.cfs-builder-preview-note{margin:8px 0 0;padding:6px 10px;font-size:12px;color:#1d2327;background:#e6fbfb;border:1px solid #4ae8ea;border-radius:4px;}</style>';
    echo '</head><body class="cfs-builder-preview">';
    echo $html;
    echo $foot;
    // No statistics from a preview, and a submit is shown as a note instead of being sent
    echo '<script>(function () {'
      . 'if (window.cfsForm && window.cfsForm.prototype) { window.cfsForm.prototype._addStat = function () {}; }'
      . 'document.addEventListener("submit", function (e) { e.preventDefault(); e.stopImmediatePropagation(); var f = e.target; if (f && !f.querySelector(".cfs-builder-preview-note")) { var n = document.createElement("div"); n.className = "cfs-builder-preview-note"; n.textContent = "' . $note . '"; f.appendChild(n); } }, true);'
      . '})();</script>';
    // Size the iframe to the content: directly (window.frameElement), or by a message for a cross-origin isolated editor
    echo '<script>(function () {'
      . 'var f = null; try { f = window.frameElement; } catch (e) {}'
      . 'try { var cs = f && window.getComputedStyle(f); if (cs && cs.fontFamily) { document.body.style.fontFamily = cs.fontFamily; } if (cs && cs.color) { document.body.style.color = cs.color; } } catch (e) {}'
      . 'var last = 0;'
      . 'function fit() { var b = document.body; if (!b) { return; } var h = Math.ceil(Math.max(b.scrollHeight, b.offsetHeight)); h = Math.min(Math.max(h, 40), 4000); if (Math.abs(h - last) > 1) { last = h; if (f) { f.style.height = h + "px"; } try { window.parent.postMessage({ cfsPreview: "height", height: h }, "*"); } catch (e) {} } }'
      . 'window.addEventListener("load", fit); document.addEventListener("DOMContentLoaded", fit);'
      . 'if (window.ResizeObserver) { new ResizeObserver(fit).observe(document.body); }'
      . 'setTimeout(fit, 150); setTimeout(fit, 800); fit();'
      . '})();</script>';
    echo '</body></html>';
    // phpcs:enable
    exit();
  }

  private static function previewMessage($text)
  {
    return '<!doctype html><html><head><meta charset="utf-8"></head><body style="margin:0;padding:12px;font:13px/1.5 -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;color:#50575e;">'
      . esc_html($text)
      . '<script>(function () { var h = document.body.scrollHeight + 4; try { if (window.frameElement) { window.frameElement.style.height = h + "px"; } } catch (e) {} try { window.parent.postMessage({ cfsPreview: "height", height: h }, "*"); } catch (e) {} })();</script></body></html>';
  }

  /* ------------------------------------------------------------------ Elementor */

  public static function isElementorEditor()
  {
    if (!class_exists('\Elementor\Plugin') || !isset(\Elementor\Plugin::$instance) || !is_object(\Elementor\Plugin::$instance)) {
      return false;
    }
    $el = \Elementor\Plugin::$instance;
    if (isset($el->editor) && is_object($el->editor) && method_exists($el->editor, 'is_edit_mode') && $el->editor->is_edit_mode()) {
      return true;
    }
    return isset($el->preview) && is_object($el->preview) && method_exists($el->preview, 'is_preview_mode') && $el->preview->is_preview_mode();
  }

  public static function registerElementorWidget($manager = null)
  {
    if (self::$elementorRegistered || !class_exists('\Elementor\Widget_Base')) {
      return;
    }
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'elementor-widget.php';
    if (!class_exists('cfsElementorFormWidget')) {
      return;
    }
    $widget = new cfsElementorFormWidget();
    self::$elementorRegistered = true;
    if ($manager && method_exists($manager, 'register')) {
      $manager->register($widget);
    } elseif ($manager && method_exists($manager, 'register_widget_type')) {
      $manager->register_widget_type($widget);
    }
  }

  /** The shared "Supsystic" category at the top of the Elementor panel, added once whichever Supsystic plugins are active */
  public static function elementorCategory($elementsManager)
  {
    if (!is_object($elementsManager) || !method_exists($elementsManager, 'add_category')) {
      return;
    }
    $cats = method_exists($elementsManager, 'get_categories') ? $elementsManager->get_categories() : [];
    if (isset($cats['supsystic'])) {
      return;
    }
    $elementsManager->add_category('supsystic', ['title' => 'Supsystic', 'icon' => 'supsystic-el-icon']);
    try {
      $prop = new \ReflectionProperty($elementsManager, 'categories');
      if (PHP_VERSION_ID < 80100) {
        $prop->setAccessible(true);
      }
      $all = $prop->getValue($elementsManager);
      if (is_array($all) && isset($all['supsystic'])) {
        $prop->setValue($elementsManager, ['supsystic' => $all['supsystic']] + $all);
      }
    } catch (\Throwable $e) {
      // The category stays where Elementor put it
    }
  }

  /** The Supsystic logo as the panel icon, painted with the panel's own colour like the other widget icons */
  public static function elementorIconStyle()
  {
    $icon = esc_url(CFS_IMG_PATH . 'supsystic-adminbar.png');
    $css = '.elementor-element .icon .supsystic-el-icon::before, .supsystic-el-icon::before {'
      . ' content: ""; display: inline-block; width: 1em; height: 1em; vertical-align: middle; background-color: currentColor;'
      . ' -webkit-mask: url("' . $icon . '") center / contain no-repeat; mask: url("' . $icon . '") center / contain no-repeat; }';
    wp_register_style('cfs-elementor-icon', false, [], CFS_VERSION);
    wp_enqueue_style('cfs-elementor-icon');
    wp_add_inline_style('cfs-elementor-icon', $css);
  }
}

<?php
/**
 * Elementor widget "Contact Form (Classic)" (cfsBuilderBlocks): pick a form; the editor shows its live preview, the
 * site prints the form shortcode. Offered unless CES runs in its CES mode; placed widgets keep working in every mode.
 */
if (!defined('ABSPATH')) {
  exit();
}

class cfsElementorFormWidget extends \Elementor\Widget_Base
{
  public function get_name()
  {
    return 'supsystic-contact-form-classic';
  }

  public function get_title()
  {
    return __('Contact Form (Classic)', CFS_LANG_CODE);
  }

  public function get_icon()
  {
    return 'supsystic-el-icon';
  }

  public function get_categories()
  {
    return ['supsystic'];
  }

  public function get_keywords()
  {
    return ['contact', 'form', 'classic', 'supsystic'];
  }

  /** Hidden from the panel in the CES mode; a widget placed before still renders and stays editable */
  public function show_in_panel()
  {
    return cfsBuilderBlocks::offered();
  }

  protected function register_controls()
  {
    $options = ['' => __('— Select a form —', CFS_LANG_CODE)];
    foreach (cfsBuilderBlocks::forms() as $f) {
      $options[(string) $f['id']] = $f['name'] . ' (#' . $f['id'] . ')';
    }
    $this->start_controls_section('content', ['label' => __('Contact Form (Classic)', CFS_LANG_CODE)]);
    $this->add_control('form_id', [
      'label' => __('Form', CFS_LANG_CODE),
      'type' => \Elementor\Controls_Manager::SELECT,
      'options' => $options,
      'default' => '',
    ]);
    if (cfsBuilderBlocks::canManage()) {
      $this->add_control('links', [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => '<a href="' . esc_url(cfsBuilderBlocks::createUrl()) . '" target="_blank" rel="noopener">' . esc_html__('Create a form in Contact Form', CFS_LANG_CODE) . '</a>',
        'content_classes' => 'elementor-descriptor',
      ]);
    }
    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $id = absint(isset($settings['form_id']) ? $settings['form_id'] : 0);
    if (!cfsBuilderBlocks::isElementorEditor()) {
      if ($id) {
        echo cfsBuilderBlocks::render($id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the form shortcode output
      }
      return;
    }
    $box = 'style="font:13px/1.5 -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;"';
    if (!$id || !cfsBuilderBlocks::formExists($id)) {
      $text = $id ? __('The selected form no longer exists. Choose another one in the panel.', CFS_LANG_CODE) : __('Contact Form (Classic): choose a form in the panel on the left.', CFS_LANG_CODE);
      echo '<div class="cfs-builder-placeholder" ' . $box . '><div style="padding:24px;border:1px dashed #c3c4c7;border-radius:4px;text-align:center;color:#50575e;background:#fff;">' . esc_html($text) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      return;
    }
    echo '<div class="cfs-builder-preview" ' . $box . '>' . cfsBuilderBlocks::previewIframe($id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with esc_url / esc_attr
    if (cfsBuilderBlocks::canManage()) {
      echo '<p style="margin:6px 0 0;font-size:12px;"><a href="' . esc_url(cfsBuilderBlocks::editUrl() . $id) . '" target="_blank" rel="noopener">' . esc_html__('Edit this form in Contact Form', CFS_LANG_CODE) . '</a></p>';
    }
    echo '</div>';
  }
}

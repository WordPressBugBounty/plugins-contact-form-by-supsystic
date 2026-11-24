<?php
class adminmenuCfs extends moduleCfs
{
  protected $_mainSlug = 'contact-form-supsystic';
  private $_mainCap = 'manage_options';
  /** The registered top-level menu slug, for the menu icon style */
  private $_menuSlug = '';
  public function init()
  {
    parent::init();
    add_action('admin_menu', [$this, 'initMenu'], 9);
    add_action('admin_menu', [$this, 'markMenuGroup'], 999);
    add_action('admin_head', [$this, 'printMenuIconStyle']);
    $plugName = plugin_basename(CFS_DIR . CFS_MAIN_FILE);
    // add_filter('plugin_action_links_'. $plugName, array($this, 'addSettingsLinkForPlug') );
  }
  // public function addSettingsLinkForPlug($links) {
  // 	$mainLink = 'http://supsystic.com/';
  // 	$twitterStatus = sprintf(__('Cool WordPress plugins from supsystic.com developers. I tried %s - and this was what I need! #supsystic.com', CFS_LANG_CODE), CFS_WP_PLUGIN_NAME);
  // 	array_unshift($links, '<a href="'. $this->getMainLink(). '">'. __('Settings', CFS_LANG_CODE). '</a>');
  // 	array_push($links, '<a title="'. __('More plugins for your WordPress site here!', CFS_LANG_CODE). '" href="'. $mainLink. '" target="_blank">supsystic.com</a>');
  // 	array_push($links, '<a title="'. __('Spread the word!', CFS_LANG_CODE). '" href="https://www.facebook.com/sharer/sharer.php?u='. urlencode($mainLink). '" target="_blank" class="dashicons-before dashicons-facebook-alt"></a>');
  // 	array_push($links, '<a title="'. __('Spread the word!', CFS_LANG_CODE). '" href="https://twitter.com/home?status='. urlencode($twitterStatus). '" target="_blank" class="dashicons-before dashicons-twitter"></a>');
  // 	array_push($links, '<a title="'. __('Spread the word!', CFS_LANG_CODE). '" href="https://plus.google.com/share?url='. urlencode($mainLink). '" target="_blank" class="dashicons-before dashicons-googleplus"></a>');
  // 	return $links;
  // }
  public function initMenu()
  {
    $mainCap = $this->getMainCap();
    $mainSlug = dispatcherCfs::applyFilters('adminMenuMainSlug', $this->_mainSlug);
    $mainMenuPageOptions = [
      'page_title' => CFS_WP_PLUGIN_NAME,
      // Sidebar label without "by Supsystic" (the page title keeps the full name): "Contact Form" with a small "Classic"
      // under it (printMenuIconStyle, like the CES item). Screen readers read "Contact Form Classic"; where WordPress
      // hides menu names or the caption does not fit, the item is just "Contact Form"
      'menu_title' => '<span class="cfsMenuTitle"><span class="cfsMenuMain">' . esc_html__('Contact Form', CFS_LANG_CODE) . '</span> <span class="cfsMenuSub">' . esc_html__('Classic', CFS_LANG_CODE) . '</span></span>',
      'capability' => $mainCap,
      'menu_slug' => $mainSlug,
      'function' => [frameCfs::_()->getModule('options'), 'getAdminPage'],
    ];
    $mainMenuPageOptions = dispatcherCfs::applyFilters('adminMenuMainOption', $mainMenuPageOptions);
    // The Supsystic logo, as in the other Supsystic plugins (sized by printMenuIconStyle); at the top with the other
    // Supsystic items, right under Dashboard (CES is '2.1'; a string, so it never collides with core positions)
    add_menu_page($mainMenuPageOptions['page_title'], $mainMenuPageOptions['menu_title'], $mainMenuPageOptions['capability'], $mainMenuPageOptions['menu_slug'], $mainMenuPageOptions['function'], CFS_IMG_PATH . 'supsystic-adminbar.png', '2.6');
    $this->_menuSlug = (string) $mainMenuPageOptions['menu_slug'];
    //remove duplicated WP menu item
    //add_submenu_page($mainMenuPageOptions['menu_slug'], '', '', $mainMenuPageOptions['capability'], $mainMenuPageOptions['menu_slug'], $mainMenuPageOptions['function']);
    $tabs = frameCfs::_()->getModule('options')->getTabs();
    $subMenus = [];
    foreach ($tabs as $tKey => $tab) {
      if ($tKey == 'main_page') {
        continue;
      } // Top level menu item - is main page, avoid place it 2 times
      if (
        (isset($tab['hidden']) && $tab['hidden']) ||
        (isset($tab['hidden_for_main']) && $tab['hidden_for_main']) || // Hidden for WP main
        (isset($tab['is_main']) && $tab['is_main'])
      ) {
        continue;
      }
      $subMenus[] = [
        'title' => $tab['label'],
        'capability' => $mainCap,
        'menu_slug' => 'admin.php?page=' . $mainSlug . '&tab=' . $tKey,
        'function' => '',
      ];
    }
    $subMenus = dispatcherCfs::applyFilters('adminMenuOptions', $subMenus);
    // Only the main item in the WP sidebar, like the other Supsystic plugins (user, 2026-11-20). The sub items are links
    // to tabs of the same page (admin.php?page=<main>&tab=…): every such URL still opens and keeps "Contact Form"
    // highlighted, and the plugin's own tab bar leads to each of them. An item a filter adds with its own page callback
    // is a real page: it stays registered, so its URL keeps working
    foreach ($subMenus as $opt) {
      if (!empty($opt['function'])) {
        add_submenu_page($mainSlug, $opt['title'], $opt['title'], $opt['capability'], $opt['menu_slug'], $opt['function']);
      }
    }
    if ($subMenus) {
      add_action('admin_head', [$this, 'hideSubmenu']);
    }
  }
  /** A page a filter registered with its own callback stays reachable by URL but is not listed under the main item */
  public function hideSubmenu()
  {
    global $submenu;
    $mainSlug = dispatcherCfs::applyFilters('adminMenuMainSlug', $this->_mainSlug);
    if (!empty($submenu[$mainSlug])) {
      unset($submenu[$mainSlug]);
    }
  }
  /** Marks this plugin's sidebar item as part of the Supsystic group (spacing around the group: printMenuIconStyle) */
  public function markMenuGroup()
  {
    global $menu;
    if ($this->_menuSlug === '') {
      return;
    }
    foreach ((array) $menu as $k => $item) {
      if (isset($item[2]) && $item[2] === $this->_menuSlug && strpos((string) (isset($item[4]) ? $item[4] : ''), 'supsystic-menu-group') === false) {
        $menu[$k][4] = trim((isset($item[4]) ? $item[4] : '') . ' supsystic-menu-group');
      }
    }
  }
  /** Sizes the Supsystic logo in the WP sidebar menu like the other Supsystic plugins (CES printMenuIconStyle) */
  public function printMenuIconStyle()
  {
    if ($this->_menuSlug === '') {
      return;
    }
    // The menu item id as WordPress builds it: toplevel_page_<slug>, characters outside [a-zA-Z0-9_:.] become "-"
    $id = 'toplevel_page_' . preg_replace('|[^a-zA-Z0-9_:.]|', '-', $this->_menuSlug);
    // Monochrome like the WordPress menu icons: the logo is a mask painted with currentColor, so the admin colour scheme
    // colours it (grey, hover, current). The <img> stays as the fallback for browsers without mask support.
    $sel = '#adminmenu #' . esc_attr($id);
    // content-box: the mask fills the 20px box under the padding, like a dashicon glyph (not centred in padding + box)
    $hov = '#adminmenu #' . esc_attr($id) . ':hover .wp-menu-image::before, #adminmenu #' . esc_attr($id) . ' > a:focus .wp-menu-image::before';
    $mask = 'url("' . esc_url(CFS_IMG_PATH . 'supsystic-adminbar.png') . '") center / 20px 20px no-repeat content-box';
    // The Supsystic items (right under Dashboard) set apart: space above the first and below the last (same rules in every Supsystic plugin)
    $group = '#adminmenu li:not(.supsystic-menu-group) + li.supsystic-menu-group { margin-top: 8px; }'
      . '#adminmenu li.supsystic-menu-group + li:not(.supsystic-menu-group) { margin-top: 8px; }';
    echo '<style id="cfs-menu-icon">' . $group . $sel . ' .wp-menu-image img { display: none; }'
      . $sel . ' .wp-menu-image::before { content: ""; display: block; box-sizing: content-box; width: 20px; height: 20px; padding: 7px 0 0; margin: 0 auto; background-color: currentColor; -webkit-mask: ' . $mask . '; mask: ' . $mask . '; }'
      // The gear turns once on hover and back on leave (like the supsystic.com logo), Supsystic cyan while hovered;
      // the centre of the 20px icon box (the padding is on top only)
      . $sel . ' .wp-menu-image::before { transform-origin: 50% calc(100% - 10px); transition: transform .6s cubic-bezier(.4, 0, .2, 1), background-color .2s ease, color .1s ease-in; }'
      . $hov . ' { transform: rotate(360deg); background-color: #4ae8ea; }'
      . '@media (prefers-reduced-motion: reduce) {' . $sel . ' .wp-menu-image::before { transition: background-color .2s ease, color .1s ease-in; }' . $hov . ' { transform: none; } }'
      // The small "Classic" caption under the name, aligned with its start, styled like the CES captions; shown only where
      // WordPress shows menu names (wide screens with the menu open, and the phone menu), the item then gets room for it
      // below the name
      . $sel . ' .cfsMenuTitle { position: relative; display: block; }'
      . $sel . ' .cfsMenuSub { display: none; }'
      . '@media screen and (min-width: 961px), screen and (max-width: 782px) {'
      . 'body:not(.folded) ' . $sel . ' div.wp-menu-name { padding-bottom: 14px; }'
      . 'body:not(.folded) ' . $sel . ' .cfsMenuSub { display: block; position: absolute; left: 0; top: 100%; white-space: nowrap; font-size: 8.5px; line-height: 11px; font-weight: 400; letter-spacing: 0; opacity: .75; }'
      . '}</style>' . "\n";
  }
  public function getMainLink()
  {
    return uriCfs::_(['baseUrl' => admin_url('admin.php'), 'page' => $this->getMainSlug()]);
  }
  public function getMainSlug()
  {
    return $this->_mainSlug;
  }
  public function getMainCap()
  {
    return dispatcherCfs::applyFilters('adminMenuAccessCap', $this->_mainCap);
  }
}

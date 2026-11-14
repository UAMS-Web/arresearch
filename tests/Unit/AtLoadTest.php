<?php

/**
 * What functions.php and the files it includes do when they load.
 *
 * This is ONE test, and it must stay the first test the suite runs (the file name sorts
 * before every other). A coverage driver credits a file-scope line only to the test that
 * was running when the file loaded, and tests/Pest.php loads the theme in the first test's
 * beforeEach. A mutation run then runs only the tests that cover a mutated line, so the
 * assertions on file-scope behavior have to be in that test, or no mutant on those lines
 * is ever tested.
 *
 * tests/bootstrap.php records every call functions.php makes to the stubbed WordPress and
 * Genesis functions in $GLOBALS['arresearch_calls'], and reads the hooks it registered into
 * $GLOBALS['arresearch_hooks'] (`false` for a hook that is not registered, otherwise its
 * priority).
 *
 * @see functions.php
 */

declare(strict_types=1);

it('registers everything the theme sets up at load', function (): void {
    $calls = $GLOBALS['arresearch_calls'];
    $hooks = $GLOBALS['arresearch_hooks'];

    // The child theme constants, and the version style.css states.
    expect(CHILD_THEME_NAME)->toBe('Minimum Pro Theme')
        ->and(CHILD_THEME_URL)->toBe('http://my.studiopress.com/themes/minimum/')
        ->and(CHILD_THEME_VERSION)->toBe('3.2.1')
        ->and(file_get_contents(dirname(__DIR__, 2).'/style.css'))->toContain('Version: '.CHILD_THEME_VERSION);

    // The textdomain, image size and layout and sidebar removals.
    expect($calls['load_child_theme_textdomain'])->toBe([['minimum', dirname(__DIR__, 2).'/languages']])
        ->and($calls['add_image_size'])->toBe([['portfolio', 540, 340, true]])
        ->and($calls['genesis_unregister_layout'])->toBe([
            ['content-sidebar-sidebar'],
            ['sidebar-content-sidebar'],
            ['sidebar-sidebar-content'],
        ])
        ->and($calls['unregister_sidebar'])->toBe([['sidebar-alt']]);

    // Theme support, in order.
    expect($calls['add_theme_support'])->toBe([
        ['html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']],
        ['genesis-responsive-viewport'],
        ['custom-header', ['width' => 320, 'height' => 120, 'header-selector' => '.site-title a', 'header-text' => false]],
        ['genesis-structural-wraps', ['header', 'nav', 'subnav', 'home-featured', 'site-inner', 'footer-widgets', 'footer']],
        ['genesis-menus', ['primary' => 'After Header Menu', 'secondary' => 'Homepage Menu', 'footer' => 'Footer Menu']],
        ['genesis-footer-widgets', 3],
        ['genesis-after-entry-widget-area'],
    ]);

    // The base sidebar and four home featured areas.
    expect($calls['genesis_register_sidebar'])->toBe([
        [[
            'id' => 'base-sidebar',
            'name' => 'Base Sidebar',
            'description' => 'This is a base sidebar above the primary.',
            'before_widget' => '<div id="%1$s"><div class="widget %2$s">',
            'after_widget' => "</div></div>\n",
            'before_title' => '<h4><span>',
            'after_title' => "</span></h4>\n",
        ]],
        [['id' => 'home-featured-1', 'name' => 'Home Featured 1', 'description' => 'This is the home featured 1 section.']],
        [['id' => 'home-featured-2', 'name' => 'Home Featured 2', 'description' => 'This is the home featured 2 section.']],
        [['id' => 'home-featured-3', 'name' => 'Home Featured 3', 'description' => 'This is the home featured 3 section.']],
        [['id' => 'home-featured-4', 'name' => 'Home Featured 4', 'description' => 'This is the home featured 4 section.']],
    ]);

    // Every hook, at its priority. The Genesis defaults the file removes read `false`, and the
    // ones it moves read their new priority.
    expect($hooks)->toBe([
        'wp_enqueue_scripts/minimum_enqueue_scripts' => 10,
        'wp_nav_menu_args/minimum_secondary_menu_args' => 10,
        'get_header/child_remove_genesis_do_nav' => 10,
        'genesis_author_box_gravatar_size/minimum_author_box_gravatar' => 10,
        'genesis_comment_list_args/minimum_comments_gravatar' => 10,
        'genesis_before_sidebar_widget_area/wpsites_widget_before_secondary_sidebar' => 10,
        'genesis_footer_creds_text/sp_footer_creds_filter' => 10,
        'genesis_after_footer/tm_do_footernav' => 5,
        'genesis_after/tm_uams_logo' => 10,
        'genesis_theme_settings_defaults/minimum_theme_defaults' => 10,
        'after_switch_theme/minimum_theme_setting_defaults' => 10,
        'simple_social_default_styles/minimum_social_default_styles' => 10,
        'customize_register/minimum_customizer' => 10,
        'genesis_site_description/genesis_seo_site_description' => false,
        'genesis_after_header/genesis_do_nav' => 15,
        'genesis_after_header/genesis_do_subnav' => false,
        'genesis_after_entry/genesis_after_entry_widget_area' => 5,
    ]);
});

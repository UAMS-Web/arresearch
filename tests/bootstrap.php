<?php

/**
 * Unit bootstrap for the theme.
 *
 * Defines the loader for functions.php, which a theme cannot avoid. There is no
 * WordPress and no Genesis here: every function that functions.php and the files
 * it includes call at file scope is stubbed inside the loader.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/arresearch-tests-no-wordpress/');
}

/**
 * Load functions.php, once, from inside the first test that asks.
 *
 * It cannot load in this bootstrap: a coverage driver records only what runs inside a test,
 * so a mutation run would report every file-scope line as uncovered and never mutate it.
 * tests/Pest.php calls this from every test's beforeEach, after Brain Monkey's setUp(), so
 * the session the file loads in is the first test's and its hooks are discarded with it.
 *
 * What functions.php does at file scope is recorded so a test can pin it. The calls to the
 * stubbed WordPress and Genesis functions go to $GLOBALS['arresearch_calls'], keyed by
 * function name, one entry of arguments per call. The hooks the file adds, and the ones it
 * removes from the Genesis defaults seeded below, are read out of Brain Monkey before
 * tearDown() discards them, into $GLOBALS['arresearch_hooks'] (`false` for a hook that is
 * not registered, otherwise its priority). get_template_directory() points at an empty
 * stand-in for Genesis, because functions.php includes the parent theme's lib/init.php.
 */
function arresearch_load_theme(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $loaded = true;

    stubTranslationFunctions();

    when('get_template_directory')->justReturn(__DIR__.'/Fixtures/genesis');
    when('get_stylesheet_directory')->justReturn(dirname(__DIR__));

    $GLOBALS['arresearch_calls'] = [];

    foreach ([
        'load_child_theme_textdomain',
        'add_theme_support',
        'add_image_size',
        'genesis_unregister_layout',
        'genesis_register_sidebar',
        'unregister_sidebar',
    ] as $recorded) {
        when($recorded)->alias(static function (mixed ...$arguments) use ($recorded): void {
            $GLOBALS['arresearch_calls'][$recorded][] = $arguments;
        });
    }

    // The Genesis defaults that functions.php removes or moves.
    add_action('genesis_site_description', 'genesis_seo_site_description');
    add_action('genesis_after_header', 'genesis_do_nav');
    add_action('genesis_after_header', 'genesis_do_subnav');
    add_action('genesis_after_entry', 'genesis_after_entry_widget_area');

    require_once __DIR__.'/../functions.php';

    $GLOBALS['arresearch_hooks'] = [
        'wp_enqueue_scripts/minimum_enqueue_scripts' => has_action('wp_enqueue_scripts', 'minimum_enqueue_scripts'),
        'wp_nav_menu_args/minimum_secondary_menu_args' => has_filter('wp_nav_menu_args', 'minimum_secondary_menu_args'),
        'get_header/child_remove_genesis_do_nav' => has_action('get_header', 'child_remove_genesis_do_nav'),
        'genesis_author_box_gravatar_size/minimum_author_box_gravatar' => has_filter('genesis_author_box_gravatar_size', 'minimum_author_box_gravatar'),
        'genesis_comment_list_args/minimum_comments_gravatar' => has_filter('genesis_comment_list_args', 'minimum_comments_gravatar'),
        'genesis_before_sidebar_widget_area/wpsites_widget_before_secondary_sidebar' => has_action('genesis_before_sidebar_widget_area', 'wpsites_widget_before_secondary_sidebar'),
        'genesis_footer_creds_text/sp_footer_creds_filter' => has_filter('genesis_footer_creds_text', 'sp_footer_creds_filter'),
        'genesis_after_footer/tm_do_footernav' => has_action('genesis_after_footer', 'tm_do_footernav'),
        'genesis_after/tm_uams_logo' => has_action('genesis_after', 'tm_uams_logo'),
        'genesis_theme_settings_defaults/minimum_theme_defaults' => has_filter('genesis_theme_settings_defaults', 'minimum_theme_defaults'),
        'after_switch_theme/minimum_theme_setting_defaults' => has_action('after_switch_theme', 'minimum_theme_setting_defaults'),
        'simple_social_default_styles/minimum_social_default_styles' => has_filter('simple_social_default_styles', 'minimum_social_default_styles'),
        'customize_register/minimum_customizer' => has_action('customize_register', 'minimum_customizer'),
        'genesis_site_description/genesis_seo_site_description' => has_action('genesis_site_description', 'genesis_seo_site_description'),
        'genesis_after_header/genesis_do_nav' => has_action('genesis_after_header', 'genesis_do_nav'),
        'genesis_after_header/genesis_do_subnav' => has_action('genesis_after_header', 'genesis_do_subnav'),
        'genesis_after_entry/genesis_after_entry_widget_area' => has_action('genesis_after_entry', 'genesis_after_entry_widget_area'),
    ];
}

/**
 * Stand in for the tagline callback that functions.php keeps commented out.
 *
 * The landing page template still calls `remove_action()` for it, and the landing test seeds it so
 * that the removal has something to remove. A declared function lets the seed pass as a callable
 * string, which `arresearch_template()` requires. Uncommenting the real function in functions.php
 * redeclares this one and fails the suite at load, which is the signal to delete this stand-in.
 */
function minimum_site_tagline(): void {}

/**
 * Load one root template file, once, from inside the test that asks, and report what it did.
 *
 * Like arresearch_load_theme(), this runs inside a test so that a coverage driver credits the
 * file-scope lines to it. Call it as the first line of every test in a template's test file,
 * and keep the assertions on file-scope behavior in the file's first test: a mutation run runs
 * only the tests that covered a mutated line, and the first caller is the one that covers the
 * file-scope lines.
 *
 * `$seeds` are Genesis hooks to register before the load, as `[hook, callback, priority]`, so
 * the template's `remove_action()` calls have something to remove. `$watch` are the hooks to
 * read back afterwards, as `[hook, callback]`. The result is `calls` (the arguments of each
 * call to add_theme_support() and genesis(), by function) and `hooks` (priority, or `false`
 * where the callback is not registered, keyed `hook/callback`).
 *
 * @param  list<array{string, callable-string, int}>  $seeds
 * @param  list<array{string, string}>  $watch
 * @return array{calls: array<string, list<array<int|string, mixed>>>, hooks: array<string, int|false>}
 */
function arresearch_template(string $file, array $seeds = [], array $watch = []): array
{
    static $loaded = [];

    if (isset($loaded[$file])) {
        return $loaded[$file];
    }

    $calls = [];

    foreach (['add_theme_support', 'genesis'] as $recorded) {
        when($recorded)->alias(static function (mixed ...$arguments) use (&$calls, $recorded): void {
            $calls[$recorded][] = $arguments;
        });
    }

    foreach ($seeds as [$hook, $callback, $priority]) {
        add_action($hook, $callback, $priority);
    }

    require_once __DIR__.'/../'.$file;

    $hooks = [];

    foreach ($watch as [$hook, $callback]) {
        // Brain Monkey keeps actions and filters apart; WordPress does not, and a template may use either.
        $action = has_action($hook, $callback);
        $hooks[$hook.'/'.$callback] = $action === false ? has_filter($hook, $callback) : $action;
    }

    return $loaded[$file] = ['calls' => $calls, 'hooks' => $hooks];
}

// cspell:ignore ABSPATH stylesheet textdomain unregister

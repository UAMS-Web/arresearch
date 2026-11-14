<?php

/**
 * Unit bootstrap for the theme.
 *
 * Loads functions.php, which a theme cannot avoid, inside one Brain Monkey
 * session. There is no WordPress and no Genesis here: every function that
 * functions.php and the files it includes call at file scope is stubbed below.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\stubs;
use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/arresearch-tests-no-wordpress/');
}

/*
 * functions.php registers hooks and calls WordPress and Genesis at file scope,
 * so it is loaded inside one Brain Monkey session with what it calls at load
 * time stubbed. The functions it defines outlive the session; the hooks it
 * registered are discarded by tearDown(), so every test starts clean.
 *
 * get_template_directory() points at an empty stand-in for Genesis, because
 * functions.php includes the parent theme's lib/init.php.
 */
setUp();

stubTranslationFunctions();

when('get_template_directory')->justReturn(__DIR__.'/Fixtures/genesis');
when('get_stylesheet_directory')->justReturn(dirname(__DIR__));

stubs([
    'load_child_theme_textdomain',
    'add_theme_support',
    'add_image_size',
    'genesis_unregister_layout',
    'genesis_register_sidebar',
    'unregister_sidebar',
]);

require_once __DIR__.'/../functions.php';

tearDown();

// cspell:ignore ABSPATH stylesheet textdomain unregister

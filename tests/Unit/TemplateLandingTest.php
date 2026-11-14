<?php

/**
 * What page_landing.php (the Landing page template) does.
 *
 * @see page_landing.php
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

it('strips the header, navigation, breadcrumbs and footer from the landing page', function (): void {
    $template = arresearch_template('page_landing.php', [
        ['genesis_header', 'genesis_header_markup_open', 5],
        ['genesis_header', 'genesis_do_header', 10],
        ['genesis_header', 'genesis_header_markup_close', 15],
        ['genesis_after_header', 'genesis_do_nav', 15],
        ['genesis_footer', 'genesis_do_subnav', 7],
        ['genesis_after_header', 'minimum_site_tagline', 10],
        ['genesis_before_loop', 'genesis_do_breadcrumbs', 10],
        ['genesis_before_footer', 'genesis_footer_widget_areas', 10],
        ['genesis_footer', 'genesis_footer_markup_open', 5],
        ['genesis_footer', 'genesis_do_footer', 10],
        ['genesis_footer', 'genesis_footer_markup_close', 15],
    ], [
        ['body_class', 'minimum_add_body_class'],
        ['wp_enqueue_scripts', 'minimum_enqueue_backstretch'],
        ['genesis_site_layout', '__genesis_return_full_width_content'],
        ['genesis_header', 'genesis_header_markup_open'],
        ['genesis_header', 'genesis_do_header'],
        ['genesis_header', 'genesis_header_markup_close'],
        ['genesis_after_header', 'genesis_do_nav'],
        ['genesis_footer', 'genesis_do_subnav'],
        ['genesis_after_header', 'minimum_site_tagline'],
        ['genesis_before_loop', 'genesis_do_breadcrumbs'],
        ['genesis_before_footer', 'genesis_footer_widget_areas'],
        ['genesis_footer', 'genesis_footer_markup_open'],
        ['genesis_footer', 'genesis_do_footer'],
        ['genesis_footer', 'genesis_footer_markup_close'],
    ]);

    expect($template['hooks'])->toBe([
        'body_class/minimum_add_body_class' => 10,
        'wp_enqueue_scripts/minimum_enqueue_backstretch' => 10,
        'genesis_site_layout/__genesis_return_full_width_content' => 10,
        'genesis_header/genesis_header_markup_open' => false,
        'genesis_header/genesis_do_header' => false,
        'genesis_header/genesis_header_markup_close' => false,
        'genesis_after_header/genesis_do_nav' => false,
        'genesis_footer/genesis_do_subnav' => false,
        'genesis_after_header/minimum_site_tagline' => false,
        'genesis_before_loop/genesis_do_breadcrumbs' => false,
        'genesis_before_footer/genesis_footer_widget_areas' => false,
        'genesis_footer/genesis_footer_markup_open' => false,
        'genesis_footer/genesis_do_footer' => false,
        'genesis_footer/genesis_footer_markup_close' => false,
    ])->and($template['calls'])->toBe(['genesis' => [[]]]);
});

it('adds the landing body class', function (): void {
    arresearch_template('page_landing.php');

    expect(minimum_add_body_class(['page']))->toBe(['page', 'minimum-landing']);
});

it('loads Backstretch with the chosen image, dropping the http: scheme', function (): void {
    arresearch_template('page_landing.php');

    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');
    Functions\expect('get_option')->once()
        ->with('minimum-backstretch-image', 'https://example.test/theme/images/bg.jpg')
        ->andReturn('http://example.test/uploads/bg.jpg');
    when('get_bloginfo')->justReturn('https://example.test/theme');
    Functions\expect('wp_enqueue_script')->once()->with('minimum-backstretch', 'https://example.test/theme/js/backstretch.js', ['jquery'], '1.0.0');
    Functions\expect('wp_enqueue_script')->once()->with('minimum-backstretch-set', 'https://example.test/theme/js/backstretch-set.js', ['jquery', 'minimum-backstretch'], '1.0.0');
    Functions\expect('wp_localize_script')->once()->with('minimum-backstretch-set', 'BackStretchImg', ['src' => '//example.test/uploads/bg.jpg']);

    minimum_enqueue_backstretch();
});

it('loads no Backstretch scripts when the image setting is empty', function (mixed $image): void {
    arresearch_template('page_landing.php');

    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');
    when('get_option')->justReturn($image);

    Functions\expect('wp_enqueue_script')->never();
    Functions\expect('wp_localize_script')->never();

    minimum_enqueue_backstretch();
})->with(['empty string' => [''], 'false' => [false], 'null' => [null]]);

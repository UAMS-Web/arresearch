<?php

/**
 * What front-page.php does.
 *
 * @see front-page.php
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

it('sets up the front page layout hooks and strips the entry header and content', function (): void {
    $template = arresearch_template('front-page.php', [
        ['genesis_after_header', 'genesis_do_subnav', 10],
        ['genesis_entry_header', 'genesis_entry_header_markup_open', 5],
        ['genesis_entry_header', 'genesis_entry_header_markup_close', 15],
        ['genesis_entry_header', 'genesis_do_post_title', 10],
        ['genesis_entry_content', 'genesis_do_post_content', 10],
    ], [
        ['genesis_meta', 'minimum_front_page_genesis_meta'],
        ['genesis_before_entry_content', 'full_image_before_entry_content'],
        ['genesis_after_header', 'genesis_do_subnav'],
        ['genesis_after_entry', 'genesis_do_subnav'],
        ['genesis_after_entry', 'sk_do_post_title_before'],
        ['genesis_after_entry', 'sk_do_post_title_after'],
        ['genesis_entry_header', 'genesis_entry_header_markup_open'],
        ['genesis_entry_header', 'genesis_entry_header_markup_close'],
        ['genesis_entry_header', 'genesis_do_post_title'],
        ['genesis_entry_content', 'genesis_do_post_content'],
        ['genesis_structural_wrap-site-inner', '__return_empty_string'],
    ]);

    expect($template['hooks'])->toBe([
        'genesis_meta/minimum_front_page_genesis_meta' => 10,
        'genesis_before_entry_content/full_image_before_entry_content' => 10,
        'genesis_after_header/genesis_do_subnav' => false,
        'genesis_after_entry/genesis_do_subnav' => 15,
        'genesis_after_entry/sk_do_post_title_before' => 7,
        'genesis_after_entry/sk_do_post_title_after' => 20,
        'genesis_entry_header/genesis_entry_header_markup_open' => false,
        'genesis_entry_header/genesis_entry_header_markup_close' => false,
        'genesis_entry_header/genesis_do_post_title' => false,
        'genesis_entry_content/genesis_do_post_content' => false,
        'genesis_structural_wrap-site-inner/__return_empty_string' => 10,
    ])->and($template['calls'])->toBe([
        'add_theme_support' => [['genesis-structural-wraps', ['header', 'site-inner', 'footer-widgets', 'footer']]],
        'genesis' => [[]],
    ]);
});

it('swaps the Genesis loop for the grid loop and forces full width on the blog home', function (): void {
    arresearch_template('front-page.php');

    add_action('genesis_loop', 'genesis_do_loop');
    add_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
    add_action('genesis_entry_footer', 'genesis_post_meta');
    add_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
    when('is_home')->justReturn(true);
    when('is_active_sidebar')->justReturn(false);

    minimum_front_page_genesis_meta();

    expect(has_action('genesis_loop', 'genesis_do_loop'))->toBeFalse()
        ->and(has_action('genesis_loop', 'minimum_grid_loop_helper'))->toBe(10)
        ->and(has_action('genesis_entry_footer', 'genesis_entry_footer_markup_open'))->toBeFalse()
        ->and(has_action('genesis_entry_footer', 'genesis_post_meta'))->toBeFalse()
        ->and(has_action('genesis_entry_footer', 'genesis_entry_footer_markup_close'))->toBeFalse()
        ->and(has_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content'))->toBe(10)
        ->and(has_action('genesis_after_content_sidebar_wrap', 'minimum_home_featured'))->toBeFalse();
});

it('leaves the loop alone when it is not the blog home', function (): void {
    arresearch_template('front-page.php');

    add_action('genesis_loop', 'genesis_do_loop');
    when('is_home')->justReturn(false);
    when('is_active_sidebar')->justReturn(false);

    minimum_front_page_genesis_meta();

    expect(has_action('genesis_loop', 'genesis_do_loop'))->toBe(10)
        ->and(has_action('genesis_loop', 'minimum_grid_loop_helper'))->toBeFalse()
        ->and(has_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content'))->toBeFalse();
});

it('adds the home featured areas when any one of the four sidebars is active', function (string $active): void {
    arresearch_template('front-page.php');

    when('is_home')->justReturn(false);
    when('is_active_sidebar')->alias(static fn (string $id): bool => $id === $active);

    minimum_front_page_genesis_meta();

    expect(has_action('genesis_after_content_sidebar_wrap', 'minimum_home_featured'))->toBe(15);
})->with(['home-featured-1', 'home-featured-2', 'home-featured-3', 'home-featured-4']);

it('adds no home featured areas when none of the four sidebars is active', function (): void {
    arresearch_template('front-page.php');

    $asked = [];
    when('is_home')->justReturn(false);
    when('is_active_sidebar')->alias(static function (string $id) use (&$asked): bool {
        $asked[] = $id;

        return false;
    });

    minimum_front_page_genesis_meta();

    expect(has_action('genesis_after_content_sidebar_wrap', 'minimum_home_featured'))->toBeFalse()
        ->and($asked)->toBe(['home-featured-1', 'home-featured-2', 'home-featured-3', 'home-featured-4']);
});

it('prints the volunteer count banner before the entry content', function (): void {
    arresearch_template('front-page.php');

    Functions\expect('get_field')->once()->with('volunteer_number')->andReturn(12345);

    ob_start();
    full_image_before_entry_content();

    expect(ob_get_clean())->toBe('<div class="full-image"><div id="image-inner"><div id="volunteer-count"><h3>Volunteers to date: <span class="volunteer-count">12,345</span></h3></div><div id="inner-logo"><img src="https://arresearch.org/wp-content/uploads/sites/15/2017/06/ARresearch-logo-lrg.png" alt="ARresearch.org logo" /></div><div id="inner-cta" class="fade-in"><a class="cta-button" href="http://ncsdvs.uams.edu/ArResearch"><i class="fa fa-pencil-square-o"></i> Join Our Registry</a><h3>Be part of the answer, Arkansas!</h3></div></div></div>');
});

it('opens and closes the wrap around the post title area', function (): void {
    arresearch_template('front-page.php');

    ob_start();
    sk_do_post_title_before();
    $open = ob_get_clean();

    ob_start();
    sk_do_post_title_after();
    $close = ob_get_clean();

    expect($open)->toBe('<div class="wrap">')
        ->and($close)->toBe('</div>');
});

it('prints the four home featured widget areas inside the featured wrapper, in order', function (): void {
    arresearch_template('front-page.php');

    Functions\expect('genesis_attr')->once()->with('home-featured')->andReturn('class="home-featured"');
    Functions\expect('genesis_structural_wrap')->once()->with('home-featured')->andReturnUsing(static function (): void {
        echo '[open]';
    });
    Functions\expect('genesis_structural_wrap')->once()->with('home-featured', 'close')->andReturnUsing(static function (): void {
        echo '[close]';
    });

    foreach ([1, 2, 3, 4] as $number) {
        Functions\expect('genesis_widget_area')->once()->with('home-featured-'.$number, [
            'before' => '<div class="home-featured-'.$number.' widget-area">',
            'after' => '</div>',
        ])->andReturnUsing(static function () use ($number): void {
            echo '[area '.$number.']';
        });
    }

    ob_start();
    minimum_home_featured();

    expect(ob_get_clean())->toBe('<div class="wrap"><div class="home-featured">[open][area 1][area 2][area 3][area 4][close]</div></div>');
});

/*
 * Order matters for these two. Brain Monkey keeps a mocked function defined for the rest of the
 * process, so the branch where genesis_grid_loop() does not exist must run before the test that
 * defines it.
 */
it('falls back to the standard Genesis loop when the grid loop is not available', function (): void {
    arresearch_template('front-page.php');

    expect(function_exists('genesis_grid_loop'))->toBeFalse();

    Functions\expect('genesis_standard_loop')->once();

    minimum_grid_loop_helper();
});

it('runs the Genesis grid loop with every post in the grid', function (): void {
    arresearch_template('front-page.php');
    stubTranslationFunctions();

    Functions\expect('genesis_standard_loop')->never();
    Functions\expect('genesis_grid_loop')->once()->with([
        'features' => 0,
        'feature_image_size' => 0,
        'feature_content_limit' => 0,
        'grid_image_size' => 0,
        'grid_content_limit' => 250,
        'more' => '[Read more]',
    ]);

    minimum_grid_loop_helper();
});

<?php

/**
 * What archive-portfolio.php does, and the pin that taxonomy-portfolio-type.php is the same file.
 *
 * The two declare the same functions, so one process can load only one of them. The taxonomy
 * template is left out of phpunit.xml's coverage source and held byte-identical to this one
 * instead: a change to either fails the identity test below until the other follows.
 *
 * @see archive-portfolio.php
 * @see taxonomy-portfolio-type.php
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

it('registers the portfolio grid layout and strips the entry parts it replaces', function (): void {
    $template = arresearch_template('archive-portfolio.php', [
        ['genesis_before_loop', 'genesis_do_taxonomy_title_description', 15],
        ['genesis_entry_header', 'genesis_entry_header_markup_open', 5],
        ['genesis_entry_header', 'genesis_post_info', 12],
        ['genesis_entry_header', 'genesis_entry_header_markup_close', 15],
        ['genesis_entry_content', 'genesis_do_post_content', 10],
        ['genesis_entry_content', 'genesis_do_post_image', 8],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_open', 5],
        ['genesis_entry_footer', 'genesis_post_meta', 10],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_close', 15],
    ], [
        ['genesis_pre_get_option_site_layout', '__genesis_return_full_width_content'],
        ['body_class', 'minimum_add_portfolio_body_class'],
        ['post_class', 'minimum_even_odd_portfolio_post_class'],
        ['genesis_before_loop', 'genesis_do_taxonomy_title_description'],
        ['genesis_before_content_sidebar_wrap', 'genesis_do_taxonomy_title_description'],
        ['genesis_entry_header', 'genesis_entry_header_markup_open'],
        ['genesis_entry_header', 'genesis_post_info'],
        ['genesis_entry_header', 'genesis_entry_header_markup_close'],
        ['genesis_entry_content', 'genesis_do_post_content'],
        ['genesis_entry_content', 'genesis_do_post_image'],
        ['genesis_entry_content', 'minimum_portfolio_grid'],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_open'],
        ['genesis_entry_footer', 'genesis_post_meta'],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_close'],
    ]);

    expect($template['hooks'])->toBe([
        'genesis_pre_get_option_site_layout/__genesis_return_full_width_content' => 10,
        'body_class/minimum_add_portfolio_body_class' => 10,
        'post_class/minimum_even_odd_portfolio_post_class' => 10,
        'genesis_before_loop/genesis_do_taxonomy_title_description' => false,
        'genesis_before_content_sidebar_wrap/genesis_do_taxonomy_title_description' => 10,
        'genesis_entry_header/genesis_entry_header_markup_open' => false,
        'genesis_entry_header/genesis_post_info' => false,
        'genesis_entry_header/genesis_entry_header_markup_close' => false,
        'genesis_entry_content/genesis_do_post_content' => false,
        'genesis_entry_content/genesis_do_post_image' => false,
        'genesis_entry_content/minimum_portfolio_grid' => 10,
        'genesis_entry_footer/genesis_entry_footer_markup_open' => false,
        'genesis_entry_footer/genesis_post_meta' => false,
        'genesis_entry_footer/genesis_entry_footer_markup_close' => false,
    ])->and($template['calls'])->toBe(['genesis' => [[]]]);
});

it('adds the portfolio body class', function (): void {
    arresearch_template('archive-portfolio.php');

    expect(minimum_add_portfolio_body_class(['archive']))->toBe(['archive', 'minimum-pro-portfolio']);
});

it('marks posts odd and even by their place in the loop, starting odd', function (int $current, string $class): void {
    arresearch_template('archive-portfolio.php');

    $GLOBALS['wp_query'] = (object) ['current_post' => $current];

    try {
        expect(minimum_even_odd_portfolio_post_class(['post']))->toBe(['post', $class]);
    } finally {
        unset($GLOBALS['wp_query']);
    }
})->with([
    'first' => [0, 'portfolio-odd'],
    'second' => [1, 'portfolio-even'],
    'third' => [2, 'portfolio-odd'],
    'fourth' => [3, 'portfolio-even'],
]);

it('prints the featured image as a link to the post', function (): void {
    arresearch_template('archive-portfolio.php');

    Functions\expect('genesis_get_image')->once()->with('format=url&size=portfolio')->andReturn('https://example.test/p.jpg');
    when('get_permalink')->justReturn('https://example.test/work/');
    Functions\expect('the_title_attribute')->once()->with(['echo' => false])->andReturn('Work');

    ob_start();
    minimum_portfolio_grid();

    expect(ob_get_clean())->toBe('<div class="portfolio-image"><a href="https://example.test/work/" rel="bookmark"><img src="https://example.test/p.jpg" alt="Work" /></a></div>');
});

it('prints nothing for a post with no featured image', function (): void {
    arresearch_template('archive-portfolio.php');

    Functions\expect('genesis_get_image')->once()->with('format=url&size=portfolio')->andReturn(false);

    ob_start();
    minimum_portfolio_grid();

    expect(ob_get_clean())->toBe('');
});

it('keeps the taxonomy template byte-identical to the archive template', function (): void {
    expect(file_get_contents(dirname(__DIR__, 2).'/taxonomy-portfolio-type.php'))
        ->toBe(file_get_contents(dirname(__DIR__, 2).'/archive-portfolio.php'));
});

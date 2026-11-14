<?php

/**
 * What single-portfolio.php does when WordPress loads it as the single portfolio template.
 *
 * It has no function of its own: everything happens at file scope, so this is one test.
 *
 * @see single-portfolio.php
 */

declare(strict_types=1);

it('forces full width and strips the entry meta, author box and comments before running Genesis', function (): void {
    $template = arresearch_template('single-portfolio.php', [
        ['genesis_entry_header', 'genesis_entry_header_markup_open', 5],
        ['genesis_entry_header', 'genesis_post_info', 12],
        ['genesis_entry_header', 'genesis_entry_header_markup_close', 15],
        ['genesis_after_entry', 'genesis_do_author_box_single', 8],
        ['genesis_after_entry', 'genesis_get_comments_template', 10],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_open', 5],
        ['genesis_entry_footer', 'genesis_post_meta', 10],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_close', 15],
    ], [
        ['genesis_entry_header', 'genesis_entry_header_markup_open'],
        ['genesis_entry_header', 'genesis_post_info'],
        ['genesis_entry_header', 'genesis_entry_header_markup_close'],
        ['genesis_after_entry', 'genesis_do_author_box_single'],
        ['genesis_after_entry', 'genesis_get_comments_template'],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_open'],
        ['genesis_entry_footer', 'genesis_post_meta'],
        ['genesis_entry_footer', 'genesis_entry_footer_markup_close'],
        ['genesis_pre_get_option_site_layout', '__genesis_return_full_width_content'],
    ]);

    expect($template['hooks'])->toBe([
        'genesis_entry_header/genesis_entry_header_markup_open' => false,
        'genesis_entry_header/genesis_post_info' => false,
        'genesis_entry_header/genesis_entry_header_markup_close' => false,
        'genesis_after_entry/genesis_do_author_box_single' => false,
        'genesis_after_entry/genesis_get_comments_template' => false,
        'genesis_entry_footer/genesis_entry_footer_markup_open' => false,
        'genesis_entry_footer/genesis_post_meta' => false,
        'genesis_entry_footer/genesis_entry_footer_markup_close' => false,
        'genesis_pre_get_option_site_layout/__genesis_return_full_width_content' => 10,
    ])->and($template['calls'])->toBe(['genesis' => [[]]]);
});

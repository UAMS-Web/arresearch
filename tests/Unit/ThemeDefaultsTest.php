<?php

/**
 * The Genesis settings and Simple Social Icons defaults the theme supplies.
 *
 * @see lib/theme-defaults.php
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

/*
 * Order matters for these two. Brain Monkey defines a mocked function for the rest of the
 * process, so the branch where Genesis is not loaded must run before any test that defines
 * genesis_update_settings().
 */
it('still sets the post count and flushes rewrite rules when Genesis is not loaded', function (): void {
    expect(function_exists('genesis_update_settings'))->toBeFalse();

    Functions\expect('update_option')->once()->with('posts_per_page', 6);
    Functions\expect('flush_rewrite_rules')->once()->with(false);

    minimum_theme_setting_defaults();
});

it('saves the Genesis defaults, sets the post count and flushes rewrite rules on theme switch', function (): void {
    Functions\expect('genesis_update_settings')->once()->with([
        'blog_cat_num' => 6,
        'content_archive' => 'full',
        'content_archive_limit' => 0,
        'content_archive_thumbnail' => 0,
        'image_alignment' => 'alignleft',
        'posts_nav' => 'numeric',
        'site_layout' => 'content-sidebar',
    ]);
    Functions\expect('update_option')->once()->with('posts_per_page', 6);
    Functions\expect('flush_rewrite_rules')->once()->with(false);

    minimum_theme_setting_defaults();
});

it('adds the theme defaults to the Genesis settings', function (): void {
    expect(minimum_theme_defaults(['site_layout' => 'sidebar-content', 'keep' => 'me']))
        ->toBe([
            'site_layout' => 'content-sidebar',
            'keep' => 'me',
            'blog_cat_num' => 6,
            'content_archive' => 'full',
            'content_archive_limit' => 0,
            'content_archive_thumbnail' => 0,
            'image_alignment' => 'alignleft',
            'posts_nav' => 'numeric',
        ]);
});

it('supplies the Simple Social Icons defaults, which override the ones it is given', function (): void {
    when('wp_parse_args')->alias(
        static fn (array $args, array $defaults): array => array_merge($defaults, $args)
    );

    expect(minimum_social_default_styles(['size' => 24, 'unrelated' => 'kept']))->toBe([
        'size' => 100,
        'unrelated' => 'kept',
        'border_radius' => 100,
        'icon_color' => '#ffffff',
        'icon_color_hover' => '#ffffff',
        'background_color' => '#333333',
        'background_color_hover' => '#0ebfe9',
        'alignment' => 'aligncenter',
    ]);
});

it('reports the customizer accent color', function (): void {
    expect(minimum_customizer_get_default_accent_color())->toBe('#0ebfe9');
});

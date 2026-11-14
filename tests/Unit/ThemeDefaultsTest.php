<?php

/**
 * The Genesis settings and Simple Social Icons defaults the theme supplies.
 *
 * @see lib/theme-defaults.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

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

it('lets the Simple Social Icons defaults it is given override its own', function (): void {
    when('wp_parse_args')->alias(
        static fn (array $args, array $defaults): array => array_merge($defaults, $args)
    );

    $styles = minimum_social_default_styles(['size' => 24]);

    expect($styles['size'])->toBe(100)
        ->and($styles['background_color_hover'])->toBe('#0ebfe9');
});

it('reports the customizer accent color', function (): void {
    expect(minimum_customizer_get_default_accent_color())->toBe('#0ebfe9');
});

<?php

/**
 * The callbacks functions.php defines.
 *
 * @see functions.php
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

it('enqueues the responsive menu script and the three stylesheets', function (): void {
    Functions\expect('get_bloginfo')->once()->with('stylesheet_directory')->andReturn('https://example.test/theme');
    Functions\expect('wp_enqueue_script')->once()->with(
        'minimum-responsive-menu',
        'https://example.test/theme/js/responsive-menu.js',
        ['jquery'],
        '1.0.0'
    );
    Functions\expect('wp_enqueue_style')->once()->with('dashicons');
    Functions\expect('wp_enqueue_style')->once()->with(
        'minimum-google-fonts',
        '//fonts.googleapis.com/css?family=Raleway:400,600|Lato:400',
        [],
        CHILD_THEME_VERSION
    );
    Functions\expect('wp_enqueue_style')->once()->with(
        'font-awesome',
        '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css'
    );

    minimum_enqueue_scripts();
});

/*
 * One equivalent mutant survives in minimum_secondary_menu_args(): `!=` to `!==`. WordPress
 * always passes theme_location as a string, so no real input tells them apart, and a test
 * that did (a boolean `true` loosely equals any non-empty string) would pin a quirk.
 */
it('limits the secondary menu to one level', function (): void {
    expect(minimum_secondary_menu_args(['theme_location' => 'secondary', 'depth' => 3, 'echo' => false]))
        ->toBe(['theme_location' => 'secondary', 'depth' => 1, 'echo' => false]);
});

it('leaves the arguments of any other menu alone', function (string $location): void {
    $args = ['theme_location' => $location, 'depth' => 3];

    expect(minimum_secondary_menu_args($args))->toBe($args);
})->with(['primary', 'footer', '']);

it('removes the primary navigation from the front page', function (): void {
    add_action('genesis_after_header', 'genesis_do_nav', 15);
    when('is_front_page')->justReturn(true);

    child_remove_genesis_do_nav();

    expect(has_action('genesis_after_header', 'genesis_do_nav'))->toBeFalse();
});

it('keeps the primary navigation on every other page', function (): void {
    add_action('genesis_after_header', 'genesis_do_nav', 15);
    when('is_front_page')->justReturn(false);

    child_remove_genesis_do_nav();

    expect(has_action('genesis_after_header', 'genesis_do_nav'))->toBe(15);
});

it('sizes the author box Gravatar at 144 whatever size it is given', function (): void {
    /** @var callable(int): int $filter */
    $filter = 'minimum_author_box_gravatar';

    expect($filter(70))->toBe(144);
});

it('sizes the comment Gravatar at 96 and keeps the other arguments', function (): void {
    expect(minimum_comments_gravatar(['avatar_size' => 48, 'type' => 'comment']))
        ->toBe(['avatar_size' => 96, 'type' => 'comment']);
});

it('prints the base sidebar above the secondary sidebar', function (): void {
    Functions\expect('dynamic_sidebar')->once()->with('base-sidebar')->andReturnUsing(static function (): bool {
        echo 'WIDGETS';

        return true;
    });

    ob_start();
    wpsites_widget_before_secondary_sidebar();

    expect(ob_get_clean())->toBe('<div class="base-sidebar">WIDGETS</div>');
});

it('replaces the footer credits with the grant acknowledgement', function (): void {
    /** @var callable(string): string $filter */
    $filter = 'sp_footer_creds_filter';
    $creds = $filter('Genesis default credits');

    expect($creds)->toStartWith('ARresearch.org is supported by the Translational Research Institute, grant 1U54TR001629-01A1,')
        ->and($creds)->toContain('UAMS Chancellor’s Circle.<br/></p><p><br/>Copyright [footer_copyright]  UAMS Translational Research Institute')
        ->and($creds)->toEndWith('All Rights Reserved &middot; [footer_loginout]')
        ->and($creds)->not->toContain('Genesis default credits');
});

it('prints the footer navigation when the theme supports it', function (): void {
    Functions\expect('genesis_nav_menu_supported')->once()->with('footer')->andReturn(true);
    Functions\expect('genesis_nav_menu')->once()->with([
        'theme_location' => 'footer',
        'menu_class' => 'menu genesis-nav-menu menu-footer',
    ]);

    tm_do_footernav();
});

it('prints no footer navigation when the theme does not support it', function (): void {
    Functions\expect('genesis_nav_menu_supported')->once()->with('footer')->andReturn(false);
    Functions\expect('genesis_nav_menu')->never();

    tm_do_footernav();
});

it('prints the UAMS logo linked to uams.edu', function (): void {
    ob_start();
    tm_uams_logo();

    expect(ob_get_clean())->toBe(
        '<div class="uams-logo"><a href="http://www.uams.edu"><img src="https://arresearch.org/wp-content/uploads/2017/06/UAMS_Logo.png" width="250" height="70" alt="University of Arkansas for Medical Sciences (UAMS)"></a></div>'
    );
});

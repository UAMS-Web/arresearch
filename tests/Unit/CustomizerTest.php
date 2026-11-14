<?php

/**
 * The Customizer section, setting and control the theme registers.
 *
 * @see lib/customize.php
 */

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Fixtures/wordpress/WP_Customize_Image_Control.php';

it('registers the backstretch image section, setting and control', function (): void {
    stubTranslationFunctions();
    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');

    $manager = Mockery::mock(WP_Customize_Manager::class);
    $manager->expects('add_section')->once()->with('minimum-image', [
        'title' => 'Backstretch Image',
        'description' => '<p>Use the included default image or personalize your site by uploading your own image for the homepage and landing page template background.</p><p>The default image is <strong>1600 x 1050 pixels</strong>.</p>',
        'priority' => 75,
    ]);
    $manager->expects('add_setting')->once()->with('minimum-backstretch-image', [
        'default' => 'https://example.test/theme/images/bg.jpg',
        'type' => 'option',
    ]);
    $manager->expects('add_control')->once()->with(Mockery::on(
        static fn (object $control): bool => $control instanceof WP_Customize_Image_Control
            && $control->id === 'backstretch-image'
            && $control->args === [
                'label' => 'Backstretch Image Upload',
                'section' => 'minimum-image',
                'settings' => 'minimum-backstretch-image',
            ]
    ));

    Assert::assertInstanceOf(WP_Customize_Manager::class, $manager);

    minimum_customizer($manager);
});

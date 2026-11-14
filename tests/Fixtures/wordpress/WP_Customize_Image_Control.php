<?php

/**
 * A stand-in for WordPress' image control, which records what it was built with.
 */

declare(strict_types=1);

/**
 * The base class of WordPress' controls, declared so the image control below has the parent it has in WordPress.
 */
class WP_Customize_Control {}

class WP_Customize_Image_Control extends WP_Customize_Control
{
    /**
     * @param  array<string, mixed>  $args
     */
    public function __construct(public object $manager, public string $id, public array $args = []) {}
}

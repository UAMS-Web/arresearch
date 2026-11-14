<?php

/**
 * A stand-in for WordPress' image control, which records what it was built with.
 */

declare(strict_types=1);

class WP_Customize_Image_Control
{
    /**
     * @param  array<string, mixed>  $args
     */
    public function __construct(public object $manager, public string $id, public array $args = []) {}
}

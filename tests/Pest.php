<?php

/**
 * Pest configuration, for the Unit suite.
 *
 * The Integration suite is class-based PHPUnit 9.6 under tests/Integration, run
 * from its own Composer setup; Pest never loads it.
 */

declare(strict_types=1);

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

/*
 * Brain Monkey around every test.
 *
 * setUp() defines WordPress' hook functions and arms Patchwork; tearDown()
 * verifies every expectation the test set (a `->once()` that never happened
 * fails here) and removes every mock, so nothing leaks into the next test.
 *
 * The assertion count: Brain Monkey's expectations are Mockery expectations,
 * which PHPUnit does not count. MockeryPHPUnitIntegration adds them after each
 * test; without them, a test whose only check is `Functions\expect(...)->once()`
 * reports as risky, and failOnRisky fails it.
 */
uses(MockeryPHPUnitIntegration::class)
    ->beforeEach(function (): void {
        setUp();

        arresearch_load_theme();
    })
    ->afterEach(function (): void {
        tearDown();
    })
    ->in('Unit');

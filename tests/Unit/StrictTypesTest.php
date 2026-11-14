<?php

/**
 * Every first-party PHP file declares strict_types, as pint.json's `declare_strict_types` rule
 * requires. The theme's templates have no other guard: a type error in one is a fatal on the
 * site, so the declaration is pinned here beside the tests that exercise each file.
 */

declare(strict_types=1);

it('declares strict_types in every first-party PHP file', function (string $file): void {
    expect(file_get_contents($file))->toContain("\ndeclare(strict_types=1);\n");
})->with(fn (): array => array_map(
    static fn (string $file): array => [$file],
    [...glob(dirname(__DIR__, 2).'/*.php') ?: [], ...glob(dirname(__DIR__, 2).'/lib/*.php') ?: []]
));

it('finds the files it checks', function (): void {
    expect(glob(dirname(__DIR__, 2).'/*.php'))->toContain(dirname(__DIR__, 2).'/functions.php')
        ->and(glob(dirname(__DIR__, 2).'/lib/*.php'))->toHaveCount(2);
});

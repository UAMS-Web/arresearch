<!-- cspell:ignore uams PCOV -->

Written against v3.3.0.

How changes to the theme are made, checked and shipped. Changes reach `main` through a pull request from a branch linked to an issue, as [`CONTRIBUTING.md`](https://github.com/UAMS-Web/arresearch/blob/main/CONTRIBUTING.md) describes.

## Requirements

- PHP 8.2 or later, and Composer.
- `composer install` from the committed `composer.lock`, which installs the test and analysis tools. Nothing is installed in the theme for production: the theme has no runtime Composer dependencies.

## Commands

| Command | What it runs |
| --- | --- |
| `composer test` | The Pest unit suite in `tests/Unit`. WordPress and Genesis are not loaded; their functions are mocked with Brain Monkey. |
| `composer stan` | PHPStan at level 10, with the WordPress, Genesis and ACF Pro stubs. The WordPress stubs are locked to the 6.8 line, the theme's minimum version. |
| `composer test:pint` | Pint, checking formatting without changing files. `composer pint` applies it. |
| `composer test:refactor` | Rector as a dry run. `composer refactor` applies it. |
| `composer mutate` | Pest mutation testing over the theme's PHP files, with PCOV. |

## Continuous integration

Every pull request runs the shared chain from `UAMS-Web/uams-ci-workflows`, pinned in [`.github/workflows/ci.yml`](https://github.com/UAMS-Web/arresearch/blob/main/.github/workflows/ci.yml). It runs Pest, PHPStan, Rector, Pint, mutation testing on the changed files, a PHP 8.2 syntax check, a check that the synced rules and skills were not edited by hand, and the download check below. The `CI` job reports the result, and `main` requires it.

[`.github/workflows/pr-body-checks.yml`](https://github.com/UAMS-Web/arresearch/blob/main/.github/workflows/pr-body-checks.yml) checks each pull request's description for an attribution footer and for closing keywords split across lines.

## What the download contains

A GitHub download of the theme (the zip or tarball) holds only the files the theme runs: `style.css`, `screenshot.png`, `functions.php`, the page templates, and the `images/`, `js/`, `languages/` and `lib/` folders. [`.gitattributes`](https://github.com/UAMS-Web/arresearch/blob/main/.gitattributes) keeps that list, and the CI download check fails when anything else would ship. A new top-level runtime file or folder needs its own line there in the same change.

## Synced files

The rules and skills under `.claude/`, `.agents/` and `.cursor/`, and the generated sections of `AGENTS.md` and `CONTRIBUTING.md`, are synced from `UAMS-Web/uams-claude-skills`. Change them there, not here; the next sync overwrites a local edit.

## This wiki

The pages are kept in [`docs/wiki/`](https://github.com/UAMS-Web/arresearch/tree/main/docs/wiki) and published by [`.github/workflows/publish-wiki.yml`](https://github.com/UAMS-Web/arresearch/blob/main/.github/workflows/publish-wiki.yml), which runs when a release is published and can be run by hand. Each run replaces the whole wiki with the folder, so an edit made in the browser is lost at the next run.

- Link between pages with relative links that keep the `.md` extension, for example `[Troubleshooting](Troubleshooting.md)`.
- Link a file elsewhere in the repository with its full GitHub URL, since the publish does not rewrite those links.
- Before cutting a release, set the `Written against` line in the footer, and on each page the release leaves accurate, to the new version.

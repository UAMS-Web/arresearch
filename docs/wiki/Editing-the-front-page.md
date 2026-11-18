<!-- cspell:ignore UAMS -->

Written against v3.3.0.

The front page is drawn by the theme's `front-page.php`, not by the page's own content. This guide covers what an editor can change from the dashboard, and what needs a change to the theme.

## What the front page shows

From top to bottom:

1. The site header. The *After Header Menu* is removed on the front page.
2. A banner with the "Volunteers to date" count, the ARResearch logo, a *Join Our Registry* button and the line "Be part of the answer, Arkansas!". It takes the place of the front page's title and content, which are not shown.
3. The *Homepage Menu*, one level deep, after the banner.
4. The four *Home Featured* widget areas, when at least one of them holds a widget.
5. The footer widgets, the footer credits, the *Footer Menu*, and the UAMS logo.

This guide assumes *Settings*, *Reading* shows a static page as the front page. With the latest posts there instead, the theme switches to a full-width Genesis grid loop (250-character excerpts, a *[Read more]* link) where Genesis provides one, and the banner and the *Homepage Menu*, which are attached to each entry, would repeat per post. That mode is not documented further here.

## Change the volunteer count

1. Edit the page set as the front page under *Settings*, *Reading*.
2. Change its `volunteer_number` field (an Advanced Custom Fields field) to the new count, as digits only.
3. Update the page. The count is shown with thousands separators: `12345` shows as `12,345`.

A value that is not a number, or an empty field, shows as `0`.

## Change the Home Featured widgets

1. Open *Appearance*, *Widgets*.
2. Add, change or remove widgets in *Home Featured 1* to *Home Featured 4*.

Removing the last widget from all four areas removes the whole section from the front page.

## Change the Homepage Menu

1. Open *Appearance*, *Menus*, and edit the menu assigned to *Homepage Menu*.
2. Keep it to top-level items. The theme shows this menu one level deep, so items nested under another item are not displayed.

## Changes that need a theme change

These are written into `front-page.php` and change only through a pull request to this repository:

- the banner's logo image and its *Join Our Registry* link;
- the banner text;
- the order of the sections listed above.

The footer credits text and the UAMS logo after the footer are written into `functions.php` in the same way.

## Reference

- [Theme reference: widget areas](Theme-reference.md#widget-areas)
- [Theme reference: menus](Theme-reference.md#menus)
- [Theme reference: page templates](Theme-reference.md#page-templates)

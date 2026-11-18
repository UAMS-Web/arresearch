<!-- cspell:ignore Customizer UAMS -->

Written against v3.3.0.

- [The front page fails with `Call to undefined function get_field()`](#the-front-page-fails-with-call-to-undefined-function-get_field)
- [The volunteer count shows 0](#the-volunteer-count-shows-0)
- [The front page does not show the page's own content](#the-front-page-does-not-show-the-pages-own-content)
- [The After Header Menu is missing on the front page](#the-after-header-menu-is-missing-on-the-front-page)
- [Nested items in the Homepage Menu do not appear](#nested-items-in-the-homepage-menu-do-not-appear)
- [The Home Featured section does not appear](#the-home-featured-section-does-not-appear)
- [The Backstretch image does not appear on the front page](#the-backstretch-image-does-not-appear-on-the-front-page)
- [A Landing page has no header or After Header Menu](#a-landing-page-has-no-header-or-after-header-menu)
- [The parent theme is missing](#the-parent-theme-is-missing)

## The front page fails with `Call to undefined function get_field()`

**Cause:** the front page reads the volunteer count with Advanced Custom Fields' `get_field()`, and does not check that the plugin is active. With the plugin off, PHP stops on the front page with this fatal error, while the other pages still load.

**Fix:** activate Advanced Custom Fields.

## The volunteer count shows 0

**Cause:** the front page's `volunteer_number` field is empty, missing, or holds something that is not a number, such as `about 500` or `1,200`. The theme shows `0` for any value that is not numeric (since v3.3.0).

**Fix:** edit the front page and enter the count as digits only, for example `1200`. The theme adds the thousands separator.

## The front page does not show the page's own content

**Cause:** by design. `front-page.php` removes the entry title and content and shows its banner in their place.

**Fix:** none from the dashboard. Change the banner through a pull request to `front-page.php`; see [Editing the front page](Editing-the-front-page.md#changes-that-need-a-theme-change).

## The After Header Menu is missing on the front page

**Cause:** by design. The theme removes the *After Header Menu* on the front page, which shows the *Homepage Menu* after the banner instead.

**Fix:** to change front-page navigation, edit the menu assigned to *Homepage Menu*.

## Nested items in the Homepage Menu do not appear

**Cause:** the theme limits the *Homepage Menu* to one level.

**Fix:** move the items to the top level of that menu.

## The Home Featured section does not appear

**Cause:** the section is added only when at least one of *Home Featured 1* to *Home Featured 4* holds a widget.

**Fix:** add a widget to one of the four areas under *Appearance*, *Widgets*.

## The Backstretch image does not appear on the front page

**Cause:** the Customizer's *Backstretch Image* description mentions the homepage, but in this theme only the Landing template loads the image.

**Fix:** use the Landing template for a page that needs the background image. Showing it on the front page takes a theme change.

## A Landing page has no header or After Header Menu

**Cause:** by design. The Landing template removes the header, the *After Header Menu*, the breadcrumbs, the footer widgets and the footer credits, and forces a full-width layout. The *Footer Menu* and the UAMS logo after the footer still appear.

**Fix:** choose the default template in the page editor for a page that needs them.

## The parent theme is missing

*Appearance*, *Themes* lists the theme under *Broken Themes* with `The parent theme is missing. Please install the "genesis" parent theme.`

**Cause:** the child theme names `genesis` as its parent, and `wp-content/themes/genesis/` does not exist.

**Fix:** install the Genesis theme in that folder. See [Setting up the theme](Setting-up-the-theme.md).

## Fixed

None yet.

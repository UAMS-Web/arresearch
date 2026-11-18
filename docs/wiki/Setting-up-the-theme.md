Written against v3.3.0.

At the end of this walkthrough the site runs the ARResearch child theme on top of Genesis, with its three menus assigned, its widget areas filled and the front page showing the volunteer count.

The theme needs WordPress 6.8 or later and PHP 8.2 or later (the `Requires at least` and `Requires PHP` lines in `style.css`).

1. **Install the Genesis parent theme** in `wp-content/themes/genesis/`. The child theme's `style.css` names it as `Template: genesis`, so WordPress looks for exactly that folder. Without it, *Appearance*, *Themes* lists the child theme as broken.
2. **Install the child theme** in `wp-content/themes/`, from the [README](https://github.com/UAMS-Web/arresearch/blob/main/README.txt)'s install steps or from a GitHub download of this repository. The download holds only the files the theme runs (see [Development](Development.md#what-the-download-contains)).
3. **Activate it** under *Appearance*, *Themes*. On activation the theme writes its Genesis setting defaults, sets *Blog pages show at most* to 6 posts, and flushes the rewrite rules. The defaults are listed in [Theme reference](Theme-reference.md#genesis-setting-defaults).
4. **Activate Advanced Custom Fields** and give the page used as the front page a field named `volunteer_number`. The front page reads that field for its "Volunteers to date" count. **Note:** the front page calls the plugin's `get_field()` with no check that the plugin is active, so the front page fails with a fatal error while the plugin is off (see [Troubleshooting](Troubleshooting.md#the-front-page-fails-with-call-to-undefined-function-get_field)).
5. **Assign the menus** under *Appearance*, *Menus*, *Manage Locations*: *After Header Menu*, *Homepage Menu* and *Footer Menu*. Where each one appears is in [Theme reference](Theme-reference.md#menus).
6. **Fill the widget areas** under *Appearance*, *Widgets*. The four *Home Featured* areas appear on the front page only when at least one of them holds a widget.
7. **Set the background image** under *Appearance*, *Customize*, *Backstretch Image*, if the site uses the Landing page template. The default is `images/bg.jpg` in the theme, at 1600 by 1050 pixels.

Next:

- [Editing the front page](Editing-the-front-page.md) for the day-to-day changes.
- [Theme reference](Theme-reference.md) for every area, menu and setting named above.

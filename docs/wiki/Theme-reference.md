<!-- cspell:ignore Customizer dashicons Raleway Lato UAMS -->

Written against v3.3.0.

Everything the theme registers or changes, read from its code. Areas, menus and settings that come from Genesis without change are not repeated here; Genesis's own documentation covers them.

- [Widget areas](#widget-areas)
- [Menus](#menus)
- [Page templates](#page-templates)
- [Customizer settings](#customizer-settings)
- [Genesis setting defaults](#genesis-setting-defaults)
- [Layouts](#layouts)
- [Image sizes and header](#image-sizes-and-header)
- [Scripts and styles](#scripts-and-styles)

## Widget areas

| Area | Registered by | Where it appears |
| --- | --- | --- |
| Base Sidebar (`base-sidebar`) | the theme | Above the primary sidebar's widgets, on every page with a sidebar |
| Primary Sidebar | Genesis | The sidebar, in the Content/Sidebar and Sidebar/Content layouts |
| Header Right | Genesis | The right side of the header |
| Home Featured 1 to 4 (`home-featured-1` to `home-featured-4`) | the theme | The front page, after the content, when at least one of the four holds a widget |
| After Entry | Genesis, enabled by the theme | After the entry on single posts |
| Footer 1 to 3 | Genesis, enabled by the theme | Three columns above the footer |

The Secondary Sidebar (`sidebar-alt`) is unregistered, because the layouts that use it are removed (see [Layouts](#layouts)).

**Note:** the *Site Tagline Right* area that Minimum Pro registers is turned off in this theme; its code in `functions.php` is commented out.

## Menus

| Location | Where it appears |
| --- | --- |
| After Header Menu (`primary`) | Below the header on every page except the front page and pages that use the Landing template |
| Homepage Menu (`secondary`) | On the front page, after the banner; shown one level deep everywhere |
| Footer Menu (`footer`) | Below the footer credits on every page |

## Page templates

| Template | File | What it does |
| --- | --- | --- |
| Front page | `front-page.php` | The banner with the volunteer count and the Home Featured areas. See [Editing the front page](Editing-the-front-page.md). |
| Landing | `page_landing.php`, chosen as *Landing* in the page editor | Full width, with no header, After Header Menu, breadcrumbs, footer widgets or footer credits. The Footer Menu and the UAMS logo still appear. Loads the Backstretch background image. |
| Portfolio archive | `archive-portfolio.php` | A full-width grid of portfolio items, each shown by its featured image at the `portfolio` size, with no entry meta or content. |
| Portfolio type archive | `taxonomy-portfolio-type.php` | The same as the portfolio archive, for one portfolio type. |
| Single portfolio item | `single-portfolio.php` | Full width, with no entry meta, author box or comments. |

The portfolio templates apply when a plugin registers a `portfolio` post type and a `portfolio-type` taxonomy; the theme itself registers neither. The README names Genesis Portfolio Pro for this.

## Customizer settings

| Section | Setting | Type | Default | Effect |
| --- | --- | --- | --- | --- |
| Backstretch Image | `minimum-backstretch-image` (stored as an option) | Image | `images/bg.jpg` in the theme (1600 by 1050 pixels) | The full-screen background on pages that use the Landing template |

The Customizer's description says the image is also used on the homepage. In this theme only the Landing template loads it.

## Genesis setting defaults

The theme supplies these as Genesis's defaults, and writes them to the Genesis settings when the theme is activated:

| Setting | Value |
| --- | --- |
| Posts shown by the Blog page template (`blog_cat_num`) | 6 |
| Content archives (`content_archive`) | full content |
| Content archive character limit (`content_archive_limit`) | 0 |
| Featured image on archives (`content_archive_thumbnail`) | off |
| Image alignment (`image_alignment`) | left |
| Entry pagination (`posts_nav`) | numeric |
| Default layout (`site_layout`) | Content/Sidebar |

Activation also sets WordPress's *Blog pages show at most* to 6.

It also sets the defaults for the Simple Social Icons plugin, when that plugin is active: 100 pixel icons with a 100 pixel border radius, white icons on `#333333`, `#0ebfe9` on hover, centered.

## Layouts

The three-column layouts (Content/Sidebar/Sidebar, Sidebar/Content/Sidebar, Sidebar/Sidebar/Content) are unregistered. The rest of Genesis's layouts remain. The front page (when it shows the latest posts), the Landing template and the portfolio templates are forced to full width.

## Image sizes and header

| Item | Value |
| --- | --- |
| `portfolio` image size | 540 by 340 pixels, cropped |
| Custom header | 320 by 120 pixels, applied to the site title, with no header text |
| Author box Gravatar | 144 pixels |
| Comment Gravatar | 96 pixels |

## Scripts and styles

| Handle | Loaded on | Source |
| --- | --- | --- |
| `minimum-responsive-menu` | every page | `js/responsive-menu.js` (needs jQuery) |
| `dashicons` | every page | WordPress |
| `minimum-google-fonts` | every page | Google Fonts: Raleway 400 and 600, Lato 400 |
| `font-awesome` | every page | Font Awesome from a public CDN, through its `latest` path rather than a fixed version |
| `minimum-backstretch`, `minimum-backstretch-set` | Landing template only | `js/backstretch.js` and `js/backstretch-set.js` |

The theme loads its translations from `languages/` under the `minimum` text domain, or from the folder a `child_theme_textdomain` filter returns.

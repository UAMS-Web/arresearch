<?php

// * Start the engine
include_once get_template_directory().'/lib/init.php';

// * Setup Theme
include_once get_stylesheet_directory().'/lib/theme-defaults.php';

// * Set Localization (do not remove)
load_child_theme_textdomain('minimum', apply_filters('child_theme_textdomain', get_stylesheet_directory().'/languages', 'minimum'));

// * Add Image upload and Color select to WordPress Theme Customizer
require_once get_stylesheet_directory().'/lib/customize.php';

// * Child theme (do not remove)
define('CHILD_THEME_NAME', __('Minimum Pro Theme', 'minimum'));
define('CHILD_THEME_URL', 'http://my.studiopress.com/themes/minimum/');
define('CHILD_THEME_VERSION', '3.2.1');

// * Add HTML5 markup structure
add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

// * Add viewport meta tag for mobile browsers
add_theme_support('genesis-responsive-viewport');

// * Enqueue scripts
add_action('wp_enqueue_scripts', 'minimum_enqueue_scripts');
function minimum_enqueue_scripts(): void
{

    wp_enqueue_script('minimum-responsive-menu', get_bloginfo('stylesheet_directory').'/js/responsive-menu.js', ['jquery'], '1.0.0');
    wp_enqueue_style('dashicons');
    wp_enqueue_style('minimum-google-fonts', '//fonts.googleapis.com/css?family=Raleway:400,600|Lato:400', [], CHILD_THEME_VERSION);
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');

}

// * Add new image sizes
add_image_size('portfolio', 540, 340, true);

// * Add support for custom header
add_theme_support('custom-header', [
    'width' => 320,
    'height' => 120,
    'header-selector' => '.site-title a',
    'header-text' => false,
]);

// * Add support for structural wraps
add_theme_support('genesis-structural-wraps', [
    'header',
    // 'site-tagline',
    'nav',
    'subnav',
    'home-featured',
    'site-inner',
    'footer-widgets',
    'footer',
]);

// * Unregister layout settings
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-content-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');

// * Unregister secondary sidebar
unregister_sidebar('sidebar-alt');

// * Remove site description
remove_action('genesis_site_description', 'genesis_seo_site_description');

// * Rename primary and secondary navigation menus
add_theme_support('genesis-menus', ['primary' => __('After Header Menu', 'minimum'), 'secondary' => __('Homepage Menu', 'minimum'), 'footer' => __('Footer Menu', 'minimum')]);

// * Reposition the primary navigation menu
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_after_header', 'genesis_do_nav', 15);

// * Reposition the secondary navigation menu
remove_action('genesis_after_header', 'genesis_do_subnav');
// add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

// * Reduce the secondary navigation menu to one level depth
add_filter('wp_nav_menu_args', 'minimum_secondary_menu_args');
function minimum_secondary_menu_args($args)
{

    // Equivalent mutant: WordPress always passes theme_location as a string, where loose and strict comparison agree.
    // @pest-mutate-ignore: NotEqualToNotIdentical
    if ($args['theme_location'] != 'secondary') {
        return $args;
    }

    $args['depth'] = 1;

    return $args;

}

add_action('get_header', 'child_remove_genesis_do_nav');
function child_remove_genesis_do_nav(): void
{
    if (is_front_page()) {
        remove_action('genesis_after_header', 'genesis_do_nav', 15);
    }
}

// * Add the site tagline section
/*
add_action( 'genesis_after_header', 'minimum_site_tagline' );
function minimum_site_tagline() {

    printf( '<div %s>', genesis_attr( 'site-tagline' ) );
    genesis_structural_wrap( 'site-tagline' );

        printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
        printf( '<p %s>%s</p>', genesis_attr( 'site-description' ), esc_html( get_bloginfo( 'description' ) ) );
        echo '</div>';

        printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
        genesis_widget_area( 'site-tagline-right' );
        echo '</div>';

    genesis_structural_wrap( 'site-tagline', 'close' );
    echo '</div>';

}
*/

// * Modify the size of the Gravatar in the author box
add_filter('genesis_author_box_gravatar_size', 'minimum_author_box_gravatar');
function minimum_author_box_gravatar($size): int
{

    return 144;

}

// * Modify the size of the Gravatar in the entry comments
add_filter('genesis_comment_list_args', 'minimum_comments_gravatar');
function minimum_comments_gravatar($args)
{

    $args['avatar_size'] = 96;

    return $args;

}

// * Add support for 3-column footer widgets
add_theme_support('genesis-footer-widgets', 3);

// * Add support for after entry widget
add_theme_support('genesis-after-entry-widget-area');

// * Relocate after entry widget
remove_action('genesis_after_entry', 'genesis_after_entry_widget_area');
add_action('genesis_after_entry', 'genesis_after_entry_widget_area', 5);

// * Register widget areas
genesis_register_sidebar([
    'id' => 'base-sidebar',
    'name' => __('Base Sidebar', 'minimum'),
    'description' => __('This is a base sidebar above the primary.', 'minimum'),
    'before_widget' => '<div id="%1$s"><div class="widget %2$s">',
    'after_widget' => "</div></div>\n",
    'before_title' => '<h4><span>',
    'after_title' => "</span></h4>\n",
]);
// Home page widget areas
/*
genesis_register_sidebar( array(
    'id'          => 'site-tagline-right',
    'name'        => __( 'Site Tagline Right', 'minimum' ),
    'description' => __( 'This is the site tagline right section.', 'minimum' ),
) );
*/
genesis_register_sidebar([
    'id' => 'home-featured-1',
    'name' => __('Home Featured 1', 'minimum'),
    'description' => __('This is the home featured 1 section.', 'minimum'),
]);
genesis_register_sidebar([
    'id' => 'home-featured-2',
    'name' => __('Home Featured 2', 'minimum'),
    'description' => __('This is the home featured 2 section.', 'minimum'),
]);
genesis_register_sidebar([
    'id' => 'home-featured-3',
    'name' => __('Home Featured 3', 'minimum'),
    'description' => __('This is the home featured 3 section.', 'minimum'),
]);
genesis_register_sidebar([
    'id' => 'home-featured-4',
    'name' => __('Home Featured 4', 'minimum'),
    'description' => __('This is the home featured 4 section.', 'minimum'),
]);

add_action('genesis_before_sidebar_widget_area', 'wpsites_widget_before_secondary_sidebar');

function wpsites_widget_before_secondary_sidebar(): void
{

    echo '<div class="base-sidebar">';
    dynamic_sidebar('base-sidebar');
    echo '</div>';

}

// Add class to primary sidebar
/*
add_filter( 'genesis_attr_sidebar-primary', 'tm_attr_sidebar_primary' );
function tm_attr_sidebar_primary( $attributes ) {
    $attributes['class'] = $attributes['class'] . ' primary';
    return $attributes;
}
*/

// * Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter($creds): string
{
    return 'ARresearch.org is supported by the Translational Research Institute, grant 1U54TR001629-01A1, through the National Institutes of Health,<br/>National Center for Advancing Translational Sciences, UAMS, and the UAMS Chancellor’s Circle.<br/></p><p><br/>Copyright [footer_copyright]  UAMS Translational Research Institute | All Rights Reserved &middot; [footer_loginout]';
}

// Display Footer Navigation Menu above footer content
add_action('genesis_after_footer', 'tm_do_footernav', 5);
/**
 * Echo the "Footer Navigation" menu.
 *
 * @uses genesis_nav_menu() Display a navigation menu.
 * @uses genesis_nav_menu_supported() Checks for support of specific nav menu.
 */
function tm_do_footernav(): void
{

    // * Do nothing if menu not supported
    if (! genesis_nav_menu_supported('footer')) {
        return;
    }

    $class = 'menu genesis-nav-menu menu-footer';

    genesis_nav_menu([
        'theme_location' => 'footer',
        'menu_class' => $class,
    ]);

}

add_action('genesis_after', 'tm_uams_logo');
function tm_uams_logo(): void
{
    echo '<div class="uams-logo"><a href="http://www.uams.edu"><img src="https://arresearch.org/wp-content/uploads/2017/06/UAMS_Logo.png" width="250" height="70" alt="University of Arkansas for Medical Sciences (UAMS)"></a></div>';
}

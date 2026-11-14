<?php

declare(strict_types=1);

// Remove 'site-inner' from structural wrap
add_theme_support('genesis-structural-wraps', ['header', 'site-inner', 'footer-widgets', 'footer']);

// * Add widget support for homepage if widgets are being used
add_action('genesis_meta', 'minimum_front_page_genesis_meta');
function minimum_front_page_genesis_meta(): void
{

    if (is_home()) {

        // * Remove entry meta in entry footer and Genesis loop
        remove_action('genesis_loop', 'genesis_do_loop');

        // * Add Genesis grid loop
        add_action('genesis_loop', 'minimum_grid_loop_helper');

        // * Remove entry footer functions
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
        remove_action('genesis_entry_footer', 'genesis_post_meta');
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);

        // * Force full width content layout
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    }

    if (is_active_sidebar('home-featured-1') || is_active_sidebar('home-featured-2') || is_active_sidebar('home-featured-3') || is_active_sidebar('home-featured-4')) {

        // * Add Home featured Widget areas
        add_action('genesis_after_content_sidebar_wrap', 'minimum_home_featured', 15);

    }
}

// Add image before content
add_action('genesis_before_entry_content', 'full_image_before_entry_content');
function full_image_before_entry_content(): void
{

    $content = '<div class="full-image"><div id="image-inner"><div id="volunteer-count"><h3>Volunteers to date: <span class="volunteer-count">'.number_format((float) get_field('volunteer_number')).'</span></h3></div><div id="inner-logo"><img src="https://arresearch.org/wp-content/uploads/sites/15/2017/06/ARresearch-logo-lrg.png" alt="ARresearch.org logo" /></div><div id="inner-cta" class="fade-in"><a class="cta-button" href="http://ncsdvs.uams.edu/ArResearch"><i class="fa fa-pencil-square-o"></i> Join Our Registry</a><h3>Be part of the answer, Arkansas!</h3></div></div></div>';

    echo $content;
}

remove_action('genesis_after_header', 'genesis_do_subnav');
add_action('genesis_after_entry', 'genesis_do_subnav', 15);

// Add custom opening div for post title
add_action('genesis_after_entry', 'sk_do_post_title_before', 7);
function sk_do_post_title_before(): void
{
    echo '<div class="wrap">';
}

// Add custom closing div for post title
add_action('genesis_after_entry', 'sk_do_post_title_after', 20);
function sk_do_post_title_after(): void
{
    echo '</div>';
}

// * Remove the entry header markup (requires HTML5 theme support)
remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
// * Remove the entry title (requires HTML5 theme support)
remove_action('genesis_entry_header', 'genesis_do_post_title');
// * Remove the post content (requires HTML5 theme support)
remove_action('genesis_entry_content', 'genesis_do_post_content');

// Remove div.site-inner's div.wrap
add_filter('genesis_structural_wrap-site-inner', '__return_empty_string');

// * Add markup for homepage widgets
function minimum_home_featured(): void
{

    printf('<div class="wrap"><div %s>', genesis_attr('home-featured'));
    genesis_structural_wrap('home-featured');

    genesis_widget_area('home-featured-1', [
        'before' => '<div class="home-featured-1 widget-area">',
        'after' => '</div>',
    ]);

    genesis_widget_area('home-featured-2', [
        'before' => '<div class="home-featured-2 widget-area">',
        'after' => '</div>',
    ]);

    genesis_widget_area('home-featured-3', [
        'before' => '<div class="home-featured-3 widget-area">',
        'after' => '</div>',
    ]);

    genesis_widget_area('home-featured-4', [
        'before' => '<div class="home-featured-4 widget-area">',
        'after' => '</div>',
    ]);

    genesis_structural_wrap('home-featured', 'close');
    echo '</div></div>'; // * end .home-featured

}

// * Genesis grid loop
function minimum_grid_loop_helper(): void
{

    if (function_exists('genesis_grid_loop')) {

        genesis_grid_loop([
            'features' => 0,
            'feature_image_size' => 0,
            'feature_content_limit' => 0,
            'grid_image_size' => 0,
            'grid_content_limit' => 250,
            'more' => __('[Read more]', 'minimum'),
        ]);

    } else {

        genesis_standard_loop();

    }

}

// * Run the Genesis loop
genesis();

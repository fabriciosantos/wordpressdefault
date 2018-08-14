<?php
/*
Plugin Name: GeoDirectory - Porto Theme Compatibility
Plugin URI: http://themeforest.net/user/sw-themes
Description: This plugin lets the GeoDirectory Plugin use the Porto theme HTML wrappers to fit and work perfectly.
Version: 1.0.0
Author: SW-THEMES
Author URI: http://themeforest.net/user/sw-themes
*/

// BECAUSE THIS PLUGIN IS CALLED BEFORE GD WE MUST CALL THIS PLUGIN ONCE GD LOADS
add_action( 'plugins_loaded', 'porto_action_calls', 10 );
function porto_action_calls() {

    /* ACTIONS
	****************************************************************************************/
	// LOAD STYLESHEET
	add_action( 'wp_enqueue_scripts', 'wpgeo_porto_styles' );
	
	// ADD BODY CLASS
	add_filter('body_class','wpgeo_porto_body_class');

    // REMOVE BREADCRUMB
    remove_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_home_before_main_content', 'geodir_breadcrumb', 20);
    remove_action('geodir_location_before_main_content', 'geodir_breadcrumb', 20);

    // REMOVE PAGE TITLES
    remove_action('geodir_listings_page_title', 'geodir_action_listings_title', 10);
    remove_action('geodir_add_listing_page_title', 'geodir_action_add_listing_page_title', 10);
    remove_action('geodir_details_main_content', 'geodir_action_page_title', 20);
    remove_action('geodir_search_page_title', 'geodir_action_search_page_title', 10);
    remove_action('geodir_author_page_title', 'geodir_action_author_page_title', 10);

    // MAKE TOP SECTION WIDE
    remove_action('geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10);
    remove_action('geodir_author_before_main_content', 'geodir_action_geodir_sidebar_author_top', 10);
    remove_action('geodir_search_before_main_content', 'geodir_action_geodir_sidebar_search_top', 10);
    remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_sidebar_detail_top', 10);
    remove_action('geodir_listings_before_main_content', 'geodir_action_geodir_sidebar_listings_top', 10);
    add_action('porto_before_main', 'wpgeo_porto_add_top_section_back', 10);

    /* FILTERS
	****************************************************************************************/
    // CHANGE PAGE LAYOUT
    add_filter('porto_meta_layout', 'wpgeo_porto_layout');

    // ADD CLASS IN TOP SECTION
    add_filter('geodir_full_page_class', 'geodir_porto_full_page_class', 10, 2);

    // CHANGE BREADCRUMB SEPARATOR
    add_filter('geodir_breadcrumb_separator', 'geodir_porto_change_breadcrumb_separator');

    // STRIP BREADCRUMB WRAPPERS
    add_filter('geodir_breadcrumb', 'geodir_porto_strip_breadcrumb_wrappers');

    // CHANGE BREADCRUMB
    add_filter('porto_breadcrumbs', 'geodir_porto_breadcrumb');

    // CHANGE PAGE TITLE
    add_filter('porto_page_title','geodir_porto_page_title');

} // Close geodir_porto_action_calls

/****************************************************************************************
 ** ACTIONS
 ****************************************************************************************/

// LOAD STYLESHEET
function wpgeo_porto_styles() {
    // Unregister font awesome
    wp_deregister_style('geodirectory-font-awesome');
    // Register the style like this for a plugin:
    wp_register_style( 'wpgeo-porto-style', plugins_url( '/css/plugin.css', __FILE__ ), array(), 'all' );
    wp_enqueue_style( 'wpgeo-porto-style' );
}

// ADD BODY CLASS
function wpgeo_porto_body_class($classes) {
	$classes[] = 'wpgeo-porto';
	return $classes;
}

// MAKE TOP SECTION WIDE
function wpgeo_porto_add_top_section_back()
{
    if (is_page_geodir_home() || geodir_is_page('home') || geodir_is_page('location')) {
        geodir_action_geodir_sidebar_home_top();
    } elseif (geodir_is_page('listing')) {
        geodir_action_geodir_sidebar_listings_top();
    } elseif (geodir_is_page('detail')) {
        geodir_action_geodir_sidebar_detail_top();
    } elseif (geodir_is_page('search')) {
        geodir_action_geodir_sidebar_search_top();
    } elseif (geodir_is_page('author')) {
        geodir_action_geodir_sidebar_author_top();
    }
}

/****************************************************************************************
 ** FILTERS
 ****************************************************************************************/

// CHANGE PORTO LAYOUT TO WIDEWIDTH OR FULLWIDTH
function wpgeo_porto_layout($layout) {

    $gd_pages = array('add-listing', 'preview', 'listing-success', 'detail', 'pt', 'listing', 'home', 'location', 'author', 'search', 'info', 'login', 'checkout', 'invoices');
    $is_gd_page = false;
    foreach ($gd_pages as $gd_page) {
        if (geodir_is_page($gd_page))
            $is_gd_page = true;
    }
    if (geodir_is_geodir_page() || $is_gd_page) {
        switch ($layout) {
            case 'wide-left-sidebar':
            case 'wide-right-sidebar':
                $layout = 'widewidth';
                break;
            case 'left-sidebar':
            case 'right-sidebar':
                $layout = 'fullwidth';
                break;
        }
    }

    return $layout;
}

// ADD CLASS IN TOP SECTION
function geodir_porto_full_page_class($class, $desc) {
    return $class . ' geo-porto-top-section';
}

// CHANGE BREADCRUMB SEPARATOR
function geodir_porto_change_breadcrumb_separator($separator) {
    $separator = '<i class="delimiter"></i>';
    return $separator;
}

// STRIP BREADCRUMB WRAPPERS
function geodir_porto_strip_breadcrumb_wrappers($breadcrumb) {
    $breadcrumb = str_replace(array("<li>","</li>"), "", $breadcrumb);
    $breadcrumb = str_replace('<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs">', '<ul class="breadcrumb"><li>', $breadcrumb);
    $breadcrumb = str_replace('</ul></div>', '</li></ul>', $breadcrumb);
    return $breadcrumb;
}

// CHANGE BREADCRUMB
function geodir_porto_breadcrumb($breadcrumbs) {
    if (geodir_is_geodir_page()) {
        return geodir_breadcrumb();
    }
    return $breadcrumbs;
}

// CHANGE PAGE TITLE
function geodir_porto_page_title($title) {
    if (geodir_is_geodir_page()) {
        $geodir_title = geodir_porto_current_page_title();
        if ($geodir_title)
            return $geodir_title;
    }
    return $title;
}

function geodir_porto_current_page_title() {
    $title = '';
    if (geodir_is_page('listing')) {
        ob_start(); // Start buffering;
        geodir_action_listings_title();
        $title = ob_get_clean();
    } else if (geodir_is_page('add-listing')) {
        ob_start(); // Start buffering;
        geodir_action_add_listing_page_title();
        $title = ob_get_clean();
    } else if (geodir_is_page('author')) {
        ob_start(); // Start buffering;
        geodir_action_author_page_title();
        $title = ob_get_clean();
    } else if (geodir_is_page('detail') || geodir_is_page('preview')) {
        $title = get_the_title();
    } else if (geodir_is_page('search')) {
        ob_start(); // Start buffering;
        geodir_action_search_page_title();
        $title = ob_get_clean();
    }
    return strip_tags($title);
}
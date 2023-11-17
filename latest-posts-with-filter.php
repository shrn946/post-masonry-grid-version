<?php
/*
Plugin Name: Post Masonry Grid wordpress
Description: A simple WordPress plugin to display the latest posts with category filtering.
Version: 1.0
Author: Hassan Naqvi
WP Design Lab
*/

// Include the file
include(plugin_dir_path(__FILE__) . 'includes/posts-three.php');

// Enqueue scripts and styles
function latest_posts_with_filter_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue Isotope
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js', array('jquery'), '3.0.6', true);
	
    // Enqueue your custom script
    wp_enqueue_script('latest-posts-with-filter', plugin_dir_url(__FILE__) . 'js/latest-posts-with-filter.js', array('isotope'), '1.0', true);

    // Enqueue styles
    wp_enqueue_style('latest-posts-with-filter-styles', plugin_dir_url(__FILE__) . 'css/latest-posts-with-filter.css');
}
add_action('wp_enqueue_scripts', 'latest_posts_with_filter_scripts');


















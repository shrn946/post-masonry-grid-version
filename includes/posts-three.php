<?php
// Post-Loop.php

function masonry_post_grid($atts) {
    ob_start();

    // Shortcode attributes
    $atts = shortcode_atts(
        array(
            'exclude_category' => '', // Comma-separated list of category slugs to exclude
        ),
        $atts,
        'masonry_post_grid'
    );

    // Exclude category parameter
    $exclude_category = explode(',', $atts['exclude_category']);

    // Output filter list
    ?>
  <ul class="filter">
    <li><a href="#all" data-filter="*" class="active">All</a></li>
    <?php
    $categories = get_categories(array('exclude' => $exclude_category));
    foreach ($categories as $category) :
        $category_slug = esc_attr(sanitize_title($category->name));
        ?>
        <li><a href="#<?php echo $category_slug; ?>" data-filter=".<?php echo $category_slug; ?>"><?php echo esc_html($category->name); ?></a></li>
    <?php endforeach; ?>
</ul>

    <div class="grid">
        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'category__not_in' => $exclude_category, // Exclude specified categories
        );

        $query = new WP_Query($args);

        // Get the fallback image URL from the plugin directory
        $fallback_image_url = plugins_url('../img/fallback-img.jpg', __FILE__);

        // Counter variable to track post index
        $post_index = 0;

        while ($query->have_posts()) : $query->the_post();
            $categories = get_the_category(); // Get post categories

            // Get the featured image URL
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

            // Fallback to default image if no featured image is set
            $background_image = $featured_image_url ? $featured_image_url : $fallback_image_url;

            // Increment the post index
            $post_index++;

            // Determine the CSS classes for the grid items
            $grid_item_classes = 'grid-item loading'; // Added 'loading' class
            if ($post_index === 2) {
                $grid_item_classes .= ' grid-item--width2';
            } elseif ($post_index === 3) {
                $grid_item_classes .= ' grid-item--width3';
            }
        ?>

            <div class="<?php echo esc_attr($grid_item_classes); ?> <?php foreach ($categories as $category) {
                                                                    echo esc_attr($category->slug) . ' ';
                                                                } ?>">

                <div class="card__cover card__cover--one">
    <?php if ($featured_image_url) : ?>
        <img src="<?php echo esc_url($background_image); ?>" class="mask">
    <?php else : ?>
        <!-- Fallback image if no featured image -->
        <img src="<?php echo esc_url($fallback_image_url); ?>" class="mask">
    <?php endif; ?>
    
    <div class="date-box">
        <div class="day"><?php echo date('D'); ?></div>
        <div class="date"><?php echo date('M'); ?></div>
    </div>

    <div class="card__tag"><?php echo esc_html($categories[0]->name); ?></div>
</div>

                <div class="card__body">
                    <div class="card__title"><a style="text-decoration:none; "href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></div>
                    <div class="card__desc">
                        <?php
                        // Display maximum of 15 words
                        echo wp_trim_words(get_the_content(), 15);
                        ?>
                    </div>
                </div>

            </div>

        <?php endwhile;

        wp_reset_postdata();
        ?>
    </div>

    <script>
        // Add a script to remove the 'loading' class after the content is loaded
        jQuery(document).ready(function ($) {
            $('.grid-item').removeClass('loading');
        });
    </script>

    <?php
    return ob_get_clean();
}

// Create a new shortcode to use the function in posts or pages
add_shortcode('masonry_post_grid', 'masonry_post_grid');

// Shortcode to show all posts without any category filters
function show_all_posts_shortcode() {
    ob_start();
    echo do_shortcode('[masonry_post_grid exclude_category=""]');
    return ob_get_clean();
}

// Create a new shortcode for showing all posts
add_shortcode('show_all_posts', 'show_all_posts_shortcode');


// Add a settings link on the Plugins page
function masonry_grid_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=masonry-grid-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'masonry_grid_settings_link');

// Add settings page under the "Settings" tab
function masonry_grid_settings_page() {
    ?>
<div class="wrap" style="font-size:20px;">
    <h1>Masonry Grid Settings</h1>
    <p>Welcome to the settings page for the Masonry Grid plugin.</p>
    <h2>How to Use Shortcode</h2>
    <p style="font-size:20px; font-weight:bold;">To showcase the Masonry Grid on your site, utilize the following shortcode:</p>
    <pre>Use this shortcode to display all posts: [masonry_post_grid]</pre>
    <pre>Customize the shortcode with the 'exclude_category' attribute: [masonry_post_grid exclude_category="category-id,category-id"]</pre>
    
    <div class="video-link">
    <h3>Video Tutorial</h3>
    
    <iframe width="560" height="315" src="https://www.youtube.com/embed/1ZmUUkivfwg?si=fVdtF579rpRE-nY9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    
    </div>
    
</div>
    <?php
}

function masonry_grid_menu() {
    add_options_page(
        'Masonry Grid Settings',
        'Masonry Grid',
        'manage_options',
        'masonry-grid-settings',
        'masonry_grid_settings_page'
    );
}

add_action('admin_menu', 'masonry_grid_menu');

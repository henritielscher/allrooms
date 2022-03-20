<?php


require get_theme_file_path("/includes/custom-functions.php");
require get_theme_file_path("/includes/artists-route.php");
require get_theme_file_path("/includes/cleanup-wordpress.php");




// Include Scripts and Styles

function allrooms_files()
{
    wp_enqueue_style("swiper", "//unpkg.com/swiper/swiper-bundle.min.css");
    wp_enqueue_style("data-tables", "https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.25/r-2.2.9/datatables.min.css");
    wp_enqueue_style("font-awesome", "//use.fontawesome.com/releases/v5.15.3/css/all.css");

    if (is_front_page() || is_post_type_archive("news") || is_singular("artist") || is_post_type_archive("tour")) {
        wp_enqueue_script("swiper-bundle", "//unpkg.com/swiper/swiper-bundle.min.js", NULL, NULL, true);
        wp_enqueue_script("jquery-datatables", "https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.25/r-2.2.9/datatables.min.js", array("jquery"), NULL, true);
    }

    if (strstr($_SERVER['SERVER_NAME'], "allrooms.local")) {
        wp_enqueue_script("main-scripts", "http://localhost:3000/bundled.js", NULL, "1.0", true);
    } else {
        // wp_enqueue_style("main-style-sheet", get_theme_file_uri("/public/styles.css"), NULL, NULL, false);
        // wp_enqueue_script("main-script", get_theme_file_uri("/public/scripts.js"), null, null, true);
        getHashedFileNames();
    }

    // wp_enqueue_script("main-script", get_theme_file_uri("/public/scripts.js"), NULL, NULL, true);

    // Erstellt Skript im Frontend
    wp_localize_script("main-scripts", "urlInfo", [
        "root_url" => get_site_url(),
        "theme_url" => get_theme_file_uri()
    ]);
}

add_action("wp_enqueue_scripts", "allrooms_files");

function allrooms_features()
{
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_image_size("superlazy", 1, 1, true);
    add_image_size("lazyload", 10, 10, true);
    add_image_size("landingNews", 990, 600, true);
    add_image_size("newsPage", 500, 303, true);
    add_image_size("landingArtist", 450, 450, true);
    add_image_size("previewArtist", 400, 400, true);
    add_image_size("adminPreview", 100, 100, true);
    add_image_size("heroBannerLarge", 1900, 520, true);
    add_image_size("heroBannerSmall", 800, 250, true);
};

add_action("after_setup_theme", "allrooms_features");



function adjustQueries($query)
{
    if ((!is_admin() && is_post_type_archive("show"))) {
        $today = date("Ymd");
        $query->set("meta_key", "date_from");
        $query->set("orderby", "meta_value_num");
        $query->set("order", "ASC");
        $query->set(
            "meta_query",
            [
                [
                    "key" => "date_from",
                    "compare" => ">=",
                    "value" => $today,
                    "type" => "numeric"
                ]
            ]
        );
    };

    if (!is_admin() && $query->is_main_query() && is_archive("news")) {
        // $query->set("paged", 1);
        $query->set("posts_per_page", 5);
    }

    if (!is_admin() && $query->is_main_query() && is_archive("tour")) {
        $query->set("posts_per_page", -1);
    }
};

add_action("pre_get_posts", "adjustQueries");


// Remove Default Image Sizes
add_filter('intermediate_image_sizes_advanced', 'prefix_remove_default_images');
// This will remove the default image sizes and the medium_large size. 
function prefix_remove_default_images($sizes)
{
    unset($sizes['small']); // 150px
    unset($sizes['medium']); // 300px
    unset($sizes['large']); // 1024px
    unset($sizes['medium_large']); // 768px
    return $sizes;
}

// Include Favicon into WP-Header
function blog_favicon()
{ ?>
    <link rel="shortcut icon" href="<?= get_theme_file_uri("/public/assets/favicon.png") ?>">
<?php }

add_action('wp_head', 'blog_favicon');

// All In One Migrating Exclusion of specific Folders and Files
add_filter("ai1wm_exclude_content_from_export", "ignoreCertainFiles");

function ignoreCertainFiles($exclude_filters)
{
    $exclude_filters[] = "themes/allrooms-theme/node_modules";
    $exclude_filters[] = "themes/allrooms-theme/src";
    $exclude_filters[] = "themes/allrooms-theme/.browserslistrc";
    $exclude_filters[] = "themes/allrooms-theme/tailwind.config.js";
    $exclude_filters[] = "themes/allrooms-theme/postcss.config.js";
    $exclude_filters[] = "themes/allrooms-theme/webpack.config.js";
    $exclude_filters[] = "themes/allrooms-theme/package.json";
    $exclude_filters[] = "themes/allrooms-theme/package-lock.json";
    return $exclude_filters;
}

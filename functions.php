<?php
/**
 * Kelvin Physio Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage kelvin-physio-theme
 * @since kelvin-physio-theme 1.0
 */
// Asset Enqueues
function kpc_enqueue_assets() {
    // Main CSS Styles
    wp_enqueue_style('kpc-theme-toggle', get_template_directory_uri() . '/assets/css/theme-toggle.css', [], '1.1');
    wp_enqueue_style('kpc-carousel-style', get_template_directory_uri() . '/assets/css/carousel.css', [], '1.0');

    // Scripts
    wp_enqueue_script('heroCarouselJs', get_template_directory_uri() . '/assets/js/carousel.js', [], null, true);
    wp_enqueue_script('theme-toggle-script', get_template_directory_uri() . '/assets/js/theme-toggle/theme-toggle.js', [], '1.0', true);
}
add_action('wp_enqueue_scripts', 'kpc_enqueue_assets');

// Override CSS Styles
function kpc_enqueue_override() {
    wp_enqueue_style('ns-override', get_template_directory_uri() . '/assets/css/override.css', [], '1.1');
}
add_action('wp_enqueue_scripts', 'kpc_enqueue_override', 999);

function kpc_register_cpts() {
    // Staff
    register_post_type('staff', [
        'labels' => ['name' => 'Staff',],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'staff-archive'],
        'supports' => ['title','editor','thumbnail'],
        'menu_position' => 4,
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    // Services
    register_post_type('service', [
        'labels' => ['name' => 'Services','singular_name' => 'Service'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'services-archive'],
        'supports' => ['title','editor','thumbnail'],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'kpc_register_cpts');

// Custom Taxonomies
function kpc_register_taxonomies() {
    // service type
    register_taxonomy('service_category', 'service', [
    'labels' => [
        'name' => 'Service Categories',
        'singular_name' => 'Service Category',
        'add_new_item' => 'Add New Service Category',
        'new_item_name' => 'New Service Category Name',
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'service-category'],
    'show_in_rest' => true,
]);

    // staff role
    register_taxonomy('staff_role', 'staff', [
    'labels' => [
        'name' => 'Staff Roles',
        'singular_name' => 'Staff Role',
        'add_new_item' => 'Add New Staff Role',
        'new_item_name' => 'New Staff Role Name',
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'staff-role'],
    'show_in_rest' => true,
]);

    // languages
    register_taxonomy('language', 'staff', [
    'labels' => [
        'name' => 'Languages',
        'singular_name' => 'Language',
        'add_new_item' => 'Add New Language',
        'new_item_name' => 'New Language Name',
    ],
    'hierarchical' => false,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'language'],
    'show_in_rest' => true,
]);

    // insurance type
    register_taxonomy('insurance_type', 'service', [
    'labels' => [
        'name' => 'Insurance Types',
        'singular_name' => 'Insurance Type',
        'add_new_item' => 'Add New Insurance Type',
        'new_item_name' => 'New Insurance Type Name',
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'insurance-type'],
    'show_in_rest' => true,
]);
}
add_action('init', 'kpc_register_taxonomies');

// Shortcode carousel
function heroCarouselShortcode() {
    $images = get_field('carousel_images', get_the_ID());
    if (!$images) return '<p>No images found.</p>';

    ob_start(); ?>
    <div class="hero-carousel">
        <?php foreach ($images as $img): ?>
            <div class="carousel-slide">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($img['ID'],'bg')); ?>"
                     alt="<?php echo esc_attr($img['alt']); ?>" />
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hero_carousel','heroCarouselShortcode');

// Custom Image Sizes
add_theme_support('post-thumbnails');
function kpc_custom_image_sizes() {
    add_image_size('small',150,150,true);
    add_image_size('medium',300,300,true);
    add_image_size('large',600,600,true);
    add_image_size('bg',1920,1080,true);
    add_image_size('gallery',960,960,true);

    add_filter('image_size_names_choose', function($sizes){
        return array_merge($sizes,[
            'small'=>'kpc Small',
            'medium'=>'kpc Medium',
            'large'=>'kpc Large',
            'bg'=>'kpc Background',
            'gallery'=>'kpc Gallery Photo',
        ]);
    });
}
add_action('after_setup_theme','kpc_custom_image_sizes');
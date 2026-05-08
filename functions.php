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
function kpc_enqueue_assets()
{
    // Main CSS Styles
    wp_enqueue_style('kpc-normalize', get_template_directory_uri() . '/assets/css/kpcNormalize.css', [], '1.0');
    wp_enqueue_style('kpc-theme-toggle', get_template_directory_uri() . '/assets/css/theme-toggle.css', [], '1.1');
    wp_enqueue_style('kpc-carousel-style', get_template_directory_uri() . '/assets/css/carousel.css', [], '1.0');
    wp_enqueue_style('mobile-menu-css', get_template_directory_uri() . '/assets/css/mobileNav.css', array(), '1.0.0');
    wp_enqueue_style('kpc-scroll', get_stylesheet_directory_uri() . '/assets/css/kpcScroll.css', [], '1.0.0');
    wp_enqueue_style('kpc-background', get_stylesheet_directory_uri() . '/assets/css/kpcWaveBackground.css', [], '1.0.0');

    // Scripts
    wp_enqueue_script('heroCarouselJs', get_template_directory_uri() . '/assets/js/carousel.js', [], null, true);
    wp_enqueue_script('theme-toggle-script', get_template_directory_uri() . '/assets/js/theme-toggle.js', [], '1.0', true);
    wp_enqueue_script('mobile-menu-js', get_template_directory_uri() . '/assets/js/mobileNav.js', array(), '1.0.0', true);
    wp_enqueue_script('kpc-scroll-js', get_template_directory_uri() . '/assets/js/kpcScroll.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'kpc_enqueue_assets');

// Override CSS Styles
function kpc_enqueue_override()
{
    wp_enqueue_style('kpc-override', get_template_directory_uri() . '/assets/css/override.css', [], '1.2');
}
add_action('wp_enqueue_scripts', 'kpc_enqueue_override', 9999);

// ========================
// CPT and Custom Taxonomies
// ========================
function kpc_register_cpts()
{
    // Staff
    register_post_type('staff', [
        'labels' => ['name' => 'Staff',],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'staff-archive'],
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_position' => 4,
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    // Services
    register_post_type('service', [
        'labels' => ['name' => 'Services', 'singular_name' => 'Service'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'services-archive'],
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'kpc_register_cpts');

// Custom Taxonomies
function kpc_register_taxonomies()
{
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

// ========================
// Shortcode
// ========================
// Shortcode: carousel
function heroCarouselShortcode()
{
    $images = get_field('carousel_images', get_the_ID());
    if (!$images)
        return '<p>No images found.</p>';

    ob_start(); ?>
    <div class="hero-carousel">
        <?php foreach ($images as $img): ?>
            <div class="carousel-slide">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($img['ID'], 'bg')); ?>"
                    alt="<?php echo esc_attr($img['alt']); ?>" />
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hero_carousel', 'heroCarouselShortcode');

// Shortcode: Post Carousel
function kpc_enqueue_post_carousel()
{
    wp_enqueue_style(
        'post-gallery-css',
        get_template_directory_uri() . '/assets/css/postGallery.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'post-carousel-js',
        get_template_directory_uri() . '/assets/js/postGallery.js',
        array(),
        '1.0.0',
        true
    );

    // this lets the js data from postCarousel.js be read by wordpress
    wp_localize_script('post-carousel-js', 'postCarouselData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('post_carousel_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'kpc_enqueue_post_carousel');

// Shortcode Enqueue
function post_carousel_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'posts' => 4,
        'category' => '',
    ), $atts);

    return '<div class="post-carousel-container" data-posts="' . esc_attr($atts['posts']) . '" data-category="' . esc_attr($atts['category']) . '"></div>';
}
add_shortcode('post_carousel', 'post_carousel_shortcode');

// Shortcode: Theme Toggle
function themeToggleShortcode()
{
    return '<button id="theme-toggle-btn" aria-label="Toggle Theme" class="toggleBtn"></button>';
}
add_shortcode('theme_toggle', 'themeToggleShortcode');

// Shortcode: background waves regular and inverted
function kpc_wave_background_shortcode() {
    return '
    <div class="kpc-background-wrapper">
        <div class="kpc-wave"></div>
        <div class="kpc-wave"></div>
        <div class="kpc-wave"></div>
    </div>';
}
add_shortcode('kpc_wave_background', 'kpc_wave_background_shortcode');

// 
function kpc_inverted_wave_background_shortcode() {
    return '
    <div class="kpc-background-wrapper">
        <div class="kpc-wave-inverted"></div>
        <div class="kpc-wave-inverted"></div>
        <div class="kpc-wave-inverted"></div>
    </div>';
}
add_shortcode('kpc_inverted_wave_background', 'kpc_inverted_wave_background_shortcode');
function kpc_scroll_shortcode($atts)
{
    $atts = shortcode_atts([
        'sections' => '',
    ], $atts, 'kpc_scroll_nav');

    if (empty($atts['sections']))
        return '';

    $ids = array_map('sanitize_html_class', explode(',', $atts['sections']));
    $count = count($ids);

    $items = '';
    foreach ($ids as $id) {
        $label = ucwords(str_replace(['-', '_'], ' ', $id));
        $items .= '<li data-label="' . esc_attr($label) . '"><a href="#' . $id . '"><span class="sr">' . esc_html($label) . '</span></a></li>';
    }

    return '<nav class="kpc-indicator" style="--kpc-section-count: ' . $count . '"><ul>' . $items . '</ul></nav>';
}
add_shortcode('kpc_scroll', 'kpc_scroll_shortcode');

// ========================
// Images and Font
// ========================
// Custom Image Sizes
add_theme_support('post-thumbnails');
function kpc_custom_image_sizes()
{
    add_image_size('small', 150, 150, true);
    add_image_size('medium', 300, 300, true);
    add_image_size('large', 600, 600, true);
    add_image_size('bg', 1920, 1080, true);
    add_image_size('gallery', 960, 960, true);

    add_filter('image_size_names_choose', function ($sizes) {
        return array_merge($sizes, [
            'small' => 'kpc Small',
            'medium' => 'kpc Medium',
            'large' => 'kpc Large',
            'bg' => 'kpc Background',
            'gallery' => 'kpc Gallery Photo',
        ]);
    });
}
add_action('after_setup_theme', 'kpc_custom_image_sizes');

// Allows WOFF and TTF files
function kpc_font_uploads($mimes)
{
    $mimes['woff'] = 'font/woff';
    $mimes['woff2'] = 'font/woff2';
    $mimes['ttf'] = 'font/ttf';
    $mimes['otf'] = 'font/otf';
    return $mimes;
}
add_filter('upload_mimes', 'kpc_font_uploads');

// ========================
// Other
// ========================
// Handler for fetching posts. Used in post carousel
function get_carousel_posts()
{
    check_ajax_referer('post_carousel_nonce', 'nonce');

    $posts_count = isset($_POST['posts']) ? intval($_POST['posts']) : 4;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    $args = array(
        'posts_per_page' => $posts_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
    );

    if (!empty($category)) {
        $args['category_name'] = $category;
    }

    $posts_query = new WP_Query($args);
    $posts_data = array();

    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';

            $thumbnail_url = has_post_thumbnail()
                ? get_the_post_thumbnail_url(get_the_ID(), 'large')
                : get_template_directory_uri() . '/assets/images/placeholder.jpg';

            $posts_data[] = array(
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 25, '...'),
                'thumbnail' => $thumbnail_url,
                'category' => $category_name,
                'date' => get_the_date(),
                'author' => get_the_author(),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($posts_data);
}
add_action('wp_ajax_get_carousel_posts', 'get_carousel_posts');
add_action('wp_ajax_nopriv_get_carousel_posts', 'get_carousel_posts');

// For fallback images
add_filter('post_thumbnail_html', function ($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if (!empty($html))
        return $html;

    $fallback = get_stylesheet_directory_uri() . '/kelvin-physio/wp-content/uploads/2026/01/logo-rbg.png';

    $classes = is_array($attr) && !empty($attr['class']) ? esc_attr($attr['class']) : '';
    $alt = esc_attr(get_the_title($post_id));

    return '<img src="' . esc_url($fallback) . '" alt="' . $alt . '" class="' . $classes . '" loading="lazy" decoding="async" />';
}, 10, 5);

// For mobile stylings for header
function add_viewport_fit_cover() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">' . "\n";
}
add_action('wp_head', 'add_viewport_fit_cover', 1);
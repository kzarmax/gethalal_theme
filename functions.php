<?php
/**
 * Functions and definitions
 *
 *
 * @package Get Halal
 */

// This theme requires WordPress 5.3 or later.
if (version_compare($GLOBALS['wp_version'], '5.3', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('gethalal_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     * @return void
     * @since Get Halal 1.0
     *
     */
    function gethalal_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Get Halal, use a find and replace
         * to change 'gethalal' to the name of your theme in all the template files.
         */
        load_child_theme_textdomain('gethalal', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * This theme does not use a hard-coded <title> tag in the document head,
         * WordPress will provide it for us.
         */
        add_theme_support('title-tag');

        /**
         * Add post-formats support.
         */
        add_theme_support(
            'post-formats',
            array(
                'link',
                'aside',
                'gallery',
                'image',
                'quote',
                'status',
                'video',
                'audio',
                'chat',
            )
        );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1568, 9999);

        register_nav_menus(
            array(
                'primary' => esc_html__('Primary menu', 'gethalal'),
                'footer' => __('Secondary menu', 'gethalal'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
                'navigation-widgets',
            )
        );

        /*
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        $logo_width = 95;
        $logo_height = 76;

        add_theme_support(
            'custom-logo',
            array(
                'height' => $logo_height,
                'width' => $logo_width,
                'flex-width' => true,
                'flex-height' => true,
                'unlink-homepage-logo' => true,
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for Block Styles.
        add_theme_support('wp-block-styles');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // Add support for editor styles.
        add_theme_support('editor-styles');
        $background_color = get_theme_mod('background_color', 'D1E4DD');
        if (127 > Gethalal_Custom_Colors::get_relative_luminance_from_hex($background_color)) {
            add_theme_support('dark-editor-style');
        }

        $editor_stylesheet_path = './assets/css/style-editor.css';

        // Note, the is_IE global variable is defined by WordPress and is used
        // to detect if the current browser is internet explorer.
        global $is_IE;
        if ($is_IE) {
            $editor_stylesheet_path = './assets/css/ie-editor.css';
        }

        // Enqueue editor styles.
        add_editor_style($editor_stylesheet_path);

        // Add custom editor font sizes.
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name' => esc_html__('Extra small', 'gethalal'),
                    'shortName' => esc_html_x('XS', 'Font size', 'gethalal'),
                    'size' => 16,
                    'slug' => 'extra-small',
                ),
                array(
                    'name' => esc_html__('Small', 'gethalal'),
                    'shortName' => esc_html_x('S', 'Font size', 'gethalal'),
                    'size' => 18,
                    'slug' => 'small',
                ),
                array(
                    'name' => esc_html__('Normal', 'gethalal'),
                    'shortName' => esc_html_x('M', 'Font size', 'gethalal'),
                    'size' => 20,
                    'slug' => 'normal',
                ),
                array(
                    'name' => esc_html__('Large', 'gethalal'),
                    'shortName' => esc_html_x('L', 'Font size', 'gethalal'),
                    'size' => 24,
                    'slug' => 'large',
                ),
                array(
                    'name' => esc_html__('Extra large', 'gethalal'),
                    'shortName' => esc_html_x('XL', 'Font size', 'gethalal'),
                    'size' => 40,
                    'slug' => 'extra-large',
                ),
                array(
                    'name' => esc_html__('Huge', 'gethalal'),
                    'shortName' => esc_html_x('XXL', 'Font size', 'gethalal'),
                    'size' => 96,
                    'slug' => 'huge',
                ),
                array(
                    'name' => esc_html__('Gigantic', 'gethalal'),
                    'shortName' => esc_html_x('XXXL', 'Font size', 'gethalal'),
                    'size' => 144,
                    'slug' => 'gigantic',
                ),
            )
        );

        // Custom background color.
        add_theme_support(
            'custom-background',
            array(
                'default-color' => 'd1e4dd',
            )
        );

        // Editor color palette.
        $black = '#000000';
        $dark_gray = '#28303D';
        $gray = '#39414D';
        $green = '#D1E4DD';
        $blue = '#D1DFE4';
        $purple = '#D1D1E4';
        $red = '#E4D1D1';
        $orange = '#E4DAD1';
        $yellow = '#EEEADD';
        $white = '#FFFFFF';

        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name' => esc_html__('Black', 'gethalal'),
                    'slug' => 'black',
                    'color' => $black,
                ),
                array(
                    'name' => esc_html__('Dark gray', 'gethalal'),
                    'slug' => 'dark-gray',
                    'color' => $dark_gray,
                ),
                array(
                    'name' => esc_html__('Gray', 'gethalal'),
                    'slug' => 'gray',
                    'color' => $gray,
                ),
                array(
                    'name' => esc_html__('Green', 'gethalal'),
                    'slug' => 'green',
                    'color' => $green,
                ),
                array(
                    'name' => esc_html__('Blue', 'gethalal'),
                    'slug' => 'blue',
                    'color' => $blue,
                ),
                array(
                    'name' => esc_html__('Purple', 'gethalal'),
                    'slug' => 'purple',
                    'color' => $purple,
                ),
                array(
                    'name' => esc_html__('Red', 'gethalal'),
                    'slug' => 'red',
                    'color' => $red,
                ),
                array(
                    'name' => esc_html__('Orange', 'gethalal'),
                    'slug' => 'orange',
                    'color' => $orange,
                ),
                array(
                    'name' => esc_html__('Yellow', 'gethalal'),
                    'slug' => 'yellow',
                    'color' => $yellow,
                ),
                array(
                    'name' => esc_html__('White', 'gethalal'),
                    'slug' => 'white',
                    'color' => $white,
                ),
            )
        );

        add_theme_support(
            'editor-gradient-presets',
            array(
                array(
                    'name' => esc_html__('Purple to yellow', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $yellow . ' 100%)',
                    'slug' => 'purple-to-yellow',
                ),
                array(
                    'name' => esc_html__('Yellow to purple', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $purple . ' 100%)',
                    'slug' => 'yellow-to-purple',
                ),
                array(
                    'name' => esc_html__('Green to yellow', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $green . ' 0%, ' . $yellow . ' 100%)',
                    'slug' => 'green-to-yellow',
                ),
                array(
                    'name' => esc_html__('Yellow to green', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $green . ' 100%)',
                    'slug' => 'yellow-to-green',
                ),
                array(
                    'name' => esc_html__('Red to yellow', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $yellow . ' 100%)',
                    'slug' => 'red-to-yellow',
                ),
                array(
                    'name' => esc_html__('Yellow to red', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $red . ' 100%)',
                    'slug' => 'yellow-to-red',
                ),
                array(
                    'name' => esc_html__('Purple to red', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $red . ' 100%)',
                    'slug' => 'purple-to-red',
                ),
                array(
                    'name' => esc_html__('Red to purple', 'gethalal'),
                    'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $purple . ' 100%)',
                    'slug' => 'red-to-purple',
                ),
            )
        );

        /*
        * Adds starter content to highlight the theme on fresh sites.
        * This is done conditionally to avoid loading the starter content on every
        * page load, as it is a one-off operation only needed once in the customizer.
        */
        if (is_customize_preview()) {
            require get_template_directory() . '/inc/starter-content.php';
            add_theme_support('starter-content', gethalal_get_starter_content());
        }

        // Add support for responsive embedded content.
        add_theme_support('responsive-embeds');

        // Add support for custom line height controls.
        add_theme_support('custom-line-height');

        // Add support for experimental link color control.
        add_theme_support('experimental-link-color');

        // Add support for experimental cover block spacing.
        add_theme_support('custom-spacing');

        // Add support for custom units.
        // This was removed in WordPress 5.6 but is still required to properly support WP 5.5.
        add_theme_support('custom-units');

        // Add support for woocommerce.
        add_theme_support('woocommerce');
    }
}
add_action('after_setup_theme', 'gethalal_setup');

/**
 * Register widget area.
 *
 * @return void
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @since Get Halal 1.0
 *
 */
function gethalal_widgets_init()
{

    register_sidebar(
        array(
            'name' => esc_html__('Footer', 'gethalal'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your footer.', 'gethalal'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'gethalal_widgets_init');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @return void
 * @global int $content_width Content width.
 *
 * @since Get Halal 1.0
 *
 */
function gethalal_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('gethalal_content_width', 1110);
}

add_action('after_setup_theme', 'gethalal_content_width', 0);

/**
 * Enqueue scripts and styles.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_scripts()
{
    // Note, the is_IE global variable is defined by WordPress and is used
    // to detect if the current browser is internet explorer.
    global $is_IE, $wp_scripts;
    if ($is_IE) {
        // If IE 11 or below, use a flattened stylesheet with static values replacing CSS Variables.
        wp_enqueue_style('gethalal-style', get_template_directory_uri() . '/assets/css/ie.css', array(), wp_get_theme()->get('Version'));
    } else {
        // If not IE, use the standard stylesheet.
        //wp_enqueue_style('gethalal-style', get_template_directory_uri() . '/style.css', array(), time());
        wp_enqueue_style( 'gethalal-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
    }

    // owl-carousel
    wp_enqueue_style('gethalal-carousel-style', get_template_directory_uri() . '/assets/vendor/owl-carousel/slick.css', array(), wp_get_theme()->get( 'Version' ));
    wp_enqueue_style('gethalal-carousel-theme-style', get_template_directory_uri() . '/assets/vendor/owl-carousel/slick-theme.css', array(), wp_get_theme()->get( 'Version' ));

    // RTL styles.
    wp_style_add_data('gethalal-style', 'rtl', 'replace');

    // Print styles.
    wp_enqueue_style('gethalal-print-style', get_template_directory_uri() . '/assets/css/print.css', array(), wp_get_theme()->get('Version'), 'print');

    // Print styles.
    wp_enqueue_style('gethalal-fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), wp_get_theme()->get('Version'), 'all');

    // Threaded comment reply styles.
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Register the IE11 polyfill file.
    wp_register_script(
        'gethalal-ie11-polyfills-asset',
        get_template_directory_uri() . '/assets/js/polyfills.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Register the IE11 polyfill loader.
    wp_register_script(
        'gethalal-ie11-polyfills',
        null,
        array(),
        wp_get_theme()->get('Version'),
        true
    );
    wp_add_inline_script(
        'gethalal-ie11-polyfills',
        wp_get_script_polyfill(
            $wp_scripts,
            array(
                'Element.prototype.matches && Element.prototype.closest && window.NodeList && NodeList.prototype.forEach' => 'gethalal-ie11-polyfills-asset',
            )
        )
    );

    // Main navigation scripts.
    if (has_nav_menu('primary')) {
        wp_enqueue_script(
            'gethalal-primary-navigation-script',
            get_template_directory_uri() . '/assets/js/primary-navigation.js',
            array('gethalal-ie11-polyfills'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    // Responsive embeds script.
    wp_enqueue_script(
        'gethalal-responsive-embeds-script',
        get_template_directory_uri() . '/assets/js/responsive-embeds.js',
        array('gethalal-ie11-polyfills'),
        wp_get_theme()->get('Version'),
        true
    );

    // Dialogs
    wp_enqueue_script(
        'gethalal-dialog-script',
        get_template_directory_uri() . '/assets/js/dialog.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    wp_enqueue_script(
        'gethalal-home-script',
        get_template_directory_uri() . '/assets/js/home.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    $frontend_data = array(
        'gethalal_nonce' => wp_create_nonce('gethalal_nonce'),
        'ajaxurl' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('gethalal-dialog-script', 'gethalal_obj', $frontend_data);

    // Date Picker on Checkout Page
    if ( is_checkout() ) {
        // Load the datepicker script (pre-registered in WordPress).
        wp_enqueue_script( 'jquery-ui-datepicker' );
        // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
        wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );
    }
}

add_action('wp_enqueue_scripts', 'gethalal_scripts');

function gethalal_login_scripts()
{
    global $is_IE, $wp_scripts;
    if ($is_IE) {
        // If IE 11 or below, use a flattened stylesheet with static values replacing CSS Variables.
        wp_enqueue_style('gethalal-style', get_template_directory_uri() . '/assets/css/ie.css', array(), wp_get_theme()->get('Version'));
    } else {
        // If not IE, use the standard stylesheet.
        // TODO replace
        wp_enqueue_style('gethalal-style', get_template_directory_uri() . '/style.css', array(), time());
        //wp_enqueue_style( 'gethalal-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
    }
}

add_action('login_enqueue_scripts', 'gethalal_login_scripts');

/**
 * Enqueue block editor script.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_block_editor_script()
{

    wp_enqueue_script('gethalal-editor', get_theme_file_uri('/assets/js/editor.js'), array('wp-blocks', 'wp-dom'), wp_get_theme()->get('Version'), true);
}

add_action('enqueue_block_editor_assets', 'gethalal_block_editor_script');

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @since Get Halal 1.0
 *
 * @link https://git.io/vWdr2
 */
function gethalal_skip_link_focus_fix()
{

    // If SCRIPT_DEBUG is defined and true, print the unminified file.
    if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
        echo '<script>';
        include get_template_directory() . '/assets/js/skip-link-focus-fix.js';
        echo '</script>';
    }

    // The following is minified via `npx terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
    ?>
    <script>
        /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", (function () {
            var t, e = location.hash.substring(1);
            /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
        }), !1);
    </script>
    <?php
}

add_action('wp_print_footer_scripts', 'gethalal_skip_link_focus_fix');

/**
 * Enqueue non-latin language styles.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_non_latin_languages()
{
    $custom_css = gethalal_get_non_latin_css('front-end');

    if ($custom_css) {
        wp_add_inline_style('gethalal-style', $custom_css);
    }
}

add_action('wp_enqueue_scripts', 'gethalal_non_latin_languages');

// SVG Icons class.
require get_template_directory() . '/classes/class-gethalal-svg-icons.php';

// Custom color classes.
require get_template_directory() . '/classes/class-gethalal-custom-colors.php';
new Gethalal_Custom_Colors();

// Utilies class.
require get_template_directory() . '/classes/class-gethalal-utils.php';

// Enhance the theme by hooking into WordPress.
require get_template_directory() . '/inc/template-functions.php';

// Menu functions and filters.
require get_template_directory() . '/inc/menu-functions.php';

// Main functions and filters.
require get_template_directory() . '/inc/main-functions.php';

// Api functions and filters.
require get_template_directory() . '/inc/api-functions.php';

// Mobile Api functions.
// TODO Disabled Mobile APIs
//require get_template_directory() . '/inc/mobile-functions.php';

// Custom template tags for the theme.
require get_template_directory() . '/inc/template-tags.php';

// Customizer additions.
require get_template_directory() . '/classes/class-gethalal-customize.php';
new Gethalal_Customize();

// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';

// Block Styles.
require get_template_directory() . '/inc/block-styles.php';

// Dark Mode.
require_once get_template_directory() . '/classes/class-gethalal-dark-mode.php';
new Gethalal_Dark_Mode();

/**
 * Enqueue scripts for the customizer preview.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_customize_preview_init()
{
    wp_enqueue_script(
        'gethalal-customize-helpers',
        get_theme_file_uri('/assets/js/customize-helpers.js'),
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    wp_enqueue_script(
        'gethalal-customize-preview',
        get_theme_file_uri('/assets/js/customize-preview.js'),
        array('customize-preview', 'customize-selective-refresh', 'jquery', 'gethalal-customize-helpers'),
        wp_get_theme()->get('Version'),
        true
    );
}

add_action('customize_preview_init', 'gethalal_customize_preview_init');

/**
 * Enqueue scripts for the customizer.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_customize_controls_enqueue_scripts()
{

    wp_enqueue_script(
        'gethalal-customize-helpers',
        get_theme_file_uri('/assets/js/customize-helpers.js'),
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}

add_action('customize_controls_enqueue_scripts', 'gethalal_customize_controls_enqueue_scripts');

/**
 * Calculate classes for the main <html> element.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_the_html_classes()
{
    /**
     * Filters the classes for the main <html> element.
     *
     * @param string The list of classes. Default empty string.
     * @since Get Halal 1.0
     *
     */
    $classes = apply_filters('gethalal_html_classes', '');
    if (!$classes) {
        return;
    }
    echo 'class="' . esc_attr($classes) . '"';
}

/**
 * Add "is-IE" class to body if the user is on Internet Explorer.
 *
 * @return void
 * @since Get Halal 1.0
 *
 */
function gethalal_add_ie_class()
{
    ?>
    <script>
        if (-1 !== navigator.userAgent.indexOf('MSIE') || -1 !== navigator.appVersion.indexOf('Trident/')) {
            document.body.classList.add('is-IE');
        }
    </script>
    <?php
}

add_action('wp_footer', 'gethalal_add_ie_class');


/**
 * Valid Order Statuses for cancelling orders
 * @param $statuses
 * @param $order
 * @return string[]
 */
function gethalal_valid_order_statuses_for_cancel($statuses, $order){
    return Gethalal_Utils::UPCOMING_ORDER_STATUSES;
}
add_filter('woocommerce_valid_order_statuses_for_cancel', 'gethalal_valid_order_statuses_for_cancel', 10, 2);


/**
 * Valid Order Statuses for reordering
 * @param $statuses
 * @param $order
 * @return string[]
 */
function gethalal_valid_order_statuses_for_order_again($statuses){
    return Gethalal_Utils::HISTORY_ORDER_STATUSES;
}
add_filter('woocommerce_valid_order_statuses_for_order_again', 'gethalal_valid_order_statuses_for_order_again', 10);


/**
 *
 * Gethalal Modal Dialog
 */
function gethalal_modal()
{
    ?>
    <div class="gethalal-site-dialog">
        <div class="modal-content">
        </div>
    </div>
    <?php
}

add_action('wp_footer', 'gethalal_modal');

/**
 * Account ZipCode Menu
 */
if(!function_exists('gethalal_zipcode_menu')) {
    function gethalal_zipcode_menu() {
        if(!is_user_logged_in() || isset($_COOKIE['gethalal_customer_post_code'])){
            $postcode = $_COOKIE['gethalal_customer_post_code'];
            WC()->customer->set_postcode($postcode);
        } else {
            $postcode = WC()->customer->get_postcode();
        }
        $target_zone = gethalal_get_zone_by_postcode($postcode);
        $zip_code = __('Your Postcode', 'gethalal');
        if($target_zone){
            $zip_code = "${postcode},${target_zone['zone_name']}";
        } else {
            $postcode = '';
            WC()->customer->set_postcode('');
        }

        echo gethalal_get_fontawesome_icon_svg('solid', 'map-marker-alt', 18);
        echo "<span class='zip-code' data-value='${postcode}'>${zip_code}</span>";
    }
}

if(!function_exists('gethalal_get_zone_by_postcode')){
    function gethalal_get_zone_by_postcode($postcode){
        $shipping_zones = WC_Shipping_Zones::get_zones('json');
        $target_zone = false;
        foreach($shipping_zones as $key => $zone){
            foreach($zone['zone_locations'] as $location){
                if($location->type == 'postcode' && $location->code == $postcode){
                    $target_zone = $zone;
                }
            }
            if($target_zone){
                break;
            }
        }
        return $target_zone;
    }
}

if(!function_exists('gethalal_get_postcodes_in_Berlin')){
    function gethalal_get_postcodes_in_Berlin(){
        $shipping_zones = WC_Shipping_Zones::get_zones('json');
        foreach($shipping_zones as $key => $zone){
            if($zone['zone_name'] == 'Berlin'){
                $postcodes = [];
                foreach($zone['zone_locations'] as $location){
                    if($location->type == 'postcode'){
                        $postcodes[] = $location->code;
                    }
                }
                return $postcodes;
            }
        }
        return [];
    }
}

/**
 * Account Locale Menu
 */

if ( is_user_logged_in() ) {
    add_filter('locale', 'gethalal_change_lang');
    function gethalal_change_lang( $locale ) {

        if( $lang = get_user_meta( get_current_user_id(), 'user_lang', true) ) {
            return $lang;
        }

        return $locale;
    }
}

/**
 * Account Login Icon Menus
 */
if (!function_exists('gethalal_logged_in_menu')) {
    /**
     * List menu account
     * when logged in or sign out
     */
    function gethalal_logged_in_menu()
    {
        if (!is_user_logged_in()) {

            $login_reg_button = '<a href="' . site_url('/login') . '" class="button login-button text-center">' . esc_html__('Join Now', 'gethalal') . '</a>';
            ?>
            <li class="my-account-login"><?php echo wp_kses_post(apply_filters('gethalal_header_account_subbox_login_register_link', $login_reg_button)); ?></li>
            <?php
        } else {
            echo gethalal_get_fontawesome_icon_svg('solid', 'user', 18);
            $user = wp_get_current_user();
            ?>
            <span class="user-name-text"><?php echo esc_attr($user->display_name) ?></span>

            <?php
            gethalal_account_menus();
            echo gethalal_get_fontawesome_icon_svg('solid', 'chevron-down', 16);
        }
    }
}

if (!function_exists('gethalal_account_menus')) {
    function gethalal_account_menus() {

        $redirect = apply_filters('logout_redirect', home_url());
        $args = array();
        $args['redirect_to'] = urlencode( $redirect );

        $logout_url = add_query_arg( $args, home_url( '/login?action=logout' ) );
        $logout_url = wp_nonce_url( $logout_url, 'log-out' );

        if ('yes' === get_option('woocommerce_force_ssl_checkout')) {
            $logout_url = str_replace('http:', 'https:', $logout_url);
        }

        $user = wp_get_current_user();

        if (!is_user_logged_in()) {
             ?>
            <div class="subbox">
                <ul>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'sign-in-alt', 18);
                        echo '<a href="' . home_url('/login') . '">' . esc_html__('Join Now', 'gethalal') . '</a>'; ?>
                    </li>
                </ul>
            </div>
            <?php
        } else {
            ?>
            <div class="subbox">
                <ul>
                    <li class="my-account-info">
                        <div class="account-name"><?php echo esc_attr($user->display_name) ?></div>
                        <div class="account-email"><?php echo esc_attr($user->user_email) ?></div>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'user', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('My Account', 'gethalal') . '</a>'; ?>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'clipboard-list', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('My Orders', 'gethalal') . '</a>'; ?>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'credit-card', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'payment-methods', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('My payment options', 'gethalal') . '</a>'; ?>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'map-marker-alt', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('My Address', 'gethalal') . '</a>'; ?>
                    </li>
                    <!--                    <li class="my-account-menu">--><?php
                    //                        echo gethalal_get_fontawesome_icon_svg('solid', 'bell', 18);
                    //                        echo '<a href="' . wc_get_endpoint_url( 'notifications', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('Notifications', 'gethalal') . '</a>' ?>
                    <!--                    </li>-->
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'lock', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'change-password', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('Change Passwords', 'gethalal') . '</a>'; ?>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'question-circle', 18);
                        echo '<a href="' . wc_get_endpoint_url( 'terms-policy', '', wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__('Terms & Policy', 'gethalal') . '</a>'; ?>
                    </li>
                    <li class="my-account-menu"><?php
                        echo gethalal_get_fontawesome_icon_svg('solid', 'sign-out-alt', 18);
                        echo '<a href="' . esc_url($logout_url) . '">' . esc_html__('Logout', 'gethalal') . '</a>'; ?>
                    </li>
                </ul>
            </div>
            <?php
        }
    }
}


/**
 * Account Cart Menu
 */
if (!function_exists('gethalal_cart_menu')) {
    /**
     * cart menu
     */
    function gethalal_cart_menu()
    {
        $count = is_null( WC()->cart ) ? 0 : WC()->cart->get_cart_contents_count();

        echo '<a href="' . wc_get_page_permalink( 'cart' ) . '"  class="shopping-bag-button">';
        echo '<img class="cart_icon" src="' . get_template_directory_uri() . '/assets/images/cart.png' . '" alt="cart"/>';
        if ($count > 0) {
            echo '<span class="cart-items-text shop-cart-count">' . esc_html($count) . '</span>';
        }
        echo '</a>';
    }
}

/**
 *
 *  Default account address fields
 */
if(!function_exists('gethalal_default_address_fields')){
   function gethalal_default_address_fields($fileds){
       return array(
           'display_name' => array(
               'label'        => __( 'Display Name', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-first' ),
               'autocomplete' => 'given-name',
               'priority'     => 10,
           ),
           'first_name' => array(
               'label'        => __( 'First name', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-first' ),
               'autocomplete' => 'given-name',
               'priority'     => 10,
           ),
           'address_1'  => array(
               'label'        => __( 'Street address', 'woocommerce' ),
               /* translators: use local order of street name and house number. */
               'placeholder'  => esc_attr__( 'House number and street name', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide', 'address-field' ),
               'autocomplete' => 'address-line1',
               'priority'     => 50,
           ),
           'email'    => array(
               'type'         => 'email',
               'label'        => __( 'Email', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide'),
               'autocomplete' => 'email',
               'priority'     => 20,
           ),
           'phone'    => array(
               'type'         => 'phone',
               'label'        => __( 'Phone', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide'),
               'autocomplete' => 'phone',
               'priority'     => 30,
           ),
           'address'  => array(
               'label'        => __( 'Address', 'woocommerce' ),
               /* translators: use local order of street name and house number. */
               'placeholder'  => esc_attr__( 'House number and street name', 'woocommerce' ),
               'required'     => false,
               'class'        => array( 'form-row-wide', 'address-field' ),
               'autocomplete' => 'address-line1',
               'priority'     => 50,
           ),
           'country'    => array(
               'type'         => 'country',
               'label'        => __( 'Country / Region', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
               'autocomplete' => 'country',
               'priority'     => 40,
           ),
           'postcode'   => array(
               'label'        => __( 'Postcode / ZIP', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide', 'address-field' ),
               'validate'     => array( 'postcode' ),
               'autocomplete' => 'postal-code',
               'priority'     => 90,
           ),
           'city'       => array(
               'label'        => __( 'City', 'woocommerce' ),
               'required'     => true,
               'class'        => array( 'form-row-wide', 'address-field' ),
               'autocomplete' => 'city',
               'priority'     => 60,
           ),
           'floor'      => array(
               'label'        => __( 'Floor', 'woocommerce' ),
               'required'     => false,
               'class'        => array( 'form-row-wide', 'address-field' ),
               'validate'     => array( 'state' ),
               'autocomplete' => 'floor',
               'priority'     => 70,
           ),
       );
   }
}

add_filter('woocommerce_default_address_fields', 'gethalal_default_address_fields', 20);

/**
 * New Address Form
 */

if (!function_exists('gethalal_new_address_form')) {
    function gethalal_new_address_form($address = [], $class = "")
    {
        ?>
        <form name="address" method="post" class="address alignwide gethalal-address <?php echo $class ?>"
              action="" enctype="multipart/form-data">
            <div class="form-section-title"><?php echo __('Personal  Information', 'gethalal') ?></div>
            <div class="form-section">
                <p class="form-row form-row-wide validate-required" id="address_username_field">
                    <input type="text" class="input-text " name="account_display_name" id="account_display_name" required
                           placeholder="<?php echo __('First and Last Name*', 'gethalal') ?>"
                           value="<?php echo $address['display_name'] ?? $_POST['account_display_name'] ?? '' ?>" autocomplete="email username">
                </p>
                <p class="form-row form-row-wide validate-required validate-phone" id="address_phone_field">
                    <input type="tel" class="input-text " name="account_phone" id="account_phone" required
                           placeholder="<?php echo __('Phone Number*', 'gethalal') ?>"
                           value="<?php echo $address['phone'] ?? $_POST['account_phone'] ?? '' ?>">
                </p>
                <p class="form-row form-row-wide validate-required validate-email" id="address_email_field">
                    <input type="email" class="input-text " name="account_email" id="account_email" placeholder="E-mail*" required
                           value="<?php echo $address['email'] ?? $_POST['account_email'] ?? WC()->customer->get_email() ?? '' ?>" autocomplete="email username">
                </p>
            </div>
            <div class="form-section-title"><?php echo __('Delivery Address', 'gethalal') ?></div>
            <div class="form-section">
                <p class="form-row form-row-wide" id="address_username_field">
                    <input type="text" class="input-text " name="account_address_text" id="account_address_text" required
                           placeholder="<?php echo __('Address*', 'gethalal') ?>"
                           value="<?php echo $address['address'] ?? $_POST['account_address_text'] ?? '' ?>">
                </p>
                <?php
                    wp_enqueue_script('wc-country-select');

                    $countries = WC()->countries->get_allowed_countries();

                    $value = $address['country'] ?? $_POST['account_address_country'] ?? WC()->countries->get_base_country();

                    $field = '<p class="form-row chzn-drop account_country validate-required" id="account_address_country_field" data-priority=""><span class="woocommerce-input-wrapper"><select name="account_address_country" id="account_address_country" class="country_to_state country_select"' . ' data-placeholder="' . __('Country*') . '" >';

                    foreach ( $countries as $ckey => $cvalue ) {
                        $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                    }

                    $field .= '</select>';

                    $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

                    $field .= '</span></p>';

                    echo $field;
                ?>
                <p class="form-row form-row-wide" id="address_area_field">
                    <input type="text" class="input-text " name="account_address_postcode" id="account__address_postcode" required
                           placeholder="<?php echo __('Enter Your zip code*', 'gethalal') ?>"
                           value="<?php echo $address['postcode'] ?? $_POST['account_address_postcode'] ?? WC()->customer->get_postcode() ?? '' ?>">
                </p>
                <p class="form-row form-row-first" id="address_city_field">
                    <input type="text" class="input-text " name="account_address_city" required
                           id="account_address_city"
                           placeholder="<?php echo __('City*', 'gethalal') ?>"
                           value="<?php echo $address['city'] ?? $_POST['account_address_city'] ?? '' ?>">
                </p>
                <p class="form-row form-row-last" id="address_floor_field">
                    <input type="text" class="input-text " name="account_address_floor" id="account_address_floor" required
                           placeholder="<?php echo __('Floor*', 'gethalal') ?>"
                           value="<?php echo $address['floor'] ?? $_POST['account_address_floor'] ?? '' ?>">
                </p>
                <p class="form-row form-row-wide"/>
            </div>
            <div class="set-default-action">
                <input id="default-address-checkbox"
                       class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="hidden"
                       name="default_address_checkbox" value="<?php echo $_POST['default_address_checkbox'] ?? 0 ?>">
                <div class="check-btn"><?php echo $_POST['default_address_checkbox']? gethalal_get_fontawesome_icon_svg('solid', 'check-circle', 25): gethalal_get_fontawesome_icon_svg('regular', 'circle', 25) ?></div>
                <div class="check-label"><?php echo __('Set it as Default', 'gethalal') ?></div>
            </div>
            <div class="form-action">
                <?php if (isset($address['id'])): ?>
                    <input type="hidden" name="address_id" value="<?php echo $address['id'] ?>"/>
                <?php endif; ?>
                <input type="submit" class="button form-button" value="<?php echo __('Save', 'gethalal') ?>">
                <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
                <input type="hidden" name="action" value="edit_address"/>
            </div>
        </form>
        <?php
    }
}


add_filter('woocommerce_get_order_item_totals', 'gethalal_woocommerce_get_order_item_totals', 10, 3);
if(!function_exists('gethalal_woocommerce_get_order_item_totals')){
    function gethalal_woocommerce_get_order_item_totals($rows, WC_Order $order, $tax_display){
        if(isset($rows['payment_method'])){
            unset($rows['payment_method']);
        }
        return $rows;
    }
}

if(!function_exists('gethalal_get_order_shipping_status')){
    function gethalal_get_order_shipping_status($order){
        if(function_exists('wc_gzd_get_shipments')){
            $shipments = wc_gzd_get_shipments(array(
                'order_id' => $order->get_id(),
                'type'     => array( 'simple')
            ));

            if (count($shipments) > 0) {
                $last_shipment = $shipments[count($shipments) - 1];
                $shipment_status = $last_shipment->get_status();
                echo "<mark class='status-${shipment_status}'>" .  Gethalal_Utils::order_shipping_statuses()[$shipment_status] . "</mark>";
                return;
            }
        }
        $status = $order->get_status();
        echo "<mark class='status-${status}'>" . wc_get_order_status_name( $status ) . "</mark>";
    }
}


/**
 * Order Status History Bar
 */
if(!function_exists('gethalal_order_status_line')){
    function gethalal_order_status_line(WC_Order $order){
        if(function_exists('wc_gzd_get_shipments')) {
            $shipments = wc_gzd_get_shipments(array(
                'order_id' => $order->get_id(),
                'type' => array('simple')
            ));

            $active_shipment = count($shipments) > 0 ? $shipments[count($shipments) - 1] : null;

            $status_index = 0;
            if ($active_shipment) {
                $status_index = array_search($active_shipment->get_status(), array_keys(Gethalal_Utils::order_shipping_statuses())) + 1;
            }

            echo '<div class="order-status-container"><div class="order-status-icon-container">';

            echo '<img class="order-status-check" src="' . esc_url(get_template_directory_uri()) . '/assets/images/' . ($status_index > 0 ? 'check_active.png' : 'check_inactive.png') . '" alt="order-status"/>';

            for ($i = 2; $i < 5; $i++) {
                echo '<img class="order-status-line" src="' . esc_url(get_template_directory_uri()) . '/assets/images/' . ($status_index > ($i - 1) ? 'line_active.png' : 'line_inactive.png') . '" alt="order-status"/>';
                echo '<img class="order-status-check" src="' . esc_url(get_template_directory_uri()) . '/assets/images/' . ($status_index > ($i - 1) ? 'check_active.png' : 'check_inactive.png') . '" alt="order-status"/>';
            }
            echo '</div>';

            echo '<div class="order-status-label-container">';
            for ($i = 1; $i < 5; $i++) {
                $order_status_date = '';
                $order_status_time = '';
                if ($status_index >= $i) {
                    switch ($i) {
                        case 0:
                            $order_status_date = date_i18n('l, j', $active_shipment->get_date_created());
                            $order_status_time = date_i18n('g:i a', $active_shipment->get_date_created());
                            break;
                        case 1:
                            $order_status_date = date_i18n('l, j', $active_shipment->get_date_created());
                            $order_status_time = date_i18n('g:i a', $active_shipment->get_date_created());
                            break;
                        case 2:
                            $order_status_date = date_i18n('l,j', $active_shipment->get_date_sent());
                            $order_status_time = date_i18n('g:i a', $active_shipment->get_date_sent());
                            break;
                        case 3:
                            $order_status_date = date_i18n('l,j', $active_shipment->get_est_delivery_date());
                            $order_status_time = date_i18n('g:i a', $active_shipment->get_est_delivery_date());
                            break;
                    }
                }


                echo '<div class="order-status-label-item">';
                echo '<div class="order-status-label">' . array_values(Gethalal_Utils::order_shipping_statuses())[$i - 1] . '</div>';
                echo '<div class="order-status-label-date">' . $order_status_date . '</div>';
                echo '<div class="order-status-label-time">' . $order_status_time . '</div>';
                echo '</div>';
            }
            echo '</div></div>';
        }
    }
}

/**
 * Address Item
 */
if (!function_exists('gethalal_address_item')) {
    function gethalal_address_item($index, $address)
    {
        $set_default_url = (is_checkout()? wc_get_page_permalink('checkout') : wc_get_page_permalink('myaccount')) . "edit-address?id=${index}&action=set_default_account_address" . (is_checkout()?'&checkout=true':'');
        ?>
        <div class="address-container">
            <?php if ($address['is_default']): ?>
                <div class="address-check">
                    <div class="check-btn active"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'check-circle', 25) ?></div>
                    <div class="check-label"><?php echo __('Default', 'gethalal') ?></div>
                </div>
            <?php else : ?>
                <a class="address-check" href="<?php echo $set_default_url ?>">
                    <div class="check-btn"><?php echo gethalal_get_fontawesome_icon_svg('regular', 'circle', 25) ?></div>
                    <div class="check-label"><?php echo __('Default', 'gethalal') ?></div>
                </a>
            <?php endif; ?>
            <div class="address-content">
                <div class="full-name"><?php echo $address['display_name'] ?> </div>
                <div class="address-text"><?php echo "${address['address']}" ?></div>
                <div class="address-country"><?php echo "${address['city']}  ,${address['postcode']}" ?></div>
            </div>
            <div class="address-action">
                <a class="address-edit-action" data-id="<?php echo $index ?>"
                   href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>"><?php echo __('Edit', 'gethalal') ?></a>
            </div>
        </div>
        <?php
    }
}


/**
 * Notification Item
 */
if (!function_exists('gethalal_notification_item')) {
    function gethalal_notification_item()
    {
        ?>
        <div class="notification-container">
            <div class="notification-header">
                <div class="notification-title">Notification Title</div>
                <div class="notification-date">12-2-2021</div>
            </div>
            <div class="notification-content">
                <div class="notification-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                    tempor in
                </div>
            </div>
        </div>
        <?php
    }
}


/**
 * Zip Code Dialog
 */
if (!function_exists('gethalal_dialog_zip_code')) {
    /**
     * Display Dialog Search
     *
     * @return void
     */
    function gethalal_dialog_zip_code()
    {
        $close_icon = gethalal_get_icon_svg('ui', 'close', 20);
        $zipcode = '';
        ?>

        <div class="site-dialog-search gethalal-search-wrap">
            <div class="dialog-search-content">
                <div class="dialog-search-header">
                    <span class="dialog-search-title"><?php echo esc_html__('Change Your Location', 'gethalal'); ?></span>
                    <span class="dialog-search-close-icon"> <?php echo $close_icon; ?></span>
                </div>

                <div class="dialog-search-main">
                    <?php
                    $form = '<form role="zipcode" method="get" id="zipcodeform" class="zipcodeform" action="' . esc_url(home_url()) . '">
                        <div>
                            <input type="text" class="search-field" value="' . $zipcode . '" name="zipcode" id="zipcode" placeholder="' . __('Enter Your Zip code', 'gethalal') . '"/>
                            <input type="submit" id="zipcodesubmit" value="' . __('Confirm', 'gethalal') . '" />
                        </div>
                    </form>';
                    echo $form;
                    ?>
                </div>

                <div class="dialog-error"></div>
            </div>
        </div>
        <?php
    }
}


/**
 * Account Language Menu
 */

/**
 * Update wp-wpml_user_selected_language cookie when user switches language
 */
add_action('template_redirect', function (){

    global $sitepress;
    if(empty($sitepress)){
        return;
    }

    // GET variables
    $switch_language = filter_input(INPUT_GET, 'switch_language', FILTER_SANITIZE_STRING);
    if( $switch_language ) {
        $languages = icl_get_languages();
        // Check if the switch_language variable is a registered language
        if( array_key_exists( $switch_language, $languages ) ) {
            // Create a cookie that never expires, technically it expires in 10 years
            setcookie( 'wp-wpml_user_selected_language', $switch_language, time() + (10 * 365 * 24 * 60 * 60), '/' );

            // Let's redirect the users to the request uri without the querystring, otherwise the server will send an uncached page
            wp_redirect( strtok( $_SERVER['REQUEST_URI'], '?' ) );
            exit;
        }
    }
});

/**
 * Switch language if the user has selected his language before
 */
add_action( 'pre_get_posts', function ( $query ) {
    // Disable for admin area & only for main query
    if( is_admin() || !$query->is_main_query() ) {
        return;
    }

    // Disable for logged in users
//    if( is_user_logged_in() ) {
//        return;
//    }

    // Check if requested webpage is /
    if( strtok( $_SERVER['REQUEST_URI'], '?' ) != '/' ) {
        return;
    }

    // GET & COOKIE variables
    $switch_language           = filter_input( INPUT_GET, 'switch_language', FILTER_VALIDATE_BOOLEAN );
    $user_selected_language    = filter_input( INPUT_COOKIE, 'wp-wpml_user_selected_language', FILTER_SANITIZE_STRING );

    // If user has selected his language before & user did not just change his language
    if ( $user_selected_language && !$switch_language ) {
        do_action( 'wpml_switch_language', $user_selected_language );

        // Redirect proper language page.
        global $sitepress;
        if(empty($sitepress)){
            return;
        }
        $languages = icl_get_languages();
        $cur_lang = $sitepress->get_current_language();
        $default_lang = apply_filters('wpml_default_language', NULL );

        if( array_key_exists( $cur_lang, $languages ) && $default_lang != $cur_lang ) {
            wp_safe_redirect($languages[$cur_lang]['url']);
        }
    }
}, 1);

if (!function_exists('gethalal_language_menu')) {
    /**
     * List menu languages
     */
    function gethalal_language_menu($is_app = false)
    {
        global $sitepress;
        if(empty($sitepress)){
            return;
        }

        $languages = icl_get_languages();

        $cur_lang = $sitepress->get_current_language();

        echo gethalal_get_fontawesome_icon_svg('solid', 'globe', $is_app?20:18);
        if( !$is_app && array_key_exists( $cur_lang, $languages ) ) {
        ?>
            <span class="language-text"><?php echo $languages[$cur_lang]['translated_name'] ?></span>
        <?php } ?>
        <div class="subbox">
            <ul>
                <?php
                    foreach($languages as $language){
                        echo '<li class="language-menu ' . ($language['code'] == $cur_lang ? 'active': '') . '">
                                <a href="' . add_query_arg('switch_language', $language['code'], $language['url']) . '">' . $language['translated_name'] . '</a>
                            </li>';
                    }
                ?>
            </ul>
        </div>
        <?php
    }
}

/**
 * Account Language Menu
 */
if (!function_exists('gethalal_get_request_url')) {
    function gethalal_get_request_url()
    {
        if (isset($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'])) {
            return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        }
        return is_admin() ? get_admin_url(get_current_blog_id()) : home_url();
    }
}


/** Forms */

if (!function_exists('gethalal_woocommerce_form_field')) {

    /**
     * Outputs a checkout/address form field.
     *
     * @param string $key Key.
     * @param mixed $args Arguments.
     * @param string $value (default: null).
     * @return string
     */
    function gethalal_woocommerce_form_field($key, $args, $value = null)
    {
        $defaults = array(
            'type' => 'text',
            'label' => '',
            'description' => '',
            'placeholder' => '',
            'maxlength' => false,
            'required' => false,
            'autocomplete' => false,
            'id' => $key,
            'class' => array(),
            'label_class' => array(),
            'input_class' => array(),
            'return' => false,
            'options' => array(),
            'custom_attributes' => array(),
            'validate' => array(),
            'default' => '',
            'autofocus' => '',
            'priority' => '',
        );

        $args = wp_parse_args($args, $defaults);
        $args = apply_filters('woocommerce_form_field_args', $args, $key, $value);

        $field = '';
        $label_id = $args['id'];

        switch ($args['type']) {
            case 'country':
                $field .= '<input type="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . $value . '" ' . ' class="country_to_state" readonly="readonly" />';

                break;
            case 'text':
            case 'password':
            case 'datetime':
            case 'datetime-local':
            case 'date':
            case 'month':
            case 'time':
            case 'week':
            case 'number':
            case 'email':
            case 'url':
            case 'tel':
                $field .= '<input type="hidden" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '"  value="' . esc_attr($value) . '" ' . ' />';

                break;
            case 'hidden':
                $field .= '<input type="' . esc_attr($args['type']) . '" class="input-hidden ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . esc_attr($value) . '" ' . ' />';

                break;
        }

        echo $field;
    }
}


if(!function_exists('gethalal_address_field_value')){
    function gethalal_address_field_value($key, $checkout, $type="billing_"){
        $checkout_value = $checkout->get_value( $key );
        if(is_user_logged_in()){
            $address = Gethalal_Utils::get_default_address(get_current_user_id());
        } else {
            $address = (array)json_decode(base64_decode($_GET['address']));
        }
        $address_key = str_replace($type, '', $key);
        if($address){
            if(isset($address[$address_key]))
                return $address[$address_key];
            if($address_key == 'first_name')
                return $address['display_name'];
            if($address_key == 'address_1')
                return $address['address'];
        }
        return $checkout_value;
    }
}


//////////////////////////////////////////////////////////////////////
//// Short Codes
///

function gethalal_shortcode_product_categories($attr = array())
{
    $render = '<div class="main-content"><div class="content-title">' . esc_html__('Categories', 'gethalal') . '</div>
            <div class="top-categories">';

    $categories = get_categories(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'order'   => 'ASC',
            'orderby' => 'menu_order'
        )
    );
    foreach ($categories as $category) {
        if ($category->parent != $attr['parent'] || $category->slug == 'uncategorized') continue;
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);
        $render .= '<a href="' . esc_url(home_url('/product-category/')) . $category->slug . '/" class="category-container"><div class="image-container"><img src="' . $image . '" class="top-category-images" alt=""></div><span>' . esc_html($category->name) . '</option></a>';
    }
    $render .= '</div></div>';
    return $render;
}

add_shortcode('gethalal_product_categories', 'gethalal_shortcode_product_categories');

function gethalal_shortcode_dashboard_review($attr = array())
{
    global $sitepress;
    $cur_lang = $sitepress?$sitepress->get_current_language():'en';
    return '<div class="carousel slide review-items" data-ride="carousel">
<div class="carousel-item">
    <div class="carousel-item-content">
        <div class="quote">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/quote.png' . '" alt="quote"/>
        </div>
        <a href="https://www.trustpilot.com/review/gethalal.de?stars=4&stars=5" target="_blank" > 
           <div class="review-text">Great experience, fast delivery and very good customer service
    And they also keep the whole service improving.
    Recently, the packaging is so nice</div>
        </a>
        <div class="review-stars">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/review_star.png' . '" alt="stars"/>
            <span class="review-time">Nov 26, 2021</span>
        </div>
        <div class="review-user"><span>Hagar Anany</span></div>
    </div>
</div>
<div class="carousel-item">
    <div class="carousel-item-content">
        <div class="quote">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/quote.png' . '" alt="quote"/>
        </div>
         <a href="https://www.trustpilot.com/review/gethalal.de?stars=4&stars=5" target="_blank" > 
        <div class="review-text">Fast delivery. Delivered in time which was not the case before, seems the team their has considered previous feedback.
Packaging got better as well.</div>
        </a>
        <div class="review-stars">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/review_star.png' . '" alt="stars"/>
            <span class="review-time">Nov 13, 2021</span>
        </div>
        <div class="review-user"><span>Ahmed Abdelrahman</span></div>
    </div>
</div>
<div class="carousel-item">
    <div class="carousel-item-content">
        <div class="quote">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/quote.png' . '" alt="quote"/>
        </div>
        <a href="https://www.trustpilot.com/review/gethalal.de?stars=4&stars=5" target="_blank" > 
            <div class="review-text">Es ist eine großartige Erfahrung für mich, solche Produkte mit einem guten Preis und einem qualitativ hochwertigen Service vor Ort zu finden.</div>
        </a>
        <div class="review-stars">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/review_star.png' . '" alt="stars"/>
            <span class="review-time">Dec 18, 2021</span>
        </div>
        <div class="review-user"><span>Abdallah Khfagy</span></div>
    </div>
</div>
<div class="carousel-item">
    <div class="carousel-item-content">
        <div class="quote">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/quote.png' . '" alt="quote"/>
        </div>
        <a href="https://www.trustpilot.com/review/gethalal.de?stars=4&stars=5" target="_blank" >  
            <div class="review-text">Excellent service</div>
        </a>
        <div class="review-stars">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/review_star.png' . '" alt="stars"/>
            <span class="review-time">Oct 3, 2021</span>
        </div>
        <div class="review-user"><span>Farah Ghandour</span></div>
    </div>
</div>
<div class="carousel-item">
    <div class="carousel-item-content">
        <div class="quote">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/quote.png' . '" alt="quote"/>
        </div>
        <a href="https://www.trustpilot.com/review/gethalal.de?stars=4&stars=5" target="_blank" > 
            <div class="review-text">متجر رائع و يوفر اصناف عديدة و لكننا نطمح للمزيد</div>
        </a>
        <div class="review-stars">
            <img class="skip-lazy" src="' . get_template_directory_uri() . '/assets/images/review_star.png' . '" alt="stars"/>
            <span class="review-time">Dec 18, 2021</span>
        </div>
        <div class="review-user"><span>AbdulhaleemAsad Sih</span></div>
    </div>
</div>
</div>
    <script src="' . get_template_directory_uri() . '/assets/vendor/owl-carousel/slick.min.js' . '"></script>
    <script>
    jQuery(function($) {
        function initSlider(){
            $(".carousel").slick({
                infinite: true,
                dots:false,
                slidesToShow: 3,
                draggable: false,
                slidesToScroll: 1,
                rtl: ' . ($cur_lang === "ar" ? "true": "false") . ',
                responsive: [
                    {
                        breakpoint: 1162,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 782,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots:true,
                        }
                    }
                ]
            });
        }
        ( function() {
            initSlider();
        }());
    });
    </script>
    ';
}
add_shortcode('gethalal_dashboard_review', 'gethalal_shortcode_dashboard_review');


function gethalal_shortcode_how_it_works_app_images_1($attr = array())
{
    global $sitepress;
    $cur_lang = $sitepress?$sitepress->get_current_language():'en';
    return '<div class="carousel slide app-images-1-slider" data-ride="carousel">
<div class="carousel-image-item">
   <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_71.png' . '" alt="quote"/>
    </div>
</div>
<div class="carousel-image-item">
       <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_72.png' . '" alt="quote"/>
    </div>
</div>
<div class="carousel-image-item">
      <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_73.png' . '" alt="quote"/>
    </div>
</div>
<div class="carousel-image-item">
       <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_74.png' . '" alt="quote"/>
    </div>
</div>
</div>
    <script src="' . get_template_directory_uri() . '/assets/vendor/owl-carousel/slick.min.js' . '"></script>
    <script>
    jQuery(function($) {
        function initSlider(){
            $(".app-images-1-slider").slick({
                infinite: true,
                dots:true,
                slidesToShow: 4,
                draggable: false,
                slidesToScroll: 1,
                rtl: ' . ($cur_lang === "ar" ? "true": "false") . ',
                responsive: [
                    {
                        breakpoint: 1162,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 782,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 450,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
        ( function() {
            initSlider();
        }());
    });
    </script>
    ';
}
add_shortcode('gethalal_how_it_works_app_images_1', 'gethalal_shortcode_how_it_works_app_images_1');

function gethalal_shortcode_how_it_works_app_images_2($attr = array())
{
    global $sitepress;
    $cur_lang = $sitepress?$sitepress->get_current_language():'en';
    return '<div class="carousel slide app-images-2-slider" data-ride="carousel">
<div class="carousel-image-item">
   <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_81.png' . '" alt="quote"/>
    </div>
</div>
<div class="carousel-image-item">
       <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_82.png' . '" alt="quote"/>
    </div>
</div>
<div class="carousel-image-item">
      <div class="carousel-item-content">
        <img src="' . get_template_directory_uri() . '/assets/images/how_it_works_83.png' . '" alt="quote"/>
    </div>
</div>
</div>
    <script src="' . get_template_directory_uri() . '/assets/vendor/owl-carousel/slick.min.js' . '"></script>
    <script>
    jQuery(function($) {
        function initSlider(){
            $(".app-images-2-slider").slick({
                infinite: true,
                dots:true,
                slidesToShow: 3,
                draggable: false,
                slidesToScroll: 1,
                rtl: ' . ($cur_lang === "ar" ? "true": "false") . ',
                responsive: [
                    {
                        breakpoint: 1162,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 782,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        ( function() {
            initSlider();
        }());
    });
    </script>
    ';
}

add_shortcode('gethalal_how_it_works_app_images_2', 'gethalal_shortcode_how_it_works_app_images_2');

function gethalal_faq_item_view( $atts = array(), $content = null, $tag = '' ) {
    return ('<div class="faq-item-container ' . (empty($atts['back'])?'': $atts['back']) . '">
    <div class="faq-item-header">
        <div class="faq-item-title">' . $atts['title'] . '</div>
        <div class="faq-item-action-plus">' . gethalal_get_icon_svg('ui', 'plus') . '</div>
        <div class="faq-item-action-minus">' . gethalal_get_icon_svg('ui', 'minus') . '</div>
    </div>
    <div class="faq-item-content">' . $content . '</div>
    </div>');
}

add_shortcode('gethalal_faq_item', 'gethalal_faq_item_view');

//////////////////////////////////////////////////////////////////////////
/// Add Custom Pages
///

function gethalal_login_url($login_url, $redirect ){
    return site_url('/login');
}
add_filter('login_url', 'gethalal_login_url', 10, 2);

function gethalal_logout_redirect($redirect_to){
    return site_url();
}
add_filter('logout_redirect', 'gethalal_logout_redirect');

function gethalal_lostpassword_url($default_url){
    return site_url('/login?action=lostpassword', 'login');
}
add_filter('lostpassword_url', 'gethalal_lostpassword_url');

function gethalal_register_url($default ){
    return site_url('/login?action=register', 'login');
}
add_filter('register_url', 'gethalal_register_url', 10, 2);

function gethalal_shop_url(){
    $categories = get_categories(
        array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'order'   => 'ASC',
            'orderby' => 'menu_order'
        )
    );
    if(count($categories) > 0){
        return esc_url( home_url('/product-category/')) . $categories[0]->slug . '/';
    }
    return home_url();
}

add_filter('gethalal_shop_url', 'gethalal_shop_url');

function gethalal_remove_search_redirect() {
    return false;
}

// Disable Redirect single product when searching
add_filter( 'woocommerce_redirect_single_search_result', 'gethalal_remove_search_redirect', 10 );


function gethalal_coupon_dialog(){
    ?>
    <div class="site-dialog-coupon gethalal-coupon-wrap">
        <div class="dialog-coupon-content">
            <div class="dialog-coupon-header">
                <span class="dialog-coupon-title"><?php echo esc_html__('Add a Coupon', 'gethalal'); ?></span>
                <span class="dialog-coupon-close-icon"> <?php echo gethalal_get_icon_svg('ui', 'close', 20); ?></span>
            </div>

            <div class="dialog-coupon-main">

                <form class="gethalal-form-coupon" method="post" action="#">

                    <div class="form-row">
                        <input type="text" name="coupon_code" class="input-text coupon-field" placeholder="<?php esc_attr_e( 'Enter Coupon Code', 'gethalal' ); ?>" id="coupon_code" value="" />
                    </div>

                    <div class="form-row">
                        <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Add', 'gethalal' ); ?>"><?php esc_html_e( 'Add', 'gethalal' ); ?></button>
                    </div>

                    <div class="clear"></div>
                </form>

            </div>
            <div class="dialog-error"></div>
        </div>
    </div>
    <?php
}

// Coupon Dialog
add_action( 'woocommerce_after_checkout_form', 'gethalal_coupon_dialog');

function gethalal_delivery_time_dialog(){
    if(is_user_logged_in()){
        $address = Gethalal_Utils::get_default_address(get_current_user_id());
    } else if(isset($_GET['address'])) {
        $address = (array)json_decode(base64_decode($_GET['address']));
    } else {
        $address = [];
    }

    $BerlinPostcodes = gethalal_get_postcodes_in_Berlin();
    $outOfBerlin = !in_array($address['postcode'], $BerlinPostcodes);

    $date_from = ((new DateTime())->sub(new DateInterval("P10D")))->format( 'Y-m-d' );

    $offset=0;
    $delivery_date_counts = [];
    do {
        $orders = wc_get_orders([
            'type' => 'shop_order',
            'date_after' => $date_from,
            'offset' => $offset,
            'limit' => 100
        ]);

        $orders_count = count($orders);
        //gethalal_log("orders: ${orders_count}, from date: ${date_from}");

        foreach ($orders as $order) {
            $order = wc_get_order($order);

            // Check Out-Of-Berlin
            $postcode = $order->get_shipping_postcode();
            $outOfBerlinOrder = !in_array($postcode, $BerlinPostcodes);
            if($outOfBerlin != $outOfBerlinOrder){
                continue;
            }

            // Check DateTime Range
            $_delivery_date = get_post_meta($order->get_id(), 'delivery_date', true);
            if (empty($_delivery_date)) {
                continue;
            }

            $delivery_date = DateTime::createFromFormat('m-d-Y', $_delivery_date);
            if(!$delivery_date){
                continue;
            }
            $delivery_date = $delivery_date->format('Y-m-d');
            if(!isset($delivery_date_counts[$delivery_date])){
                $delivery_date_counts[$delivery_date] = 0;
            }
            $delivery_date_counts[$delivery_date]++;
        }
        $offset += 100;
    }while(count($orders) == 100);

    $add_date = $outOfBerlin ? 5 : 0;

    if($add_date){
        $target_date = ((new DateTime())->add(new DateInterval("P${add_date}D")));
    } else {
        $target_date = new DateTime();
    }
    $target_date = $target_date->format('Y-m-d');

    gethalal_log(json_encode($delivery_date_counts));

    foreach($delivery_date_counts as $key_delivery_date => $counts){

        // Check Limit Count (Out-Of-Berlin: 50, Berlin: 20)
        if($counts > ($outOfBerlin ? 50 : 20) && $key_delivery_date > $target_date){
            $target_date = $key_delivery_date;
            $add_date = round((time() - strtotime($key_delivery_date)) / (3600 * 24));
        }
    }

    // Exclude Sunday
    if(intval(DateTime::createFromFormat('Y-m-d', $target_date)->format('w')) > 0) {
        $add_date++;
    }

    gethalal_log("out-of-berlin: ${outOfBerlin}, adding date: ${add_date}");

    ?>
    <div class="site-dialog-delivery-time gethalal-delivery-time-wrap">
        <div class="dialog-delivery-time-content">
            <div class="dialog-delivery-time-header">
                <span class="dialog-delivery-time-title"><?php echo esc_html__('Delivery Date & Time', 'gethalal'); ?></span>
                <span class="dialog-delivery-time-close-icon"> <?php echo gethalal_get_icon_svg('ui', 'close', 28); ?></span>
            </div>

            <div class="dialog-delivery-time-main">

                <div class="gethalal-form-delivery-time">
                    <div class="form-row form-delivery-time-header">
                        <div class="delivery-year" id="delivery_year_text">2021</div>
                        <div class="delivery-date" id="delivery_date_text" style="margin-top: 8px">Fri,February 6</div>
                    </div>
                    <div class="form-row" style="margin-top: 12px">
                        <div id="dialog_delivery_time_picker"></div>
                    </div>

                    <div class="form-separator-2"></div>

                    <!-- TODO remove comment with shipping plugin -->
<!--                    <div class="form-row delivery-cycles">-->
<!--                        --><?php
//                            foreach (Gethalal_Utils::delivery_cycles() as $key => $delivery_cycle){
//                                echo '<div class="delivery-cycle-item ' . ($key == $delivery_meta_data['delivery_cycle']?'active':'') . '" data-id="' . $key . '">' .$delivery_cycle['text']. '</div>';
//                            }
//                        ?>
<!--                    </div>-->

                    <div class="form-row delivery-times">
                        <?php
                        foreach (Gethalal_Utils::delivery_times() as $key => $delivery_time){
                            echo '<div class="delivery-time-item time-item-' . $key . '" data-id="' . $key. '">' .$delivery_time['text']. '</div>';
                        }
                        ?>
                    </div>

                    <div class="form-separator-2"></div>

                    <div class="form-row">
                        <button class="button delivery-submit" name="apply-delivery-time" value="<?php esc_attr_e( 'Done', 'gethalal' ); ?>"><?php esc_html_e( 'Done', 'gethalal' ); ?></button>
                        <?php wp_nonce_field( 'gethalal_set_delivery_time', 'gethalal_set_delivery_time-nonce' ); ?>
                        <input type="hidden" name="action" value="set_delivery_time" />
                    </div>

                    <div class="clear"></div>
                </div>
                <script>
                    jQuery(function($){
                        function setDate(dateText){
                            let date = new Date(dateText);
                            if(!date || !date.getMonth() || date < init_date){
                                date = init_date;
                            }
                            $('#delivery_year_text').html(date.getFullYear());
                            let date_text = (date.getMonth() + 1) + '-' + date.getDate() + '-' + date.getFullYear();
                            $('#delivery_date').val(date_text);
                            setDeliveryStr();
                        }

                        function setDeliveryStr(){
                            let fullmonth_array = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                            let fullweek_array = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                            //let delivery_times_array = ['13:00 ~ 16:00', '16:00 ~ 20:00'];
                            let delivery_times_array = ['15:00 ~ 20:00'];
                            // console.log('delivery date', date, dateText);
                            let delivery_date = new Date($('#delivery_date').val());
                            let date_str = fullweek_array[delivery_date.getDay()] + ',' + (fullmonth_array[delivery_date.getMonth()]) + ' ' + delivery_date.getDate();

                            let delivery_time = $('#delivery_time').val();
                            let time_str = delivery_times_array[Number(delivery_time)];

                            $('#delivery_date_text').html(date_str);
                            $('#delivery_text').val(date_str + ',' + time_str);
                        }

                        // Disable special dates
                        var disabledDates = [
                            '03-08',
                        ];

                        var init_date = $('#delivery_date').val();
                        let minDate = 0;
                        if(init_date.length >0){
                            init_date = new Date(init_date);
                        }
                        if(!init_date || init_date.length === 0){
                            var add_to_date = <?php echo $add_date ?>;
                            var fromDate = new Date(Date.now() + add_to_date * 24 * 3600 * 1000);
                            minDate += add_to_date;

                            var hour = fromDate.getHours();
                            if(add_to_date === 0 && (hour >= 12)){
                                minDate += 1;
                                fromDate = new Date(Date.now() + minDate * 24 * 3600 * 1000);
                            }
                            // Excluding Sunday
                            if(fromDate.getDay() === 0) {
                                minDate += 1;
                            }
                            // Exclude Diable Dates
                            do{
                                fromDate = new Date(Date.now() + minDate * 24 * 3600 * 1000);
                                var date_str = jQuery.datepicker.formatDate('mm-dd', fromDate);
                                if(!disabledDates.includes(date_str)){
                                    break;
                                }
                                minDate += 1;
                            } while (1);

                            init_date = new Date(Date.now() + (minDate * 24 * 3600 * 1000));
                        }

                        // console.log('init date', init_date);
                        $("#dialog_delivery_time_picker").datepicker({
                            minDate: minDate,
                            defaultDate: init_date,
                            firstDay: 0,
                            dateFormat: 'mm-dd-yy',
                            showOtherMonths: true,
                            onSelect: function(dateText, instance){
                                setDate(dateText);
                            },
                            beforeShowDay: function( date ) {
                                var day = date.getDay();
                                var date_str = jQuery.datepicker.formatDate('mm-dd', date);
                                if(disabledDates.includes(date_str)){
                                    return [false, ""];
                                }
                                return [ ( day > 0 ), "" ];
                            },
                        });

                        setDate(new Date());

                        var init_time = $('#delivery_time').val();
                        $(".delivery-time-item.time-item-" + init_time).addClass('active');

                        $('.delivery-cycle-item').on('click', function(e){
                            if(e.target.className.indexOf('active') < 0){
                                $('.delivery-cycle-item').removeClass('active');
                                e.target.classList.add('active');
                                $('#delivery_cycle').val(e.target.getAttribute('data-id'));
                            }
                        })
                        $('.delivery-time-item').on('click', function(e){
                            if(e.target.className.indexOf('active') < 0){
                                $('.delivery-time-item').removeClass('active');
                                e.target.classList.add('active');
                                $('#delivery_time').val(e.target.getAttribute('data-id'));
                                setDeliveryStr();
                            }
                        })
                        $('.delivery-submit').on('click', function(e){
                            e.preventDefault();
                            return true;
                        })
                    });
                </script>
            </div>
            <div class="dialog-error"></div>
        </div>
    </div>
<?php
}

add_action( 'woocommerce_after_checkout_form', 'gethalal_delivery_time_dialog');


function gethalal_checkout_fields($fields) {
    array_push($fields, [
        'delivery_date' => array(
            'type'        => 'hidden',
        ),
        'delivery_time' => array(
            'type'        => 'hidden',
        ),
        'delivery_cycle' => array(
            'type'        => 'hidden',
        ),
    ]);
    return $fields;
}

add_filter('woocommerce_checkout_fields', 'gethalal_checkout_fields', 10, 1);

function gethalal_checkout_validation($data, $errors){
    if(empty($data['delivery_date'])){
        $errors->add( 'delivery', __( 'Invalid Delivery Time.', 'gethalal' ) );
    }
}

add_action('woocommerce_after_checkout_validation', 'gethalal_checkout_validation', 10, 2);

function gethalal_checkout_update_order_meta($order_id, $data){
    $delivery_date = wc_clean(wp_unslash($data['delivery_date']));
    $delivery_cycle = wc_clean(wp_unslash($data['delivery_cycle']));
    $delivery_time = wc_clean(wp_unslash($data['delivery_time']));

    update_post_meta($order_id, 'delivery_date', $delivery_date);
    update_post_meta($order_id, 'delivery_cycle',$delivery_cycle);
    update_post_meta($order_id, 'delivery_time', $delivery_time);
}

add_action('woocommerce_checkout_update_order_meta', 'gethalal_checkout_update_order_meta', 10, 2);


function gethalal_order_data_delivery_time(WC_Order $order){
    $order_id = $order->get_id();
    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {
    ?>
        <div class="order_data_column">
            <h3>
                <?php esc_html_e( 'Delivery Date&time', 'gethalal' ); ?>
            </h3>
            <div class="address">
                <?php
                    $order_delivery_date = get_post_meta($order_id, 'delivery_date', true);
                    $delivery_time_text = "";
                    if($order_delivery_date){
                        $delivery_times = Gethalal_Utils::delivery_times();
                        //$delivery_cycles = Gethalal_Utils::delivery_cycles();
                        //$order_delivery_cycle = get_post_meta($order_id, 'delivery_cycle', true);
                        $order_delivery_time = get_post_meta($order_id, 'delivery_time', true);
                        if($order_delivery_time != ""){
                            $delivery_time_text = sprintf(('%s, %s'), $order_delivery_date, $delivery_times[intval($order_delivery_time)]['text']);
                        } else {
                            $delivery_time_text = sprintf(('%s'), $order_delivery_date);
                        }
                    }
                    echo '<p>' . $delivery_time_text . '</p>';
                ?>
            </div>
        </div>
    <?php
    }
}

add_action('woocommerce_admin_order_data_after_shipping_address', 'gethalal_order_data_delivery_time', 10, 1);

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices');
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

// Add column of Delivery Date/Time on admin page / orders list
if(is_admin()){
    // Adding a custom new column to admin orders list
    add_filter( 'manage_edit-shop_order_columns', 'gethalal_custom_column_delivery_date', 20 );
    function gethalal_custom_column_delivery_date($columns)
    {
        $reordered_columns = $columns;
        $reordered_columns['delivery_date'] = __( 'Delivery Date','gethalal');

        return $reordered_columns;
    }

    // Adding custom fields meta data for the column
    add_action( 'manage_shop_order_posts_custom_column' , 'gethalal_custom_orders_list_column_content', 20, 2 );
    function gethalal_custom_orders_list_column_content( $column, $post_id )
    {
        if ( 'delivery_date' != $column ) return;

        global $the_order;

        // Get the customer id
        $order_id = $the_order->get_id();

        $order_delivery_date = get_post_meta($order_id, 'delivery_date', true);
        $delivery_time_text = "";
        if($order_delivery_date){
            $delivery_times = Gethalal_Utils::delivery_times();
            //$delivery_cycles = Gethalal_Utils::delivery_cycles();
            //$order_delivery_cycle = get_post_meta($order_id, 'delivery_cycle', true);
            $order_delivery_time = get_post_meta($order_id, 'delivery_time', true);
            if($order_delivery_time != ""){
                // TODO only one timeslot
                //$delivery_time_text = sprintf(('%s, %s'), $order_delivery_date, $delivery_times[intval($order_delivery_time)]['text']);
                $delivery_time_text = sprintf(('%s, %s'), $order_delivery_date, $delivery_times[0]['text']);
            } else {
                $delivery_time_text = sprintf(('%s'), $order_delivery_date);
            }
        }
        echo '<p>' . $delivery_time_text . '</p>';
    }

    // Make custom column sortable
    add_filter( "manage_edit-shop_order_sortable_columns", 'gethalal_order_column_meta_field_sortable' );
    function gethalal_order_column_meta_field_sortable( $columns )
    {
        $meta_key = 'delivery_date';
        return wp_parse_args( array('delivery_date' => $meta_key), $columns );
    }

}

//////////////////////////////////////////////////////////////////////////////////////////////////
///
///    Gethalal Custom login and register
///


function gethalal_flush_rewrites(){
    $url_path = trim(parse_url(str_replace(home_url(), '', gethalal_get_request_url()), PHP_URL_PATH), '/');
    switch ($url_path) {
        case 'login':
        case 'register':
        case 'forgot_password':
        case 'reset':
        case 'address':
            $template_load = locate_template('login.php', true);
            break;
// TODO Disabled Mobile Paypal Payment
//        case 'paypal':
//            $template_load = locate_template('paypal.php', true);
            break;
        default:
            $template_load = null;
    }
    if ($template_load) {
        exit(); // just exit if template was found and loaded
    }
}
add_action('init', 'gethalal_flush_rewrites', 10, 0);

/**
 * Output the login page header.
 *
 * @param string $title Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string $message Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
 * @global string $action The action that brought the visitor to the login page.
 *
 * @since 2.1.0
 *
 * @global string $error Login error message set by deprecated pluggable wp_login() function
 *                                    or plugins replacing it.
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 */
if (!function_exists('gethalal_login_header')) {
    function gethalal_login_header($title = 'Log In', $message = '', $wp_error = null) {

        global $error, $interim_login, $action;

        // Don't index any of these forms.
        add_filter('wp_robots', 'wp_robots_sensitive_page');
        add_action('login_head', 'wp_strict_cross_origin_referrer');

        //add_action( 'login_head', 'wp_login_viewport_meta' );

        if (!is_wp_error($wp_error)) {
            $wp_error = new WP_Error();
        }

        // Shake it!
        $shake_error_codes = array('empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password', 'retrieve_password_email_failure');
        /**
         * Filters the error codes array for shaking the login form.
         *
         * @param array $shake_error_codes Error codes that shake the login form.
         * @since 3.0.0
         *
         */
        $shake_error_codes = apply_filters('shake_error_codes', $shake_error_codes);

        if ($shake_error_codes && $wp_error->has_errors() && in_array($wp_error->get_error_code(), $shake_error_codes, true)) {
            add_action('login_footer', 'wp_shake_js', 12);
        }

        $login_title = get_bloginfo('name', 'display');

        /* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
        $login_title = sprintf(__('%1$s &lsaquo; %2$s &#8212; WordPress'), $title, $login_title);

        if (wp_is_recovery_mode()) {
            /* translators: %s: Login screen title. */
            $login_title = sprintf(__('Recovery Mode &#8212; %s'), $login_title);
        }

        /**
         * Filters the title tag content for login page.
         *
         * @param string $login_title The page title, with extra context added.
         * @param string $title The original page title.
         * @since 4.9.0
         *
         */
        $login_title = apply_filters('login_title', $login_title, $title);

        ?><!DOCTYPE html>
            <html <?php language_attributes(); ?>>
            <head>
                <meta http-equiv="Content-Type"
                      content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
                <title><?php echo $login_title; ?></title>
                <?php

                wp_enqueue_style('login');

                /*
                 * Remove all stored post data on logging out.
                 * This could be added by add_action('login_head'...) like wp_shake_js(),
                 * but maybe better if it's not removable by plugins.
                 */
                if ('loggedout' === $wp_error->get_error_code()) {
                    ?>
                    <script>if ("sessionStorage" in window) {
                            try {
                                for (var key in sessionStorage) {
                                    if (key.indexOf("wp-autosave-") != -1) {
                                        sessionStorage.removeItem(key)
                                    }
                                }
                            } catch (e) {
                            }
                        }
                        ;</script>
                    <?php
                }

                /**
                 * Enqueue scripts and styles for the login page.
                 *
                 * @since 3.1.0
                 */
                do_action('login_enqueue_scripts');

                /**
                 * Fires in the login page header after scripts are enqueued.
                 *
                 * @since 2.1.0
                 */
                do_action('login_head');

                $login_header_url = __('https://wordpress.org/');

                /**
                 * Filters link URL of the header logo above login form.
                 *
                 * @param string $login_header_url Login header logo URL.
                 * @since 2.1.0
                 *
                 */
                $login_header_url = apply_filters('login_headerurl', $login_header_url);

                $login_header_title = '';

                /**
                 * Filters the title attribute of the header logo above login form.
                 *
                 * @param string $login_header_title Login header logo title attribute.
                 * @deprecated 5.2.0 Use {@see 'login_headertext'} instead.
                 *
                 * @since 2.1.0
                 */
                $login_header_title = apply_filters_deprecated(
                    'login_headertitle',
                    array($login_header_title),
                    '5.2.0',
                    'login_headertext',
                    __('Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead.')
                );

                $login_header_text = empty($login_header_title) ? __('Powered by WordPress') : $login_header_title;

                /**
                 * Filters the link text of the header logo above the login form.
                 *
                 * @param string $login_header_text The login header logo link text.
                 * @since 5.2.0
                 *
                 */
                $login_header_text = apply_filters('login_headertext', $login_header_text);

                $classes = array('login-action-' . $action, 'wp-core-ui');

                if (is_rtl()) {
                    $classes[] = 'rtl';
                }

                if ($interim_login) {
                    $classes[] = 'interim-login';

                    ?>
                    <style type="text/css">html {
                            background-color: transparent;
                        }</style>
                    <?php

                    if ('success' === $interim_login) {
                        $classes[] = 'interim-login-success';
                    }
                }

                $classes[] = ' locale-' . sanitize_html_class(strtolower(str_replace('_', '-', get_locale())));

                /**
                 * Filters the login page body classes.
                 *
                 * @param array $classes An array of body classes.
                 * @param string $action The action that brought the visitor to the login page.
                 * @since 3.5.0
                 *
                 */
                $classes = apply_filters('login_body_class', $classes, $action);

                ?>
            </head>
            <body class="login gethalal-login no-js <?php echo esc_attr(implode(' ', $classes)); ?>">
            <script type="text/javascript">
                document.body.className = document.body.className.replace('no-js', 'js');
            </script>
            <?php
            /**
             * Fires in the login page header after the body tag is opened.
             *
             * @since 4.6.0
             */
            do_action('login_header');

            ?>
            <div id="login">
        <?php
        /**
         * Filters the message to display above the login form.
         *
         * @param string $message Login message text.
         * @since 2.1.0
         *
         */
        $message = apply_filters('login_message', $message);

        if (!empty($message)) {
            echo $message . "\n";
        }

        // In case a plugin uses $error rather than the $wp_errors object.
        if (!empty($error)) {
            $wp_error->add('error', $error);
            unset($error);
        }

        if ($wp_error->has_errors()) {
            $errors = '';
            $messages = '';

            foreach ($wp_error->get_error_codes() as $code) {
                $severity = $wp_error->get_error_data($code);
                foreach ($wp_error->get_error_messages($code) as $error_message) {
                    if ('message' === $severity) {
                        $messages .= '	' . $error_message . "<br />\n";
                    } else {
                        $errors .= '	' . $error_message . "<br />\n";
                    }
                }
            }

            if (!empty($errors)) {
                /**
                 * Filters the error messages displayed above the login form.
                 *
                 * @param string $errors Login error message.
                 * @since 2.1.0
                 *
                 */
                echo '<div id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
            }

            if (!empty($messages)) {
                /**
                 * Filters instructional messages displayed above the login form.
                 *
                 * @param string $messages Login messages.
                 * @since 2.5.0
                 *
                 */
                echo '<p class="message">' . apply_filters('login_messages', $messages) . "</p>\n";
            }
        }
    } // End of login_header().
}

/**
 * Outputs the footer for the login page.
 *
 * @param string $input_id Which input to auto-focus.
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 *
 * @since 3.1.0
 *
 */
if (!function_exists('gethalal_login_footer')) {
    function gethalal_login_footer($input_id = '') {
            global $interim_login;

            // Don't allow interim logins to navigate away from the page.
    //        if (!$interim_login) {
    //
    //            the_privacy_policy_link('<div class="privacy-policy-page-link">', '</div>');
    //        }

            ?>
        </div><?php // End of <div id="login">. ?>

        <?php

        if (!empty($input_id)) {
            ?>
            <script type="text/javascript">
                try {
                    document.getElementById('<?php echo $input_id; ?>').focus();
                } catch (e) {
                }
                if (typeof wpOnload === 'function') wpOnload();
            </script>
            <?php
        }

        /**
         * Fires in the login page footer.
         *
         * @since 3.1.0
         */
        do_action('login_footer');

        ?>
        <div class="clear"></div>
        </body>
        </html>
        <?php
    }
}


if(!function_exists('gethalal_log')){
    function gethalal_log($message) {
        // Record Logs
        $show_log = false;

        if($show_log){
            $logger = wc_get_logger();
            $logger->info($message, array('source' => 'gethalal'));
        }
    }
}
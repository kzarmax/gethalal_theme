<?php
/**
 * Displays header site branding
 *
 * @package Get Halal
 */

$blog_info    = get_bloginfo( 'name' );
$description  = get_bloginfo( 'description', 'display' );
$show_title   = ( true === get_theme_mod( 'display_title_and_tagline', true ) );
$header_class = $show_title ? 'site-title' : 'screen-reader-text';

$search_text = get_search_query();
$is_searching = strlen($search_text) > 0;
if(is_checkout()) :
?>

<div class="site-branding checkout-header" style="justify-content: center;">
    <?php if ( has_custom_logo() ) : ?>
        <div class="site-logo"><a href="<?php echo site_url() ?>"><?php the_custom_logo(); ?></a></div>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="site-branding main-page-header">
    <div class="header-left">
        <?php if ( has_custom_logo() ) : ?>
            <div class="site-logo"><a href="<?php echo site_url() ?>"><?php the_custom_logo(); ?></a></div>
        <?php endif; ?>
        <?php gethalal_dialog_zip_code(); ?>
        <div class="input-container">
<!--            Hide Input Zip Code-->
<!--            <div class="zip-container">-->
<!--                --><?php //gethalal_zipcode_menu(); ?>
<!--            </div>-->
            <div class="search-container">
                <form role="search" method="get" id="searchform" class="searchform search-box" action="<?php echo esc_url( home_url() ); ?>">
                    <img class="desktop-search-icon" src="<?php echo get_template_directory_uri() . '/assets/images/search.png' ?>" alt="search"/>
                    <input type="text" name="s" class="search-text-input" value="<?php echo $search_text ?>" placeholder="<?php echo __('Search for products you want...', 'gethalal') ?>">
                    <a href="<?php echo apply_filters('gethalal_shop_url', array()) ?>" class="search-clear <?php echo $is_searching?'is-searching':'' ?>">
                        <?php echo gethalal_get_icon_svg( 'ui', 'close', 16 ) ?>
                    </a>
                    <input type="hidden" name="post_type" value="product" />
                </form>
            </div>
        </div>

    </div>

    <div class="header-right">
        <div class="cart-container">
            <?php gethalal_cart_menu(); ?>
        </div>

        <div class="language-container">
            <?php gethalal_language_menu(); ?>
        </div>

        <div class="profile-container">
            <?php gethalal_logged_in_menu(); ?>
        </div>
    </div>
</div>

<div class="site-branding-app">
    <div class="header-left">
        <div class="gethalal-app-menu">
            <img class="side_menu_icon" src="<?php echo get_template_directory_uri() . '/assets/images/side_menu.png' ?>" alt="side_menu"/>
            <?php gethalal_account_menus(); ?>
        </div>
        <div class="language-container-app">
            <?php gethalal_language_menu(true); ?>
        </div>
    </div>
    <div class="site-logo-app"><a href="<?php echo site_url() ?>"><?php the_custom_logo(); ?></a></div>
    <div class="header-right">
        <div class="app-search-container">
            <a href="<?php echo home_url('/?s=&post_type=product') ?>">
                <img class="search_icon" src="<?php echo get_template_directory_uri() . '/assets/images/search.png' ?>" alt="search"/>
            </a>
        </div>
        <div class="cart-container">
            <?php gethalal_cart_menu(); ?>
        </div>
    </div>
</div>
<!-- .site-branding -->

<?php endif; ?>

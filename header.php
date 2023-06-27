<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Get Halal
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php gethalal_the_html_classes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
	<?php wp_head(); ?>
</head>

<body <?php body_class('woocommerce'); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gethalal' ); ?></a>

<!--    --><?php //if( is_home() || is_front_page() ){?>
<!--        <div class="trustpilot-reviews">-->
<!--            <img src="--><?php //echo get_template_directory_uri() . '/assets/images/trustpilot_logo.png' ?><!--" alt="trustpilot-logo"/>-->
<!--            <img class="review-stars" src="--><?php //echo get_template_directory_uri() . '/assets/images/trustpilot_stars.png' ?><!--" alt="trustpilot-reviews"/>-->
<!--            <span class="review-five-counter">4,89/5</span>-->
<!--            <span class="review-users">Based On 124 reviews</span>-->
<!--        </div>-->
<!--    --><?php //} ?>
	<?php get_template_part( 'template-parts/header/site-header' ); ?>

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

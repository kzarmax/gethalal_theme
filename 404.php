<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Get Halal
 */

get_header();
?>

<div class="main-container">
    <div class="main-content">
        <header class="page-header alignwide">
            <div class="page-title"><?php esc_html_e( 'Nothing here', 'gethalal' ); ?></div>
        </header><!-- .page-header -->

        <div class="error-404 not-found default-max-width">
            <div class="page-content">
                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'gethalal' ); ?></p>
            </div><!-- .page-content -->
        </div><!-- .error-404 -->
    </div>
</div>

<?php
get_footer();

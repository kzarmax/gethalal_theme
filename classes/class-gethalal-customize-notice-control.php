<?php
/**
 * Customize API: Gethalal_Customize_Notice_Control class
 *
 * @package Get Halal
 */

/**
 * Customize Notice Control class.
 *
 * @since Get Halal 1.0
 *
 * @see WP_Customize_Control
 */
class Gethalal_Customize_Notice_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @since Get Halal 1.0
	 *
	 * @var string
	 */
	public $type = 'gethalal-notice';

	/**
	 * Renders the control content.
	 *
	 * This simply prints the notice we need.
	 *
	 * @since Get Halal 1.0
	 *
	 * @return void
	 */
	public function render_content() {
		?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'To access the Dark Mode settings, select a light background color.', 'gethalal' ); ?></p>
			<p><a href="<?php echo esc_url( __( 'https://wordpress.org/support/article/gethalal/#dark-mode-support', 'gethalal' ) ); ?>">
				<?php esc_html_e( 'Learn more about Dark Mode.', 'gethalal' ); ?>
			</a></p>
		</div>
		<?php
	}
}

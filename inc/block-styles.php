<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package Get Halal
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 *
	 * @since Get Halal 1.0
	 *
	 * @return void
	 */
	function gethalal_register_block_styles() {
		// Columns: Overlap.
		register_block_style(
			'core/columns',
			array(
				'name'  => 'gethalal-columns-overlap',
				'label' => esc_html__( 'Overlap', 'gethalal' ),
			)
		);

		// Cover: Borders.
		register_block_style(
			'core/cover',
			array(
				'name'  => 'gethalal-border',
				'label' => esc_html__( 'Borders', 'gethalal' ),
			)
		);

		// Group: Borders.
		register_block_style(
			'core/group',
			array(
				'name'  => 'gethalal-border',
				'label' => esc_html__( 'Borders', 'gethalal' ),
			)
		);

		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'gethalal-border',
				'label' => esc_html__( 'Borders', 'gethalal' ),
			)
		);

		// Image: Frame.
		register_block_style(
			'core/image',
			array(
				'name'  => 'gethalal-image-frame',
				'label' => esc_html__( 'Frame', 'gethalal' ),
			)
		);

		// Latest Posts: Dividers.
		register_block_style(
			'core/latest-posts',
			array(
				'name'  => 'gethalal-latest-posts-dividers',
				'label' => esc_html__( 'Dividers', 'gethalal' ),
			)
		);

		// Latest Posts: Borders.
		register_block_style(
			'core/latest-posts',
			array(
				'name'  => 'gethalal-latest-posts-borders',
				'label' => esc_html__( 'Borders', 'gethalal' ),
			)
		);

		// Media & Text: Borders.
		register_block_style(
			'core/media-text',
			array(
				'name'  => 'gethalal-border',
				'label' => esc_html__( 'Borders', 'gethalal' ),
			)
		);

		// Separator: Thick.
		register_block_style(
			'core/separator',
			array(
				'name'  => 'gethalal-separator-thick',
				'label' => esc_html__( 'Thick', 'gethalal' ),
			)
		);

		// Social icons: Dark gray color.
		register_block_style(
			'core/social-links',
			array(
				'name'  => 'gethalal-social-icons-color',
				'label' => esc_html__( 'Dark gray', 'gethalal' ),
			)
		);
	}
	add_action( 'init', 'gethalal_register_block_styles' );
}

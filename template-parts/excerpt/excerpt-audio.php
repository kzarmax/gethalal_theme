<?php
/**
 * Show the appropriate content for the Audio post format.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Get Halal
 */

$content = get_the_content();

if ( has_block( 'core/audio', $content ) ) {
	gethalal_print_first_instance_of_block( 'core/audio', $content );
} elseif ( has_block( 'core/embed', $content ) ) {
	gethalal_print_first_instance_of_block( 'core/embed', $content );
} else {
	gethalal_print_first_instance_of_block( 'core-embed/*', $content );
}

// Add the excerpt.
the_excerpt();

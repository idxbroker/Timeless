<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Curb Appeal
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'equity_child_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function equity_child_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_equity_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['equity_child_metabox'] = array(
		'id'         => 'equity_child_metabox',
		'title'      => __( 'Single Post Options', 'timeless' ),
		'pages'      => array( 'page', 'post', 'listing', 'idx-wrapper' ), // Post type
		'context'    => 'side',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Custom Background Image', 'timeless' ),
				'desc' => __( 'Upload an image to use as the background for this post.', 'timeless' ),
				'id'   => $prefix . 'single_post_background',
				'type' => 'file',
			),
			array(
				'name' => __( 'Disable Background', 'timeless' ),
				'desc' => __( 'Check to disable the background image on this post.', 'timeless' ),
				'id'   => $prefix . 'disable_single_post_background',
				'type' => 'checkbox',
			),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'equity_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function equity_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once EQUITY_CLASSES_DIR . '/metaboxes/init.php';

}

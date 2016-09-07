<?php

/* Register Impacts post type */
$args = array(
  'labels' => array(
		'name'               => _x( 'Impacts', 'post type general name', 'mori' ),
		'singular_name'      => _x( 'Impact', 'post type singular name', 'mori' ),
		'menu_name'          => _x( 'Impacts', 'admin menu', 'mori' ),
		'name_admin_bar'     => _x( 'Impact', 'add new on admin bar', 'mori' ),
		'add_new'            => _x( 'Add New Impact', 'Impact', 'mori' ),
		'add_new_item'       => __( 'Add New Impact', 'mori' ),
		'new_item'           => __( 'New Impact', 'mori' ),
		'edit_item'          => __( 'Edit Impact', 'mori' ),
		'view_item'          => __( 'View Impact', 'mori' ),
		'all_items'          => __( 'All Impacts', 'mori' ),
		'search_items'       => __( 'Search Impacts', 'mori' ),
		'parent_item_colon'  => __( 'Parent Impacts:', 'mori' ),
		'not_found'          => __( 'No Impacts found.', 'mori' ),
		'not_found_in_trash' => __( 'No Impacts found in Trash.', 'mori' )
  ),
  'public' => false,
  'show_ui' => true,
);
register_post_type( 'impact', $args );

/* Disable default location for Impacts post type and move it to the plugin menu */
add_action('admin_menu', function() {

  remove_menu_page( 'edit.php?post_type=impact' );
  add_menu_page( 'MORI', 'MORI', 'manage_options', 'edit.php?post_type=impact');

});

?>

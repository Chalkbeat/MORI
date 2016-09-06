<?php

register_taxonomy(
	'impact_type',
	'impact',
	array(
    'labels' => array(
  		'name'              => _x( 'Impact Types', 'taxonomy general name' ),
  		'singular_name'     => _x( 'Impact Type', 'taxonomy singular name' ),
  		'search_items'      => __( 'Search Impact Types' ),
  		'all_items'         => __( 'All Impact Types' ),
  		'parent_item'       => __( 'Parent Impact Type' ),
  		'parent_item_colon' => __( 'Parent Impact Type:' ),
  		'edit_item'         => __( 'Edit Impact Type' ),
  		'update_item'       => __( 'Update Impact Type' ),
  		'add_new_item'      => __( 'Add New Impact Type' ),
  		'new_item_name'     => __( 'New Impact Type' ),
  		'menu_name'         => __( 'Impact Types' ),
  	),
		'hierarchical' => true,
    'query_var'         => false,
	)
);

?>

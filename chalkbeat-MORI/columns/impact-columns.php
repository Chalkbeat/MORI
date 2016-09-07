<?php

add_filter('manage_edit-impact_columns', function($columns) {
  $new_columns['cb'] = '<input type="checkbox" />';
  $new_columns['title'] = _x('Title', 'column name');
  $new_columns['author'] = __('Author');

  // Create columns based on MORI Settings
  $active_taxonomies = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();
  foreach($active_taxonomies as $taxonomy) {
    $taxonomy = get_taxonomy($taxonomy);
    $new_columns[$taxonomy->name] = _x($taxonomy->labels->name, 'column name');
  }

  $new_columns['date'] = _x('Date', 'column name');
  return $new_columns;
});

/*
  Populate data into columns according to MORI Settings
*/
add_action( 'manage_impact_posts_custom_column' , function($column, $post_id) {

  $columns = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();

  if ( in_array($column, $columns) ) {

      $post_meta = get_post_meta($post_id);
      $impact_taxonomies = get_object_taxonomies('impact');

      if ( in_array($column, $impact_taxonomies) ){

        $terms = get_the_term_list( $post_id, $column, '', ', ', '' );
        echo $terms;

    } elseif ( isset($post_meta['mori_assigned_type']) && is_array($post_meta['mori_assigned_type']) && in_array('single', $post_meta['mori_assigned_type']) && !isset($impact_taxonomies[$column]) ) {

        $terms = get_the_term_list( $post_meta['mori_assigned_id'][0], $column, '', ', ', '' );

        if ( is_string( $terms ) ) {
          echo $terms;
        } else {
          _e( 'Unable to get terms(s)', 'mori' );
        }

      } else {
        echo '';
      }

  }

}, 10, 2 );


 ?>

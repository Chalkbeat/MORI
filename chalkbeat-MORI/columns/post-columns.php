<?php

add_filter('manage_edit-post_columns', function($columns) {
  unset($columns['date']);
  $impact_columns = array(
    'impact' => 'Impacts',
    'date' => 'Date',
  );
  return array_merge($columns, $impact_columns);
});

/*
  Populate data into columns according to MORI Settings
*/
add_action( 'manage_post_posts_custom_column' , function($column, $post_id) {

  if ($column == 'impact') {

    $impacts = new WP_Query(array(
      'post_type' => 'impact',
      'posts_per_page' => -1,
      'meta_key' => 'mori_assigned_id',
      'meta_value' => $post_id,
      'order' => 'ASC',
    ));

    if ( count($impacts->posts) >= 1 ) {

      foreach($impacts->posts as $i=>$impact) { ?>
        <a href="<?php echo get_edit_post_link($impact->ID); ?>"><?php echo $impact->post_title; ?></a><?php if ( isset($impacts->posts[$i+1]) ) { ?>, <?php } ?>
    <?php }

    } else { ?>
      <span aria-hidden="true">—</span>
    <?php }

  }

}, 10, 2 );

 ?>

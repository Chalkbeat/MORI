<?php

add_action('restrict_manage_posts', function() {
  global $typenow;
  $active_taxonomies = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();

  foreach($active_taxonomies as $taxonomy) {
    $post_type = 'impact'; // change to your post type
    if ($typenow == $post_type) {
      $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
      $info_taxonomy = get_taxonomy($taxonomy);
      wp_dropdown_categories(array(
        'show_option_all' => __("Show All {$info_taxonomy->label}"),
        'taxonomy'        => $taxonomy,
        'name'            => $taxonomy,
        'orderby'         => 'name',
        'selected'        => $selected,
        'show_count'      => true,
        'hide_empty'      => true,
      ));
    };
  }
});

add_filter('pre_get_posts', function($query) {

  if ( $query->is_main_query() ) {

    global $pagenow;
    $active_taxonomies = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();
    $matches = array();
    $post_type = 'impact'; // change to your post type
    $query_vars = &$query->query_vars;

    foreach($active_taxonomies as $taxonomy) {
      if ( $pagenow == 'edit.php' && isset($query_vars['post_type']) && $query_vars['post_type'] == $post_type && isset($_GET[$taxonomy]) && $_GET[$taxonomy] != 0 ) {
        $term = get_term_by('id', $_GET[$taxonomy], $taxonomy);
        $matches = (isset($term)) ? mori_get_impacts_by_taxonomy($term, $matches) : array();
        $query_vars[$taxonomy] = 0;
      }
    }

    $query_vars['post__in'] = array_unique($matches);

  }

});

/*
Filters all impacts assigned to posts by a taxonomy term. This lets us use filters
on the admin pages that work against the taxonomy of the assigned posts as well as
against taxonomy applied to the impacts themselves.

Returns an array of matching post IDs.
*/
function mori_get_impacts_by_taxonomy($term, $already_matched = array()) {
  $impacts = new WP_Query( array(
    'post_type' => 'impact',
    'posts_per_page' => -1,
    'meta_key' => 'mori_assigned_type',
    'meta_value' => 'single',
    'post__in' => $already_matched,
    'fields' => 'ids',
  ));
  $matching_impacts = array();

  foreach($impacts->posts as $impact) {
    $assigned_id = get_post_meta($impact, 'mori_assigned_id');

    if ( has_term($term->term_id, $term->taxonomy, $assigned_id[0]) ) {
      $matching_impacts[] = $impact;
    }
  }
  return $matching_impacts;

}

 ?>

<?php
/*
Create Impact post based on the form submissions
*/

if ($impact_narrative) {

  $impact_post = array(
    'ID'       => (isset($_POST['impact_id'])) ? $_POST['impact_id'] : 0,
    'post_type'     => 'impact',
    'post_title'    => wp_strip_all_tags( $_POST['mori_post_title'] ),
    'post_content'  => $impact_narrative,
    'post_status'   => 'publish',
    'post_author'   => $_POST['user'],
  );

  $post_id = wp_insert_post( $impact_post );

  add_post_meta($post_id, 'mori_assigned_post_type', $_POST['mori_assigned_post_type']);

  add_post_meta($post_id, 'mori_assigned_id', $_POST['mori_assigned_id']);

}


?>

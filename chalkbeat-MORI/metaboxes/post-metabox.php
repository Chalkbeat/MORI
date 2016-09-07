<?php

/* Add Meta Boxes for MORI to posts */
add_action('add_meta_boxes_post', function($post) {
  add_meta_box(
    'assigned_impacts',
    __( 'Impacts Assigned to "<em>'.$post->post_title.'</em>"' ),
    'MORI_post_metabox',
    'post',
    'normal',
    'default'
  );
});

/* Impact Assignemnt meta box */
function MORI_post_metabox($post) {
  $impacts = new WP_Query(array(
    'post_type' => 'impact',
    'posts_per_page' => -1,
    'meta_key' => 'mori_assigned_id',
    'meta_value' => $post->ID,
    'order' => 'ASC',
  ));

  if ($impacts->posts) { ?>

    <ol>
      <?php foreach ($impacts->posts as $post) { ?>
        <li>
          <a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo $post->post_title; ?></a>
        </li>
      <?php } ?>
    </ol>
    <p>
      <strong><a title="Add a new impact" href="<?php echo get_admin_url(); ?>post-new.php?post_type=impact" taget="_blank">+ Add New Impact</a></strong>
    </p>

  <?php } else { ?>
    <p>
      This post has impacts assigned to it.
      <strong><a title="Add a new impact" href="<?php echo get_admin_url(); ?>post-new.php?post_type=impact" taget="_blank">Want to add one?</a></strong>
    </p>
  <?php } ?>

  <?php
}

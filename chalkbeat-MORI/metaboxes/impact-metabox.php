<?php

/* Add Meta Boxes for MORI to Impact posts */
add_action('add_meta_boxes_impact', function($post) {
  add_meta_box(
    'impact_assignment',
    __( 'Impact Assignment' ),
    'MORI_add_impact_meta',
    'impact',
    'normal',
    'default'
  );
});


/* Impact Assignemnt meta box */
function MORI_add_impact_meta($post) {
  $post_meta = get_post_meta($post->ID);

  $url = isset($post_meta['mori_assigned_url']) ? parse_url($post_meta['mori_assigned_url'][0]) : null;
  $taxonomy_name = isset($post_meta['mori_assigned_taxonomy_name']) ? $post_meta['mori_assigned_taxonomy_name'][0] : null;
  $taxonomy_term = isset($post_meta['mori_assigned_taxonomy_term']) ? $post_meta['mori_assigned_taxonomy_term'][0] : null;

  include('templates/submit-url.php');

};

function mori_save_impact_meta($post_id, $post) {

  global $pagenow;
  if ( !in_array($pagenow, array('post.php', 'post-new.php')) ) return $post_id;

  // Declare which meta to save on which type of assignment
  $metadata = array(
    'mori_assigned_url',
    'mori_assigned_type',
    'mori_assigned_post_type',
    'mori_assigned_id',
    'mori_assigned_taxonomy_name',
    'mori_assigned_taxonomy_term',
  );

  // Can never be too safe
  if (!current_user_can("edit_post", $post_id)) return $post_id;
  if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return $post_id;
  $slug = "impact";
  if ($slug != $post->post_type) return $post_id;

  $post_meta = get_post_meta($post->ID);
  $url = isset($post_meta['mori_assigned_url']) ? parse_url($post_meta['mori_assigned_url'][0]) : parse_url($_POST['mori_assigned_url']);

  $assigned_post_id = url_to_postid($url['path']);

  if ($assigned_post_id) {

  $assigned_post = get_post($assigned_post_id);

    if ( !isset($_POST['mori_assigned_id']) || !isset($_POST['mori_assigned_type']) ) {

        $_POST['mori_assigned_id'] = $assigned_post->ID;
        $_POST['mori_assigned_post_type'] = $assigned_post->post_type;
        $_POST['mori_assigned_type'] = 'single';

    }

    // Update each metadata property of the active type
    foreach($metadata as $meta_box) {

      if(isset($_POST[$meta_box])) {

        update_post_meta($post_id, $meta_box, $_POST[$meta_box]);

      } else {

        delete_post_meta($post_id, $meta_box);

      }

    }

  }

}

// Save metabox meta data to impact post
add_action('edit_impact', 'mori_save_impact_meta', 10, 3);
add_action('save_impact', 'mori_save_impact_meta', 10, 3);
//add_action('edit_page_form', 'mori_save_impact_meta', 10, 3);
add_action('publish_impact', 'mori_save_impact_meta', 10, 3);

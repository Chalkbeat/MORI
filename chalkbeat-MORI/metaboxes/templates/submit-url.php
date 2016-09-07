<?php

wp_enqueue_style( 'mori_metaboxes', MORI_BASE_URL . '/css/mori-admin.css' );

$assigned_post = url_to_postid($url['path']);

if (empty($url['path']) || $assigned_post == 0 ) { ?>

  <?php if ($assigned_post) {
    $url = false;
  } ?>

<?php } elseif ( url_to_postid($url['path']) ) {

  $assigned_post_id = url_to_postid($url['path']);
  $assigned_post = get_post($assigned_post_id);
  $assigned_post_meta = get_post_meta($assigned_post_id);
  $assigned_post_type = get_post_type_object($assigned_post->post_type);
  ?>

  <div class="mori-impact-metabox">

    <h2>
      <strong><a href="<?php echo $assigned_post->guid; ?>" target="_blank"><?php echo $assigned_post->post_title; ?></a></strong>
      &mdash; <?php echo $assigned_post_type->labels->singular_name; ?>
    </h2>

    <?php
    $active_taxonomies = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();
    if (count($active_taxonomies) > 1) { ?>
      <table class="mori-impacts-table">
        <thead>
          <tr class="title-row">
            <th>Taxonomy</th>
            <th>Terms</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($active_taxonomies as $taxonomy) {

            $taxonomy = get_taxonomy($taxonomy);

            if ($taxonomy->public == true && $taxonomy->name !== 'post_format') { ?>
              <tr>
                <th><?php echo $taxonomy->labels->name; ?></th>
                <td>
                  <?php echo get_the_term_list($assigned_post_id, $taxonomy->name,' ', ', '); ?>
                </td>
              </tr>
              <?php }
            } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>

  <input type="hidden" name="mori_assigned_type" value="single">
  <input type="hidden" name="mori_assigned_id" value="<?php echo $assigned_post_id; ?>">
  <br/>

<?php } ?>

<table class="form-table">
  <tbody>
    <tr>
      <th><label for="title">Assigned URL</label></th>
      <td>
        <input class="large-text" size="30" id="title" type="url" name="mori_assigned_url" value="<?php if ($url) {  echo $url['scheme'].'://'.$url['host'].$url['path']; } ?>" placeholder="The URL for the page you want to add an impact for" />
      </td>
    </tr>
  </tbody>
</table>

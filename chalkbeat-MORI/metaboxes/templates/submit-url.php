<?php

wp_enqueue_style( 'mori_metaboxes', MORI_BASE_FOLDER.'/css/mori-admin.css' );

$assinged_post = url_to_postid($url['path']);

if (empty($url['path']) || $assinged_post == 0 ) { ?>

  <?php if ($assinged_post) {
    $url = false;
  } ?>

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

  <br/>

  <!-- <input value="Check URL" type="submit" class="button button-primary"> -->

  <br/>

<?php } else {

  // Show post assigned to
  if ( url_to_postid($url['path']) ) {

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

    <input type="hidden" name="mori_assigned_type" value="single">
    <input type="hidden" name="mori_assigned_id" value="<?php echo $assigned_post_id; ?>">
    <br/>

    <?php
  }

  // Show taxonomy assigned to
  if ($taxonomy_term) {
    $assigned_term = get_term_by('id', $taxonomy_term, $taxonomy_name);
    $assigned_term_taxonomy = get_taxonomy($assigned_term->taxonomy);
    ?>

    <div class="mori-impact-metabox">
      <h2>
        <strong><a href="<?php echo get_term_link($assigned_term->term_id, $taxonomy_name); ?>" target="_blank"><?php echo $assigned_term->name; ?></a></strong>
        &mdash; <?php echo $assigned_term_taxonomy->labels->name; ?>
      </h2>

      <table class="mori-impacts-table">
        <tbody>
          <?php if ($assigned_term->description) { ?>
          <tr>
            <th>Description</th>
            <td><?php echo $assigned_term->description; ?></td>
          </tr>
          <?php } ?>
          <?php if ($assigned_term->count) { ?>
          <tr>
            <th># of Posts</th>
            <td><?php echo number_format($assigned_term->count); ?></td>
          </tr>
          <?php } ?>
          <?php if ($assigned_term->count) { ?>
          <tr>
            <th>Recent Posts</th>
            <td>
              <?php
              $recent_posts = wp_get_recent_posts(array(
                'numberposts' => 3,
                'post_type' => array('post', 'page'),
                'tax_query' => array(
            			array(
            				'taxonomy' => $assigned_term->taxonomy,
            				'field' => 'slug',
            				'terms' => $assigned_term->slug,
            				'operator' => 'IN'
            			),
                ),
              ));
              ?>

              <ul class="postlist">
              <?php foreach($recent_posts as $post) { ?>
                <li>
                  <a href="<?php echo get_permalink($post); ?>" target="_blank">
                    <?php echo $post['post_title']; ?>
                  </a>
                  &mdash; <?php echo date('F j, Y', strtotime($post['post_date'])); ?>
                </li>

                <?php } ?>
              </ul>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

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

    <input type="hidden" name="mori_assigned_type" value="archive">
    <input type="hidden" name="mori_assigned_taxonomy_name" value="<?php echo $taxonomy_name; ?>">
    <input type="hidden" name="mori_assigned_taxonomy_term" value="<?php echo $assigned_term->term_id; ?>">
    <br/>
  <?php } ?>

<?php } ?>

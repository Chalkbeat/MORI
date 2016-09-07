<?php

add_action('admin_menu', function() {
  add_submenu_page( 'edit.php?post_type=impact', 'Data Migration', 'Data Migration', 'manage_options', 'mori_data_migration_page', 'mori_data_migration_page');
});

function mori_data_migration_page() { ?>

  <div class="wrap">

    <h1>Migrate Old MORI Data</h1>

    <?php

    $old_posts = new WP_Query(array(
      'posts_per_page' => -1,
      // 'date_query' => array(
      //   array(
      //     'after'     => 'July 1st, 2015',
      //     'inclusive' => true,
      //   ),
      // ),
      'meta_query' => array(
        // 'relation' => 'AND',
        array(
          'relation' => 'OR',
          array(
      			'key'     => 'civic_deliberations',
      			'value'   => 'a:0:{}',
      			'compare' => '!=',
      		),
          array(
      			'key'     => 'informed_actions',
      			'value'   => 'a:0:{}',
      			'compare' => '!=',
      		),
        ),
        // array(
        //   'key'     => 'mori_translated_to_mori2',
        //   'value'   => 'true',
        //   'compare' => '!=',
        // ),
      ),
    ));

    if ( !isset($_POST['import_old_posts']) || $_POST['import_old_posts'] == false ) { ?>

      <form method="post">

        <p>Unimported data found Data for <strong><?php echo count($old_posts->posts); ?></strong> impacts.</p>

        <?php submit_button('Import Data', 'primary large', 'import_old_posts'); ?>

      </form>

    <?php } else { ?>

      <?php if ( $old_posts->have_posts() ) { ?>
        <ul>
        <?php while ( $old_posts->have_posts() ) {
  		    $old_posts->the_post();
          if ( !mori_import_old_post(get_the_ID()) ) { ?>
          <li>
            <h4><?php the_title(); ?></h4>
          </li>
          <?php } ?>
        <?php } ?>
        </ul>

      <?php } else { ?>

        <p>Post's MORI data imported successfully.</p>

      <?php } ?>

    <?php } ?>

  </div>

<?php }

/* Handle the actual work of translating old data assigned to posts to the new post type */
function mori_import_old_post($post_id) {

  /* Import each "civic deliberation" on a post */
  $civic_deliberations = get_post_meta(get_the_ID(), 'civic_deliberations');

  $original_post = get_post($post_id);

  foreach($civic_deliberations as $deliberation) {

    foreach($deliberation as $key => $postdata) {

      if ( isset($postdata['impact']) && isset($postdata['narrative']) ) {

        $post = wp_insert_post(array(
          'post_type' => 'impact',
          'post_status' => 'publish',
          'post_title' => $postdata['impact'],
          'post_date' => $original_post->post_date,
          'post_content' => $postdata['narrative'],
        ));

        add_post_meta($post, 'mori_assigned_url', get_the_permalink($original_post->ID));
        add_post_meta($post, 'mori_assigned_type', 'single');
        add_post_meta($post, 'mori_assigned_post_type', $original_post->post_type);
        add_post_meta($post, 'mori_assigned_id', $original_post->ID);

      }

    }

  }

  /* Import each "informed action" on a post */
  $informed_actions = get_post_meta(get_the_ID(), 'informed_actions');

  foreach($informed_actions as $action) {

    foreach($action as $key => $postdata) {

      if ( isset($postdata['impact']) && isset($postdata['narrative']) ) {

        $post = wp_insert_post(array(
          'post_type' => 'impact',
          'post_status' => 'publish',
          'post_title' => $postdata['impact'],
          'post_date' => $original_post->post_date,
          'post_content' => $postdata['narrative'],
        ));

        add_post_meta($post, 'mori_assigned_url', get_the_permalink($original_post->ID));
        add_post_meta($post, 'mori_assigned_type', 'single');
        add_post_meta($post, 'mori_assigned_post_type', $original_post->post_type);
        add_post_meta($post, 'mori_assigned_id', $original_post->ID);

      }

    }

  }

  //add_post_meta($original_post->ID, 'mori_translated_to_mori2', 'true');

  return true;
}

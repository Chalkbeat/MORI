<?php

add_action( 'wp_dashboard_setup', function() {
  if (  current_user_can('editor') || current_user_can('administrator') ) {
    wp_add_dashboard_widget(
      'mori_impacts_dashboard_widget',
      'Recent MORI Impacts',
      'mori_impacts_dashboard_widget');
  }
});

function mori_impacts_dashboard_widget() {
  $recent_impacts = wp_get_recent_posts(array(
    'numberposts' => 5,
    'post_type' => 'impact',
    'post_status' => 'publish',
  ));
  ?>

  <div id="published-posts">

    <ul>

    <?php foreach ($recent_impacts as $post) { ?>
      <li>
        <span><?php echo date('F jS, g:i a', strtotime($post['post_date'])); ?></span>
        <a href="<?php echo get_admin_url(); ?>post.php?post=<?php echo $post['ID']; ?>&action=edit"><?php echo $post['post_title']; ?></a>
      </li>
    <?php } ?>

    </ul>
  </div>
  <div id="dashboard_activity">
    <ul class="subsubsub">
      <li>
        <a class="button button-primary button-small" href="<?php echo get_admin_url(); ?>post-new.php?post_type=impact">Add Impact</a> |
      </li>
    	<li class="all"><a href="<?php echo get_admin_url(); ?>edit.php?post_type=impact">All Impacts
        <span class="count">(<span class="all-count"><?php echo wp_count_posts('impact')->publish; ?></span>)</span></a>
      </li>
    </ul>

  </div>

<?php

}

?>

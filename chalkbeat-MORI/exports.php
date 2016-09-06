<?php

add_action('admin_menu', function() {
  add_submenu_page( 'edit.php?post_type=impact', 'Export MORI Data', 'Export Data', 'manage_options', 'mori_data_export_page', 'mori_data_export_page');
});

function mori_data_export_page() {
  add_action( 'admin_enqueue_scripts', function() {

    wp_enqueue_script('field-date-js', 'Field_Date.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), time(), true);
    wp_enqueue_style( 'jquery-ui-datepicker' );

  });

  ?>

  <div class="wrap">

    <h1>Export MORI Data</h1>

    <?php if ( isset($_POST['submit']) ) {

      $impact_query_args = array(
        'posts_per_page' => -1,
        'post_type' => 'impact',
      );

      if ( isset($_POST['start_date']) && isset($_POST['end_date']) ) {

        $impact_query_args['date_query'] = array(
          array(
            'after'     => (isset($_POST['start_date'])) ? $_POST['start_date'] : '',
            'before'    => (isset($_POST['end_date'])) ? $_POST['end_date'] : date('F j, Y'),
            'inclusive' => true,
          ),
        );

      }

      $impact_query = new WP_Query($impact_query_args);

      // Set up header row for csv
      $active_taxonomies = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();
      $title_row = array('title', 'author', 'date', 'narrative', 'impact taxonomy', 'url assigned to');
      foreach ($active_taxonomies as $taxonomy) {
        $title_row[] = $taxonomy;
      }

      // Add header row to output
      $output = array($title_row);

      // Add individual row data to output
      foreach ($impact_query->posts as $post) {

        $post_meta = get_post_meta($post->ID);

        $post_row = array(
          $post->post_title,
          get_the_author_meta('display_name', $post->post_author),
          $post->post_date,
          $post->post_content,
          implode(', ', wp_get_post_terms( $post->ID, 'impact_type', array('fields' => 'names') ) ),
          ($post_meta['mori_assigned_type'][0] == 'single') ? get_the_permalink($post_meta['mori_assigned_id'][0]) : '',
        );


        // Fill in data for active taxonomies
        foreach ($active_taxonomies as $taxonomy) {

          $impact_taxonomies = get_object_taxonomies('impact');

          if ( in_array($taxonomy, $impact_taxonomies) ){

            $terms = wp_get_post_terms( $post->ID, $taxonomy, array('fields' => 'names') );
            $post_row[] = implode(', ', $terms);


          } elseif ( is_array($post_meta['mori_assigned_type']) && in_array('single', $post_meta['mori_assigned_type']) ) {

            $terms = wp_get_post_terms( $post_meta['mori_assigned_id'][0], $taxonomy, array('fields' => 'names') );

            $post_row[] = implode(', ', $terms);

          } else {
            $post_row[] = '';
          }

        }

        $output[] = $post_row;

      }

      // Output file with MORI data
      $upload_dir = wp_upload_dir();
      $file_location = $upload_dir['basedir'].'/mori-export.csv';
      $file_url = $upload_dir['baseurl'].'/mori-export.csv';
      $file = fopen( $file_location,'w+');

      if ($file) {
          
        foreach ($output as $line) {
            fputcsv($file,$line);
        }

        fclose($file); ?>

        <div class="notice notice-success">
          <p>Export Successful: <a href="<?php echo $file_url; ?>" target="_blank">Download the export</a></p>
        </div>

      <?php } else { ?>

        <div class="notice notice-error">
          <p>
            Failed to generate export. Please check your file permissions. Current file permissions:
            <?php echo substr(decoct(fileperms($file_location)),2); ?>
          </p>
        </div>

      <?php }

    } ?>

    <form method="post">

      <table class="form-table mori-admin-table">
        <tbody>
          <tr>
            <th>Start Date</th>
            <td>
              <input type="date" id="start_date" name="start_date" value="<?php if ( isset($_POST['start_date']) ) { echo $_POST['start_date']; } ?>" class="datepicker" />
            </td>
          </tr>
          <tr>
            <th>End Date</th>
            <td>
              <input type="date" id="end_date" name="end_date" value="<?php if ( isset($_POST['end_date']) ) { echo $_POST['end_date']; } ?>" class="datepicker" />
            </td>
          </tr>
        </tbody>
      </table>

      <?php submit_button('Export Data', 'primary large', 'submit'); ?>

    </form>

  </div>

  <script>
  jQuery(document).ready(function(){
	   jQuery('.datepicker').datepicker();
  });
  </script>

<?php }


function pu_display_date_picker($args){
     extract( $args );

     echo '<input type="date" id="datepicker" name="example[datepicker]" value="" class="example-datepicker" />';
}

?>

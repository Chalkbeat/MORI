<?php
/**
 * Plugin Name:       MORI - Metrics on Reporting Impacts
 * Plugin URI:        https://github.com/Chalkbeat/MORI
 * Description:       MORI helps you track your reporting's impact
 * Version:           0.1
 * Author:            Chalkbeat
 * Author URI:        http://chalkbeat.org/
 * Text Domain:       mori
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MORI_BASE_FOLDER', plugin_dir_path( __FILE__ ) );
define( 'MORI_BASE_URL', plugins_url( '', __FILE__ ) );

// Pieces requiring WordPress to be initialized
add_action('init', function() {

  // Post Type for Impacts
  include('impact-post-type.php');

  // Taxonomy for Impact Types
  include('impact-type-taxonomy.php');

  // MORI Settings pag
  include('settings.php');

  // Columns/filters for Impacts views in admin
  include('columns/impact-columns.php');
  include('columns/post-columns.php');
  include('filters/impact-filters.php');

  if ( is_plugin_active('mori/mori.php') ) {
    // Migrate old data from older MORI
    include('old-data-migration.php');
  }

  // CSV exports
  include('exports.php');

});

// Pieces that need to be initialized at a different time

// Impact Submissions
include('metaboxes/impact-metabox.php');
include('metaboxes/post-metabox.php');

// Impact Dashboard Widget
include('impact-dashboard-widget.php');

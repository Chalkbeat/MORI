<?php
add_action('admin_menu', function() {
  add_submenu_page( 'edit.php?post_type=impact', 'Settings', 'Settings', 'manage_options', 'impact_settings_page', 'impact_settings_page');
});

add_action('admin_init', function() {
  register_setting('mori_options', 'active_taxonomies');
});

function impact_settings_page() {

  wp_enqueue_style( 'mori_metaboxes', MORI_BASE_FOLDER.'/css/mori-admin.css' );

  $taxonomies = get_taxonomies();
?>

  <div class="wrap">
    <h1>MORI Settings</h1>

    <form method="post" action="options.php">

    <?php
      settings_fields('mori_options');
      do_settings_sections('mori_options');
      $selected = get_option('active_taxonomies') ? array_keys(get_option('active_taxonomies')) : array();
    ?>

      <table class="form-table mori-admin-table">
        <tbody>
          <tr>
            <th>
              <h3>Taxonomies</h3>
              <p>Selected taxonomies will appear on the MORI Impacts pages.</p>
              <p>Visible on admin columns, admin filters, and meta boxes on individual Impacts.</p>
            </th>
            <td>
              <fieldset>
                <ul>
                <?php foreach($taxonomies as $taxonomy) {
                  $taxonomy = get_taxonomy($taxonomy);

                  if ($taxonomy->labels->name) { ?>
                  <li>
                  	<label for="<?php echo $taxonomy->name; ?>">
                  		<input name="<?php echo 'active_taxonomies['.$taxonomy->name.']'; ?>" type="checkbox" id="<?php echo $taxonomy->name; ?>"
                      <?php if ( in_array($taxonomy->name, $selected) ) { echo 'checked="checked"'; } ?> />
                  		<span><?php esc_attr_e( $taxonomy->labels->name, 'mori' ); ?></span>
                  	</label>
                  </li>
                  <?php } ?>
                <?php } ?>
                </ul>
              </fieldset>
            </td>
          </tr>
        </tbody>
      </table>

      <?php submit_button('Save Settings', 'primary', 'submit'); ?>
    </form>
  </div>

<?php
}

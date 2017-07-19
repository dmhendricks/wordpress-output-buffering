<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Output Buffering
 * Plugin URI:        https://github.com/dmhendricks/wordpress-output-buffering
 * Description:       Buffers the entire WP process, capturing the final output for manipulation.
 * Version:           1.0.2
 * Author:            Daniel M. Hendricks
 * Original Author:   kfriend (https://stackoverflow.com/users/419673/kfriend)
 * Author URI:        https://www.danhendricks.com
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * GitHub Plugin URI: dmhendricks/wordpress-output-buffering
 */

ob_start();

if( !is_admin() && !( defined('DOING_AJAX') && DOING_AJAX ) ) {
  add_action('shutdown', function() {
    $final = '';

    // We'll need to get the number of OB levels we're in, so that we can iterate over each, collecting
    // that buffer's output into the final output.
    $levels = ob_get_level();
    for ($i = 0; $i < $levels; $i++)
    {
      $final .= ob_get_clean();
    }

    // Apply any filters to the final output
    echo apply_filters('final_output', $final);
  }, 0);
}
?>

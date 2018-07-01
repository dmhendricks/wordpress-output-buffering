<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Output Buffering
 * Plugin URI:        https://github.com/dmhendricks/wordpress-output-buffering
 * Description:       Buffers the entire WP process, capturing the final output for manipulation.
 * Version:           1.0.4
 * Author:            Daniel M. Hendricks
 * Original Author:   kfriend (https://stackoverflow.com/users/419673/kfriend)
 * Author URI:        https://www.danhendricks.com
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * GitHub Plugin URI: dmhendricks/wordpress-output-buffering
 */
namespace CloudVerve\MustUse;

class OutputBuffering {

  public function __construct() {

    if( $this->can_load() ) $this->output_buffering();

  }

  /**
    * Returns string of addition CSS classes based on post type
    *
    * Returns CSS classes such as page-{slug}, parent-{slug}, post-type-{type} and
    *   category-{slug} for easier selector targeting
    *
    * @param array $classes An array of *current* body_class classes
    * @return array Modified array of body classes including new ones
    */
  public function output_buffering() {

    ob_start();

    add_action( 'shutdown', function() {
      $final = '';

      // Iterate over each OB level
      $levels = ob_get_level();
      for ( $i = 0; $i < $levels; $i++ ) {
        $final .= ob_get_clean();
      }

      // Apply any filters to the final output
      echo apply_filters( 'final_output', $final );
    }, 0);

  }

  /**
    * Get a list of screens/requests types to enable output buffering for.
    *   'site' for frontend (default), 'admin' for WP Admin, 'ajax' for AJAX requests.
    *
    * @return array
    */
  private function get_load_screens() {

    if( defined( 'OB_ENABLE_SCREENS' ) ) {
      return is_array( OB_ENABLE_SCREENS ) ? OB_ENABLE_SCREENS : array( OB_ENABLE_SCREENS );
    }

    $screens = array( 'site' );
    if( defined( 'OB_ENABLE_ADMIN' ) || defined( 'OB_ENABLE_AJAX' ) ) {
      if( defined( 'OB_ENABLE_ADMIN' ) && OB_ENABLE_ADMIN ) $screens[] = 'admin';
      if( defined( 'OB_ENABLE_AJAX' ) && OB_ENABLE_AJAX ) $screens[] = 'ajax';
    }

    return $screens;

  }

  /**
    * Determine if output buffering should be enabled on frontend (default), WP Admin,
    * and/or while doing AJAX requests.
    *
    * @return bool Whether or not output buffering should be performed.
    */
  private function can_load() {

    if( strpos( $_SERVER['REQUEST_URI'], '/wp-json' ) === 0 ) return false;

    $load_screens = $this->get_load_screens();
    $load_admin   = is_admin() && in_array( 'admin', $load_screens );
    $load_ajax    = $this->is_ajax() && in_array( 'ajax', $load_screens );
    $load_site    = (!is_admin() && !$this->is_ajax()) && in_array( 'site', $load_screens );

    return $load_admin || $load_ajax || $load_site;

  }

  /**
    * Determine if current request is AJAX
    *
    * @return bool
    */
  private function is_ajax() {
    return defined( 'DOING_AJAX' ) && DOING_AJAX;
  }

}

new OutputBuffering();
?>

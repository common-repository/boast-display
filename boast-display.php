<?php
  /*
   * Plugin Name: Boast Display
   * Description: Display Boasts on your Wordpress site to help customer confidence and SEO.
   * Version: 1.2.2
   * Author: Web Ascender
   * Author URI: https://webascender.com
   * License: GPLv2
   */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_boast_display() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/boast-display-activator.php';
	BoastDisplayActivator::activate();
}


register_activation_hook( __FILE__, 'activate_boast_display' );


require plugin_dir_path( __FILE__ ) . 'includes/boast-display.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function run_plugin_name() {
	$plugin = new BoastDisplay();
	$plugin->run();
}
run_plugin_name();

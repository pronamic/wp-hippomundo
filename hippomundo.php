<?php
/*
Plugin Name: Hippomundo
Plugin URI: https://www.pronamic.eu/plugins/hippomundo/
Description: Integrate Hippomundo results into your WordPress website.

Version: 1.0.0
Requires at least: 4.0

Author: Pronamic
Author URI: https://www.pronamic.eu/

Text Domain: hippomundo
Domain Path: /languages/

License: GPL

GitLab URI: https://gitlab.com/pronamic-plugins/hippomundo
*/

/**
 * Autoload
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Plugin
 */
global $hippomundo_plugin;

$hippomundo_plugin = new Pronamic_WP_Hippomundo_Plugin( __FILE__ );

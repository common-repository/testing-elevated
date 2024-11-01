<?php
/**
 * Plugin autoloader config
 * Declare all constants here.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated;

/**
 * Define constants below for specific plugin.
 * Edit this file as per the plugin.
 */

/**
 * Plugin namespace.
 *
 * @const string TESTING_ELEVATED_PLUGIN_NAMESPACE Plugin namespace.
 */
if ( ! defined( 'TESTING_ELEVATED_PLUGIN_NAMESPACE' ) ) {
	define( 'TESTING_ELEVATED_PLUGIN_NAMESPACE', 'Testing_Elevated' );
}

/**
 * Plugin directory path.
 *
 * @const string TESTING_ELEVATED_PLUGIN_DIR Plugin directory path.
 */
if ( ! defined( 'TESTING_ELEVATED_PLUGIN_DIR' ) ) {
	define( 'TESTING_ELEVATED_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/testing-elevated/' );
}

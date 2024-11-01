<?php
/**
 * Testing Elevated plugin config
 * Declare all constants here.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated;

/**
 * Define constants below.
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
	define( 'TESTING_ELEVATED_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin directory URL.
 *
 * @const string TESTING_ELEVATED_PLUGIN_URL Plugin directory URL.
 */
if ( ! defined( 'TESTING_ELEVATED_PLUGIN_URL' ) ) {
	define( 'TESTING_ELEVATED_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

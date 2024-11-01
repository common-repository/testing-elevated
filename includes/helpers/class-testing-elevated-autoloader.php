<?php
/**
 * Autoloader
 * It loads the resources like class, trait, etc. automatically when needed.
 * We don't need to require or include the files manually.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Helpers;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

require_once __DIR__ . '/autoloader-config.php';
require_once TESTING_ELEVATED_PLUGIN_DIR . 'includes/traits/trait-testing-elevated-singleton.php';

// Bailout, if exists.
if ( class_exists( 'Testing_Elevated\Includes\Helpers\Testing_Elevated_Autoloader' ) ) {
	return;
}

/**
 * Class Testing_Elevated_Autoloader
 * It loads the resources like class, trait, etc. automatically when needed.
 * We don't need to require or include the files manually.
 * It is a singleton class.
 * It works only for the classes which are in the namespace defined autoloader.config.php file.
 */
final class Testing_Elevated_Autoloader {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Plugin namespace.
	 * It is used to check whether the resource belongs to the plugin or not.
	 *
	 * @const string TESTING_ELEVATED_PLUGIN_NAMESPACE Plugin namespace.
	 */
	const TESTING_ELEVATED_PLUGIN_NAMESPACE = TESTING_ELEVATED_PLUGIN_NAMESPACE ?? 'Plugin_Namespace';

	/**
	 * Plugin directory path.
	 *
	 * @const string TESTING_ELEVATED_PLUGIN_DIR Plugin directory path.
	 */
	const TESTING_ELEVATED_PLUGIN_DIR = TESTING_ELEVATED_PLUGIN_DIR ?? WP_CONTENT_DIR . '/plugins/';

	/**
	 * Possible resource types.
	 *
	 * @const array RESOURCE_TYPES Possible resource types.
	 */
	const RESOURCE_TYPES_TO_DIT_MAP = array(
		'classes'    => 'class',
		'traits'     => 'trait',
		'interfaces' => 'interface',
	);

	/**
	 * Initialize the autoloader.
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public function __construct() {
		spl_autoload_register( array( __CLASS__, 'loader' ) );
	}

	/**
	 * Autoload the resource.
	 *
	 * @param string $resource_path Resource Path to load.
	 * @return void
	 */
	private function loader( string $resource_path ): void {
		// trim the leading backslash.
		$resource_path = ltrim( $resource_path, '\\' );

		// check and remove the plugin namespace from the resource path.
		if ( ! self::check_and_remove_main_namespace( $resource_path ) ) {
			return;
		}

		// Remove the resource name from the resource path.
		$resource_path_without_resource_name = self::remove_resource_name( $resource_path );

		// get the parent directory path of resource file.
		$dir_path = self::get_parent_dir_path( $resource_path_without_resource_name );

		// get file name with .php extension.
		$resource_name     = self::get_resource_name( $resource_path );
		$resource_filename = self::resource_name_to_filename( $resource_name, $resource_path_without_resource_name );

		// combine the path and file name.
		$resource_filepath = $dir_path . $resource_filename;

		// add if exists.
		if ( file_exists( $resource_filepath ) ) {
			require_once $resource_filepath;
		}
	}

	/**
	 * Check and Remove the main namespace from the resource path.
	 *
	 * @param string $resource_path resource path used with 'use' keyword.
	 * @return bool
	 */
	private static function check_and_remove_main_namespace( string &$resource_path ): bool {
		// separate the path.
		$resource_path_arr = explode( '\\', $resource_path );

		// disable autoload for the resource which is not from this plugin.
		if ( count( $resource_path_arr ) === 0 || self::TESTING_ELEVATED_PLUGIN_NAMESPACE !== $resource_path_arr[0] ) {
			return false;
		}

		// remove plugin namespace from the array because it will be included in the $root_path.
		array_shift( $resource_path_arr );

		// combine the path again.
		$resource_path = implode( '\\', $resource_path_arr );

		return true;
	}

	/**
	 * Remove the resource name from the resource path.
	 *
	 * @param string $resource_path resource path used with 'use' keyword.
	 * @return string
	 */
	private static function remove_resource_name( string $resource_path ): string {
		// Get the last position of the backslash in resource path.
		$last_backslash_position = strrpos( $resource_path, '\\' );

		/*
		 * If the backslash is present in the resource path, then remove the resource name.
		 * Else return the empty path.
		 */
		if ( $last_backslash_position ) {
			return substr( $resource_path, 0, $last_backslash_position );
		} else {
			return '';
		}
	}

	/**
	 * Get the parent directory path of resource file.
	 *
	 * @param string $resource_path_without_resource_name resource path used with 'use' keyword.
	 * @return string
	 */
	private static function get_parent_dir_path( string $resource_path_without_resource_name ): string {
		// replace the backslash with the directory separator.
		$path = str_replace( '_', '-', $resource_path_without_resource_name );
		$path = strtolower( $path );

		// don't append the DIRECTORY_SEPARATOR if the path is empty.
		if ( '' === $path ) {
			return self::TESTING_ELEVATED_PLUGIN_DIR;
		}

		$path = self::TESTING_ELEVATED_PLUGIN_DIR . $path;
		return str_replace(
			'\\',
			DIRECTORY_SEPARATOR,
			$path
		) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the resource name from the resource path.
	 *
	 * @param string $resource_path resource path used with 'use' keyword.
	 * @return string
	 */
	private static function get_resource_name( string $resource_path ): string {
		// get the last position of the backslash in resource path.
		$last_backslash_position = strrpos( $resource_path, '\\' );

		/*
		 * If the backslash is present in the resource path, then separate the resource name.
		 * Else return the resource path as it is.
		 */
		if ( $last_backslash_position ) {
			return substr( $resource_path, $last_backslash_position + 1 );
		} else {
			return $resource_path;
		}
	}

	/**
	 * Get the file name form the resource name.
	 *
	 * @param string $resource_name resource name.
	 * @param string $resource_path_without_resource_name resource path used with 'use' keyword.
	 *
	 * @return string
	 */
	private static function resource_name_to_filename( string $resource_name, string $resource_path_without_resource_name ): string {
		$filename = str_replace( '_', '-', $resource_name );
		$filename = strtolower( $filename );

		// trim the backslash from the resource path.
		$trimmed_resource_path = trim( $resource_path_without_resource_name, '\\' );

		// check if the last word is class, interface or trait.
		$prefix = '';
		if ( false !== strrpos( $trimmed_resource_path, '\\' ) ) {
			$last_word = substr( $trimmed_resource_path, strrpos( $trimmed_resource_path, '\\' ) + 1 );
			$last_word = strtolower( $last_word );

			// check if the last word matches any resource type.
			if ( isset( self::RESOURCE_TYPES_TO_DIT_MAP[ $last_word ] ) ) {
				$prefix = self::RESOURCE_TYPES_TO_DIT_MAP[ $last_word ] . '-';
			}
		}

		return $prefix . $filename . '.php';
	}
}

Testing_Elevated_Autoloader::get_instance();

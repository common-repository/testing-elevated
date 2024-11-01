<?php
/**
 * Testing Elevated File API class.
 * It handles all the file related operations.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

/**
 * Class Testing_Elevated_File
 * It handles all the file related operations.
 */
class Testing_Elevated_File {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Read the file.
	 *
	 * @param string $relative_path Relative file path.
	 *
	 * @return string
	 */
	public function read( string $relative_path ): string {
		$absolute_path = $this->get_absolute_path( $relative_path );

		if ( ! file_exists( $absolute_path ) ) {
			return '';
		}

		return file_get_contents( $absolute_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Write the file.
	 *
	 * @param string $relative_path Relative file path.
	 * @param string $content       File content.
	 *
	 * @return void
	 */
	public function write( string $relative_path, string $content ): void {
		$absolute_path = $this->get_absolute_path( $relative_path );

		file_put_contents( $absolute_path, $content ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}

	/**
	 * Delete the file.
	 *
	 * @param string $relative_path Relative file path.
	 *
	 * @return void
	 */
	public function delete( string $relative_path ): void {
		$absolute_path = $this->get_absolute_path( $relative_path );

		if ( ! file_exists( $absolute_path ) ) {
			return;
		}

		wp_delete_file( $absolute_path );
	}

	/**
	 * Copy the file.
	 *
	 * @param string $old_relative_path Relative file path.
	 * @param string $new_relative_path New file path.
	 *
	 * @return void
	 */
	public function copy( string $old_relative_path, string $new_relative_path ): void {
		$old_absolute_path = $this->get_absolute_path( $old_relative_path );
		$new_absolute_path = $this->get_absolute_path( $new_relative_path );

		copy( $old_absolute_path, $new_absolute_path );
	}

	/**
	 * Get the absolute path.
	 *
	 * @param string $relative_path Relative file path.
	 *
	 * @return string
	 */
	public function get_absolute_path( string $relative_path ): string {
		return WP_CONTENT_DIR . $relative_path;
	}
}

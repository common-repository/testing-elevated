<?php
/**
 * Testing Elevated Query class.
 * It handles all the query related operations.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

/**
 * Class Testing_Elevated_Query
 * It handles all the query related operations.
 */
class Testing_Elevated_Query {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * File relative path.
	 * This file stores all the queries.
	 *
	 * @const string FILE_RELATIVE_PATH File relative path.
	 */
	const FILE_RELATIVE_PATH = '/queries.json';

	/**
	 * Get all queries.
	 *
	 * @return array
	 */
	public function get(): array {
		$json_encoded_data = Testing_Elevated_File::get_instance()->read( self::FILE_RELATIVE_PATH );

		$data = json_decode( $json_encoded_data, true );

		return $data['queries'] ?? array();
	}

	/**
	 * Save all queries.
	 *
	 * @param array $queries Queries.
	 *
	 * @return void
	 */
	public function save( array $queries ): void {
		$queries = $this->filter( $queries );

		$data = array(
			'queries' => $queries,
			'date'    => time(),
			'version' => '1.0.0',
		);

		$json_encoded_data = wp_json_encode( $data );

		Testing_Elevated_File::get_instance()->write( self::FILE_RELATIVE_PATH, $json_encoded_data );
	}

	/**
	 * Delete all queries.
	 *
	 * @return void
	 */
	public function delete(): void {
		Testing_Elevated_File::get_instance()->delete( self::FILE_RELATIVE_PATH );
	}

	/**
	 * Filter all queries.
	 *
	 * @param array $queries Queries.
	 *
	 * @return array
	 */
	public function filter( array $queries ): array {
		return array_filter(
			$queries,
			function ( $query ) {
				// Allow only INSERT, UPDATE and DELETE queries.
				return in_array( $query['type'], array( 'insert', 'update', 'delete' ), true );
			}
		);
	}
}

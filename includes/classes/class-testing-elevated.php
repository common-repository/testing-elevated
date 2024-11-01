<?php
/**
 * Testing Elevated main class.
 * It handles all the database and testing related operations.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

/**
 * Class Testing_Elevated
 * It is a main class
 * It handles all the database and testing related operations
 */
class Testing_Elevated {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Testing_Elevated constructor.
	 * It is a private constructor to prevent direct object creation.
	 */
	private function __construct() {
		// disable testing environment for self ajax requests.
		if ( Testing_Elevated_AJAX::get_instance()->is_testing_elevated_ajax_request() ) {
			return;
		}

		$this->init_db();

		if ( ! $this->is_enabled() ) {
			return;
		}

		$this->start();
		$this->fire_old_queries();
		$this->end();
	}

	/**
	 * Initialize the database.
	 * It assigns db instance to global $wpdb.
	 *
	 * @return void
	 */
	public function init_db(): void {
		global $wpdb;

		$db_user     = defined( 'DB_USER' ) ? DB_USER : '';
		$db_password = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
		$db_name     = defined( 'DB_NAME' ) ? DB_NAME : '';
		$db_host     = defined( 'DB_HOST' ) ? DB_HOST : '';

		$wpdb = new Testing_Elevated_DB( $db_user, $db_password, $db_name, $db_host ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Check if the testing is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		global $wpdb, $table_prefix;

		$table_name = $table_prefix . 'options';

		return '1' === $wpdb->get_var( "SELECT option_value FROM {$table_name} WHERE option_name = 'Testing_Elevated_Status'" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Start testing.
	 * It sets the autocommit to false.
	 *
	 * @return void
	 */
	public function start(): void {
		global $wpdb;

		$wpdb->query( 'SET autocommit = 0' );
	}

	/**
	 * Commit testing.
	 * It commits all the queries.
	 */
	public function commit(): void {
		global $wpdb;

		$wpdb->query( 'COMMIT' );
		$wpdb->query( 'SET autocommit = 1' );
	}

	/**
	 * Rollback testing.
	 * It rolls back all the queries.
	 *
	 * @return void
	 */
	public function rollback(): void {
		// delete all the queries so next time they don't execute.
		Testing_Elevated_Query::get_instance()->delete();
	}

	/**
	 * End testing.
	 * It commits all the queries.
	 *
	 * @return void
	 */
	public function end(): void {
		register_shutdown_function( array( $this, 'record_queries' ) );
	}

	/**
	 * Record all queries.
	 * It records all the queries fired during the test.
	 *
	 * @hook query
	 *
	 * @return void
	 */
	public function record_queries(): void {
		$queries = Testing_Elevated_DB::$testing_elevated_queries;

		Testing_Elevated_Query::get_instance()->save( $queries );
	}

	/**
	 * Fire old queries.
	 * It fires all the queries recorded during the test.
	 *
	 * @return void
	 */
	public function fire_old_queries(): void {
		global $wpdb, $table_prefix;

		$queries = Testing_Elevated_Query::get_instance()->get();

		foreach ( $queries as $query ) {
			$result = $wpdb->query( $query['query'] ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( 'insert' === $query['type'] ) {
				// get the table name from the query.
				$primary_key = $this->get_table_primary_key( $table_prefix . $query['table'] );
				$wpdb->update(
					$table_prefix . $query['table'],
					array(
						$primary_key => $query['id'],
					),
					array(
						$primary_key => $wpdb->insert_id,
					)
				);
			}
		}
	}

	/**
	 * Get the table primary key.
	 *
	 * @param string $table_name Table name.
	 *
	 * @return string
	 */
	public function get_table_primary_key( string $table_name ): string {
		global $wpdb;

		$res = $wpdb->get_var( "SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'", 4 ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		return $res ? $res : 'id';
	}

	/**
	 * Clean up after committing the changes.
	 *
	 * @return void
	 */
	public function cleanup(): void {
		global $wpdb;

		// delete all the queries.
		Testing_Elevated_Query::get_instance()->delete();

		$table_name = $wpdb->prefix . 'options';

		$wpdb->delete( $table_name, array( 'option_name' => 'Testing_Elevated_Status' ) );
	}
}

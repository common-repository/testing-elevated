<?php
/**
 * Testing Elevated plugin ajax class.
 * It handles all the ajax requests. These requests are used to start or stop the testing.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

/**
 * Class Testing_Elevated_AJAX
 * It handles all the ajax requests. These requests are used to start or stop the testing.
 */
class Testing_Elevated_AJAX {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Valid ajax actions.
	 *
	 * @var string[] Valid ajax actions.
	 */
	private array $valid_actions = array(
		'testing_elevated_start',
		'testing_elevated_commit',
		'testing_elevated_rollback',
	);

	/**
	 * Testing_Elevated_AJAX constructor.
	 * It attaches the ajax actions.
	 */
	public function __construct() {
		// Start testing.
		add_action( 'wp_ajax_testing_elevated_start', array( $this, 'start_testing' ) );
		add_action( 'wp_ajax_nopriv_testing_elevated_start', array( $this, 'start_testing' ) );

		// Commit changes made during testing.
		add_action( 'wp_ajax_testing_elevated_commit', array( $this, 'commit_changes' ) );
		add_action( 'wp_ajax_nopriv_testing_elevated_commit', array( $this, 'commit_changes' ) );

		// Rollback changes made during testing.
		add_action( 'wp_ajax_testing_elevated_rollback', array( $this, 'rollback_changes' ) );
		add_action( 'wp_ajax_nopriv_testing_elevated_rollback', array( $this, 'rollback_changes' ) );
	}

	/**
	 * Check if the request is a Testing Elevated AJAX request.
	 *
	 * @return bool
	 */
	public function is_testing_elevated_ajax_request(): bool {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			return false;
		}

		// custom sanitization and validation.
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		return in_array( $action, $this->valid_actions, true );
	}

	/**
	 * Start the testing.
	 *
	 * @return void
	 */
	public function start_testing(): void {
		$this->nonce_check();

		update_option( 'Testing_Elevated_Status', true );

		wp_send_json_success( 'You can now start testing.' );
	}

	/**
	 * Commit the changes made during testing.
	 *
	 * @return void
	 */
	public function commit_changes(): void {
		$this->nonce_check();

		Testing_Elevated::get_instance()->fire_old_queries();
		Testing_Elevated::get_instance()->commit();
		Testing_Elevated::get_instance()->cleanup();

		wp_send_json_success( 'Changes made during testing are committed.' );
	}

	/**
	 * Rollback the changes made during testing.
	 *
	 * @return void
	 */
	public function rollback_changes(): void {
		$this->nonce_check();

		Testing_Elevated::get_instance()->rollback();

		wp_send_json_success( 'Changes made during testing are rolled back.' );
	}

	/**
	 * Check if the nonce is valid.
	 *
	 * @return void
	 */
	public function nonce_check(): void {
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'testing_elevated_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce.' );
		}
	}
}

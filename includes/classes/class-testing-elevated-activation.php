<?php
/**
 * Testing Elevated plugin activation class.
 * It handles all the activation and deactivation related operations.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

/**
 * Class Testing_Elevated_Activation
 * It handles all the activation and deactivation related operations.
 */
class Testing_Elevated_Activation {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Plugin activation function.
	 * It copies the db.php Drop-ins file to the wp-content directory.
	 *
	 * @return void
	 */
	public function activate(): void {
		Testing_Elevated_Query::get_instance()->delete();
		Testing_Elevated_File::get_instance()->copy( '/plugins/testing-elevated/wp-content/db.php', '/db.php' );
	}

	/**
	 * Plugin deactivation function.
	 * It deletes the db.php Drop-ins file from the wp-content directory.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		// remove callback added by register_shutdown_function.
		Testing_Elevated::get_instance()->commit();
		Testing_Elevated::get_instance()->cleanup();
		Testing_Elevated_Query::get_instance()->delete();
		Testing_Elevated_File::get_instance()->delete( '/db.php' );
	}
}

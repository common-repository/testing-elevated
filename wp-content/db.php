<?php
/**
 * Drop-ins db.php
 * This files initializes the database connection.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated;

require_once WP_CONTENT_DIR . '/plugins/testing-elevated/includes/helpers/class-testing-elevated-autoloader.php';

use Testing_Elevated\Includes\Classes\Testing_Elevated;

/**
 * Initialize the database and testing environment.
 * It defines the global $wpdb and starts the testing environment.
 */
Testing_Elevated::get_instance();

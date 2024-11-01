<?php
/**
 * Plugin Name: Testing Elevated
 * Description: Test out various features of your WordPress website effortlessly. Unsure about how your site's UI appears or what specific features do? Simply activate this plugin to make changes, view the results, and decide whether to keep them or revert back.
 * Author:      Utsav Ladani
 * Author URI:  https://profiles.wordpress.org/utsavladani/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0.0
 * Text Domain: testing-elevated
 *
 * @package testing-elevated
 */

namespace Testing_Elevated;

require_once __DIR__ . '/includes/helpers/class-testing-elevated-autoloader.php';

use Testing_Elevated\Includes\Classes\Testing_Elevated_Activation;
use Testing_Elevated\Includes\Classes\Testing_Elevated_AJAX;
use Testing_Elevated\Includes\Classes\Testing_Elevated_UI;

// Register activation hook.
register_activation_hook( __FILE__, array( Testing_Elevated_Activation::get_instance(), 'activate' ) );

// Register deactivation hook.
register_deactivation_hook( __FILE__, array( Testing_Elevated_Activation::get_instance(), 'deactivate' ) );

// Register AJAX hooks.
Testing_Elevated_AJAX::get_instance();
Testing_Elevated_UI::get_instance();

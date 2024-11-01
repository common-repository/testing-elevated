<?php
/**
 * Testing Elevated plugin UI class.
 * It renders the right side menu and notices.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Classes;

use Testing_Elevated\Includes\Traits\Testing_Elevated_Singleton;

require_once WP_CONTENT_DIR . '/plugins/testing-elevated/plugin-config.php';

/**
 * Class Testing_Elevated_UI
 * It renders the right side menu and notices.
 */
class Testing_Elevated_UI {
	/**
	 * Use Singleton trait.
	 */
	use Testing_Elevated_Singleton;

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		$this->add_menu();
	}

	/**
	 * Add menu to UI.
	 *
	 * @return void
	 */
	public function add_menu(): void {
		add_action( 'wp_head', array( $this, 'render_menu' ) );
		add_action( 'admin_head', array( $this, 'render_menu' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_menu_styles_and_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_menu_styles_and_scripts' ) );
	}

	/**
	 * Render the right side menu.
	 *
	 * @return void
	 */
	public function render_menu(): void {
		?>
		<div class="testing-elevated-menu-wrapper">
			<div class="testing-elevated-menu__title">
				<img class="testing-elevated-menu__title__image" src="<?php echo esc_url( TESTING_ELEVATED_PLUGIN_URL . 'assets/images/testing_elevated_logo.png' ); ?>" alt="Testing Elevated">
			</div>
			<ul id="testing-elevated-menu" class="testing-elevated-menu">
				<li id="testing-elevated-start" class="testing-elevated-menu__item" data-action="testing_elevated_start">Start</li>
				<li id="testing-elevated-commit" class="testing-elevated-menu__item" data-action="testing_elevated_commit">Commit</li>
				<li id="testing-elevated-rollback" class="testing-elevated-menu__item" data-action="testing_elevated_rollback">Rollback</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Enqueue the styles and scripts for the menu.
	 *
	 * @return void
	 */
	public function enqueue_menu_styles_and_scripts(): void {
		wp_enqueue_style(
			'testing-elevated-menu-style',
			TESTING_ELEVATED_PLUGIN_URL . 'assets/css/menu.css',
			array(),
			filemtime( TESTING_ELEVATED_PLUGIN_DIR . 'assets/css/menu.css' )
		);

		wp_enqueue_script(
			'testing-elevated-menu-script',
			TESTING_ELEVATED_PLUGIN_URL . 'assets/js/menu.js',
			array(),
			filemtime( TESTING_ELEVATED_PLUGIN_DIR . 'assets/js/menu.js' ),
			true
		);

		wp_localize_script(
			'testing-elevated-menu-script',
			'testing_elevated_menu_object',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'testing_elevated_nonce' ),
				'enabled'  => Testing_Elevated::get_instance()->is_enabled(),
			)
		);
	}
}

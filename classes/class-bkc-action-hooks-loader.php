<?php
/**
 * Action Hooks Loader Class
 *
 * @package Action Hooks
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'BKC_Action_Hooks_Loader' ) ) :

	/**
	 * Create class BKC_Action_Hooks_Loader
	 */
	class BKC_Action_Hooks_Loader {

		/**
		 * Declare a static variable instance.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * WordPress Customizer Object
		 *
		 * @since 1.0.0
		 * @var $wp_customize
		 */
		private $wp_customize;

		/**
		 * Initiate class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new BKC_Action_Hooks_Loader();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			define( 'BKC_ACTION_HOOKS_VER', '1.0.0' );
			define( 'BKC_ACTION_HOOKS_FILE', trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'bkc-action-hooks.php' );
			define( 'BKC_ACTION_HOOKS_DIR', plugin_dir_path( BKC_ACTION_HOOKS_FILE ) );
			define( 'BKC_ACTION_HOOKS_URL', plugins_url( '/', BKC_ACTION_HOOKS_FILE ) );
		}

		/**
		 * Include files required to plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function includes() {

			require_once BKC_ACTION_HOOKS_DIR . 'classes/class-bkc-action-hooks-customizer.php';
			require_once BKC_ACTION_HOOKS_DIR . 'classes/class-bkc-action-hooks-markup.php';
		}
	}

	BKC_Action_Hooks_Loader::instance();
endif;

<?php
/**
 * Action Hooks Markup Class
 *
 * @package Action Hooks
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'BKC_Action_Hooks_Markup' ) ) :

	/**
	 * Create class BKC_Action_Hooks_Markup
	 */
	class BKC_Action_Hooks_Markup {

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
				self::$instance = new BKC_Action_Hooks_Markup();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Include files required to plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {

			add_action( 'wp', array( $this, 'hooks_markup' ) );
		}

		/**
		 * Action Hooks Markup.
		 *
		 * @return void
		 */
		public function hooks_markup() {

			$hooks = get_option( 'bkc-action-hooks', array() );

			if ( ! empty( $hooks ) ) {
				foreach ( $hooks as $key => $hook ) {

					$content = $hook['hook_content'];
					$priority = ( ! empty( $hook['priority'] ) ) ? $hook['priority'] : 10;

					add_action( $hook['action_hook'], function () use ( $content ) {
						echo do_shortcode( $content );
					}, $priority );
				}
			}
		}
	}

	BKC_Action_Hooks_Markup::instance();
endif;

<?php
/**
 * BKC Action Hooks Customizer Class
 *
 * @package BKC Action Hooks
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'BKC_Action_Hooks_Customizer' ) ) :

	/**
	 * Create class BKC_Action_Hooks_Customizer
	 */
	class BKC_Action_Hooks_Customizer {

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
		 * Action Hooks
		 *
		 * @since 1.0.0
		 * @var $wp_customize
		 */
		public static $action_hooks = array();

		/**
		 * Initiate class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new BKC_Action_Hooks_Customizer();
			}

			return self::$instance;
		}

		/**
		 * Declare constants
		 *
		 * @since 1.0.0
		 * @return void
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

			add_action( 'customize_register',        array( $this, 'customize_register' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
			add_action( 'wp_ajax_reset_all_action_hooks', array( $this, 'reset_all_action_hooks_callback' ) );
			add_action('after_switch_theme', array( $this, 'delete_action_hooks' ) );
			if ( ! is_admin() ) {
				add_action( 'wp_footer',             array( $this, 'update_action_hooks' ) );
			}
		}

		/**
		 * AJAX Customizer Reset
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function reset_all_action_hooks_callback() {

			// Validate nonce.
			check_ajax_referer( 'reset-all-action-hooks', 'nonce' );
			$this->delete_action_hooks();
			wp_send_json_error( 'pass' );

			wp_die();
		}

		/**
		 * AJAX Customizer Reset
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function delete_action_hooks() {

			self::$action_hooks = array();
			delete_option( 'bkc-frontend-actions' );
		}

		/**
		 * Customizer Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function controls_scripts() {

			// Enqueue JS.
			wp_enqueue_script( 'bkc-action-hooks-script', BKC_ACTION_HOOKS_URL . 'assets/customizer-script.js', array( 'jquery' ), BKC_ACTION_HOOKS_VER, true );

			// Add localize JS.
			wp_localize_script( 'bkc-action-hooks-script', 'actionHooksLocalized', array(
				'btnTitle'       => __( 'Reload Action Hooks List', 'bkc-action-hooks' ),
				'btnDescription' => __( 'Click to reload all WordPress Action Hooks list loading for the site.', 'bkc-action-hooks' ),
				'nonce'          => wp_create_nonce( 'reset-all-action-hooks' ),
			) );
		}

		public function update_action_hooks() {
			
			$all_actions = self::get_action_hooks();

			if ( ! $all_actions || empty( $all_actions ) ) {
				
				global $wp_actions;
				$all_actions = array_keys( $wp_actions );
				$start_index = array_search( 'get_header', $all_actions );
				$end_index   = array_search( 'wp_footer', $all_actions );

				$all_actions = array_slice( $all_actions, $start_index );
				update_option( 'bkc-frontend-actions', $all_actions );
			}
		}

		public static function get_action_hooks() {

			if ( ! isset( self::$action_hooks ) || empty( self::$action_hooks ) ) {
				self::$action_hooks = get_option( 'bkc-frontend-actions', false );
			}
			return self::$action_hooks;
		}

		/**
		 * Customizer Setting
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function customize_register( $wp_customize ) {
			
			$this->wp_customize = $wp_customize;

			$wp_customize->register_control_type( 'BKC_Control_Repeater' );

			require_once BKC_ACTION_HOOKS_DIR . 'custom-control/repeater/class-bkc-control-repeater.php';

			$hooks = self::get_action_hooks();

			/**
			 * Layout Panel
			 */
			$wp_customize->add_section( 'bkc-actin-hooks-panel', array(
				'title'    => __( 'Action Hooks', 'bkc-action-hooks' ),
			) );

			/**
			 * Option: Body Font Size
			 */
			$wp_customize->add_setting( 'bkc-action-hooks', array(
				'default'     => array(
					array(
						'action_hook'  => '',
						'hook_content' => '',
						'priority'     => '',
					),
				),
				'type'        => 'option',
			) );
			$wp_customize->add_control( new BKC_Control_Repeater( $wp_customize, 'bkc-action-hooks', array(
				'type'     => 'bkc-repeater',
				'section'  => 'bkc-actin-hooks-panel',
				'label'    => __( 'Action', 'bkc-action-hooks' ),
				'row_label' => __( 'Action', 'bkc-action-hooks' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'fields' => array(
					'action_hook' => array(
						'id'          => 'action_hook',
						'type'        => 'select',
						'label'       => __( '', 'bkc-action-hooks' ),
						'default'     => '',
						'choices'     => $hooks,
					),
					'hook_content' => array(
						'id'          => 'hook_content',
						'type'        => 'textarea',
						'label'       => __( 'Content', 'bkc-action-hooks' ),
						'default'     => '',
					),
					'priority' => array(
						'id'          => 'priority',
						'type'        => 'number',
						'label'       => __( 'Priority', 'bkc-action-hooks' ),
						'default'     => '',
					),
				)
			) ) );
		}
	}

	BKC_Action_Hooks_Customizer::instance();
endif;
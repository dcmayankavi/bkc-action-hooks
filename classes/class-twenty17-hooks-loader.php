<?php
/**
 * Twenty17 Hooks Loader Class
 *
 * @package twenty17-hooks
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'Twenty17_Hooks_Loader' ) ) :

	/**
	 * Create class Twenty17_Hooks_Loader
	 */
	class Twenty17_Hooks_Loader {

		/**
		 * Declare a static variable instance.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Initiate class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new Twenty17_Hooks_Loader();
				self::$instance->constants();
				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Declare constants
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function constants() {

			define( 'TWENTY17_HOOKS_VER', '1.0.0' );
			define( 'TWENTY17_HOOKS_FILE', trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'twentyseventeen-hooks.php' );
			define( 'TWENTY17_HOOKS_DIR', plugin_dir_path( TWENTY17_HOOKS_FILE ) );
			define( 'TWENTY17_HOOKS_URL', plugins_url( '/', TWENTY17_HOOKS_FILE ) );
		}

		/**
		 * Include files required to plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {

			add_action( 'customize_register',        array( $this, 'customize_register' ) );
			add_action( 'admin_enqueue_scripts',     array( $this, 'scripts' ) );
		}

		/**
		 * Twentyseventeen Hooks Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function scripts() {
			wp_enqueue_style( 'twenty17-hooks-style', TWENTY17_HOOKS_URL . 'assets/style.css' );
			wp_enqueue_script( 'twenty17-hooks-script', TWENTY17_HOOKS_URL . 'assets/script.js', array( 'jquery' ), TWENTY17_HOOKS_VER, true );
		}

		/**
		 * Twentyseventeen Hooks Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function customize_register( $wp_customize ) {
			
			$wp_customize->register_control_type( 'Ast_Control_Responsive' );

			require_once TWENTY17_HOOKS_DIR . 'classes/custom-control/responsive/class-ast-control-responsive.php';

			/**
			 * Layout Panel
			 */
			$wp_customize->add_section( 'panel-2017-hooks', array(
				'title'    => __( '2017 Hooks', 'twentyseventeen-hooks' ),
			) );

			/**
			 * Option: Body Font Size
			 */
			$wp_customize->add_setting( 'twenty17-hooks', array(
				'default'     => array(
					array(
						'action_hook'  => 'wp_footer',
						'hook_content' => 'https://aristath.github.io/kirki/',
						'priority'     => '',
					),
				),
				'type'        => 'option',
			) );
			$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, 'twenty17-hooks', array(
				'type'     => 'ast-responsive',
				'section'  => 'panel-2017-hooks',
				'label'    => __( 'Action', 'astra' ),
				'row_label' => __( 'Action', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'fields' => array(
					'action_hook' => array(
						'id'          => 'action_hook',
						'type'        => 'select',
						'label'       => __( '', 'my_textdomain' ),
						'default'     => '',
						'choices'     => array(
							'wp_head' => 'wp_head',
							'wp_footer' => 'wp_footer',
						),
					),
					'hook_content' => array(
						'id'          => 'hook_content',
						'type'        => 'textarea',
						'label'       => __( 'Content', 'my_textdomain' ),
						'default'     => '',
					),
					'priority' => array(
						'id'          => 'priority',
						'type'        => 'number',
						'label'       => __( 'Priority', 'my_textdomain' ),
						'default'     => '',
					),
				)
			) ) );
		}
	}

	Twenty17_Hooks_Loader::instance();
endif;

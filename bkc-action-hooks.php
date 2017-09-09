<?php
/**
 * Plugin Name: BKC Action Hooks
 * Description: This Plugin provides all frontend hooks in Customizer to add content multiple time on same hook with repeater.
 * Version: 1.0.0
 * Author: Dinesh Chouhan
 * Author URI: http://dineshchouhan.com
 * Text Domain: bkc-action-hooks
 *
 * @package BKC Action Hooks
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once 'classes/class-bkc-action-hooks-loader.php';

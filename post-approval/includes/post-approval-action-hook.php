<?php 

/**
 * The action hook functionality of the plugin.
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/includes
 */

// Exit if accessed directly.


if ( !defined( 'ABSPATH' ) ) exit;


add_action('admin_init', 'post_approval_scripts');
add_action( 'save_post', 'change_post_status_after_save',10,3);
add_action( 'wp_ajax_process_post', 'ajax_process_post' );
add_action( 'wp_ajax_edit_restricted_post', 'ajax_edit_restricted_post');
add_action( 'wp_ajax_delete_restricted_post', 'ajax_delete_restricted_post');
add_action( 'wp_ajax_delete_assign_post', 'ajax_delete_assign_post');
add_action( 'wp_ajax_re_assign_post', 'ajax_re_assign_post');
add_action( 'wp_ajax_update_restricted_post', 'ajax_udpate_restricted_post' );

<?php
/**
 *  Action Hook
 */

// Exit if accessed directly.

if ( !defined( 'ABSPATH' ) ) exit;

global $wpdb;
 
define( 'TABLE_RES_POST', $wpdb->prefix.'restricted_post' );
define( 'TABLE_USER_POST_APPROVAL', $wpdb->prefix.'user_approval_post' );
define( 'TABLE_USER_POST_COMMENT', $wpdb->prefix.'user_post_comment' );


require_once plugin_dir_path( __FILE__ ) . '/action.php';

add_action('admin_init', 'post_approval_scripts');

add_action( 'wp_ajax_process_post', 'ajax_process_post' );

// Hook to admin_head for the CSS to be applied earlier
add_action( 'admin_head', 'pas_hide_minor_publishing' );

add_action( 'save_post', 'change_post_status_after_save',10,3);

add_action( 'wp_ajax_edit_restricted_post', 'ajax_edit_restricted_post' );

add_action( 'wp_ajax_delete_restricted_post', 'ajax_delete_restricted_post' );

add_action( 'wp_ajax_delete_assign_post', 'ajax_delete_assign_post' );

add_action( 'wp_ajax_re_assign_post', 'ajax_re_assign_post' );

add_action( 'wp_ajax_update_restricted_post', 'ajax_udpate_restricted_post' );

add_action('init', 'add_custom_capability_to_editor');

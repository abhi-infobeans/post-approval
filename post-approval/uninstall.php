<?php
/**
 * Post Approval Uninstall
 *
 * Uninstalls the plugin and associated data.
 *
 * @package Post_Approval
 * @version 1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

/*
 * Only remove ALL post restricted data if user constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action.
 */

// Delete options.
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_post_restricted_users%';" );

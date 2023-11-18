<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Post_Approval
 * @subpackage Post_Approval/includes
 * @author     Infobeans <infobeans@test.com>
 */
class Post_Approval_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		global $wpdb;
	    $table_name1 = TABLE_RES_POST;
	    $table_name2 = TABLE_USER_POST_COMMENT;
	    $table_name3 = TABLE_USER_POST_APPROVAL;
	    
	    $sql1 = "DROP TABLE IF EXISTS $table_name1";
	    $sql2 = "DROP TABLE IF EXISTS $table_name2";
	    $sql3 = "DROP TABLE IF EXISTS $table_name3";

	    $wpdb->query($sql1);
	    $wpdb->query($sql2);
	    $wpdb->query($sql3);
	    $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_post_restricted_users%';" );

	}
}

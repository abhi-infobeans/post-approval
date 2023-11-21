<?php

/**
 * Fired during plugin activation
 *
 * @link       https://infobeans.com/
 * @since      1.0.0
 *
 * @package    Post-Approval
 * @subpackage Post-Approval/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Post_Approval
 * @subpackage Post_Approval/includes
 * @author     Infobeans <infobeans@test.com>
 */
class Post_Approval_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{

		// WP Globals
		global $table_prefix, $wpdb;

		// Restricted Post Table
		$restrictedPostTable = TABLE_RES_POST;
		$userApprovalPostTable = TABLE_USER_POST_APPROVAL;
		$userPostCommentTable = TABLE_USER_POST_COMMENT;

		// Create Restricted Post Table if not exist
		if ($wpdb->get_var("show tables like '$restrictedPostTable'") != $restrictedPostTable) {

			// Query - Create Table
			$sql = "CREATE TABLE `$restrictedPostTable` (";
			$sql .= " `id` int(11) NOT NULL auto_increment, ";
			$sql .= " `post_title` varchar(500) NOT NULL, ";
			$sql .= " `post_slug` varchar(500) NOT NULL, ";
			$sql .= " PRIMARY KEY `post_id` (`id`) ";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

			// Include Upgrade Script
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

			// Create Table
			dbDelta($sql);
		}

		// Create User Approval Post Table if not exist
		if ($wpdb->get_var("show tables like '$userApprovalPostTable'") != $userApprovalPostTable) {

			// Query - Create Table
			$sql = "CREATE TABLE `$userApprovalPostTable` (";
			$sql .= " `id` int(11) NOT NULL auto_increment, ";
			$sql .= " `post_id` int(11) NOT NULL, ";
			$sql .= " `user_id` int(11) NOT NULL, ";
			$sql .= " `post_type` varchar(500) NOT NULL, ";
			$sql .= " PRIMARY KEY `user_id` (`id`) ";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

			// Include Upgrade Script
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

			// Create Table
			dbDelta($sql);
		}

		// Create User post Custom Comment table if not exist
		if ($wpdb->get_var("show tables like '$userPostCommentTable'") != $userPostCommentTable) {

			// Query - Create Table
			$sql = "CREATE TABLE `$userPostCommentTable` (";
			$sql .= " `id` int(11) NOT NULL auto_increment, ";
			$sql .= " `post_id` int(11) NOT NULL, ";
			$sql .= " `user_id` int(11) NOT NULL, ";
			$sql .= " `user_comment` varchar(5000) NOT NULL, ";
			$sql .= " PRIMARY KEY `post_id` (`id`) ";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

			// Include Upgrade Script
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

			// Create Table
			dbDelta($sql);
		}
	}
}

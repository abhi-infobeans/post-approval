<?php 

/**
 *  DB Table Initialize
 */


/**
 * Function to Initialize DB Tables
 *
 * @param 
 * @return 
 */

function init_db_pas() {

	// WP Globals
	global $table_prefix, $wpdb;

	// Restricted Post Table
	$restrictedPostTable = TABLE_RES_POST;
	$userApprovalPostTable = TABLE_USER_POST_APPROVAL;
	$userPostCommentTable = TABLE_USER_POST_COMMENT;

	// Create Restricted Post Table if not exist
	if( $wpdb->get_var( "show tables like '$restrictedPostTable'" ) != $restrictedPostTable ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$restrictedPostTable` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `post_title` varchar(500) NOT NULL, ";
		$sql .= " `post_slug` varchar(500) NOT NULL, ";
		$sql .= " PRIMARY KEY `post_id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
		// Create Table
		dbDelta( $sql );
	}

	// Create User Approval Post Table if not exist
	if( $wpdb->get_var( "show tables like '$userApprovalPostTable'" ) != $userApprovalPostTable ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$userApprovalPostTable` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `post_id` int(11) NOT NULL, ";
		$sql .= " `user_id` int(11) NOT NULL, ";
		$sql .= " `post_type` varchar(500) NOT NULL, ";
		$sql .= " PRIMARY KEY `user_id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
		// Create Table
		dbDelta( $sql );
	}

	// Create User post Custom Comment table if not exist
	if( $wpdb->get_var( "show tables like '$userPostCommentTable'" ) != $userPostCommentTable ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$userPostCommentTable` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `post_id` int(11) NOT NULL, ";
		$sql .= " `user_id` int(11) NOT NULL, ";
		$sql .= " `user_comment` varchar(5000) NOT NULL, ";
		$sql .= " PRIMARY KEY `post_id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

		// Create Table
		dbDelta( $sql );
	}

}

/**
 * Function to carete table plugin activation time 
 *
 * @param 
 * @return 
 */

function post_approval_deactivation(){

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
}


/**
 * Return a ready-to-use admin url for adding a new content type.
 *
 * @param string $content_type Content type to link to.
 * @return string
 */
function pas_get_add_new_link( $content_type = '' ) {

	return pas_admin_url( 'admin.php?page=' . $content_type );
}


/**
 * Return the appropriate admin URL depending on our context.
 *
 * @param string $path URL path.
 * @return string
 */
function pas_admin_url( $path ) {
	if ( is_multisite() && is_network_admin() ) {
		return network_admin_url( $path );
	}

	return admin_url( $path );
}

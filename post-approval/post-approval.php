<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://infobeans.com
 * @since             1.0.0
 * @package           Post_Approval
 *
 * @wordpress-plugin
 * Plugin Name:       Post Approval
 * Plugin URI:        https://post_approval.com
 * Description:       The Post Approval plugin is a powerful tool for managing post restrictions and ensuring a controlled review process before posts are published. It allows administrators to assign post types, such as posts and custom posts, to specific editor users for careful review.
 * Version:           1.0.0
 * Author:            Infobeans
 * Author URI:        https://infobeans.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-approval
 * Requires wordpress at least: 6.4.1 
 * Tested on: 6.4.1
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}


/**
 * Currently plugin version.
 */
global $wpdb;
define('POST_APPROVAL_VERSION', '1.0.0');
define('TABLE_RES_POST', $wpdb->prefix . 'restricted_post');
define('TABLE_USER_POST_APPROVAL', $wpdb->prefix . 'user_approval_post');
define('TABLE_USER_POST_COMMENT', $wpdb->prefix . 'user_post_comment');
define('POST_APPROVAL_PLUGIN_DIR', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-approval-activator.php
 */
function activate_post_approval()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-post-approval-activator.php';
	Post_Approval_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-approval-deactivator.php
 */
function deactivate_post_approval()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-post-approval-deactivator.php';
	Post_Approval_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_post_approval');
register_deactivation_hook(__FILE__, 'deactivate_post_approval');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-post-approval.php';

/**
 * The core plugin file that is used to define action hook,
 * admin-specific hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/post-approval-action-hook.php';

/**
 * The core plugin file that is used to define hook action functions,
 * admin-specific actions.
 */
require plugin_dir_path(__FILE__) . 'includes/post-approval-action.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_post_approval()
{

	$plugin = new Post_Approval();
	$plugin->run();
}
run_post_approval();

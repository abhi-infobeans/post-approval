<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin
 * @author     Infobeans <infobeans@test.com>
 */
class Post_Approval_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/post-approval-admin.css', array(), $this->version, 'all');
		wp_enqueue_style('chosen-css', plugin_dir_url(__FILE__) . 'css/chosen.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script('post-approval-script', plugin_dir_url(__FILE__) . 'js/post-approval-admin.js', array('jquery'), '1.0', true);
	}

	/**
	 * To add a new menu in the admin area sidebar
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu()
	{


		add_menu_page('POST APPROVAL', 'POST APPROVAL', 'manage_options', 'restricted-post-list', 'restricted_post_list', $this->pas_menu_icon());

		add_submenu_page('restricted-post-list', 'RESTRICTION SETTING FORM', 'RESTRICTION SETTING FORM', 'manage_options', 'post_approval_settings', 'post_approval_settings');

		add_submenu_page('restricted-post-list', 'My Pending Review posts', 'My Pending Review posts', 'editor_capiblity', 'pending-review-post', 'pending_review_post');

		add_submenu_page('restricted-post-list', ' PENDING REVIEW POSTS', 'PENDING REVIEW POSTS', 'manage_options', 'all-pending-review-post', 'all_pending_review_post');
	}

	/**
	 * For update save functionality for restricted post in the admin area.
	 *
	 * @since    1.0.0
	 */
	public function pas_hide_minor_publishing()
	{

		global $post;
		$screen = get_current_screen();
		$restricted_post = get_restricted_post(false);
		if (!$post) {
			return;
		}
		$review_user =  (int) get_post_meta($post->ID, 'post_viewer', true);
		if (in_array($screen->id, $restricted_post)) {

			echo '<style>button.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary { display: none; }</style>';

			if ($review_user === get_current_user_id()) {
				echo '<style>button.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary { display: block; }</style>';
			}
		}
	}

	/**
	 * For add capbility to editor user role in the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_custom_capability_to_editor()
	{

		$editor_role = get_role('editor');
		$editor_role->add_cap('editor_capiblity');
	}


	/**
	 * Returns SVG icon for the plugin menu .
	 *
	 * @since     1.0.0
	 * @return    string  The icon name.
	 */

	function pas_menu_icon()
	{
		return 'dashicons-forms';
	}
}

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
 * Defines the plugin name, version, and two examples hooks for how to
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin
 * @author     Infobeans <infobeans@test.com>
 */
class Post_Approval_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Approval_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Approval_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-approval-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Approval_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Approval_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'post-approval-script', plugin_dir_url( __FILE__ ) . 'js/post-approval-admin.js', array( 'jquery' ), '1.0', true );

		wp_localize_script( 'post-approval-script', 'approval_object', 
        	array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 
        		   'nonce' => wp_create_nonce('post-approval-nonce')
        	    ) 
            );

	}

    /**
	 * To add a new menu in the admin area sidebar
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu(){


		add_menu_page( 'POST SETTINGS','POST SETTINGS', 'manage_options', 'post_approval_settings','post_approval_settings', $this->pas_menu_icon());
		
		add_submenu_page( 'post_approval_settings', 'Restricted Posts ', 'Restricted Posts', 'manage_options', 'restricted-post-list','restricted_post_list');

		add_submenu_page( 'post_approval_settings', 'My Pending Review posts', 'My Pending Review posts', 'editor_capiblity', 'pending-review-post','pending_review_post');
		
	}
   

    /**
	 * For update save functionality for restricted post in the admin area.
	 *
	 * @since    1.0.0
	 */
    public function pas_hide_minor_publishing() {

	    global $post;
	    $screen = get_current_screen();
	    $restricted_post = get_restricted_post(false);
	    if(!$post) { return; }
	    $review_user =  ( int ) get_post_meta($post->ID,'post_viewer',true);
	    if( in_array( $screen->id, $restricted_post ) ) {
	        
	        echo '<style>button.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary { display: none; }</style>';

	        if($review_user === get_current_user_id()){ 
				echo '<style>button.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary { display: block; }</style>';

	        }
	    }
	}


	/**
	 * For add capbility to editor user role in the admin area.
	 *
	 * @since    1.0.0
	 */
    public function add_custom_capability_to_editor() {

	    $editor_role = get_role('editor');
        $editor_role->add_cap('editor_capiblity');
	}


	/**
	 * Returns SVG icon for the plugin menu .
	 *
	 * @since     1.0.0
	 * @return    string  The icon name.
	 */

	function pas_menu_icon() {
		return 'dashicons-forms';
	}

}

<?php

/**
* Plugin Name: Post Approval

* Plugin URI: http://mysite.com/

* Description: Post approval life cycle system.

* Version: 1.0 

* Author: Infobeans

* Author URI: Infobeans.com

* License: 
* 
*/


if ( !defined( 'ABSPATH' ) ) exit;


define( 'POST_APPROVAL_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . '/action-hook.php';

require_once plugin_dir_path( __FILE__ ) . '/config.php';


// Activate Plugin
register_activation_hook( __FILE__, 'post_approval_activation');


// De-activate Plugin
register_deactivation_hook( __FILE__,'post_approval_deactivation' );


add_action( 'admin_menu', 'register_admin_post_setting_page' );


function post_approval_activation() {
	init_db_pas();
}


/**
 * Register post setting page
 */
function register_admin_post_setting_page(){

	add_menu_page( 'POST SETTINGS','POST SETTINGS', 'manage_options', 'post_approval_settings','post_approval_settings', pas_menu_icon());
	add_submenu_page( 'post_approval_settings', 'Restricted Posts ', 'Restricted Posts', 'manage_options', 'restricted-post-list','restricted_post_list');

	add_submenu_page( 'post_approval_settings', 'My Pending Review posts', 'My Pending Review posts
', 'editor_capiblity', 'pending-review-post','pending_review_post');
}


/**
  * function to get editor assign post 
  * 
  * @param 
  * @return post array
 */

function pending_review_post(){ 
    		
    			global $wpdb; $all_ids = array(); $login_user = get_current_user_id();
    			$user_approval_table= $wpdb->prefix."user_approval_post";
			    if($login_user){
			    	$review_post_ids = $wpdb->get_results( "SELECT id, post_id FROM $user_approval_table where user_id = $login_user ", ARRAY_A );
				     if($review_post_ids){
				     	foreach($review_post_ids as $ids){
				     	 $all_ids[] = (int) $ids['post_id'];
				     	}
				     }     
			    }
				$args = array(
					'post_type' => 'any',
					'post_status'=>'draft',
				    'post__in' => $all_ids
				);
	            $review_posts = get_posts($args);
				if(isset($_GET['view']) && $_GET['view']!=''){
                    require_once plugin_dir_path( __FILE__ ) . 'inc/template-pending-post-view.php';
				}else{
			        require_once plugin_dir_path( __FILE__ ) . 'inc/template-pending-post-list.php';
				}
	     ?>

<?php }


/**
 * Return the Restriction Form data.
 *
 * @param 
 * @return Form data
 */
function post_approval_settings(){
   
    require_once plugin_dir_path( __FILE__ ) . 'inc/form/restriction-settings-form.php';

}


/**
 * Return the Restriction post list.
 *
 * @param 
 * @return data array
 */
function restricted_post_list(){

    require_once plugin_dir_path( __FILE__ ) . 'inc/restricted-post-list.php';

}
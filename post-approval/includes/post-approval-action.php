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

/**
 * Returns SVG icon for custom menu icon
 *
 * @return string
 */
function pas_menu_icon() {
	return 'dashicons-forms';
}

/**
 * function to get restricted user Data
 * 
 * @return user array
 */
function get_assign_user($post){
	
    $key = $post."_post_restricted_users";
    $user_name = '';
    $restrected_user = get_option($key);

    foreach($restrected_user as $user){
    	$user_info = get_userdata($user);
        $user_name.= ucfirst($user_info->display_name) . " | ";
    }

    return rtrim($user_name, ' |');

}

/**
 * function to get all editor Data
 * 
 * @return editor array
 */
function get_all_editors(){
	$usersData = get_users( array( 'role__in' => 'editor', 'fields' => array('id','display_name' )));
	return $usersData;
}

/**
 * function to get all restricted Posts Data
 * 
 * @return post array
 * 
 */
function get_restricted_post($postdata = false){
	global $wpdb;
    $table_name = $wpdb->prefix."restricted_post";
    $all_post_types=array(); 
    $all_posts = array();
    $restricted_post_result = $wpdb->get_results( "SELECT post_slug,id FROM $table_name", ARRAY_A );
    if($restricted_post_result){

    	if($postdata){
	    	foreach($restricted_post_result as $result){
	          $all_post_types['title'] = $result['post_slug'];
	          $all_post_types['id'] = $result['id'];
	          $all_posts[] = $all_post_types;
	        }	
    	}else{
	    	foreach($restricted_post_result as $result){
	          $all_posts[] = $result['post_slug'];
	        }
    	}
    }
    return $all_posts;
}

/**
 * function to check post exist or not
 * 
 * @return int count
 */
function exist_post_or_not($post_id){
    global $wpdb;
    $rowcountvlaue = false;
    $user_approval_table= $wpdb->prefix."user_approval_post";
    $rowcountvlaue = $wpdb->get_var( "SELECT count(*) as count FROM $user_approval_table user_id where post_id = $post_id " );
    return $rowcountvlaue;
}

/**
 * Function to check editor post and assign
 * 
 * @return Review User
 * 
 * @param  Post ID
 */
function reviewr_users($post_id){
    global $wpdb; $assignpost = false;
    $post_type = get_post_type($post_id);
    $key = $post_type."_post_restricted_users";
    $user_approval_table= TABLE_USER_POST_APPROVAL;
    $post_viewer_users = get_option($key);
    $user_name = '';
    if($post_id){
	    if($post_viewer_users ){
            /* Condition for check first assign post to editor user*/
	    	foreach($post_viewer_users as $user){	            
	            $assign_user_post = get_user_meta($user,$post_type.'_assign_post',true);	            
	            if(!$assign_user_post){
		            $sql = $wpdb->prepare( "INSERT INTO ".$user_approval_table." (user_id, post_id,post_type) VALUES ( %d, %d, %s)", $user, $post_id,$post_type );
		            $wpdb->query($sql);
		            update_user_meta($user,$post_type.'_assign_post',1);
		            update_post_meta($post_id,'post_viewer',$user);
		            $assignpost = true;
		            break;
	            }
	    	}
	        /* Condition to check user post count and assign new post*/ 
	        if(!$assignpost){
	            $user_posts_count_data = $wpdb->get_results( "SELECT count(*) as total, user_id FROM $user_approval_table group by user_id", ARRAY_A );
	            $comonvalue = array_column($user_posts_count_data, 'total', 'user_id');
	            $min_count_userid = array_keys($comonvalue, min($comonvalue));
                $sql = $wpdb->prepare( "INSERT INTO ".$user_approval_table." (user_id, post_id,post_type) VALUES ( %d, %d, %s)", $min_count_userid[0], $post_id,$post_type );
                update_post_meta($post_id,'post_viewer',$min_count_userid[0]);
		        $wpdb->query($sql);
	        }
        }
    }
}

/**
 * Function ajax request to check restricted post user
 * 
 * @return User Data
 * 
 * @param  Post Type
 */
function ajax_process_post() {
    check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
	$roles ='editor';
	$user_data = '';
	$usersData = get_users( array( 'role__in' => $roles, 'fields' => array('id','display_name' )));
    $postype =$_POST['post_type'];
    if($postype){
    	$key=$postype."_post_restricted_users";
    	$old_restrected_user = get_option($key);
	    foreach ( $usersData  as $user ) {
	    	if(!empty($old_restrected_user)){
	    		$checked_val = (in_array($user->id, $old_restrected_user)) ? 'selected' : '';
	    	}
       
        $user_data.='<option  value ='.$user->id.' '.$checked_val.' >' . ucfirst($user->display_name  ).'</option>';
		}
    }
    echo json_encode(array('success' => true, 'data' => $user_data));
    wp_die(); 
}

/**
 * Function ajax request to check restricted post user
 * 
 * @return User Data
 * 
 * @param  Post Id
 */
function change_post_status_after_save( $post_id, $post, $update ) {
    if(!exist_post_or_not($post_id)){
     	reviewr_users($post->ID);
    }
}

/**
 * Function ajax request to update restricted post user
 * 
 * @return User Data
 * 
 * @param  User Id Post ID
 */
function ajax_edit_restricted_post(){

	check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
	$roles ='editor';
	$user_data = '';
	$usersData = get_users( array( 'role__in' => $roles, 'fields' => array('id','display_name' )));
    $postype =$_POST['post_type'];
    $id =$_POST['id'];

    if($postype){
    
    	$key=$postype."_post_restricted_users";
    	$old_restrected_user = get_option($key);
        $user_data .='<ul class="restricted_user_data">';
	    foreach ( $usersData  as $user ) {
	    	if(!empty($old_restrected_user)){
	    		$checked_val = (in_array($user->id, $old_restrected_user)) ? 'checked' : '';
	    	}
			$user_data.= '<li class="li-user"> <input type="checkbox" name="restricted_user_'.$id.'[]" value ='.$user->id.' '.$checked_val.'>' . ucfirst($user->display_name ). '</li>';
		}
	    $user_data .='</ul>';
    }
    echo json_encode(array('success' => true, 'data' => $user_data));
    wp_die();
}

/**
 * Function ajax request to delete restricted post
 * 
 * @return message
 * 
 * @param  Post ID
 */
function ajax_delete_restricted_post(){

    global $wpdb;
	check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
	if($_POST['id'] && $_POST['post_type']){
		delete_option($_POST['post_type'].'_post_restricted_users');
        $wpdb->delete( TABLE_RES_POST, array( 'id' => $_POST['id']) );
        echo json_encode(array('success' => true, 'message' => 'Deleted Successfully!!!'));
	}else{
        echo json_encode(array('success' => false, 'message' => 'Something goes worng, please try again latter'));
	}
    wp_die(); 
}


/**
 * Function ajax request to updte restricted post
 * 
 * @return message
 * 
 * @param  Post ID, Post Type
 */
function ajax_udpate_restricted_post(){

    check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
    if($_POST['id'] && $_POST['post_type']){
    	$restricted_post= $_POST['post_type'];
		update_option($restricted_post.'_post_restricted_users',$_POST['checked_val'],0);
        echo json_encode(array('success' => true, 'message' => 'Updated Successfully!!!'));
	}else{
        echo json_encode(array('success' => false, 'message' => 'Something goes worng, please try again latter'));
	}
    wp_die(); 
}


/**
 * Function ajax request to delete assign post
 * 
 * @return message
 * 
 * @param  Post ID
 */
function ajax_delete_assign_post(){

    global $wpdb;
	check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
	if($_POST['id']){
		wp_trash_post($_POST['id']);
        $wpdb->delete( TABLE_USER_POST_APPROVAL, array( 'post_id' => $_POST['id']) );
        echo json_encode(array('success' => true, 'message' => 'Deleted Successfully!!!'));
	}else{
        echo json_encode(array('success' => false, 'message' => 'Something goes worng, please try again latter'));
	}
    wp_die(); 
}


/**
 * Fuction to re assign post to editor.
 *
 * @param Post ID, User Id
 * 
 * @return comment
 */
function ajax_re_assign_post(){
	global $wpdb;
	check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
    $user_comment_table= TABLE_USER_POST_COMMENT;
    $user_approval_table= TABLE_USER_POST_APPROVAL;
    if($_POST['id'] && $_POST['userid'] && $_POST['assign_user']){

        $sql = $wpdb->prepare( "INSERT INTO ".$user_comment_table." (user_id, post_id,user_comment) VALUES ( %d, %d, %s)", $_POST['userid'], $_POST['id'], $_POST['comment'] );
        $wpdb->update($user_approval_table, array('post_id'=>$_POST['id'], 'user_id'=>$_POST['assign_user']), array('post_id'=>$_POST['id']));

        update_post_meta($_POST['id'],'post_viewer',$_POST['assign_user']);

        $wpdb->query($sql);
        $url = admin_url()."/admin.php?page=pending-review-post";
        echo json_encode(array('success' => true, 'message' => 'Re assigned post Successfully!!!','url'=>$url));
	}else{
        echo json_encode(array('success' => false, 'message' => 'Something goes worng, please try again latter'));
	}
	wp_die(); 
}


/**
 * Fuction to update post comment.
 *
 * @param Post ID
 * 
 * @return comment
 */
function user_post_comment($post_id = false){
  
  	global $wpdb; $postcomment = '';
    $user_comment_table= TABLE_USER_POST_COMMENT;
    if($post_id ){
        $post_comment = $wpdb->get_results( "SELECT user_id, user_comment FROM $user_comment_table where post_id = $post_id", ARRAY_A );
        if($post_comment){
         	foreach ($post_comment as $comment){
         		$user= get_userdata($comment['user_id']);
        		$postcomment.= '<b>'.ucfirst($user->display_name).':</b> ';
         		$postcomment.= $comment['user_comment']."<br>";
         	}
        }
    }
    return $postcomment;
}


/**
 * Return the Restriction Form data.
 *
 * @param 
 * @return Form data
 */
function post_approval_settings(){

	if(isset($_GET['edit']) && $_GET['edit'] && $_GET['edit']!=''){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/form/post-approval-restriction-settings-edit-form.php';
	}else{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/form/post-approval-restriction-settings-form.php';
	}
    
}


/**
  * function to get editor assign post 
  * 
  * @param 
  * @return post array
 */
function pending_review_post(){ 
    			
    		global $wpdb; $all_ids =  $args = $review_posts = array();
			$login_user = get_current_user_id();
		        $user_approval_table= $wpdb->prefix."user_approval_post";
			    if($login_user){
			    	$review_post_ids = $wpdb->get_results( "SELECT id, post_id FROM $user_approval_table where user_id = $login_user ", ARRAY_A );
				     if($review_post_ids){
				     	foreach($review_post_ids as $ids){
				     	 $all_ids[] = (int) $ids['post_id'];
				     	}
				     }     
			    }
			    if(!empty($all_ids)) {
				$args = array(
					'post_type' => 'any',
					'post_status'=>'draft',
				    'post__in' => $all_ids
				    );

				 $review_posts = get_posts($args);
			    }
				if(isset($_GET['view']) && $_GET['view']!=''){
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/post-approval-pending-post-view.php';
				}else{
			        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/post-approval-pending-post-list.php';
				}
	     ?>
<?php }


/**
 * Return the Restriction post list.
 *
 * @param 
 * @return data array
 */
function restricted_post_list(){
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/post-approval-restricted-post-list.php';
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

/**
 * load script file.
 *
 * @param 
 * @return 
 */
function post_approval_scripts() {

    wp_enqueue_script( 'post-approval-script', POST_APPROVAL_PLUGIN_DIR. 'includes/js/post-approval.js', array('jquery'), '1.0', true );

   wp_enqueue_script( 'chosen.jquery', POST_APPROVAL_PLUGIN_DIR. 'includes/js/chosen.jquery.js', array('jquery'), '1.0', true );
    
    wp_localize_script( 'post-approval-script', 'approval_object', 
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('post-approval-nonce')
        )
    );
}

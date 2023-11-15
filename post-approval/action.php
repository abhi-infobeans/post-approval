<?php
/**
 *  Action Hook
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


function post_approval_scripts() {

	wp_enqueue_style( 'post-approval-style', POST_APPROVAL_PLUGIN_DIR. 'include/css/common.css', array(), '1.0', 'all' );

    wp_enqueue_script( 'post-approval-script', POST_APPROVAL_PLUGIN_DIR. 'include/js/post-approval.js', array('jquery'), '1.0', true );
    
    wp_localize_script( 'post-approval-script', 'approval_object', 
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('post-approval-nonce')
        )
    );
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
 * Function to check Editor post and assign
 * 
 *  @return 
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
	    		$checked_val = (in_array($user->id, $old_restrected_user)) ? 'checked' : '';
	    	}
			$user_data.= '<li class="li-user"> <input type="checkbox" name="restricted_user[]" value ='.$user->id.' '.$checked_val.'>' . ucfirst($user->display_name ). '</li>';
		}
    }
    echo json_encode(array('success' => true, 'data' => $user_data));
    wp_die(); 
}


function pas_hide_minor_publishing() {

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

function change_post_status_after_save( $post_id, $post, $update ) {
    if(!exist_post_or_not($post_id)){
     	reviewr_users($post->ID);
    }
}

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


function add_custom_capability_to_editor() {
    $editor_role = get_role('editor');
    $editor_role->add_cap('editor_capiblity');
}



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


function ajax_re_assign_post(){

	global $wpdb;
	check_ajax_referer( 'post-approval-nonce', 'nonce' );  // Check the nonce.
    $user_comment_table= TABLE_USER_POST_COMMENT;
    $user_approval_table= TABLE_USER_POST_APPROVAL;


    if($_POST['id'] && $_POST['userid'] && $_POST['assign_user']){

        $sql = $wpdb->prepare( "INSERT INTO ".$user_comment_table." (user_id, post_id,user_comment) VALUES ( %d, %d, %s)", $_POST['userid'], $_POST['id'], $_POST['comment'] );


        $wpdb->update($user_approval_table, array('post_id'=>$_POST['id'], 'user_id'=>$_POST['assign_user']), array('post_id'=>$_POST['id']));

        $wpdb->query($sql);

        $url = admin_url()."/admin.php?page=pending-review-post";
        echo json_encode(array('success' => true, 'message' => 'Re assigned post Successfully!!!','url'=>$url));
	}else{
        echo json_encode(array('success' => false, 'message' => 'Something goes worng, please try again latter'));
	}

	wp_die(); 


}


function user_post_comment($post_id = false){
  
  	global $wpdb; $postcomment = '';
    $user_comment_table= TABLE_USER_POST_COMMENT;
    if($post_id ){
        
        $post_comment = $wpdb->get_results( "SELECT user_id, user_comment FROM $user_comment_table where post_id = $post_id", ARRAY_A );

        if($post_comment){
         	foreach ($post_comment as $comment){
         		$user= get_userdata($comment['user_id']);
        		$postcomment.= '<b>'.ucfirst($user->display_name).':</b> ';
         		$postcomment.= $comment['user_comment'];
         	}
        }
    }
    return $postcomment;
}

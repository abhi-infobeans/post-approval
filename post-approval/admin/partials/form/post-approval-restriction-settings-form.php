<?php 

/**
 * Provide a restriction settings form in admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin/partials/form
 */


    global $wpdb;
    $table_name = TABLE_RES_POST;
    
    $all_post_types=array();

    $url = admin_url();
    
    $restricted_post_result = $wpdb->get_results( "SELECT post_slug FROM $table_name", ARRAY_A );

    if($restricted_post_result){
    	foreach($restricted_post_result as $result){
          $all_post_types[] = $result['post_slug'];
        }
    }
    
    if(isset($_POST['savedata']) && $_POST['savedata'] == 'savedata' && $_POST['restricted_post']!=''){
   
      if(isset($_POST['restricted_post']) && $_POST['restricted_post']!=''){
  
        $restricted_post = $_POST['restricted_post'];
	
						if(!in_array( $restricted_post, $all_post_types )){
				            
				            $sql = $wpdb->prepare( "INSERT INTO ".$table_name." (post_title, post_slug) VALUES ( %s, %s )", $restricted_post, $restricted_post );
				                $wpdb->query($sql);
				     }
      }

      if(isset($_POST['restricted_user']) && $_POST['restricted_user']!=''){
        
      	update_option($restricted_post.'_post_restricted_users',$_POST['restricted_user'],0);

      	foreach($_POST['restricted_user'] as $user){
          update_user_meta($user,$restricted_post."_assign_post",0);
      	}

      }else{

        update_option($restricted_post.'_post_restricted_users',0);

      }
    
     echo '<div id="message" class="updated notice is-dismissible"><p>Restriction settings has been updated successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div> 
     <script>function updateurl(){ window.location.href = "'.$url.'admin.php?page=restricted-post-list";} 
      setTimeout(updateurl, 3000);</script>';

    }

	  echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		
		echo '<h1 class="wp-heading-inline">Restriction Settings Form</h1><br><br>';

		echo '<form id="restricted_form" name="restricted_form" action="" method="post">';

	$args = array(
	   'public'   => true,
	   '_builtin' => false
	);
 
	$output = 'names'; // 'names' or 'objects' (default: 'names')
	$operator = 'or'; // 'and' or 'or' (default: 'and')
	$post_types = get_post_types( $args, $output, $operator );
	$usersData = get_all_editors();

     echo '<div><b>Please select the type of post that needs to be restricted </b></div><br>';
   
	if ( $post_types ) { // If there are any custom public post types.

      echo '<select name="restricted_post" id="restricted_post" class="restricted_post">';
      echo '<option value="">Please select post type</option>';
        foreach ( $post_types  as $post_type ) {

		    	$exclude = array( 'page','attachment');
		        if( TRUE === in_array( $post_type, $exclude ) )
		                continue;
		        
		        echo '<option  value ='.$post_type.'>' . ucfirst($post_type ).'</option>';
		    }
      echo '</select>';
	}

    echo '<div><br><b>Please select the user that you want to restrict as a viewer of the post </b></div>';
    
		if ( $usersData ) { // If there are any editor users.

			echo '<ul class="restricted_user_data">';
			    
			    foreach ( $usersData  as $user ) {
			    	
			        echo '<li class="li-user"> <input type="checkbox" name="restricted_user[]" value ='.$user->id.'>' . ucfirst($user->display_name ). '</li>';
			    }

			echo '</ul>';
		}else{
			echo "<div class='no-user'><br>There is no user with an editor role. Please create a new user with editor permissions to assign the post.</div>";
		}

    echo '<input type="submit"value="Save" class="button button-primary">';
    echo '<input type="hidden" name="savedata" value="savedata">';
	echo '</form>';
echo '</div>';

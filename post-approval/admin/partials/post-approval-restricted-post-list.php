<?php 

/**
 * Provide a Restriction post list view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin/partials
 * 
 * 
 */

	$postdata = get_restricted_post(true); 
    $usersData  = get_all_editors();
 ?>
<div id="wpbody-content">
		<div class="wrap">
		 <h1 class="wp-heading-inline"><b>Restricted Post List </b> </h1> 
             <a href="<?php echo esc_url( pas_get_add_new_link( 'post_approval_settings' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Post Type', 'custom-post-type-ui' ); ?></a>
			<br class="clear">
		    <hr class="wp-header-end">
        
        <table class="wp-list-table widefat fixed striped table-view-list pages">
            <thead>
               <tr>
                  <td>Post Type</td>
                  <td>Reviewer User</td>
                  <td>Action</td>
               </tr>
            </thead>
            <tbody id="the-list">
	            <?php 
		            if($postdata ){ 

						if ( $usersData ) { 
							$editor ='<ul>';
							    foreach ( $usersData  as $user ) {
							     $editor.= '<li class="li-user"> <input type="checkbox" name="restricted_user[]" value ='.$user->id.'>' . ucfirst($user->display_name ). '</li>';
							    }

							$editor.= '</ul>';
						}

			            foreach($postdata as $post){

			         echo '<tr id="" class="iedit author-self level-0 post-3 type-page status-draft hentry">
			                  <td>'.ucfirst($post["title"]).'</td>
			                  <td>'.get_assign_user($post["title"]).'</td>
			                  <td><span class="edit_rpost" post_type ='.$post["title"].' postid ='.$post["id"].' >Edit </span> | <span class="delete_rpost" postid ='.$post["id"].' post_type ='.$post["title"].'>Delete</span></td></tr>';

			        echo '<tr id="res-'.$post["id"].'" class="restricted_show_hide"><td colspan="2"><span class="restricted_user_'.$post["id"].'">'.$editor.'</span></td><td><span class = "page-title-action update_rpost button button-primary" post_type ='.$post["title"].' postid ='.$post["id"].'>Update</span> </td></tr>';   


			            } 
		            }else{
		            	echo '<tr><td colspan="3"><b>No Restricted Post</b></td></tr>';
		            }

	            ?>
            </tbody>
            <tfoot>
               <tr>
                  <td>Post Type</td>
                  <td>Reviewer User</td>
                  <td>Action</td>
               </tr>
            </tfoot>
         </table>  
       </div>    
      <div id="ajax-response"></div>
      <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
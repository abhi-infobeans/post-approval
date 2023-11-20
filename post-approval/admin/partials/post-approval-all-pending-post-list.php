<?php

/**
 * Provide a pending view post list content view for the admin plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://infobeans.com
 * @since      1.0.0
 *
 * @package    Post_Approval
 * @subpackage Post_Approval/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wpbody-content">
		<div class="wrap">

<h1 class="wp-heading-inline"><b> Pending Review Posts </b> </h1> 

<br class="clear">
<hr class="wp-header-end">

<table class="wp-list-table widefat fixed striped table-view-list pages">
<thead>
   <tr>
      <td>Post Title</td>
      <td>Assigned User</td>
      <td>Post Type</td>
      <td>Comment</td>
      <td>Action</td>
   </tr>
</thead>
<tbody id="the-list">
    <?php 
        if($review_posts ){ 

            foreach($review_posts as $post){
                 
               $user_info = get_userdata(get_post_meta($post->ID,'post_viewer',true));
       		   $user_name = ucfirst($user_info->display_name);


               $comment_data = '';
               if(user_post_comment($post->ID)){
                
                $comment_data .= '<a href="#TB_inline?width=600&height=550&inlineId=modal-window-'.$post->ID.'" class="thickbox">View User comment</a>';
                  
                  add_thickbox();
                  $comment_data .='<div id="modal-window-'.$post->ID.'" style="display:none;">
                  <p>'.user_post_comment($post->ID).'</p></div>';

               }
               ?>
               
        <?php 
         echo '<tr id="" class="iedit author-self level-0 post-3 type-page status-draft hentry">
                  <td>'.ucfirst($post->post_title).'</td>
                  <td>'.$user_name.'</td>
                  <td>'.$post->post_type.'</td>
                  <td>'.$comment_data.'</td>
                  <td><a href= "'.admin_url().'/admin.php?page=all-pending-review-post&view='.$post->ID.'">  Re Assign </a> | <span class="delete_post" postid ="'.$post->ID.'">Delete</span> </td></tr>';
            }   
        }else{
        	echo '<tr><td colspan="5"><b>No Restricted Post</b></td></tr>';
        }

    ?>
</tbody>
<tfoot>
   <tr>
      <td>Post Title</td>
      <td>Assigned User</td>
      <td>Post Type</td>
      <td>Comment</td>
      <td>Action</td>
   </tr>
</tfoot>
</table> 

</div>    
      <div id="ajax-response"></div>
      <div class="clear"></div>
   </div>
<div class="clear"></div>
<?php
/**
 * Provide a pending view post list content in admin area view for the plugin
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

if( isset($_GET['view']) ){
   $data = get_post($_GET['view']);
}else{
   exit;
}

global $wpdb; 
$user_approval_table= TABLE_USER_POST_APPROVAL;
$post_viewer_users = get_option($data->post_type."_post_restricted_users");
if(($key = array_search(get_current_user_id(), $post_viewer_users)) !== false) {
    unset($post_viewer_users[$key]);
}
?>

   <div id="wpbody-content">
      <div class="wrap">
         <h1 class="wp-heading-inline"><b>Re Assign Post </b> </h1>
         <br class="clear">
         <hr class="wp-header-end">
         <div class="main">
            <table class="form-table cptui-table">
               <tbody>
                  <tr>
                     <th scope="row">
                        <label for="name">Post Title: </label>
                     </th>
                     <td>
                        <input type="text" id="post_title" name="post_title" value="<?php echo $data->post_title; ?>" disabled><br>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="label"> Add Comment: </label></th>
                     <td>
                        <textarea id="user_comment" name="user_comment" rows="8" cols="40"></textarea>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="label"> Re Assigne Users: </label></th>
                     <td>
                        <select id="assigne_user" name="assigne_user">
                           <option value="">Select any one</option>
                      <?php 
                           foreach($post_viewer_users as $id){
                              
                              $username = get_user_by('id',$id);

                           echo "<option value='".$id."'>".ucfirst($username->display_name)."</option>";
                           }
                      ?>
                      </select>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2">
                        <button class="assign-post button button-primary">Re Assign</button>
                        <input type="hidden"  id="post_id" value="<?php echo $_GET['view']; ?>">
                        <input type="hidden" id="user_id" value="<?php echo get_current_user_id();?>">
                     </td>
                  </tr>

               </tbody>
            </table>
         </div>
      </div>
      <div id="ajax-response"></div>
      <div class="clear"></div>
   </div>
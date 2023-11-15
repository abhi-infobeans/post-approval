
<?php
/**
 *  Pending view post list content .
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



?>

<div id="wpbody-content">
		<div class="wrap">

<h1 class="wp-heading-inline"><b>Review Post Listing </b> </h1> 

<br class="clear">
<hr class="wp-header-end">

<table class="wp-list-table widefat fixed striped table-view-list pages">
<thead>
   <tr>
      <td>Post Id</td>
      <td>Post Title</td>
      <td>Post Contect</td>
      <td>Comment</td>
      <td>Action</td>
   </tr>
</thead>
<tbody id="the-list">
    <?php 
        if($review_posts ){ 

            foreach($review_posts as $post){

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
                  <td>'.ucfirst($post->ID).'</td>
                  <td>'.ucfirst($post->post_title).'</td>
                  <td>'.$post->post_content.'</td>
                  <td>'.$comment_data.'</td>
                  <td><a href= "'.get_edit_post_link($post->ID).'">  View </a> | <a href= "'.admin_url().'/admin.php?page=pending-review-post&view='.$post->ID.'">  Re Assign </a> | <span class="delete_post" postid ="'.$post->ID.'">Delete</span> </td></tr>';

            }   
        }else{
        	echo '<tr><td colspan="4"><b>No Restricted Post</b></td></tr>';
        }

    ?>
</tbody>
<tfoot>
   <tr>
      <td>Post Id</td>
      <td>Post Title</td>
      <td>Post Contect</td>
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
</div>



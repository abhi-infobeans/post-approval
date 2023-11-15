
jQuery(document).ready(function($) {
    

    var checked = []
	$("input[name='options[]']:checked").each(function (){
	    checked.push(parseInt($(this).val()));
	});


    $('.restricted_post').on('change', function(){
        var post_type = $(this).val();
	        if(post_type){
		        $.ajax({
		            url : approval_object.ajax_url,
		            type : 'post',
		            data : {
		                action : 'process_post',
		                nonce : approval_object.nonce,
		                post_type : post_type
		            },
		            dataType: 'JSON',

		            success : function( response ) {
		                console.log(response.data)
		                $(".restricted_user_data").html(response.data);
		            }
		        });
	        }
        return false;
    });


    $('.edit_rpost').on('click', function(){
	    	
	    	var postid = $(this).attr( "postid" );
	        var post_type = $(this).attr( "post_type" );

	    	$("#res-"+postid).toggle();

	        $.ajax({
	            url : approval_object.ajax_url,
	            type : 'post',
	            data : {
	                action : 'edit_restricted_post',
	                nonce : approval_object.nonce,
	                id : postid,
	                post_type:post_type

	            },
	            dataType: 'JSON',

	            success : function( response ) {
	                console.log(response.data)
	                $(".restricted_user_"+postid).html(response.data);
	            }
	        });
        return false;
    });


    $('.delete_rpost').on('click', function(){

	    if(confirm("Are you sure you want to delete this?")){

	    	var postid = $(this).attr( "postid" );
	    	var post_type = $(this).attr( "post_type" );

	    	
	        $.ajax({
	            url : approval_object.ajax_url,
	            type : 'post',
	            data : {
	                action : 'delete_restricted_post',
	                nonce : approval_object.nonce,
	                id : postid,
	                post_type:post_type
	            },
	            dataType: 'JSON',

	            success : function( response ) {                
	                alert(response.message);
	                location.reload(true);
	            }
	        });
	    }
	    else{
	        return false;
	    }
        
        return false;
    });



    $('.update_rpost').on('click', function(){

	    	var postid = $(this).attr( "postid" );
	    	var post_type = $(this).attr( "post_type" );
            var checked = []
			
			$("input[name='restricted_user_"+postid+"[]']:checked").each(function (){
			    checked.push(parseInt($(this).val()));
			});

		if(checked.length >0 ) {
             $.ajax({
	            url : approval_object.ajax_url,
	            type : 'post',
	            data : {
	                action : 'update_restricted_post',
	                nonce : approval_object.nonce,
	                id : postid,
	                post_type:post_type,
	                checked_val:checked

	            },
	            dataType: 'JSON',

	            success : function( response ) {                
	                alert(response.message);
	                location.reload(true);
	            }
	        });
		}else{
		   alert('If you wish to delete all users from the system, you can use the "delete" function to remove them all at once.');
		}
	    	
	        
	    
        
        return false;
    });



    $('.delete_post').on('click', function(){

	    if(confirm("Are you sure you want to delete this post?")){

	    	var postid = $(this).attr( "postid" );
	    	
	        $.ajax({
	            url : approval_object.ajax_url,
	            type : 'post',
	            data : {
	                action : 'delete_assign_post',
	                nonce : approval_object.nonce,
	                id : postid,
	            },
	            dataType: 'JSON',

	            success : function( response ) {                
	               alert(response.message);
	                location.reload(true);
	            }
	        });
	    }
	    else{
	        return false;
	    }
        
        return false;
    });


    $('.assign-post').on('click', function(){

	    if(confirm("Are you sure you want to re-assigne this post?")){

	    	var postid = $("#post_id").val();
	    	var userid = $("#user_id").val();
	    	var comment = $("#user_comment").val();
	        var assign_user = $("#assigne_user").val();

	        $.ajax({
	            url : approval_object.ajax_url,
	            type : 'post',
	            data : {
	                action : 're_assign_post',
	                nonce : approval_object.nonce,
	                id : postid,
	                userid : userid,
	                comment : comment,
	                assign_user : assign_user,
	            },
	            dataType: 'JSON',

	            success : function( response ) { 
	                if(response.success){
                        alert(response.message);
	                	location.href = response.url;
	                } else{
	                	alert(response.message);
	                	location.reload(true);
	                }              
	                
	            }
	        });
	    }
	    else{
	        return false;
	    }
        
        return false;
    });





});
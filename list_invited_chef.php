<?php
$result = array();
$filter = $data['key'];
$bookingID = $data['bookingID'];
if(!empty($filter)){
	if($filter == "all"){
	$my_posts = get_posts(array(
	'posts_per_page'	=> -1,
	'post_type'		=> 'invitation',
	'post_status'    => 'publish',
	'meta_query'	=> array(
		'relation'		=> 'AND',
		array(
			'key'	 	=> 'invited_by',
			'value'	  	=> $user_id,
			'compare' 	=> '=',
		),
		array(
			'key'	  	=> '_invited_jobs',
			'value'	  	=> $bookingID,
			'compare' 	=> '=',
		),
			array(
			'key'	  	=> '_status',
			'value'	  	=> "rejected",
			'compare' 	=> '!=',
		),
	),
));	
	} else{
$my_posts = get_posts(array(
	'posts_per_page'	=> -1,
	'post_type'		=> 'invitation',
	'post_status'    => 'publish',
	'meta_query'	=> array(
		'relation'		=> 'AND',
		array(
			'key'	 	=> 'invited_by',
			'value'	  	=> $user_id,
			'compare' 	=> '=',
		),
		array(
			'key'	  	=> '_status',
			'value'	  	=> $filter,
			'compare' 	=> '=',
		),
		array(
			'key'	  	=> '_invited_jobs',
			'value'	  	=> $bookingID,
			'compare' 	=> '=',
		),
	),
));
	}

foreach($my_posts as $value){
	$postId = $value->ID;
	$userId = $value->post_author;
	$chefChatId = '1345879658'.$userId;
	$userChatId = '1345879658'.$user_id;
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$budget1 = get_post_meta($postId,"_proposal_price", true);
	if($budget1){
		$budget = $budget1;
	} else {
		$budget = get_user_meta($userId,"_ChefBudget", true);
	}
	$location = get_user_meta($userId, '_location', true);
	$rating = get_user_meta($userId, 'avrg_rating', true);
	if($rating){
		$avrgRating = $rating;
	}else {
		$avrgRating = 0;
	}
	$status = get_post_meta($postId, '_status', true);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$result[] = array(
	                    "bookingId"=>$postId,
	                    "chefId"=>$userId,
					    "fullName"=>$fullName ,
						"location"=>$location,
						"rating"=>$avrgRating,
						"costPerHour"=>$budget,
						"profilePic"=>$imageUrl,
						"chefChatId"=>(int)$chefChatId,
						"userChatId"=>(int)$userChatId,
						"status"=>$status
						);
}
   
	$msg = 'list of chef.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $result));
	exit();
} else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
}
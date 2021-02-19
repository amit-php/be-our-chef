<?php 
	 $postId1         = $data['postId'];
	$result = array();
	 if(!empty( $postId1 )){
	if(empty(get_the_title($postId1))){
	    $msg = 'job is not founded.';
		$success = false;
		$resultRes = array("bookingDetails"=>$result);
		$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();	
	}
     $postId         = get_post_field( '_invited_jobs', $postId1 );
	 $userID         = get_post_field( 'post_author', $postId );
	 $chefChatId = '1345879658'.$user_id;
	 $userChatId = '1345879658'.$userID;
     $user_info = get_userdata($userID);
	 $user_first_name = $user_info->first_name;
	 $user_last_name = $user_info->last_name;
	 $fullName = $user_first_name.' '.$user_last_name;
	 $userImag = get_user_meta($user_id,"_profile_image_user", true);
	 $imageUrl = (string)wp_get_attachment_url($userImag);
	 $location = get_post_meta($postId, "_location", true);
	 $date = get_post_meta($postId, "_date", true);
	 $time = get_post_meta($postId, "_time", true);
	 $budget = get_post_meta($postId, "_budget", true);
	 $status = get_post_meta($postId1, "_status", true);
	 $how_many_people = get_post_meta($postId, "how_many_people", true);
	 $dietary_requirements = get_post_meta($postId, "dietary_requirements", true);
	 $description = get_post_meta($postId, "_description", true);
	 $created = get_post_meta($postId1, "created", true);
	 $title = get_the_title($postId);
	 $result = array("jobId"=>$postId1,"bookingID"=>$postId,"userId"=>$userID,"title"=>$title, "fullName"=>$fullName, "image"=>$imageUrl, 
	 "location"=>$location,"date"=>$date,"time"=>$time,"budget"=>$budget,"how_many_people"=>$how_many_people,
     "dietary_requirements"=>$dietary_requirements,"description"=>$description,
	 "chefChatId"=>(int)$chefChatId,"userChatId"=>(int)$userChatId,"status"=>$status,"confirmTimestamp"=>(string)$created);
	 

    $msg = 'booking details.';
	$success = true;
	$resultRes = array("bookingDetails"=>$result);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
	 } else{
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit(); 
	 }
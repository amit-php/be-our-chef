<?php

	$jobId = $data['jobId'];
	if($jobId){
	$userId = get_post_field( 'post_author', $jobId );
	 $chefChatId = '1345879658'.$user_id;
	 $userChatId = '1345879658'.$userId;
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$location = get_post_meta($jobId, "_location", true);
	$date     = get_post_meta($jobId, "_date", true);
	$occasion = get_the_title($jobId);
	$perice   = get_post_meta($jobId, "_budget", true);
	$howManeyPeople   = get_post_meta($jobId, "how_many_people", true);
	$dietaryReq   = get_post_meta($jobId, "dietary_requirements", true);
	$details   = get_post_meta($jobId, "_description", true);
	$status   = get_post_meta($jobId, "_status", true);
	$result = array("jobId"=>$jobId,"userId"=>$userId, "occasionType"=>$occasion,"fullName"=>$fullName,
	"location"=>$location,"date"=>$date,"perice"=>$perice,"userImage"=>$imageUrl,"howManeyPeople"=>$howManeyPeople
	,"dietaryReq"=>$dietaryReq,"details"=>$details,"chefChatId"=>(int)$chefChatId,"userChatId"=>(int)$userChatId,"status"=>$status);

 $resultRes = array('jobList'=>$result);
	$msg = 'job details.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
	}else{
	
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
	}
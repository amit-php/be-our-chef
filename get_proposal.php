<?php 
	$jobId         = $data['jobId'];
	$result = array();
	$imgs = [];
	if($jobId){
	if(!empty(get_the_title($jobId))){
	$proposal_details = get_post_meta($jobId, "_proposal_details", true);
	$proposal_price = get_post_meta($jobId, "_proposal_price", true);
	$proposal_image = get_field("proposal_image", $jobId);
	if($proposal_image){
		    foreach( $proposal_image as $row ) {
        $img[] = $row['image'];
	
			}
	}
	
	 
	 $result = array("jobId"=>$jobId,"proposalDetails"=>$proposal_details, "proposalPrice"=>$proposal_price, "image"=>$img);
	 

    $msg = 'booking details.';
	$success = true;
	$resultRes = array("bookingDetails"=>$result);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
	} else{
		$msg = 'not found.';
	$success = false;
	$resultRes = array("bookingDetails"=>$result);
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	}
	}else{
	$msg = 'Required field.';
	$success = false;
	$resultRes = array("bookingDetails"=>$result);
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	}
	 
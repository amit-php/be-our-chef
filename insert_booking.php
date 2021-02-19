<?php
	$occasion       = $data['occasion'];
	$location       = $data['location'];
	$date           = $data['date'];
	$time           = $data['time'];
	$budget         = $data['budget'];
	$NumberOfpeople = $data['NumberOfpeople'];
	$diatery        = $data['diatery'];
	$description    = $data['description'];
	$postCode = $data['postCode'];
	if(!empty($occasion) && !empty($location) && !empty($date) && !empty($time) && !empty($budget) &&
	!empty($NumberOfpeople) && !empty($diatery) && !empty($description)){
	$my_post = array(
	    'post_type' => 'booking',
		'post_title'    => $occasion,
		'post_status'   => 'publish',
		'post_author'   => $user_id,
	);
	// Insert the post into the database.
	$bookingId = wp_insert_post( $my_post );
	if(!is_wp_error($bookingId)){
	$timestamp = strtotime($date);
	$month = date("m",$timestamp);
	$year = date("Y",$timestamp);
	$day = date("d",$timestamp);
    update_post_meta($bookingId, "_location", $location);
	update_post_meta($bookingId, "_date", $date);
	update_post_meta($bookingId, "_time", $time);
	update_post_meta($bookingId, "_budget", $budget);
	update_post_meta($bookingId, "how_many_people", $NumberOfpeople);
	update_post_meta($bookingId, "dietary_requirements", $diatery);
	update_post_meta($bookingId, "_description", $description);
	update_post_meta($bookingId, "_postal_codes", $postCode);
	update_post_meta($bookingId, "_status", 'upcoming');
	$msg = 'booking is added.';
	$success = true;
	$resultRes = array("bookingId"=>$bookingId);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
	}else{
	  //there was an error in the post insertion, 
	$msg = $post_id->get_error_message();
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
	}
	} else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();	
	}
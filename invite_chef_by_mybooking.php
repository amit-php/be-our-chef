<?php
$chefId = $data['chefId'];
$jobsId = $data['jobsId'];
if(!empty($chefId) && !empty($jobsId)){
	foreach($chefId as $chefIds){
		$chef = get_user_by( 'id', $chefIds );
		$chefdetails = 'invited to chef '. $chef->first_name . ' ' . $chef->last_name;
		
		$my_post = array(
		'post_type' => 'invitation',
		'post_title'    => $chefdetails,
		'post_status'   => 'publish',
		'post_author'   => $chefIds ,
		);
		// Insert the post into the database.
			$invationId = wp_insert_post( $my_post );
		if(!is_wp_error($bookingId)){
			$jobDate = get_post_meta($jobsId,"_date",true);
		    update_post_meta($invationId,"_date",$jobDate);
			update_post_meta($invationId,"_invited_jobs",$jobsId);
			update_post_meta($invationId,"_status","invited");
			update_post_meta($invationId,"invited_by",$user_id);
			
		}
	}
	
    $msg = 'chef is invited.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
	
}else{
	 $msg = 'Requirde fields.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
}
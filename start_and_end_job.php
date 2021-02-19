<?php
$bookinid = $data['bookingId'];
$status = $data['status'];
require_once('vendor/autoload.php');
require_once('vendor-new/stripe/init.php');
if(!empty($bookinid) && !empty($status)){
	if($status == "start"){
	 update_post_meta($bookinid, "_status","in progress");
	 $jobId = get_post_meta($bookinid, "_invited_jobs", true);
	 update_post_meta($jobId, "_status", "in progress");
	$msg = 'job is started.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
	} 
	if($status == "end"){
	$transferGroup = get_post_meta($bookinid, "transfer_group",true);
	$chef_fee = 	get_post_meta($bookinid, "_tax",true);
	$get_post = get_posts(array(
						'author'=>$user_id,
						'posts_per_page'	=> 1,
						'post_type'		=> 'bank',
						'post_status'    => 'publish',
					  ));
	foreach($get_post as $get_posts){
		$postids = $get_posts->ID;
		$chefId = get_post_meta($postids, "_customer_id", true);
	}	
    if($chefId)	{
	$stripe = new \Stripe\StripeClient(
    'sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0'
	);
	$transfers = $stripe->transfers->create([
	  'amount' => 	1,
	  'currency' => 'gbp',
	  'destination' => $chefId,
	  'transfer_group' => $transferGroup,
	]);
	update_post_meta($bookinid, "_status", "completed");
	$jobId = get_post_meta($bookinid, "_invited_jobs", true);
	update_post_meta($jobId, "_status", "completed");
	$msg = 'job is completed.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $transfers));
	exit();	
	} else{
	 $msg = 'no account id is found.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();	
	}
	}
}
else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
}
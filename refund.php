<?php
	require_once('vendor/autoload.php');
	require_once('vendor-new/stripe/init.php');
    $bookingId = $data['bookingId'];
	if($bookingId){
		$chrgid = get_post_meta($bookingId,"_charge_id", true);
		$refund_amount = get_post_meta($bookingId,"_tax", true);
 if(!empty($chrgid)) {
    \Stripe\Stripe::setApiKey("sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0"); //secret key
    $charge = \Stripe\Refund::create([
      'charge' => $chrgid,
      'amount' => (int)$refund_amount*100,
    ]);
	if($charge->id){
    update_post_meta($bookingId, 'cancel_id', $charge->id );
    update_post_meta($bookingId, 'cancel_transaction_id', $charge->balance_transaction );
	update_post_meta($bookingId, "_status", "cancel");
	$jobId = get_post_meta($bookingId, "_invited_jobs", true);
	update_post_meta($jobId, "_status", "cancel");
	
    $msg = 'cancel successfully.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $charge));
	exit();
	}else {
		$msg = 'Charge is not refunded.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
		exit(); 	
	}
       } else {
	$msg = 'Charge id is not found.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit(); 
	}
	}else{
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit(); 
	}

?>
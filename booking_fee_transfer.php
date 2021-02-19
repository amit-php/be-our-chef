<?php
	$bookingId = $data['bookingId'];
	$cardId = $data['cardId'];
	include get_template_directory() . '/vendor/autoload.php';
	if(!empty($bookingId) && !empty($cardId)){
		$booikingFee = get_post_meta($bookingId, "_tax", true);
		$stripeToken = get_post_meta($cardId, "_tax", true);
	\Stripe\Stripe::setApiKey("sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0"); //secret key
    $charge = \Stripe\Charge::create([
      "amount"      => $booikingFee,
      "currency"    => "usd",
      "source"      => $stripeToken, // obtained with Stripe.js
      "description" => "test",
    ]);
	print_r($charge);
     $paymentStatus    =   $charge->status;
     $tansactionId     =   $charge->id;
     $blancetransation =   $charge->balance_transaction;
     $paymentMethod    =   $charge->payment_method;
	}
	 else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();	
	}
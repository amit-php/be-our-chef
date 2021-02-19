<?php
	$bookingId = $data['bookingId'];
	$cardId = $data['cardId'];
	require_once('vendor/autoload.php');
	if(!empty($bookingId) && !empty($cardId)){
		$booikingFee = get_post_meta($bookingId, "_proposal_price", true);
		$stripeToken = get_post_meta($cardId, "token_id", true);
		$card_id = get_post_meta($cardId, "card_id", true);
		$customer_id = get_post_meta($cardId, "customer_id", true);
		$customerName = get_post_meta($cardId, "accountholdername", true);
		
		/////paymet methord id======================================>
		\Stripe\Stripe::setApiKey("sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0");
			$charge = \Stripe\PaymentIntent::create([
			'amount' => $booikingFee*100,
			'currency' => 'gbp',
			//"payment_method"=>$_stripe_pmethod_id,
			"capture_method"=>'manual',
			"customer"=>$customer_id,
			"confirm"=>true,
			"transfer_group"=>'ORDER'.$bookingId,
			//'transfer_group' => '{ORDER'.$Packagename.'}',
			]);
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents/'.$charge->id.'/capture');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "amount_to_capture=".$booikingFee*100);
			curl_setopt($ch, CURLOPT_USERPWD, 'sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0' . ':' . '');
			$headers = array();
			$headers[] = 'Content-Type: application/x-www-form-urlencoded';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);

			curl_close($ch);

			$charge = json_decode($result,true);

	/*\Stripe\Stripe::setApiKey("sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0"); //secret key
    $charge = \Stripe\Charge::create([
      "amount"      => $booikingFee*100,
      "currency"    => "gbp",
      "customer"      => $customer_id, // obtained with Stripe.js
      "description" => $customerName,
    ]);*/
	
	/* $payment_intent = \Stripe\PaymentIntent::create([
  'payment_method_types' => ['card'],
  'amount' => 100,
  'currency' => 'gbp',
  'application_fee_amount' => 10,
  'transfer_data' => [
    'destination' => "ca_IuSwX8YLtjqYtZSKGtyRXXNU5SAbv3Ch",
  ],
]);
print_r($payment_intent);
				die;*/

     $paymentStatus    =   $charge['status'];
     $tansactionId     =   $charge['id'];
	 $chargeId         =   $charge['charges']['data']['0']['id'];
	 if($paymentStatus == "succeeded"){
	 update_post_meta($bookingId, "_status", "confirmed");
	  update_post_meta($bookingId, "created", $charge['created']);
	 update_post_meta($bookingId, "card", $cardId);
	 update_post_meta($bookingId, "transfer_group", $charge['transfer_group']);
	 update_post_meta($bookingId, "tansactionid", $tansactionId);
	 update_post_meta($bookingId, "_charge_id", $chargeId);
	 
	 $jobId = get_post_meta($bookingId, "_invited_jobs", true);
	 update_post_meta($jobId, "_status", "confirmed");
	 update_post_meta($jobId, "created",  $charge['created']);
	 update_post_meta($jobId, "card", $cardId);
	 update_post_meta($jobId, "transfer_group", $charge['transfer_group']);
	 update_post_meta($jobId, "tansactionid", $tansactionId);
	 update_post_meta($jobId, "_charge_id", $chargeId);
	 $msg = 'payment is sucess.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $charge));
	exit();	
	 } else{
	$msg = 'payment is failed.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $charge));
	exit();	 
	 }
	}
	 else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();	
	}
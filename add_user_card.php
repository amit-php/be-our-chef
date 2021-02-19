<?php
	$cardDetails   = $data['cardDetails'];
	$accountInfo = [];
	require_once('vendor/autoload.php');
	if($cardDetails){
			$cardId = $cardDetails['id'];
		if(!empty($cardId)){
			$accountHolderName = $cardDetails['card']['name'];
			$my_post = array(
				'post_type' => 'user_bank',
				'post_title'    => $accountHolderName,
				'post_status'   => 'publish',
				'post_author'   => $user_id,
			);
		 // Insert the post into the database.
		  $postId = wp_insert_post( $my_post );
		  if(!is_wp_error($postId)){
			update_post_meta($postId, "accountholdername", $cardDetails['card']['name']);
			update_post_meta($postId, "card_id", $cardDetails['card']['id']);
			update_post_meta($postId, "brand", $cardDetails['card']['brand']);
			update_post_meta($postId, "country", $cardDetails['card']['country']);
			update_post_meta($postId, "exp_month",    $cardDetails['card']['exp_month']);
			update_post_meta($postId, "exp_year",  $cardDetails['card']['exp_year']);
			update_post_meta($postId, "last4", $cardDetails['card']['last4']);
			update_post_meta($postId, "funding", $cardDetails['card']['funding']);
			update_post_meta($postId, "token_id", $cardDetails['id']);
			update_post_meta($postId, "livemode", $cardDetails['livemode']);
			update_post_meta($postId, "created", $cardDetails['created']);
			update_post_meta($postId, "type", $cardDetails['type']);
			if($cardDetails['id']){
				//Create a Customer
			\Stripe\Stripe::setApiKey('sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0');
			 $customer = \Stripe\Customer::create(array(
				 "source" => $cardDetails['id'],
				 "description" => $cardDetails['card']['name'])
				 );
				$newCustomer = $customer->id;
				update_post_meta($postId, "customer_id", $newCustomer);
			}
			$get_post = get_posts(array(
						'author'=>$user_id,
						'posts_per_page'	=> -1,
						'post_type'		=> 'user_bank',
						'post_status'    => 'publish',
					  ));
			if($get_post){
				foreach($get_post as $get_data){
			$accountholdername = get_post_meta($get_data->ID, "accountholdername", true);
			$card_id           = get_post_meta($get_data->ID, "card_id", true);
			$brand             = get_post_meta($get_data->ID, "brand", true);
			$country           = get_post_meta($get_data->ID, "country", true);
			$exp_month         = get_post_meta($get_data->ID, "exp_month",    true);
			$exp_year          = get_post_meta($get_data->ID, "exp_year",  true);
			$last4             = get_post_meta($get_data->ID, "last4", true);
			$funding           = get_post_meta($get_data->ID, "funding", true);
			$token_id          = get_post_meta($get_data->ID, "token_id", true);
			$livemode          = get_post_meta($get_data->ID, "livemode", true);
			$created           = get_post_meta($get_data->ID, "created", true);
			$type              = get_post_meta($get_data->ID, "type", true);
			$customer_id       = get_post_meta($get_data->ID, "customer_id", true);
			$accountInfo[] = array("accountId"=>$get_data->ID, "accountholdername"=>$accountholdername,"card_id"=>$card_id,
			"brand"=>$brand,"country"=>$country,"exp_month"=>$exp_month,"exp_year"=>$exp_year,"last4"=>$last4,"funding"=>$funding,
			"tokenId"=>$token_id, "livemode"=>$livemode, "created"=>$created, "type"=>$type,"customerId"=>$customer_id);
			}
			$cardDetails = array("cardInfo"=>$accountInfo);
			$msg = 'card is added.';
			$success = true;
			$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $cardDetails));
			exit();
			} else {
				$msg = 'No card is found.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
				exit(); 
				 }
				
				 } else{
				$msg = 'card id is false.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
				exit(); 
				 }
			
		} else {
		$msg = 'card id is false.';
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
	
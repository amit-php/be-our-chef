<?php
	 $accountDetails       = $data['accountDetails'];
	require_once('vendor-new/stripe/init.php');
	require_once('vendor/autoload.php');
	$accountInfo = [];
	if($accountDetails){
	$tokenId = $accountDetails['tokenId'];
	
	if($tokenId){
		$accountHolderName = $accountDetails['bankAccount']['accountHolderName'];
		//============================>
		
		\Stripe\Stripe::setApiKey("sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0");
		$user_info = get_userdata($user_id);
		$email = $user_info->user_email;
		$firstName = $user_info->first_name;
		$lastName = $user_info->last_name;
		if(empty($lastName)){
			$lastName = "cena";
		}
		//$phone = get_user_meta()  ; 
		//print_r($dobArr);
		$createStripeConnectAccount = \Stripe\Account::create([
		'type' => 'custom',
		'country' => 'GB',
		'business_type' => 'individual',
		'email' => $email,
		'capabilities' => [
		'card_payments' => ['requested' => true],
		'transfers' => ['requested' => true],
		],
		'business_profile' => [
			'mcc' => '5734',
			'url' => 'https://be-our-chief.weavers-web.com/',
		  ],
		  'individual'=>[
			//'ssn_last_4'=>'0000',
			'address'=>[
			'line1'=>"Kent, Canterbury, England, UK",
			'line2'=>" England, UK",
			'city'=>"London",
			'state'=>"England",
			'postal_code'=>"CT1 2EE",
			'country'=>'GB',
			],
			'first_name'=>$firstName,
			'last_name'=>$lastName,
			'dob'=>['day'=>05,'month'=>04,'year'=>1993],
			'phone'=>9748619128,
			'email'=> $email
			]
		

		]);

		$accountID = $createStripeConnectAccount->id;

		if(!empty($accountID)){

		$agreementApprove = \Stripe\Account::update(
		$accountID,
		[
		'tos_acceptance' => [
		'date' => time(),
		'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
		],
		]
		);

		//update_user_meta($userID,'_stripe_connected_account_id',$accountID);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/accounts/'.$accountID.'/external_accounts');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "external_account=".$tokenId);
		curl_setopt($ch, CURLOPT_USERPWD, 'sk_test_51IFxeJGPxVWU5CKrc0ATBbWAal5iw3o9l2LBq3z6ilrSGWwyQcEXZ6x7s2byfALYWbehv8UeaPbIZCFT895KXiJD00eVH4spF0' . ':' . '');

		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		curl_close($ch);

		$bankAccountInfo = json_decode($result, true);
		} else{
			$msg = 'no account id is found.';
			$success = true;
			$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
			exit();
		}

		
		//=================================>
		$my_post = array(
			'post_type' => 'bank',
			'post_title'    => $accountHolderName,
			'post_status'   => 'publish',
			'post_author'   => $user_id,
		);
		// Insert the post into the database.
		$postId = wp_insert_post( $my_post );
	    if(!is_wp_error($postId)){
			update_post_meta($postId, "accountholdername", $accountDetails['bankAccount']['accountHolderName']);
			update_post_meta($postId, "accountholdertype", $accountDetails['bankAccount']['accountHolderType']);
			update_post_meta($postId, "bankname", $accountDetails['bankAccount']['bankName']);
			update_post_meta($postId, "currency", $accountDetails['bankAccount']['currency']);
			update_post_meta($postId, "last4",    $accountDetails['bankAccount']['last4']);
			update_post_meta($postId, "created",  $accountDetails['created']);
			update_post_meta($postId, "_tokenid", $accountDetails['tokenId']);
			update_post_meta($postId, "livemode", $accountDetails['livemode']);
			update_post_meta($postId, "_customer_id", $accountID);
			$get_post = get_posts(array(
						'author'=>$user_id,
						'posts_per_page'	=> -1,
						'post_type'		=> 'bank',
						'post_status'    => 'publish',
					  ));
			if($get_post){
			foreach($get_post as $get_data){
			$accountholdername = get_post_meta($get_data->ID, "accountholdername", true);
			$accountholdertype = get_post_meta($get_data->ID, "accountholdertype", true);
			$bankname          = get_post_meta($get_data->ID, "bankname", true);
			$currency          = get_post_meta($get_data->ID, "currency", true);
			$last4             = get_post_meta($get_data->ID, "last4",    true);
			$created           = get_post_meta($get_data->ID, "created",  true);
			$tokenid           = get_post_meta($get_data->ID, "_tokenid", true);
			$livemode          = get_post_meta($get_data->ID, "livemode", true);
			$customer_id       = get_post_meta($get_data->ID, "_customer_id", true);
			$accountInfo[] = array("accountId"=>$get_data->ID, "accountholdername"=>$accountholdername,"accountholdertype"=>$accountholdertype,
			"bankname"=>$bankname,"currency"=>$currency,"last4"=>$last4,"created"=>$created,"tokenid"=>$tokenid,"livemode"=>$livemode,"customer_id"=>$customer_id);
				
			}
			$result = array("accountInfo"=>$accountInfo,"bankInfo"=>$bankAccountInfo);
			$msg = 'sucess.';
			$success = true;
			$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $result));
			exit();	
				
			} else {
			$msg = 'no account details is found.';
			$success = true;
			$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
			exit();		
			}
		
		} else {
			$msg = 'mismatch information.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
			exit();
		}	
	   
	} else {
		$msg = 'Tokenid is false.';
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
	
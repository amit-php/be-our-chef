	<?php
	$accountInfo =[];
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
			$accountInfo[] = array("accountId"=>$get_data->ID, "accountholdername"=>$accountholdername,"card_id"=>$card_id,
			"brand"=>$brand,"country"=>$country,"exp_month"=>$exp_month,"exp_year"=>$exp_year,"last4"=>$last4,"funding"=>$funding,
			"tokenId"=>$token_id, "livemode"=>$livemode, "created"=>$created, "type"=>$type);
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
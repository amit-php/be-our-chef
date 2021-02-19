<?php
$accountInfo = [];
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
			$accountInfo[] = array("accountId"=>$get_data->ID, "accountholdername"=>$accountholdername,"accountholdertype"=>$accountholdertype,
			"bankname"=>$bankname,"currency"=>$currency,"last4"=>$last4,"created"=>$created,"tokenid"=>$tokenid,"livemode"=>$livemode);
				
			}
			$result = array("accountInfo"=>$accountInfo);
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
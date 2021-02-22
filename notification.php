<?php
function sendPushNotification($pushCode, $chefId, $pushTitle, $pushBody){
    $chef = get_user_by( 'id', $chefId );
	$chefemail = $chef->user_email;
		$to = $chefemail;
		$subject = $pushTitle;
		$txt = $pushBody;
		$headers = "From: webmaster@yopmail.com";

		mail($to,$subject,$txt,$headers);
		die;
	$_device_token_id = get_user_meta($userId,'_device_token_id',true);
	$_device_os_name  = get_user_meta($userId,'_device_os_name',true);

	if($pushCode == 1 || $pushCode == 2 || $pushCode == 3 || $pushCode == 4){
		$msgTitle = $pushTitle;
		$msgBody = $pushBody;
		$flag = 1;
	}else{
		$flag = 2;
	}


	if($flag != 2){
		
		$to = $chefemail;
		$subject = $pushTitle;
		$txt = $pushBody;
		$headers = "From: webmaster@yopmail.com";

		mail($to,$subject,$txt,$headers);

		/*if($_device_os_name == 'iOS'){

			if(!empty($_device_token_id)){
				$url = "https://fcm.googleapis.com/fcm/send";
				$token = $_device_token_id;
				
				$serverKey = 'AAAA0g5yJSE:APA91bF-5BSDPREpW1aVBdnpsZmYg8HGt7Rn9npWcSLfK8HUOMUJkcLhDmckqg28wihUjaQOt0mqNc0npZldKzKOzOHIgQx2lOm54RcSLATFYhVTZXoJ1z36_mxv0dNtHZxejNjqSE9t'; // new 1
			
				
				$notification = array(
					'title' => $msgTitle, 
					'text' => $msgBody, 
					'sound' => 'default', 
					'badge' => 0, 
					'notification_type' => $msgTitle,
					'push_code' => $pushCode,
					);

				$arrayToSend = array('to' => $token, 'notification' => $notification, 'content_available'=> true, 'mutable_content'=> true, 'priority'=>'high');
				$json = json_encode($arrayToSend);
				$headers = array();
				$headers[] = 'Content-Type: application/json';
				$headers[] = 'Authorization: key='. $serverKey;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				
				$response = curl_exec($ch);
				
				if($response === FALSE){
					die('Notification Send Error: ' . curl_error($ch));
				}
				curl_close($ch);
			}
		}

		if($_device_os_name == 'Android'){

			if(!empty($_device_token_id)){

				define( 'API_ACCESS_KEY', 'AAAA0g5yJSE:APA91bF-5BSDPREpW1aVBdnpsZmYg8HGt7Rn9npWcSLfK8HUOMUJkcLhDmckqg28wihUjaQOt0mqNc0npZldKzKOzOHIgQx2lOm54RcSLATFYhVTZXoJ1z36_mxv0dNtHZxejNjqSE9t' );

				$singleID = $_device_token_id ; 
				
				$fcmMsg = array(
				'title' => $msgTitle,	
				'body' => $msgBody,
				'sound' => "default",
				"icon" => "appicon"
				);

				$fcmData = array(
				'title' => $msgTitle,
				'message' => $msgBody,	
				'push_code' => $pushCode, 
				);

				$fcmFields = array(
				//'title' => $msgTitle,	
				'to' => $singleID,
				'priority' => 'high',
				'notification' => $fcmMsg,
				'data' => $fcmData
				);

				$headers = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
				);

				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
				$result = curl_exec($ch );
				curl_close( $ch );
			}
		}*/
				
	}
}

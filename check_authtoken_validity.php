<?php
include_once('generate_access_token.php');

if(!empty($data['FCMToken'])){
	$device_type = $data['OSType'];
	$device_token = $data['FCMToken'];
}

if(empty($device_type) && empty($device_token)){
	$device_token = $_POST['FCMToken'];
	$device_type = $_POST['OSType'];	
}

$get_access_token = get_user_meta($user_id,'_access_token',true);
$_is_first_time = get_user_meta($user_id,'_is_first_time',true);
$_email_verified_status = get_user_meta($user_id, '_email_verified_status' , true);


/****************************************/

if($_email_verified_status == 'yes'){

	$user_info = get_userdata($user_id);

	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$email_id = $user_info->user_email; 

	if(!empty($device_type) && !empty($device_token)){			
		update_user_meta( $user_id, '_device_token_id', $device_token);
		update_user_meta( $user_id, '_device_os_name', $device_type);
		$device_token = get_user_meta($user_id,'_device_token_id',true);
		$device_type = get_user_meta($user_id,'_device_os_name',true);
	}

	$random_otp = get_user_meta($user_id, '_email_verified_otp' ,true);
	$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);

	if(empty($encryptedUserId)){
		$encryptedUserId = base64_encode($random_otp.$user_id);
		update_user_meta($user_id, '_encrypted_user_id', $encryptedUserId);
	}

	
	$image_attributes = get_user_meta($user_id,'_profile_image_user',true);
	if(!empty($image_attributes)){
		
		$image_attributes_new = wp_get_attachment_image_src($image_attributes, 'thumbnail');
		$imgUrl = $image_attributes_new[0];
	}else{
		$imgUrl = '';
	}

	if(empty($email_id)){
		$email_id = '';
	}

	$phone = get_user_meta($user_id,'_phone',true);
    $signType         = get_user_meta($user_id, '_signup_type', true);

    $stripeConnectID = get_user_meta($user_id, '_sp_stripe_account_no', true);

	if(empty($stripeConnectID)){
		$stripeConnectID = '';
	}

	$profileDetails = array(
		  "accessToken"=> $get_access_token,
		  "firstName"=> $user_first_name,
		  "lastName"=> $user_last_name,
		  "email"=> $email_id,
		  "profileImageURL"=> $imgUrl,
		  'encryptedUserId'=>$encryptedUserId,
		  'otp'=> (int)$random_otp,
		  'phone'=> (int)$phone,
		  'signType'=>$signType,
		  
	);

	$resultRes = array('profileDetails'=>$profileDetails);


	$msg = 'Login Successfully.';
	$success = true;
	
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

}else{

	$user_info = get_userdata($user_id);

	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	//$email_id = $user_info->user_login; 
	$email_id = $user_info->user_email;
	$phone = get_user_meta($user_id,'_phone',true);


	$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);
	$random_otp = get_user_meta($user_id, '_email_verified_otp' ,true);


	// Registration confirmation Mail Starts	
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	$from = 'admin@'.$sitename; 
	$to = $email_id;
	$subject = 'OTP For Music App';
	$sender = 'From: '.'Music App'.' <'.$from.'>' . "\r\n";

	$message =
	'<html><head></head><body>
	<table width="100%" border="0" cellspacing="3" cellpadding="3">
	<tr>
	<td style="text-align: left;padding:10px 5px;color:#212121;font-size:22px;font-family:Verdana;text-transform:uppercase;"><strong>Registration is Successful</strong></td>
	</tr>
	<tr>
	<td style="padding:5px 5px;font-size:16px;text-align: left;color:#212121;font-family:Verdana;">
	Dear '.$user_first_name.' '.$user_last_name.',<br /><br />
	Thank you for Registration on <b>'.'Music'.'</b> APP .This is a Confirmation Message. <br /><br />
	<b>EMAIL</b> : <b>'.$email_id.'</b><br />
	<b>OTP</b> : <b>'.$random_otp.'</b> <br />
	<b>Thank you.</b>
	</td>
	</tr>
	</table></html>';

	$headers[] = 'MIME-Version: 1.0' . "\r\n";
	$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers[] = "X-Mailer: PHP \r\n";
	$headers[] = $sender;
	$mail = wp_mail( $to, $subject, $message, $headers );

	$unverifiedProfileDetails = array('email'=> $email_id, 'firstName'=> $user_first_name, 'lastName'=> $user_last_name,'encryptedUserId'=>$encryptedUserId,'otp'=> (int)$random_otp,'phone'=> (int)$phone);
	$resultRes = array('unverifiedProfileDetails' => $unverifiedProfileDetails);

	$msg = 'Sorry,Your email is not verified.Please verify your Email.';
	$success = false;
	$serverResponse = array("code" => 602, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse, 'result'=> $resultRes));
}


/*********************************/
			


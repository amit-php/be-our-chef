<?php
include_once('generate_access_token.php');

/*************** Customer Signup Functionality Starts ******************/

$fullName  = $data['fullName'];
$email_id   = $data['email'];
$id   = $data['id'];
$usertype = $data['usertype'];


if(empty($fullName) && empty($email_id)){
	$fullName = $_POST['fullName'];
	$email_id = $_POST['email'];
	$id = $_POST['id'];
}

$password = base64_encode($email_id);
if(!filter_var($email_id, FILTER_VALIDATE_EMAIL)){
	$msg = 'Invalid email format';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));
	exit();
}


if(!empty($fullName) && !empty($email_id) && !empty($password)){

if(!email_exists($email_id)){ 

	$fullNameArr = explode(" ",$fullName);
	$firstName = $fullNameArr[0];
	$lastName = $fullNameArr[1];

	
	$signup_data = array(
		'user_login'  => $email_id,
		'first_name'  => $firstName,
		'last_name'  => $lastName,
		'display_name'  => $email_id,
		'nickname'  => $firstName,
		'user_email'  => $email_id,
		'user_pass'  => $password,
		'role'  => $usertype
	);

	$user_signup = wp_insert_user($signup_data);

	if($user_signup && !is_wp_error($user_signup)){

		$random_otp = rand(100000,999999);
		$encryptedUserId = base64_encode($random_otp.$user_signup);

		update_user_meta($user_signup, '_email_verified_otp' ,$random_otp);
		update_user_meta($user_signup, '_email_verified_status' ,'yes'); // no for false and yes for true
		update_user_meta($user_signup, '_change_random_otp_status', 'false');
		update_user_meta($user_signup, '_encrypted_user_signup', $encryptedUserId);

		update_user_meta($user_signup, '_signup_type', 'apple');
		update_user_meta($user_signup, '_email', $email_id);

		$curSignUpDate = date("d-m-Y");
		update_user_meta($user_signup, '_cursignup_date', $curSignUpDate);

		

		$image_attributes = get_user_meta($user_signup,'_profile_image_user',true);
		if(!empty($image_attributes)){
			$image_attributes_new = wp_get_attachment_image_src($image_attributes, 'thumbnail');
			$imgUrl = $image_attributes_new[0];
		}else{
			$imgUrl = '';
		}

		$access_token_val = get_rand_alphanumeric(8);
		$access_token = $access_token_val.'_'.$user_signup;
		update_user_meta( $user_signup, '_access_token', $access_token);
		$get_access_token = get_user_meta($user_signup,'_access_token',true);
		 $signType         = get_user_meta($user_signup, '_signup_type', true);
		$random_otp 	  = get_user_meta($user_signup, '_email_verified_otp' ,true);
		$address          = get_user_meta($user_signup, 'address', true);

		
		update_user_meta($user_signup, '_unique_id', $id);


		$profileDetails = array(
				"accessToken"=> $get_access_token,
				"fullName"=> $fullName,
				"email"=> $email_id,
			    "phone"=> (int)$phone,
				"profileImageURL"=> $imgUrl,
				'encryptedUserId'=>$encryptedUserId,
				'otp'=> (int)$random_otp,
				//"signType"=> (string)$signType,
			);
	
		
		$msg = 'Login Successfull.';
		$success = true;

		
		$serverResponse = array("code" => 200, "message"=> $msg, "isSuccess"=> $success);
		
		$resultRes = array('profileDetails' => $profileDetails);
		echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

		exit();

	}else{
		$msg = 'Sorry, your signup process is failed. Please, try again.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));

		exit();
	}
	

   } else {

		$login_info = array();
		$login_info['user_login'] = $email_id;
		$login_info['user_password'] = $password;
		$user_signon = wp_signon( $login_info, false );

		if(is_wp_error($user_signon)){

			$msg = 'Incorrect email or password.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));

		} else {

			$user_signup = $user_signon->ID;
			$user_info = get_userdata($user_signup);
			$user_role = $user_info->roles;
			
			$user_info = get_userdata($user_signup);

			$user_first_name = $user_info->first_name;
			$user_last_name = $user_info->last_name;
			$fullName = $user_first_name.' '.$user_last_name;
			$email_new = $user_info->user_login;		
			//$get_access_token = get_user_meta($user_signup,'_access_token',true);
			$random_otp  = get_user_meta($user_signup, '_email_verified_otp' ,true);
			$phone       = get_user_meta($user_signup, '_phone', true);
		   


			
			$encryptedUserId = get_user_meta($user_signup, '_encrypted_user_signup' ,true);

			$image_attributes = get_user_meta($user_signup,'_profile_image_user',true);
			if(!empty($image_attributes)){
				$image_attributes_new = wp_get_attachment_image_src($image_attributes, 'full');
				$imgUrl = $image_attributes_new[0];
			}else{
				$imgUrl = '';
			}

			$access_token_val = get_rand_alphanumeric(8);
			$access_token = $access_token_val.'_'.$user_signup;
			update_user_meta( $user_signup, '_access_token', $access_token);
			$get_access_token = get_user_meta($user_signup,'_access_token',true);


			if(!empty($device_type) && !empty($device_token)){			
			update_user_meta( $user_signup, '_device_token_id', $device_token);
			update_user_meta( $user_signup, '_device_os_name', $device_type);
			$device_token = get_user_meta($user_signup,'_device_token_id',true);
			$device_type = get_user_meta($user_signup,'_device_os_name',true);
			}

			update_user_meta($user_signup, '_unique_id', $id);
			$address             = get_user_meta($user_signup, '_location', true);
		    $postalCode          = get_user_meta($user_signup, '_postal_code', true);
			$profileDetails = array(
									    "accessToken"=> $get_access_token,
										"fullName"=> $fullName,
										"email"=> $email_new,
									    "phone"=> (int)$phone,
										"profileImageURL"=> $imgUrl,
										'encryptedUserId'=>$encryptedUserId,
										'otp'=> (int)$random_otp,
										"location"=> (string)$address,
									    "postalCode"=>$postalCode
										//"signType"=> (string)$signType,
								);

			$resultRes = array('profileDetails'=>$profileDetails);
			$msg = 'Account Information.';
			$success = true;

			$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
		}

}

}else{
	$msg = 'All Fields are Required.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));

	exit();
}	
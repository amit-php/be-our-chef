<?php
include_once('generate_access_token.php');

$email_id = $data['email'];
$otp = $data['otp'];

if(empty($email_id) && empty($otp)){
$email_id = $_POST['email'];
$otp = $_POST['otp'];
}

if(!empty($email_id) && !empty($otp)){

	if(!filter_var($email_id, FILTER_VALIDATE_EMAIL)){
			
			$flag = 1;

			if(!username_exists($email_id)){
				$msg = 'Incorrect username.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse));
				exit();
			}else{
				$uName = $email_id;
				$$email_newggg = $email_id;
			}
		}else{

			$flag = 2;

			if(!email_exists($email_id)){
				$msg = 'Incorrect email.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse));
				exit();
			}else{

				$userP = get_user_by('email', $email_id);
				$uName = $userP->user_login;
				$email_newggg = $userP->user_email;

			}
		}

	$email_id = $email_newggg;

	if(email_exists($email_id)){

		//$user = get_userdatabylogin($email_id);
		$user = get_user_by( 'email', $email_id );
		$user_id = $user->ID;


		$_phone_verified_otp = get_user_meta($user_id, '_email_verified_otp' ,true); //_phone_verified_status
		$_phone_verified_status = get_user_meta($user_id, '_email_verified_status' ,true); //_phone_verified_status

		if($_phone_verified_otp == $otp){

			$user_info = get_userdata($user_id);
			$firstName = $user_info->first_name;
			$lastName = $user_info->last_name;

			update_user_meta($user_id, '_email_verified_status' ,'yes'); 
			update_user_meta($user_id, '_change_random_otp_status', 'true');


			$cur_time = date('d-m-Y h:i:s a');
			update_user_meta($user_id, '_last_change_otp_status_time', $cur_time);

			$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);
			$phone = get_user_meta($user_id,'_phone',true);


			/************************Extra Func Starts ******************/

			$access_token_val = get_rand_alphanumeric(8);
			$access_token = $access_token_val.'_'.$user_id;
			update_user_meta( $user_id, '_access_token', $access_token);
			$get_access_token = get_user_meta($user_id,'_access_token',true);
			
			$_is_first_time = get_user_meta($user_id,'_is_first_time',true);

			if(empty($_is_first_time)){
				$_is_first_time = 0;
			}

			$_is_first_time = $_is_first_time + 1;

			update_user_meta($user_id, '_is_first_time', $_is_first_time);


			if(!empty($device_type) && !empty($device_token)){			
				update_user_meta( $user_id, '_device_token_id', $device_token);
				update_user_meta( $user_id, '_device_os_name', $device_type);
				$device_token = get_user_meta($user_id,'_device_token_id',true);
				$device_type  = get_user_meta($user_id,'_device_os_name',true);
			}

			
			
			$user_info        = get_userdata($user_id);
			$user_first_name  = $user_info->first_name;
			$user_last_name   = $user_info->last_name;
			$fullName		  = $user_first_name.' '.$user_last_name;
			$email_new        = $user_info->user_email;
	        $signType         = get_user_meta($user_id, '_signup_type', true);
			$get_access_token = get_user_meta($user_id,'_access_token',true);
			$random_otp 	  = get_user_meta($user_id, '_email_verified_otp' ,true);
			$phone            = get_user_meta($user_id, '_phone', true);
			$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);
			$image_attributes = get_user_meta($user_id,'_profile_image_user',true);
			if(!empty($image_attributes)){
				$image_attributes_new = wp_get_attachment_image_src($image_attributes, 'full');
				$imgUrl = $image_attributes_new[0];
			}else{
				$imgUrl = '';
			}

			$profileDetails = array(
									  "accessToken"=> $get_access_token,
									  "fullName"=> $fullName,
									  "email"=> $email_new,
									  "phone"=> (int)$phone,
									  "profileImageURL"=> $imgUrl,
									  'encryptedUserId'=>$encryptedUserId,
									  'otp'=> (int)$random_otp,
									  "signType"=> (string)$signType,
								);

			$resultRes = array('profileDetails'=>$profileDetails);

			/***********************Extra Func Ends ***********************/
			 
            


			//$profileDetails = array('email'=> $email_id, 'firstName'=> $firstName, 'lastName'=> $lastName,'encryptedUserId'=>$encryptedUserId,'otp'=> (int)$otp,'phone'=> (int)$phone);

			$msg = 'Success! You have been verified.';
			$success = true;
			$serverResponse = array("code" => 200, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse, 'result'=> $resultRes));

			exit();

		} else {

			$msg = 'Error! Sorry, OTP is invalid. Please try again.';
			$success = false;
			$serverResponse = array("code" => $user_id, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));

			exit();

		}
	} else {

		$msg = 'User does not exists.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();

	}
} else {
	
		$msg = 'All Fields are Required.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();

}	
	
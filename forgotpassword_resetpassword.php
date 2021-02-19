<?php
$email = $data['email'];
$otp = $data['otp'];
$newPassword = $data['newPassword'];


if(empty($email) && empty($otp) && empty($newPassword)){
$email = $_POST['email'];
$otp = $_POST['otp'];
$newPassword = $_POST['newPassword'];
}

$user_info = get_user_by('email',$email);
$user_id = $user_info->ID;

if(!empty($email) && !empty($otp) && !empty($newPassword)){

	if(email_exists($email)){

		$_email_verified_otp = get_user_meta($user_id, '_email_verified_otp' ,true); 
		$_email_verified_status = get_user_meta($user_id, '_email_verified_status' ,true); 

		if($_email_verified_otp == $otp){

			update_user_meta($user_id, '_email_verified_status' ,'yes'); 
			update_user_meta($user_id, '_change_random_otp_status', 'true');

			$cur_time = date('d-m-Y h:i:s a');
			update_user_meta($user_id, '_last_change_otp_status_time', $cur_time);

			wp_set_password($newPassword, $user_id);
			

		    $msg = 'Your password has been reset successfully.';
		    $success = true;
		
		    $serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
		    echo json_encode(array("serverResponse" => $serverResponse));

		}else{
			$msg = 'Sorry, OTP mismatch.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
		}
	}else{
		$msg = 'Email does not exist.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
	}
}else{
		$msg = 'All Fields are Required.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
}	

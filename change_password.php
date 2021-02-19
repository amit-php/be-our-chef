<?php 
/*
	$oldPassword = $data['oldPass'];
	$newPassword = $data['newPass'];

	if(empty($oldPassword) && empty($newPassword)) {
		$oldPassword = $_POST['oldPass'];
	    $newPassword = $_POST['newPass'];
    }
    if(!empty($oldPassword) && !empty($newPassword)) {

    	if($oldPassword == $newPassword) {
	        $msg = 'the New password is same as old password.';
	        $success = false;
	        $serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	        echo json_encode(array("serverResponse" => $serverResponse));
	        exit();
    	} else {
    		$user = get_user_by( 'id', $user_id );
			if ( $user && wp_check_password( $oldPassword, $user->data->user_pass, $user->ID ) ) {
			    update_user_meta($user_id, '_email_verified_status' ,'yes'); 
				update_user_meta($user_id, '_change_random_otp_status', 'true');
				$cur_time = date('d-m-Y h:i:s a');
				update_user_meta($user_id, '_last_change_otp_status_time', $cur_time);
				wp_set_password($newPassword,$user_id );
			    $msg = 'Your password has been change successfully.';
			    $success = true;
			    $serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
			    echo json_encode(array("serverResponse" => $serverResponse));
			} else {
			      $msg = 'the New password is same as old password.';
			      $success = false;
			      $serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			      echo json_encode(array("serverResponse" => $serverResponse));
			      exit();
			}
    	}

    }


*/

$oldPass = $data['oldPass'];
$newPass = $data['newPass'];

if(empty($oldPass) && empty($newPass)){
	$oldPass = $_POST['oldPass'];
	$newPass = $_POST['newPass'];
}

if(!empty($oldPass) && !empty($newPass)){

	$user_info = get_userdata($user_id);

	if(wp_check_password($oldPass, $user_info->user_pass, $user_id)){
		
		wp_set_password($newPass, $user_id);


		update_user_meta($user_id, '_email_verified_status' ,'yes'); 
		update_user_meta($user_id, '_change_random_otp_status', 'true');
		$cur_time = date('d-m-Y h:i:s a');
		update_user_meta($user_id, '_last_change_otp_status_time', $cur_time);

		$msg = 'Congratulations! New Password Save Successfully.';
		$success = true;
		$serverResponse = array("code" => 200, "message"=> $msg, "isSuccess"=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));

	}else{
		$msg = 'Sorry, old password is mismatched. Please, try again.';
		$success = false;
		$serverResponse = array("code" => 600, "message"=> $msg, "isSuccess"=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
	}	

}else{
		$msg = 'Some field is missing';
		$success = false;
		$serverResponse = array("code" => 600, "message"=> $msg, "isSuccess"=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
}
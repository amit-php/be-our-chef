<?php
$username = $data['email'];

if(empty($username)){
$username = $_POST['email'];
}
if(!empty($username)){
	
	if(email_exists($username)){
			
			$user_info = get_user_by('email',$username);
			$user_id = $user_info->ID;
			$random_otp = rand(100000,999999);
			$user_first_name = $user_info->first_name;
			$user_last_name = $user_info->last_name;

			update_user_meta($user_id, '_email_verified_otp', $random_otp);
			update_user_meta($user_id, '_change_random_otp_status', 'false');

			// Resend OTP confirmation Mail Starts	
			$sitename = strtolower( $_SERVER['SERVER_NAME'] );
			$from = 'admin@'.$sitename; 
			$to = $username;
			$subject = 'OTP for SERVICE 2 OFFER App';
			$sender = 'From: '.'SERVICE 2 OFFER App'.' <'.$from.'>' . "\r\n";

			$message =
			'<html>
			<head></head><body>
			<table width="100%" border="0" cellspacing="3" cellpadding="3">
			<tr>
			<td style="text-align: left;padding:10px 5px;color:#212121;font-size:22px;font-family:Verdana;text-transform:uppercase;"><strong>Your OTP is '.$random_otp.'</strong></td>
			</tr>
			</table></body>
			</html>';

			$headers[] = 'MIME-Version: 1.0' . "\r\n";
			$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers[] = "X-Mailer: PHP \r\n";
			$headers[] = $sender;
			$mail = wp_mail( $to, $subject, $message, $headers );
			//$mail1 = mail( $to, $subject, $message, $headers );

			$msg = 'An OTP has been sent to your Email.';
			$success = true;

			$_email_verified_status = get_user_meta($user_id, '_email_verified_status' , true);

			$serverResponse = array("code" => 200, "message"=> $msg, "isSuccess"=> $success);
			$resultRes = array(
							   "firstName"=> $user_first_name,
							   "lastName"=> $user_last_name,
							   "email"=> $username, 
							   "otp"=> (int)$random_otp
							  );
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

		} else{
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
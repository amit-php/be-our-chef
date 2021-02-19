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
			$subject = 'OTP for Gigue App';
			$sender = 'From: '.'Gigue App'.' <'.$from.'>' . "\r\n";

			$message =
			'<html>
			<head></head><body>
			<table width="100%" border="0" cellspacing="3" cellpadding="3">
			<tr>
			<td style="text-align: left;padding:10px 5px;color:#212121;font-size:22px;font-family:Verdana;">
			<p>You have requested to reset your password. Open the app and input the OTP 6-digit password to reset the forgotten password and create a new one. For security purposes, this link will only remain active for the next 20 Mins.</p>
			<p>A strong password includes 12 or more characters and a combination of uppercase and lowercase letters, numbers and symbols.</p>
			<strong style="text-transform:uppercase;">Your one-time password is '.$random_otp.'</strong>
			<p style="color:blue;">If you did not request that we send this Forgotten Password email to you, please report this email to us at support@Giguelabs.com.</p>
			<p>Thank you for using Gigue!</p>
			<p>Regards,<br/>
			Gigue Labs Customer Service Team*<br/>
			support@Giguelabs.com</p>
			</td>
			</tr>
			</table>
			</body>
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

			if($_email_verified_status == 'yes'){
				$code = 200;
			}else{
				$code = 200;
			}

			$serverResponse = array("code" => $code, "message"=> $msg, "isSuccess"=> $success);
			$resultRes = array(
							  "firstName"=> $user_first_name,
							  "lastName"=> $user_last_name,
							  "email"=> $username, 
							  "otp"=> (int)$random_otp
							  );

			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
			exit();

		}else{
			$msg = 'Email does not exist.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
	 	} 
}else{
			$msg = 'All Fields are Required.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
}	

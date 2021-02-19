<?php
    $fullName = $data['fullName'];
    $fullNameArr = explode(" ",$fullName);
	$firstName =  $fullNameArr[0];
	$lastName  =  $fullNameArr[1];
	$email_id  =  $data['email'];
	$phone     =  $data['phone'];
	$password  =  $data['password'];
	$usertype  =  "user";


	if(!filter_var($email_id, FILTER_VALIDATE_EMAIL)){

		$msg = 'Invalid email format';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();

	}

	if(!empty($fullName) && !empty($email_id) && !empty($password)){

			if(!email_exists($email_id)){

                    $signup_data = array(
						'user_login'    => $email_id,
						'first_name'    => $firstName,
						'last_name'     => $lastName,
						'display_name'  => $fullName,
						'nickname'      => $firstName,
						'user_email'    => $email_id,
						'user_pass'     => $password,
						'role'          => $usertype
					);

				$user_signup = wp_insert_user($signup_data);

				if($user_signup && !is_wp_error($user_signup)){

						$random_otp = rand(100000,999999);
						$encryptedUserId = base64_encode($random_otp.$user_signup);

						update_user_meta($user_signup, '_email_verified_otp' ,$random_otp);
						update_user_meta($user_signup, '_email_verified_status' ,'no'); // no for false and yes for true
						update_user_meta($user_signup, '_change_random_otp_status', 'false');
						update_user_meta($user_signup, '_encrypted_user_id', $encryptedUserId);

						update_user_meta($user_signup, '_phone', $phone);

						update_user_meta($user_signup, '_signup_type', 'manual');
						update_user_meta($user_signup, '_email', $email_id);

						$curSignUpDate = date("d-m-Y");
						update_user_meta($user_signup, '_cursignup_date', $curSignUpDate);

						// Registration confirmation Mail Starts	
						$sitename = strtolower( $_SERVER['SERVER_NAME'] );
						$from = 'admin@'.$sitename; 
						$to = $email_id;
						$subject = 'OTP For Signup on Be our chef App';
						$sender = 'From: '.'Be our chef App'.' <'.$from.'>' . "\r\n";

						$message =
						'<html><head></head><body>
						<table width="100%" border="0" cellspacing="3" cellpadding="3">
						<tr>
						<td style="text-align: left;padding:10px 5px;color:#212121;font-size:22px;font-family:Verdana;text-transform:uppercase;"><strong>Registration is Successful</strong></td>
						</tr>
						<tr>
						<td style="padding:5px 5px;font-size:16px;text-align: left;color:#212121;font-family:Verdana;">
						Dear '.$firstName.' '.$lastName.',<br /><br />
						Thank you for Registration on <b>'.'Be our chef'.'</b> APP .This is a Confirmation Message. <br /><br />
						<b>User Name</b> : <b>'.$email_id.'</b><br />
						<b>OTP</b> : <b>'.$random_otp.'</b> <br />
						<b>Thank you.</b>
						</td>
						</tr>
						</table></body></html>';

						$headers[] = 'MIME-Version: 1.0' . "\r\n";
						$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers[] = "X-Mailer: PHP \r\n";
						$headers[] = $sender;
						$mail = wp_mail( $to, $subject, $message, $headers );
						//$mail1 = mail( $to, $subject, $message, $headers );

						$msg = 'You have registered successfully. Please check your inbox, a code has been send on your email.';
						$success = true;

						$serverResponse = array("code" => 602, "message"=> $msg, "isSuccess"=> $success);
						$unverifiedProfileDetails = array(
							                            'userName'=>$email_id,
							                            'email'=> $email_id,
							                            'firstName'=> $firstName,
							                            'lastName'=> $lastName,
							                            'encryptedUserId'=>$encryptedUserId,
							                            'otp'=> (int)$random_otp,
							                            'phone'=> (int)$phone,
							                            'usertype'=> (string)$usertype,
							                        );
						$resultRes = array('unverifiedProfileDetails' => $unverifiedProfileDetails);
						echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
				
					}else{
						$msg = 'Sorry, your signup process is failed. Please, try again.';
						$success = false;
						$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
						echo json_encode(array("serverResponse" => $serverResponse));
					}

					
					} else {
						$msg = 'Sorry, Email ID is already registered.';
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
			

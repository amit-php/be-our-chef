<?php
	include_once('generate_access_token.php');

	$email_id = $data['userName'];
	$password = $data['password'];
	$usertype = $data['usertype'];

	if(empty($email_id) && empty($password)){
		$email_id = $_POST['userName'];
		$password = $_POST['password'];	
		$usertype = $_POST['usertype'];
	}

	if(!empty($email_id) && !empty($password) && !empty($usertype)){

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
			}
		}
		$login_info = array();
		$login_info['user_login'] = $uName;
		$login_info['user_password'] = $password;
		$user_signon = wp_signon( $login_info, false );
		if(is_wp_error($user_signon)){
			$msg = 'Incorrect password.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
		} else {
			$user_id = $user_signon->ID;
			$user_info = get_userdata($user_id);
			$user_role = $user_info->roles;
			$user_first_name = $user_info->first_name;
			$user_last_name = $user_info->last_name;
			$email_newggg = $user_info->user_email;
			if(in_array($usertype,$user_role)) {
				$_email_verified_status = get_user_meta($user_id, '_email_verified_status' , true);
				
				if($_email_verified_status == 'yes'){

						$access_token_val = get_rand_alphanumeric(8);
						$access_token = $access_token_val.'_'.$user_id;
						update_user_meta( $user_id, '_access_token', $access_token);
						$get_access_token = get_user_meta($user_id,'_access_token',true);
						
						$_is_first_time = get_user_meta($user_id,'_is_first_time',true);

						if(empty($_is_first_time)){
							$_is_first_time = 0;
						}
						$_is_first_time = $_is_first_time + 1;
						update_user_meta( $user_id, '_is_first_time', $_is_first_time);
						$user_info        = get_userdata($user_id);
						$user_first_name  = $user_info->first_name;
						$user_last_name   = $user_info->last_name;
						$fullName         = $user_first_name.' '.$user_last_name;
						$email_new        = $user_info->user_email;
	                    $signType         = get_user_meta($user_id, '_signup_type', true);
						$get_access_token = get_user_meta($user_id,'_access_token',true);
						$random_otp 	  = get_user_meta($user_id, '_email_verified_otp' ,true);
						$phone            = get_user_meta($user_id, '_phone', true);
						$address          = get_user_meta($user_id, '_location', true);
						$postalCode          = get_user_meta($user_id, '_postal_code', true);
						//$lat              = get_user_meta($user_id, '_lat', true);
						//$lang              = get_user_meta($user_id, '_lang', true);
						//$radius             = get_user_meta($user_id, '_radius', true);
						//$specializations     = get_user_meta($user_id, '_specializations', true);

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
									  "location"=> (string)$address,
									  "postalCode"=>$postalCode,
									  //"radius"=> (string)$radius,
									 // "specializations"=> (string)$specializations,
									  //"lat"=> (string)$lat,
									  //"lang"=>  (string)$lang,
									  "profileImageURL"=> $imgUrl,
									  'encryptedUserId'=>$encryptedUserId,
									  'otp'=> (int)$random_otp,
									  "signType"=> (string)$signType,
									);

					$resultRes = array('profileDetails'=>$profileDetails);
					$msg = 'Login Successfully.';
					$success = true;

					$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
					echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

					exit();


				} else {

					$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);
					$random_otp = get_user_meta($user_id, '_email_verified_otp' ,true);
					$phone = get_user_meta($user_id,'_phone',true);

					

					// Registration confirmation Mail Starts	
					$sitename = strtolower( $_SERVER['SERVER_NAME'] );
					$from = 'admin@'.$sitename; 
					$to = $email_newggg;
					$subject = 'OTP For SERVICE 2 OFFER App';
					$sender = 'From: '.'SERVICE 2 OFFER App'.' <'.$from.'>' . "\r\n";

					$message =
					'<html><head></head><body>
					<table width="100%" border="0" cellspacing="3" cellpadding="3">
					<tr>
					<td style="text-align: left;padding:10px 5px;color:#212121;font-size:22px;font-family:Verdana;text-transform:uppercase;"><strong>Registration is Successful</strong></td>
					</tr>
					<tr>
					<td style="padding:5px 5px;font-size:16px;text-align: left;color:#212121;font-family:Verdana;">
					Dear '.$user_first_name.' '.$user_last_name.',<br /><br />
					Thank you for Registration on <b>'.'Welly'.'</b> APP .This is a Confirmation Message. <br /><br />
					<b>EMAIL</b> : <b>'.$email_newggg.'</b><br />
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

					$unverifiedProfileDetails = array('email'=> $email_newggg, 'firstName'=> $user_first_name, 'lastName'=> $user_last_name,'encryptedUserId'=>$encryptedUserId,'otp'=> (int)$random_otp,'phone'=> (int)$phone);

					$resultRes = array('unverifiedProfileDetails' => $unverifiedProfileDetails);
					$msg = 'Sorry,Email is not Verified.Please verified your Email.';
					$success = true;
					$serverResponse = array("code" => 602, 'message'=> $msg, 'isSuccess'=> $success);
					echo json_encode(array("serverResponse" => $serverResponse, 'result'=> $resultRes));
					exit();
				}

			} else {
				$msg = 'Sorry,You are not a subscriber.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse));
				exit();
			}
		}
	}else{
			$msg = 'All Fields are Required.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
	}	



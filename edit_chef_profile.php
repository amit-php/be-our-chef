<?php
$fullName    = $data['fullName'];
$phone       = $data['phone'];
$location    = $data['location'];
$experience  = $data['experience'];
$about       = $data['about'];
$website     = $data['website'];
$facebook    = $data['facebook'];
$twitter     = $data['twitter'];
$insta       = $data['insta'];
$specialization = $data['specialization'];
$location    = $data['location'];
$radius      = $data['radius'];
$postalcode  = $data['postCode'];
$ChefBudget  = $data['ChefBudget'];



$fullNameArr = explode(" ",$fullName);
$firstName   = $fullNameArr[0];
$lastName    = $fullNameArr[1];


if(!empty($fullName)){

	$user_data = wp_update_user(array('ID' => $user_id, 'first_name' => $firstName, 'last_name' => $lastName,'nickname' => $firstName));

  if(!empty($phone)){
	update_user_meta($user_id, '_phone', $phone);
   }

   if(!empty($location)){
	update_user_meta($user_id, '_location', $location);
   }

   if(!empty($experience)){
	update_user_meta($user_id, 'experience', $experience);
   }

   if(!empty($about)){
	update_user_meta($user_id, '_about_me', $about);
   }

   if(!empty($website)){
	update_user_meta($user_id, 'website', $website);
   }

   if(!empty($facebook)){
	update_user_meta($user_id, 'fb', $facebook);
   }

   if(!empty($twitter)){
	update_user_meta($user_id, 'twitter', $twitter);
   }

   if(!empty($insta)){
	update_user_meta($user_id, 'insta', $insta);
   }

   if(!empty($specialization)){
	update_user_meta($user_id, '_specializations', $specialization);
   }
	
   if(!empty($radius)){
	update_user_meta($user_id, '_radius', $radius);
   }

  if(!empty($postalcode)){
	update_user_meta($user_id, '_postal_code', $postalcode);
   }
    if(!empty($ChefBudget)){
	update_user_meta($user_id, '_ChefBudget', $ChefBudget);
   }

	
	$user_info = get_userdata($user_id);
	$username = $user_info->user_login;
	$eml = $user_info->user_email;

	delete_user_meta($user_id, '_search_keywords');


	if(!empty($eml)){
		add_user_meta($user_signup, '_search_keywords', $eml);
	}

	if(!empty($username)){
		add_user_meta($user_signup, '_search_keywords', $username);
	}

	if(!empty($firstName)){
		add_user_meta($user_signup, '_search_keywords', $firstName);
	}

	if(!empty($lastName)){
		add_user_meta($user_signup, '_search_keywords', $lastName);
	}
	if(!empty($phone)){
		add_user_meta($user_signup, '_search_keywords', $phone);
	}

	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userName = $user_info->user_login;
	$email_new = $user_info->user_email;

	$get_access_token = get_user_meta($user_id,'_access_token',true);
	$random_otp 	  = get_user_meta($user_id, '_email_verified_otp' ,true);
	$phone            = get_user_meta($user_id, '_phone', true);
	$location         = get_user_meta($user_id, '_location', true);
	$radius           = get_user_meta($user_id, '_radius', true);
	$experience       = get_user_meta($user_id, 'experience', true);
	$website          = get_user_meta($user_id, 'website', true);
	$fb               = get_user_meta($user_id, 'fb', true);
	$twitter          = get_user_meta($user_id, 'twitter', true);
	$insta            = get_user_meta($user_id, 'insta', true);
	$postal_code            = get_user_meta($user_id, '_postal_code', true);
	$ChefBudget            = get_user_meta($user_id, '_ChefBudget', true);
	$socialMedia      = array("fb"=>$fb,"twitter"=>$twitter,"insta"=>$insta);
	$specializations  = get_user_meta($user_id, '_specializations', true);
	$specializationsArr = explode(",",$specializations);
	$user_lat = get_user_meta($user_id, '_user_lat',true);
	$user_long = get_user_meta($user_id, '_user_long',true);

	$encrypteduser_id  = get_user_meta($user_id, '_encrypted_user_id' ,true);
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
							  "userName"=>$userName,
							  "phone"=> (int)$phone,
							  "location"=> (string) $location,
							  "profileImageURL"=> $imgUrl,
							  "radius"=>(string) $radius,
							  "website"=>(string) $website,
							  "socialMedia"=>$socialMedia,
							  "specializations"=>$specializationsArr,
							  'encrypteduser_id'=>$encrypteduser_id,
							  'postalCode'=>$postal_code,
							  "ChefBudget"=>$ChefBudget,
							   "userLat"=>$user_lat,
							  "userLong"=>$user_long
							  
						);

	$msg = 'Information Updated Successfully.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse,"result"=>$profileDetails));


}else{

	$msg = 'Name is missing.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));
}
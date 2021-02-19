<?php


$user_info = get_userdata($user_id);
$user_role = $user_info->roles;

$usertype = 'chef';

if(in_array($usertype,$user_role)){

	$user_info = get_userdata($user_id);

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
	$about       	  = get_user_meta($user_id, '_about_me', true);
	$website          = get_user_meta($user_id, 'website', true);
	$fb               = get_user_meta($user_id, 'fb', true);
	$twitter          = get_user_meta($user_id, 'twitter', true);
	$insta            = get_user_meta($user_id, 'insta', true);
	$postal_code            = get_user_meta($user_id, '_postal_code', true);
	$socialMedia      = array("fb"=>$fb,"twitter"=>$twitter,"insta"=>$insta);
	$specializations  = get_user_meta($user_id, '_specializations', true);
	$specializationsArr = explode(",",$specializations);
    $ChefBudget            = get_user_meta($user_id, '_ChefBudget', true);
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

	$menuLists = array();
	$itImageIDs = get_user_meta($user_id,'_menu_image_ids',false);
	//$itDocIDs = get_post_meta($tripId,'_itinerary_doc_ids',false);
	$itImageIDs =  array_unique($itImageIDs);

	foreach($itImageIDs as $itImageID){
		$url = wp_get_attachment_image_src($itImageID, 'full');
		$menuLists[] = array('menuImageId'=> (string)$itImageID,'menuImageUrl'=>$url[0]);
	}

///======

	$profileDetails = array(
							  "accessToken"=> (string)$get_access_token,
							  "fullName"=> (string)$fullName,
							  "email"=> (string)$email_new,
							  "userName"=>$userName,
							  "phone"=> (int)$phone,
							  "location"=> (string)$location,
							  "profileImageURL"=> (string)$imgUrl,
							  "radius"=> (string)$radius,
							  "website"=> (string)$website,
							  "socialMedia"=>$socialMedia,
							  "specializations"=>$specializationsArr,
							  'encrypteduser_id'=>(string)$encrypteduser_id,
							  'otp'=> (int)$random_otp,
							  'about'=> (string)$about,
							  'experience'=> (string)$experience,
							  'menuLists'=>$menuLists,
							  'postalCode'=>$postal_code,
							  'chefBudget'=>$ChefBudget,
							  "userLat"=>$user_lat,
							  "userLong"=>$user_long
						);

	$resultRes = array('profileDetails'=>$profileDetails);
	$msg = 'Account Information.';
	$success = true;

	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

}else{

	$msg = 'Sorry,You are not a Subscriber.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));
	exit();

}

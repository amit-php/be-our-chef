<?php
    $menuLists = array();
    $userId    =  $data['userId'];
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name  = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$about    = (string)get_user_meta($userId,"_about_me", true);
	$menu     = get_user_meta($userId,"_specializations", true);
	$fb       = get_user_meta($userId,"fb", true);
	$twiter   = get_user_meta($userId,"twitter", true);
	$insta    = get_user_meta($userId,"insta", true);
	$menuArray = explode(",",$menu);
	$itImageIDs = get_user_meta($userId,'_menu_image_ids',false);
	$location = get_user_meta($userId, '_location', $location);
	$itImageIDs =  array_unique($itImageIDs);
	foreach($itImageIDs as $itImageID){
					$url = wp_get_attachment_image_src($itImageID, 'full');
					$menuLists[] = array('menuImageId'=> (string)$itImageID,'menuImageUrl'=>$url[0]);
				}
	$rating = get_user_meta($userId, 'avrg_rating', true);
	if($rating){
		$avrgRating = $rating;
	}else {
		$avrgRating = 0;
	}
	$budget = get_user_meta($userId,"_ChefBudget", true);
	$result   = array("userId"=>$userId,
					"fullName"=>$fullName ,
					"location"=>$location,
					"rating"=>$avrgRating,
					"costPerHour"=>$budget,
					"about"=>$about,
					"menu"=>$menuArray ,
					"fb"=>$fb,
					"twitter"=>$twiter,
					"insta"=>$insta,
					"menuImg"=>$menuLists,
					"profilePic"=>$imageUrl);

    $resultRes = array('chefDetails'=>$result);
	$msg = 'Chef details.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
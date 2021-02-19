<?php
$result = array();
$topuser = array();
//$search_term = $data['searchKey'];
$location = get_user_meta($user_id,"_postal_code", true);

// The search term
// WP_User_Query arguments
$args1 = array (
    'role' => 'chef',
    'order' => 'ASC',
   // 'orderby' => 'display_name',
    //'search' => '*'.esc_attr( $search_term ).'*',
    'meta_query' => array(
        //'relation' => 'OR',
        array(
            'key'     => '_postal_code',
            'value'   => $location,
            'compare' => '='
        )   
    )
);
 
// Create the WP_User_Query object
$wp_user_query = new WP_User_Query($args1);
 
// Get the results
$user = $wp_user_query->get_results();
 

 foreach ($user as $key => $value) {
    $userId = $value->ID;
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$budget = get_user_meta($userId,"_ChefBudget", true);
	$location = get_user_meta($userId, '_location', $location);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$rating = get_user_meta($userId, 'avrg_rating', true);
	if($rating){
		$avrgRating = $rating;
	}else {
		$avrgRating = 0;
	}
	$result[] = array("userId"=>$userId,
					    "fullName"=>$fullName ,
						"location"=>$location,
						"rating"=>$avrgRating,
						"costPerHour"=>$budget,
						"profilePic"=>$imageUrl);
}
$location1 = get_user_meta($user_id,"_postal_code", true);
$args2 = array (
    'role' => 'chef',
    'order' => 'ASC',
   // 'orderby' => 'display_name',
    //'search' => '*'.esc_attr( $search_term ).'*',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => '_postal_code',
            'value'   => $location1,
            'compare' => '='
        ),
        array(
            'key'     => 'avrg_rating',
            'value'   => 2,
            'compare' => '>'
        )		
    )
);
 
// Create the WP_User_Query object
$wp_user_query2 = new WP_User_Query($args2);
 
// Get the results
$topChef = $wp_user_query2->get_results();
 foreach ($topChef as $key => $value) {
    $userId = $value->ID;
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$budget = get_user_meta($userId,"_ChefBudget", true);
	$location = get_user_meta($userId, '_location', $location);
	$rating = get_user_meta($userId, 'avrg_rating', $location);
	$topuser[] = array("userId"=>$userId,
					    "fullName"=>$fullName ,
						"location"=>$location,
						"rating"=>$rating,
						"costPerHour"=>$budget,
						"profilePic"=>$imageUrl);
}
    $adverImg      =  (string)get_theme_value('weaversweb_advertisement_logo');
	$adverDiscount =  (string)get_theme_value('weaversweb_advertisement_discount_text');
	$refralcode    =  (string)get_theme_value('weaversweb_referral_text');
	$adverArray    =   array("image"=>$adverImg, "discount"=>$adverDiscount,"referralCode"=>$refralcode);
    $resultRes     =   array('chefList'=>$result, "advertisement"=>$adverArray,"topuser"=>$topuser);
	$msg = 'List of chef.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
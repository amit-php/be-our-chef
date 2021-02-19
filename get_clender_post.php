<?php $result = array();
$date = $data['date'];
$my_posts = get_posts(array(
	'author'=>$user_id,
    'post_type'      => 'booking',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
	'meta_query'	=> array(
		array(
			'key'	 	=> '_date',
			'value'	  	=> $date,
			'compare' 	=> '=',
		),	
	),
));

foreach($my_posts as $values){
	 $postId         = $values->ID;
	 $userID         = get_post_field( 'post_author', $postId );
     $user_info = get_userdata($userID);
	 $user_first_name = $user_info->first_name;
	 $user_last_name = $user_info->last_name;
	 $fullName = $user_first_name.' '.$user_last_name;
	 $userImag = get_user_meta($user_id,"_profile_image_user", true);
	 $imageUrl = (string)wp_get_attachment_url($userImag);
	 $location = get_post_meta($postId, "_location", true);
	 $date = get_post_meta($postId, "_date", true);
	 $time = get_post_meta($postId, "_time", true);
	 $budget = get_post_meta($postId, "_budget", true);
	 $how_many_people = get_post_meta($postId, "how_many_people", true);
	 $dietary_requirements = get_post_meta($postId, "dietary_requirements", true);
	 $description = get_post_meta($postId, "_description", true);
	 $title = get_the_title($postId);
	 $result[] = array("bookingID"=>$postId,"title"=>$title, "fullName"=>$fullName, "image"=>$imageUrl, 
	 "location"=>$location,"date"=>$date,"time"=>$time,"budget"=>$budget,"how_many_people"=>$how_many_people,
     "dietary_requirements"=>$dietary_requirements,"description"=>$description);
	 
}
    $msg = 'my booking.';
	$success = true;
	$resultRes = array("mybooking"=>$result);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
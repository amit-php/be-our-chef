<?php $result = array();
$totalEarnini = array();
$args = array(
    'author'=>$user_id,
    'post_type'      => 'invitation',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
	'meta_query'	=> array(
		//'relation'		=> 'AND',
			array(
			'key'	  	=> '_status',
			'value'	  	=> "completed",
			'compare' 	=> '=',
		),
	),
);
$my_posts = get_posts( $args );
if($my_posts){

foreach($my_posts as $values){
	 $postId         = get_post_meta($values->ID, "_invited_jobs", true);
	 $userID         = get_post_meta($values->ID, "invited_by", true);
	 $author_id = get_post_field( 'post_author', $postId );
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
	 $status = get_post_meta($values->ID, "_status", true);
	 $title = get_the_title($postId);
	 $created = get_post_meta($values->ID, "created", true);
	 $totalEarnini[] = get_post_meta($values->ID, "_tax", true);
	 $result[] = array("postId"=>$values->ID,"jobID"=>$postId,"status"=>$status,"title"=>$title, "fullName"=>$fullName, "image"=>$imageUrl, 
	 "location"=>$location,"date"=>$date,"time"=>$time,"budget"=>$budget,"how_many_people"=>$how_many_people,
     "dietary_requirements"=>$dietary_requirements,"description"=>$description,"userId"=>$author_id,"confirmTimestamp"=>(string)$created);
	 
}
    $total = array_sum($totalEarnini);
    $msg = 'my booking.';
	$success = true;
	$resultRes = array("totalEarning"=>(int)$total, "mybooking"=>$result);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
} else{
	  $msg = 'no job list is found.';
	$success = false;
	$resultRes = array("totalEarning"=>0,"mybooking"=>array());
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
}
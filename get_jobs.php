<?php
$result = array();

$locations = get_user_meta($user_id,"_postal_code", true);
$args = array(
	'post_type'      => 'booking',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
	'meta_query'	=> array(
		array(
			'key'	  	=> '_postal_codes',
			'value'	  	=> $locations,
			'compare' 	=> '=',
		)
	)
);

$my_posts = get_posts( $args );
foreach($my_posts as $value){
	$postId = $value->ID;

	$userId = get_post_field( 'post_author', $postId );
	$user_info = get_userdata($userId);
	$user_first_name = $user_info->first_name;
	$user_last_name = $user_info->last_name;
	$fullName = $user_first_name.' '.$user_last_name;
	$userImag = get_user_meta($userId,"_profile_image_user", true);
	$imageUrl = (string)wp_get_attachment_url($userImag);
	$location = get_post_meta($postId, "_location", true);
	$date     = get_post_meta($postId, "_date", true);
	$occasion = get_the_title($postId);
	$perice   = get_post_meta($postId, "_budget", true);
	$result[] = array("jobId"=>$postId, "occasionType"=>$occasion,"fullName"=>$fullName,
	"location"=>$location,"date"=>$date,"perice"=>$perice,"userImage"=>$imageUrl);
}
 $resultRes = array('jobList'=>$result);
	$msg = 'list of jobs.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
<?php
$args=array(
    'post_type'      => 'specializations',
    'post_status'    => 'publish',
    'posts_per_page' => -1
);
$my_posts = get_posts( $args );
if($my_posts){
	foreach ($my_posts as $key => $value) {
		$title = get_the_title($value->ID);
		$id = $value->ID;
		$resurl[]= array("id"=>$id,"name"=>$title);
	}
	$msg = 'specializations list.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse, "result"=>$resurl));

}else {
	$msg = 'Sorry, no result is found.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse,"result"=>[]));
        

}
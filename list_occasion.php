<?php
$result = array();
$args = array(
    'post_type'      => 'occasion',
    'post_status'    => 'publish',
    'posts_per_page' => -1
);
$my_posts = get_posts( $args );
foreach($my_posts as $value){
	$postId = $value->ID;
	$title = get_the_title($postId);
	$result[] = array("postId"=>$postId, "occasionType"=>$title);
}
 $resultRes = array('occasionType'=>$result);
	$msg = 'list of occasion.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
<?php $result = array();
$args = array(
    'author'=>$user_id,
    'post_type'      => 'booking',
    'post_status'    => 'publish',
    'posts_per_page' => -1
);
$my_posts = get_posts( $args );

foreach($my_posts as $values){
	 $postId         = $values->ID;
	  $date = get_post_meta($postId, "_date", true);
	 //$result[] = array("bookingID"=>$postId,"bookingDate"=>$date);
	  $result[] = $date;
	 
}
    $countdate = array_count_values($result);
	foreach($countdate as $key=>$countdates){
		$date = $key;
		$count = $countdates;
		$rr[] = array("date"=>$date,"count"=>$count);
	}
	$resultRes = array("clenderView"=>$rr);
    $msg = 'my booking.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
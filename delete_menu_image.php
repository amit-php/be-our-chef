<?php
$menuImageId = $data['menuImageId'];
if(empty($menuImageId)){
	$menuImageId = $_POST['menuImageId'];
}

//$itineraryImageDetails = array();

if(!empty($menuImageId)){

	delete_user_meta($user_id,'_menu_image_ids',$menuImageId);

	$menuLists = array();
	$itImageIDs = get_user_meta($user_id,'_menu_image_ids',false);
	//$itDocIDs = get_post_meta($tripId,'_itinerary_doc_ids',false);
	$itImageIDs =  array_unique($itImageIDs);

	foreach($itImageIDs as $itImageID){
		$url = wp_get_attachment_image_src($itImageID, 'full');
		$menuLists[] = array('menuImageId'=> (string)$itImageID,'menuImageUrl'=>$url[0]);
	}

	$msg = 'Image deleted successfully.';
	$success = true;

	$resultRes = array('menuLists'=>$menuLists);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse, 'result'=> $resultRes));

	exit();

}else{

	$msg = 'MenuImageId is Missing.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));

	exit();

}

<?php
$postalcode = $data['postCode'];
$location = $data['location'];
$userLat = $data['userLat'];
$userLong = $data['userLong'];

if(!empty($postalcode) && !empty($location)){
	update_user_meta($user_id, '_postal_code',$postalcode);
	update_user_meta($user_id, '_location',$location);
	update_user_meta($user_id, '_user_lat',$userLat);
	update_user_meta($user_id, '_user_long',$userLong);

	$msg = 'postal code is added.';
	$success = true;
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));

	exit();

}else{

	$msg = 'Postalcode and Location are required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));

	exit();
}
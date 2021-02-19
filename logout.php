<?php
update_user_meta($user_id, '_access_token', '');

$msg = "Logged out Successfully.";
$success = true;
$serverResponse = array("code" => 200, 'message'=> $msg, 'isSuccess'=> $success);
echo json_encode(array("serverResponse" => $serverResponse));	



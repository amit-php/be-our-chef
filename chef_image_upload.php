<?php
$image = array();
$images = array();

$image = $_FILES['image'];
$num_files = count($_FILES['image']['name']);

for( $i=0; $i < $num_files; $i++ ) {
	$images[] = array('name'=>$_FILES['image']['name'][$i],'type'=>$_FILES['image']['type'][$i],'tmp_name'=>$_FILES['image']['tmp_name'][$i],'error'=>$_FILES['image']['error'][$i],'size'=>$_FILES['image']['size'][$i]);
}
$imagesCount = count($images);

if(!empty($imagesCount > 0)){

		for($j=0; $j < $imagesCount; $j++){
			$attachmentID = (string)image_upload($images[$j]);
		}

		$image_attributes = wp_get_attachment_image_src($attachmentID);

		if($image_attributes){

			if(!empty($attachmentID)){
					update_user_meta($user_id,'_profile_image_user',$attachmentID);
			}


			$image_attributes_new = wp_get_attachment_image_src($attachmentID, 'full');
			//$profileDetails = profileDetails($user_id);

			$msg = 'Image successfully uploaded.';
			$success = true;

			$resultRes = array("imgUrl"=> $image_attributes_new[0]);
			$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

			exit();
			
		}else{

			$msg = 'Sorry,Due to some problem Image Uploading is Failed.Please try again.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));

			exit();
		}

}else{
	$msg = 'Image uploading format is invalid.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));
	exit();
}

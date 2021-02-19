<?php
$image = array();
$images = array();

$image = $_FILES['image'];
$num_files = count($_FILES['image']['name']);

/*date_default_timezone_set('Asia/Kolkata');
$myfile = fopen("log_file.txt", "a") or die("Unable to open file!");
$txt_data = 'api heat by :'.$user_id.'      Entry Time :'.date("Y-m-d h:i:sa").'\n';
fwrite($myfile, "\r\n". $txt_data);
fclose($myfile);*/

$menuLists = array();


for($i=0; $i < $num_files; $i++){
	$images[] = array('name'=>$_FILES['image']['name'][$i],'type'=>$_FILES['image']['type'][$i],'tmp_name'=>$_FILES['image']['tmp_name'][$i],'error'=>$_FILES['image']['error'][$i],'size'=>$_FILES['image']['size'][$i]);
}

$imagesCount = count($images);

if(!empty($imagesCount > 0)){

		for($j=0; $j < $imagesCount; $j++){
			$attachmentId1 = image_upload($images[$j]);
			$attachmentId[] = (string)$attachmentId1;
			//$url = wp_get_attachment_image_src($attachmentId1, 'full');
			//$itineraryImageDetails[] = array('id'=> (string)$attachmentId1,'url'=>$url[0]);

			$imageIdsCount = count($attachmentId);
			//$itineraryDocIdsCount = count($itineraryDocIds);

			if($imageIdsCount > 0){
				foreach($attachmentId as $menuImageId){
					add_user_meta($user_id,'_menu_image_ids',$menuImageId);
				}
			}
		}

		if(!empty($attachmentId)){
			$image_attributes = wp_get_attachment_image_src($attachmentId[0]);
			if($image_attributes){
				$msg = 'Image successfully uploaded.';
				$success = true;


				$itImageIDs = get_user_meta($user_id,'_menu_image_ids',false);
				//$itDocIDs = get_post_meta($tripId,'_itinerary_doc_ids',false);
				$itImageIDs =  array_unique($itImageIDs);

				foreach($itImageIDs as $itImageID){
					$url = wp_get_attachment_image_src($itImageID, 'full');
					$menuLists[] = array('menuImageId'=> (string)$itImageID,'menuImageUrl'=>$url[0]);
				}

				//$photoIDs = implode(",",$itImageIDs);
				
				$resultRes = array('menuLists'=>$menuLists);
				$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse, 'result'=> $resultRes));

				exit();
			}else{
				$msg = 'Sorry,Due to some problem Image Uploading is Failed.Please try again.';
				$success = false;
				$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
				echo json_encode(array("serverResponse" => $serverResponse));
				exit();
			}	

		}else{
			$msg = 'Sorry,Due to some problem Image Uploading is Failed.Please try again.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
		}

}else{
		$msg = 'All Fields are Required.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();
}


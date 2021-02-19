<?php
	$image = array();
	$images = array();
	$proposalDetails       = $_POST['proposalDetails'];
	$proposalPrice         = $_POST['proposalPrice'];
	$proposalMenu          = $_POST['proposalMenu'];
	$userId                = $_POST['userId'];
	$jobId                 = $_POST['jobId'];
	$gallery = $_FILES['gallery'];
    $num_files = count($_FILES['image']['name']);
	$menuLists = array();
	if(!empty($proposalDetails) && !empty($proposalPrice) && !empty($userId) && !empty($jobId)){
	$priceWithtax = 
	$my_posts = get_posts(array(
	'author'=>$user_id,
	'posts_per_page'	=> -1,
	'post_type'		=> 'invitation',
	'post_status'    => 'publish',
	'meta_query'	=> array(
		//'relation'		=> 'AND',
	
		array(
			'key'	  	=> '_invited_jobs',
			'value'	  	=> $jobId,
			'compare' 	=> '=',
		),
		
	),
  ));
		if($my_posts){
	$msg = 'you are already applied or invited for this job.';
	$success = false;
	$resultRes = array();
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();	
		}
	$chef = get_user_by( 'id', $user_id );
	$chefdetails = 'Applyed by chef '. $chef->first_name . ' ' . $chef->last_name;
	$status = get_post_meta($jobId , "_status", true);
	if($status == "upcoming"){
		$my_post = array(
			'post_type' => 'invitation',
			'post_title'    => $chefdetails,
			'post_status'   => 'publish',
			'post_author'   => $user_id,
		);
		// Insert the post into the database.
		$bookingId = wp_insert_post( $my_post );
		update_post_meta($bookingId, "_invited_jobs", $jobId);
	}else{
		$bookingId = $jobId;
	}
	if(!is_wp_error($bookingId)){
	$jobDate = get_post_meta($jobId,"_date",true);
	update_post_meta($bookingId,"_date",$jobDate);
	update_post_meta($bookingId, "_status", "applied");
	update_post_meta($bookingId, "invited_by", $userId);
	update_post_meta($bookingId, "_proposal_details", $proposalDetails);
	$priceWithtext = $proposalPrice*12/100;
	update_post_meta($bookingId, "_tax", $proposalPrice);
	$totalPrice = $priceWithtext+$proposalPrice;
	update_post_meta($bookingId, "_proposal_price", $totalPrice);
	//add peposal image

			  
			  //=============gallery image==========
				$attachmentId3 = array();
				$gallery = array();
				$gallerys = array();
				$num_files = count($_FILES['gallery']['name']);
				for($i=0; $i < $num_files; $i++){
					$gallerys[] = array('name'=>$_FILES['gallery']['name'][$i],'type'=>$_FILES['gallery']['type'][$i],'tmp_name'=>$_FILES['gallery']['tmp_name'][$i],'error'=>$_FILES['gallery']['error'][$i],'size'=>$_FILES['gallery']['size'][$i]);
				}

				$gallerysCount = count($gallerys);

				if(!empty($gallerysCount > 0)){

						for($j=0; $j < $gallerysCount; $j++){
							$attachmentId2 = image_upload($gallerys[$j]);
							$attachmentId3[] = (string)$attachmentId2;
						}

		               if(!empty($attachmentId3)){
                            $key3 = "field_6006c70977729";
							$val3 = array();
							foreach($attachmentId3 as $gallerysid){
							     array_push($val3,array("image"=>$gallerysid));
							    }
							  update_field( $key3, $val3, $bookingId );
						}
				}
			  
	//update_post_meta($bookingId, "proposal_menu", $location);

	$msg = ' applied successfully.';
	$success = true;
	$resultRes = array("bookingId"=>$bookingId);
	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));
	exit();
	}else{
	  //there was an error in the post insertion, 
	$msg = $post_id->get_error_message();
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();
	}
	} else {
	$msg = 'Required field.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> array()));
	exit();	
	}
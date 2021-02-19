<?php

	$jobId = $data['jobId'];
	$jobIdStatus = $data['status'];
	
	if(!empty($jobId)){
		$tiele = get_post_field("post_title", $jobId);
		if($tiele){
			update_post_meta($jobId, "_status", $jobIdStatus);
				$msg = 'job is '.$jobIdStatus.' successfully';
			$success = true;
			$serverResponse = array("code" => 200, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
		} else {
				$msg = 'no job details found.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
		}
		
		}else{
			$msg = 'Required field.';
			$success = false;
			$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
			echo json_encode(array("serverResponse" => $serverResponse));
			exit();
		}


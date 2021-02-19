<?php
include_once('generate_access_token.php');
$firstName = $data['firstName'];
$lastName = $data['lastName'];
$loginId = $data['loginId'];
$email_id = $data['email'];
$deviceId = $data['deviceId'];
$usertype = $data['usertype'];

if(!empty($data['FCMToken'])){
	$device_type = $data['OSType'];
	$device_token = $data['FCMToken'];
}

//$usertype = 'subscriber';


if(!empty($firstName) && !empty($loginId) && !empty($lastName)){

	$response = array();
	$uname = $firstName.$lastName.$loginId;
	
	if(!empty($email_id)){
		$email_exists = username_exists($email_id);
	}else{
		$uname_exists = username_exists($uname);
	}

	$user_id = '';

	if($uname_exists) {
	  $user = get_user_by( 'login', $uname);
	  $user_id = $user->ID;
	  $user_name = $user->user_login;
	}

	if($email_exists){
	  $user = get_user_by( 'login', $email_id);
	  $user_id = $user->ID;
	  $user_name = $user->user_login;
	}
	
	if(empty($user_id)){

		if(empty($email_id)){
			$user_name = strtolower($uname);
		}else{
			$user_name = strtolower($email_id);
		}
		   				
			
	   $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

	   $userdata = array(
						'user_login'  => $user_name,
						'first_name'  => $firstName,
						'last_name'  => $lastName,
						'display_name'  => $user_name,
						'nickname'  => $firstName,
						'user_pass'  => $random_password,
						'role'  => $usertype
					);

		
		$user_id = wp_insert_user($userdata) ;
		update_user_meta($user_id, '_device_id', $deviceId);

		$result_args = array(
						    'post_status'   => 'publish',
							'post_type' => 'user_answers',
						    'order' =>'ASC',
						    'posts_per_page'=> -1,
						    'meta_query' => array(
						        array(
								        'key'       => '_device_id',
								        'value'     => $deviceId,
								        'compare'   => '=',
								    ),
						    )
						);  

		$result_query = new WP_Query($result_args);
		$result_count = $result_query->found_posts;

		if($result_count > 0){

			if($result_query->have_posts()) : while($result_query->have_posts()) : $result_query->the_post();

				$pid = get_the_ID();
				$arg = array(
				    'ID' => $pid,
				    'post_author' => $user_id,
				);
				wp_update_post($arg);

			endwhile; else: endif;
			wp_reset_postdata();

		} 
    }


	$expiration = time() + apply_filters('auth_cookie_expiration', 1209600, $user_id, true);
   	$cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');


	$random_otp = rand(100000,999999);
	update_user_meta($user_id, '_email_verified_status' , 'yes');
	update_user_meta($user_signup, '_email_verified_otp' ,$random_otp);
	update_user_meta($user_signup, '_change_random_otp_status', 'false');
	


	$access_token_val = get_rand_alphanumeric(8);
	$access_token = $access_token_val.'_'.$user_id;
	update_user_meta( $user_id, '_access_token', $access_token);
	$get_access_token = get_user_meta($user_id,'_access_token',true);

	$_is_first_time = get_user_meta($user_id,'_is_first_time',true);

	if(empty($_is_first_time)){
		$_is_first_time = 0;
	}

	$_is_first_time = $_is_first_time + 1;

	update_user_meta( $user_id, '_is_first_time', $_is_first_time);


	if(!empty($device_type) && !empty($device_token)){			
		update_user_meta( $user_id, '_device_token_id', $device_token);
		update_user_meta( $user_id, '_device_os_name', $device_type);
		$device_token = get_user_meta($user_id,'_device_token_id',true);
		$device_type = get_user_meta($user_id,'_device_os_name',true);
	}



	$random_otp = get_user_meta($user_id, '_email_verified_otp' ,true);
	$encryptedUserId = get_user_meta($user_id, '_encrypted_user_id' ,true);

	if(empty($encryptedUserId)){
		$encryptedUserId = base64_encode($random_otp.$user_id);
		update_user_meta($user_id, '_encrypted_user_id', $encryptedUserId);
	}


	$image_attributes = get_user_meta($user_id,'_profile_image_user',true);
	if(!empty($image_attributes)){
		
		$image_attributes_new = wp_get_attachment_image_src($image_attributes, 'thumbnail');
		$imgUrl = $image_attributes_new[0];
	}else{
		$imgUrl = '';
	}




	if($_is_first_time == 1){

	$deviceId = get_user_meta($user_id, '_device_id', true);

	$termsAll = get_terms([
    'taxonomy' => 'useranswers_types',
    'hide_empty' => false,
	]);

	$rsArr = array();

	$i = 0;

	foreach($termsAll as $terms){

				$term_id_p = $terms->term_id;
				$tname = $terms->name;

				
				$result_args = array (
				    'post_status'   => 'publish',
					'post_type' => 'user_answers',
				    'order' =>'ASC',
				    'posts_per_page'=> -1,
				    'meta_query' => array(
				        array(
						        'key'       => '_device_id',
						        'value'     => $deviceId,
						        'compare'   => '=',
						    ),
				    ),
				    'tax_query' => array(
				        array(
				        'taxonomy' => 'useranswers_types',
				        'field'    => 'id',
				        'terms'    => $term_id_p,
				        )
				    ),
				);  

				$result_query = new WP_Query($result_args);
				$result_count = $result_query->found_posts;
				$_answer_points = get_field('_answer_points', 'useranswers_types' . '_' . $term_id_p);
				$_answer_description = get_field('_answer_description', 'useranswers_types' . '_' . $term_id_p);

				//$rsTotal = ((int)$result_count * (int)$_answer_points);
				
				//$rsTotal = 0;
				$pPoints = array();

				if($result_query->have_posts()) : while($result_query->have_posts()) : $result_query->the_post();

					$pid = get_the_ID();
					$pPoints[] = get_post_meta($pid,'_question_points_new',true);

					//$rsTotal = (int)$pPoints + $rsTotal;
					

				endwhile; else: endif;
				//wp_reset_postdata();

				$rsTotal = (int)array_sum($pPoints);
				
				$totalPointsArr[] = (int)$rsTotal;
				
				$rsArr[] = array('signId'=>$term_id_p,'totalPoints'=>(int)$rsTotal,'name'=>$tname,'description'=>$_answer_description);
				
				$i++;
			}

			$b = array_keys($totalPointsArr,max($totalPointsArr));
			$b = $b[0];


			update_user_meta($user_id, '_result_sign_Id', $rsArr[$b]['signId']);
			update_user_meta($user_id, '_result_total_points', $rsArr[$b]['totalPoints']);
			update_user_meta($user_id, '_result_name', $rsArr[$b]['name']);

	}


	$signId = get_user_meta($user_id, '_result_sign_Id', true);
	$totalPoints = get_user_meta($user_id, '_result_total_points', true);
	$name = get_user_meta($user_id, '_result_name', true);



	$profileDetails = array(
		  "accessToken"=> $get_access_token,
		  "firstName"=> $firstName,
		  "lastName"=> $lastName,
		  "email"=> $email_id,
		  "profileImageURL"=> $imgUrl,
		  'encryptedUserId'=>$encryptedUserId,
		  'signId'=>$signId,
		  'totalPoints'=>$totalPoints,
		  'name'=>$name
	);

	$resultRes = array('profileDetails'=>$profileDetails);

	$msg = 'Login Successfully.';
	$success = true;

	$serverResponse = array("code" => 200, 'message'=> $msg , 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse , 'result'=> $resultRes));

}else{

	$msg = 'All Fields are Required.';
	$success = false;
	$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
	echo json_encode(array("serverResponse" => $serverResponse));

}



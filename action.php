  <?php
include_once('../wp-load.php');
header("Cache-Control: no-cache"); 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Accept: multipart/form-data");
header("Content-Type: application/json"); 

$data = json_decode(file_get_contents('php://input'), true);

$resultRes = array();
$action = $data['action'];

if(empty($action)){
	$action = $_POST['action'];	
}
$x_auth_token = $_SERVER['HTTP_ACCESSTOKEN'];
if(!empty($action)){

	if(!empty($x_auth_token)){

		$token = explode("_", $x_auth_token);
		$user_id = $token[1];
		if(!empty($user_id)){
			$get_access_token = get_user_meta($user_id, '_access_token', true);
		}else{
			$flag = 2;
		}

		if($get_access_token == $x_auth_token){
			$flag = 1; // Authtoken Match
		}else{
			$flag = 2; // Authtoken Mismatch
		}	
	}

	ini_set('serialize_precision', 14); 
	ini_set('precision', 14);

	if($action == 'signUpChef' || $action == 'signUpUser'  || $action == 'login' || $action == 'resendOTP' || $action == 'forgetPassword' || $action == 'verifyOTP' || $action == 'resetPassword' || $action == 'googleLogin' || $action == 'facebookLogin' || $action == 'linkdinLogin' || $action == 'appleLogin' || $action == 'getEventTitle' || $action == 'getEventTitle' || $action == 'contactUs' || $action == 'faq' || $action == 'termcondition' || $action == 'privacyPolicy' || $action == 'getSpecializations'){
		$flag = 1;
	}

	/******************** Limited Actions Starts *************/

		if($action == 'signUpChef'){
			require_once('auth/signup_chef.php');
		}
		if($action == 'signUpUser'){
			require_once('auth/signup_user.php');
		}


		if($action == 'login'){
			require_once('auth/signin.php');
		}
		if($action == 'getSpecializations'){
			require_once('auth/get_specializations.php');
		}

		if($action == 'googleLogin'){
			require_once('auth/google-login.php');
		}

		if($action == 'facebookLogin'){
			require_once('auth/facebook_login.php');
		}
		if($action == 'linkdinLogin'){
			require_once('auth/linkdin_login.php');
		}
		if($action == 'appleLogin'){
			require_once('auth/apple_login.php');
		}

		if($action == 'verifyOTP'){
			require_once('auth/verify_otp.php');
		}

		if($action == 'resendOTP'){
			require_once('auth/resend_otp.php');
		}

		if($action == 'forgetPassword'){
			require_once('auth/forgotpassword_getotp.php');
		}

		if($action == 'resetPassword'){
			require_once('auth/forgotpassword_resetpassword.php');
		}
		if($action == 'getGeners'){
			require_once('musician/get_all_geners.php');
		}
		if($action == 'getEventTitle'){
			require_once('user/get_events_title.php');
		}
		if($action == 'contactUs'){
			require_once('userProfile/contact-us.php');
		}
		if($action == 'faq'){
			require_once('userProfile/faq.php');
		}
		if($action == 'termcondition'){
			require_once('userProfile/term_condition.php');
		}
		if($action == 'privacyPolicy'){
			require_once('userProfile/privacy_policy.php');
		}
	
	


	/******************** Limited Actions Ends *************/

	if($flag == 1){

		if($action == 'internalLogin'){
			require_once('auth/check_authtoken_validity.php');
		}

		if($action == 'changePassword'){
			require_once('auth/change_password.php');
		}

		if($action == 'logout'){
			require_once('auth/logout.php');
		}

		if($action == 'imageUpload'){
			require_once('userProfile/image_upload_user.php');
		}

		
		if($action == 'getUserDetails'){
			require_once('userProfile/user_details.php');
		}
		
		if($action == 'editUserInfo'){
			require_once('userProfile/edit_user_profile.php');
		}
		
		if($action == 'uploadUserImage'){
			require_once('userProfile/image_upload_user.php');
		}
		
		if($action == 'editMusicianProfile'){
			require_once('musician/edit_musician_profile.php');
		}
		if($action == 'musicianProfileDetails'){
			require_once('musician/musician_details.php');
		}
		if($action == 'creatEvents'){
			require_once('user/creat_events.php');
		}
		if($action == 'userEventsList'){
			require_once('user/event_list.php');
		}
	
		//chef==========>
		if($action == 'getChefrDetails'){
			require_once('chef/chef_profile_details.php');
		}
		if($action == 'editChefInfo'){
			require_once('chef/edit_chef_profile.php');
		}

		if($action == 'chefProfileImageUpload'){
			require_once('chef/chef_image_upload.php');
		}

		if($action == 'uploadMenuImage'){
			require_once('chef/upload_menu_image.php');
		}

		if($action == 'deleteMenuImage'){
			require_once('chef/delete_menu_image.php');
		}
		if($action == 'addProposal'){
			require_once('chef/add_perposal.php');
		}
		if($action == 'chefMyjobs'){
			require_once('chef/my_jobs.php');
		}
		if($action == 'myjobsDetails'){
			require_once('chef/my_job_details.php');
		}
		if($action == 'acceptProposal'){
			require_once('chef/accept_perposal.php');
		}
		if($action == 'getclanderView'){
			require_once('chef/get_my_jobs_cleanderview.php');
		}
			if($action == 'getMyjobsclanderList'){
			require_once('chef/get_my_jobs_clander.php');
		}
			if($action == 'addChefBank'){
			require_once('chef/add_chef_bank_account.php');
		}
			if($action == 'getChefAccount'){
			require_once('chef/get_chef_account.php');
		}
			if($action == 'checkBankStatus'){
			require_once('chef/bank_status.php');
		}
			if($action == 'complitedJobs'){
			require_once('chef/complited_jobs.php');
		}
		///user========>
		if($action == 'addPostcode'){
			require_once('auth/add_user_postal_code.php');
		}
        if($action == 'getChefList'){
			require_once('user/user_list.php');
		}
		  if($action == 'getChefrDetail'){
			require_once('user/chef_details.php');
		}
		 if($action == 'listOfoccasion'){
			require_once('user/list_occasion.php');
		}
		if($action == 'addBooking'){
			require_once('user/insert_booking.php');
		}
		if($action == 'myBookingClender'){
			require_once('user/get_booking_cleanderview.php');
		}
		if($action == 'getclenderPost'){
			require_once('user/get_clender_post.php');
		}
		if($action == 'myBooking'){
			require_once('user/my_booking.php');
		}
		if($action == 'bookingDetails'){
			require_once('user/booking_details.php');
		}
		if($action == 'jobList'){
			require_once('chef/get_jobs.php');
		}
		if($action == 'jobDetails'){
			require_once('chef/job_details.php');
		}
		if($action == 'popupMybooking'){
			require_once('user/popup_mybooking.php');
		}
		if($action == 'inviteChef'){
			require_once('user/invite_chef.php');
		}
			if($action == 'inviteChefBymybooking'){
			require_once('user/invite_chef_by_mybooking.php');
		}
			if($action == 'listInvitedchef'){
			require_once('user/list_invited_chef.php');
		}
		if($action == 'getProposal'){
			require_once('user/get_proposal.php');
		}
		if($action == 'userListPopup'){
			require_once('user/user_list_popup.php');
		}
		if($action == 'addCard'){
			require_once('user/add_user_card.php');
		}
		if($action == 'getUserCard'){
			require_once('user/get_user_card.php');
		}
		if($action == 'bookingFeeTransfer'){
			require_once('user/booking_fee_transfer.php');
		}
		if($action == 'startendJob'){
			require_once('user/start_and_end_job.php');
		}
		if($action == 'refund'){
			require_once('user/refund.php');
		}
		//============common===========================
			if($action == 'addReview'){
			require_once('common/add_review.php');
		}
			if($action == 'getReviews'){
			require_once('common/list_of_rating.php');
		}
			

	}else{
		$msg = 'Sorry, Your Session is Expired. Please Login Again.';
		$success = false;
		$serverResponse = array("code" => 601, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();
	}

}else{
		$msg = 'action is missing.';
		$success = false;
		$serverResponse = array("code" => 600, 'message'=> $msg, 'isSuccess'=> $success);
		echo json_encode(array("serverResponse" => $serverResponse));
		exit();
}
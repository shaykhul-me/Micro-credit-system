<?php
session_start();
$uname= isset($_SESSION["username"])?$_SESSION["username"] : "";
$uid=isset($_SESSION["userid"])?$_SESSION["userid"] : "";
$ac_type= isset($_SESSION["ac_role"])?$_SESSION["ac_role"] : "";//account type

if($uname=="" || $uid=="" || $ac_type==""){
	header('location:login.php');
}
// if(!isset($_SESSION['other_info'])){
// 	header('location: login.php');
// 	exit;
// }
// For Redirect/Referesh Page
$default_redirect_url = 'http'."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// $csrf_input = "<input type='hidden' name='csrf' value=''>";
$csrf_input = "";

// $ignore_csrf_input = "<input type='hidden' name='ignore_csrf' value='true'>";
$ignore_csrf_input = "";
// $form_success_message = "";
// if ($_SERVER['REQUEST_METHOD'] == "POST") {
// 	if(!isset($_POST['ignore_csrf'])){
// 		if (!isset($_POST['csrf'])) {		
// 			$form_success_message =  "submission('$default_redirect_url', 'Error', '<div class=\"alert alert-danger\">Csrf Not Found</div>')";
// 		} else if(!in_array($_POST['csrf'],$_SESSION['csrf'])) {
// 			$form_success_message =  "submission('$default_redirect_url', 'Error', '<div class=\"alert alert-danger\">Csrf not matched</div>')";
// 		}else{
// 			$search_array_key = array_search($_POST['csrf'], $_SESSION['csrf']);
// 			unset($_SESSION['csrf'][$search_array_key]);
// 		}
// 	}
// 	if(isset($_SESSION['csrf'])){
// 		$time_key_now = time()-600;
// 		foreach ($_SESSION['csrf'] as $key => $value) {
// 			if ($key < $time_key_now) {				
// 				unset($_SESSION['csrf'][$key]);
// 			}
// 		}
// 	}
// } else {
	
// 	$new_csrf_token = bin2hex(random_bytes(32)); // Generate a new token
// 	$seassino_id = time();
// 	$_SESSION['csrf'][$seassino_id] = $new_csrf_token;
// 	$csrf_input = "<input type='hidden' name='csrf' value='{$new_csrf_token}'>";
// }


include('connect.php');
include('main_function.php');

$date_sql_from_setting = manual_query("SELECT * FROM settings WHERE s_name='calculation_date(yyyy-mm-dd)'");
$date_from_settings = 0;
if ($date_sql_from_setting['count'] == 1) {
	$date_from_setting_res = mysqli_fetch_assoc($date_sql_from_setting['query']);

	$date_from_settings = $date_from_setting_res['s_value'];
}

date_default_timezone_set("Asia/Dhaka");
$datetime= date("Y-m-d h:i:s");
$today = date("Y-m-d");
if(isset($_SESSION['c_date'])){
	$datetime = $_SESSION['c_date'];
}
// $datetime= date("2021-10-25 07:52:00");
// manual date part

if ($date_from_settings != 0) {
	$manual_time_from_set = date("h:i:s");
	$datetime = "$date_from_settings $manual_time_from_set";
	$date = $date_from_settings;
}

// $datetime= date("2021-10-25 07:52:00");
$date = date("d-m-Y", strtotime($datetime));
$ip=$_SERVER['REMOTE_ADDR'];
$user_other_info= isset($_SESSION['other_info'])?$_SESSION['other_info']:"";

?>
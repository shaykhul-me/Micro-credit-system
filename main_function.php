<?php

function ranpass($length)
{
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	return substr(str_shuffle($chars), 0, $length);
}

function option_collect($category_name, $selected_name)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM settings WHERE s_category = '$category_name'");
	$count = mysqli_num_rows($query);
	$options = "";
	//$option_list = array();
	while($get_option_res = mysqli_fetch_array($query)){
		//$option_list[] = $get_option_res['s_name'];
		$selected = ($selected_name == $get_option_res['s_name']) ? "SELECTED" : "";
		$option_name = $get_option_res['s_name'];
		$options .= "<option value='$option_name' $selected>$option_name</option>";
	}
	return array('count' => $count, 'query' => $query, 'category' => $category_name, 'options' => $options,);
}	


// Array list
$staff_desig_array = array(
	"Uddokta" => "উদ্দোক্তা",
	"Office Incharge" => "অফিস ইনচার্জ",
	"Office Assistant" => "অফিস এসিস্ট্যান্ট",
	"Branch Incharge" => "ব্রাঞ্চ ইনচার্জ",
	"Branch Assistant" => "ব্রাঞ্চ এসিস্ট্যান্ট",
	"Clark" => "ক্লার্ক",
	"Others" => "অন্যান্য",
	"admin" => "admin",
	"staff" => "স্টাফ"
);




function baseurl()
{
	$host  = $_SERVER['HTTP_HOST'];
	$host_upper = strtoupper($host);
	$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$baseurl = "https://" . $host . $path . "/";
	return $baseurl;
}
function idcard($it, $id)
{
	global $conn;
	$get_four = "select  $it from students where studentid=$id;";
	$run_four = mysqli_query($conn, $get_four);
	$row_four = mysqli_fetch_array($run_four);
	$itemname = $row_four["$it"];
	return $itemname;
}
function item_name($item)
{
	if ($item == "name") {
		$i_name = "Name";
	} elseif ($item == "class") {
		$i_name = "Class";
	} elseif ($item == "classroll") {
		$i_name = "Roll";
	} elseif ($item == "year") {
		$i_name = "Year";
	} elseif ($item == "fathersname") {
		$i_name = "F. Name";
	} elseif ($item == "mothersname") {
		$i_name = "M. Name";
	} elseif ($item == "divition") {
		$i_name = "Group";
	} elseif ($item == "bloodgroup") {
		$i_name = "Blood Group";
	} elseif ($item == "mobile") {
		$i_name = "Mobile";
	} else {
		$i_name = "Blood Group";
	}
	return $i_name;
}
// double search with start date and end date function
function balace_sheet_search($tablename, $start_date, $end_date)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM $tablename WHERE Date(datetime) BETWEEN '$start_date' AND '$end_date'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
function special($studentid, $myclass, $year, $exam_name)
{
	global $conn;
	$arr = array('Bangla 1st paper', 'Bangla 2nd paper');
	$mrk = 0;
	for ($i = 0; $i <= 1; $i++) {
		$sub_name = $arr[$i];
		$query1 = "select * from tbl_marks where studentid = '$studentid' and class='$myclass' and year='$year' and exam_name='$exam_name' and sub_name='$sub_name'";
		$data1 = mysqli_query($conn, $query1);
		$count = mysqli_num_rows($data1);
		$row = mysqli_fetch_array($data1);
		if ($count == '1') {
			$mrk += $row['total'];
		}
	}
	$mrk = ceil($mrk / 2);
	if ($mrk >= 80 && $mrk <= 100) {
		$sgrd = "A+";
		$spnt = "5";
	} elseif ($mrk >= 70 && $mrk <= 79) {
		$sgrd = "A";
		$spnt = "4";
	} elseif ($mrk >= 60 && $mrk <= 69) {
		$sgrd = "A-";
		$spnt = "3.5";
	} elseif ($mrk >= 50 && $mrk <= 59) {
		$sgrd = "B";
		$spnt = "3";
	} elseif ($mrk >= 40 && $mrk <= 49) {
		$sgrd = "C";
		$gdp = "2";
	} elseif ($mrk >= 33 && $mrk <= 39) {
		$sgrd = "D";
		$spnt = "1";
	} elseif ($mrk >= 0 && $mrk <= 32) {
		$sgrd = "F";
		$spnt = "0";
	}
	//multiple variable return
	return array('mrk' => $mrk, 'sgrd' => $sgrd, 'spnt' => $spnt);
}




// post view by limit 5
function get_posts_limit($tablename, $limit)
{
	global $conn;
	global $update;
	$query = mysqli_query($conn, "SELECT * FROM $tablename LIMIT $limit");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}








// for password protected.
function password_protect($item)
{
	$protect1 = trim(stripslashes($item));
	$protect2 = preg_replace("/[^A-Za-z0-9>&<!#_@+?*-]/", '', $protect1);
	$protect3 = md5($protect2);
	$protect4 = substr($protect3, 3, -6);
	return $protect4;
}
// for field  protected.
function field_protect($item1)
{
	$protect5 = trim(stripslashes($item1));
	$protect6 = htmlspecialchars($protect5);
	return $protect6;
}
// for insert data funtion
function insert_data($mysqli, $array, $table_name)
{
	global  $success;
	$placeholders = array_fill(0, count($array), '?');

	$keys   = array();
	$values = array();
	foreach ($array as $k => $v) {
		$keys[] = $k;
		$values[] = !empty($v) ? $v : null;
	}
	$query = "insert into $table_name " .
		'(' . implode(', ', $keys) . ') values ' .
		'(' . implode(', ', $placeholders) . '); ';
	// insert into fruit (name, color) values (?, ?);    

	$stmt = $mysqli->prepare($query);

	// create a by reference array... 
	$params = array();
	foreach ($array as &$value) {
		$params[] = &$value;
	}
	$types  = array(str_repeat('s', count($params)));
	$values = array_merge($types, $params);

	/*           
   $values = Array
      (
          [0] => ss
          [1] => pineapple
          [2] => purple
      ) 
   */

	call_user_func_array(array($stmt, 'bind_param'), $values);

	$success = $stmt->execute();
	$last_id = $mysqli->insert_id;
	$successmsg = "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Inserted Sucessfully</div>";
	$errormsg = "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>Sorry Data Not Inseted.</div>";
	if ($success) {
		return array('last_id' => $last_id, 'successmsg' => $successmsg);
		// print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Inserted Sucessfully ID = $last_id</div>";
	} else {
		return $errormsg;
	}
}



// post view funtions 
function get_posts($tablename)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}

// post view funtions 
function get_posts_sort($tablename, $sort_col, $sort_type)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename ORDER BY $sort_col $sort_type");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//single condition checking function
function single_condition_select($tablename, $field, $value)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM $tablename where $field = '$value'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//double condition checking function
function double_condition_select($tablename, $s_id, $id, $itemname, $item)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM $tablename where $s_id = '$id' and $itemname = '$item'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//three condition checking function
function three_condition_select($tablename, $classname, $myclass, $id, $roll, $yr, $year)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename where $classname = '$myclass' and $id = $roll and $yr = '$year' ");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//four condition checking function
function four_condition_select($tablename, $classname, $myclass, $id, $roll, $yr, $year, $group, $division)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename where $classname = '$myclass' and $id = $roll and $yr = '$year' and $group = '$division' ");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//NotIn condition checking function
function notin_select($tablename1, $classname, $myclass, $item_name, $tablename2, $sid, $id)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename1 where $classname = '$myclass' and $item_name Not IN (select $item_name from $tablename2 where $sid = $id)");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//Double NotIn condition checking function
function double_notcondition_select($tablename1, $column_name, $column_value, $first_column, $first_value, $second_column, $second_value)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename1 WHERE $column_name = '$column_value' AND $first_column != '$first_value' AND $second_column != '$second_value'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
// Single post view funtions 
function single_post_view($tablename, $post_id)
{
	global $conn;

	if ($tablename == "account_create") {
		$first_field = "id";
	} elseif ($tablename == "students") {
		$first_field = "studentid";
	} elseif ($tablename == "staffs") {
		$first_field = "staffid";
	} elseif ($tablename == "users") {
		$first_field = "userid";
	} elseif ($tablename == "members") {
		$first_field = "id";
	} else {
		echo "No Data selected";
	}


	$query = mysqli_query($conn, "SELECT * FROM $tablename WHERE $first_field='$post_id'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}

// for update post 
function updatethis(array $id, array $values, $tablename)
{
	global $conn;
	$sIDColumn  = key($id);
	$sIDValue   = current($id);
	$arrayValues = $values;
	array_walk($values, function (&$value, $key) {
		$value = "{$key} = '{$value}'";
	});
	$sUpdate = implode(", ", array_values($values));
	$sql        = "UPDATE {$tablename} SET {$sUpdate} WHERE {$sIDColumn} = '{$sIDValue}'";
	$update = mysqli_query($conn, $sql);
	$successmsg = "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Updated Sucessfully</div>";
	$edited_table = $tablename;
	$edited_id = $sIDValue;

	if ($update) {
		return array('edited_table' => $edited_table, 'edited_id' => $edited_id, 'successmsg' => $successmsg);
		print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Updated Sucessfully</div>";
	} else {
		print "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>Data Didn't Updated Unsucessfully</div>";
	}
}

// for update post with double condition
function updatethis_with_double_condition(array $first_condition, array $second_condition, array $values, $tablename)
{
	global $conn;
	$sIDColumn  = key($first_condition);
	$sIDValue   = current($first_condition);
	$second_column  = key($second_condition);
	$second_value   = current($second_condition);
	$arrayValues = $values;
	array_walk($values, function (&$value, $key) {
		$value = "{$key} = '{$value}'";
	});
	$sUpdate = implode(", ", array_values($values));
	$sql = "UPDATE {$tablename} SET {$sUpdate} WHERE {$sIDColumn} = '{$sIDValue}' AND {$second_column} = '{$second_value}'";
	$update = mysqli_query($conn, $sql);
	$successmsg = "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Updated Sucessfully</div>";
	$edited_table = $tablename;
	$edited_id = $sIDValue;

	if ($update) {
		return array('edited_table' => $edited_table, 'edited_id' => $edited_id, 'successmsg' => $successmsg);
		// print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Updated Sucessfully</div>";
	} else {
		print "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>Data Didn't Updated Unsucessfully</div>";
	}
}
// for delete data 
function deletedata($table, $id)
{
	global $conn;
	global $tablename;
	if ($table == "students") {
		$first_field = "studentid";
	} elseif ($table == "teachers") {
		$first_field = "teacherid";
	} elseif ($table == "staffs") {
		$first_field = "staffid";
	} elseif ($table == "users") {
		$first_field = "userid";
	} elseif ($table == "gallery") {
		$first_field = "id";
	} elseif ($table == "elearning") {
		$first_field = "id";
	} elseif ($table == "ckpost") {
		$first_field = "id";
	} else {
		$first_field = "id";
	}
	//Delete Record storage
	date_default_timezone_set("Asia/Dhaka");
	$datetime = date("Y-m-d h:i:sa");
	global $uid;
	$file = '../delete.txt';
	// Open the file to get existing content
	$current = file_get_contents($file);
	// Append a new person to the file
	$current .= "Deleted BY=$uid--" . $datetime . "---" . $table . "---" . $id . "\n";
	// Write the contents back to the file
	file_put_contents($file, $current);

	//Data Delete
	$sql = "DELETE FROM $table WHERE $first_field = '" . $id . "'";
	$result = mysqli_query($conn, $sql);

	if ($result) {
		return array("msg"=>"<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>DELETE Data  Unsucessfully</div>");
	} else {
		return array("msg"=>"<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>DELETE Data  Unsucessfully</div>");
	}
}

function deletemark($table, $id, $id1, $id2, $id3)
{
	global $conn;
	global $tablename;
	$sql = "DELETE FROM $table WHERE class = '$id' and divition= '$id1' and year = '$id2' and sub_name = '$id3'";
	$result = mysqli_query($conn, $sql);

	if ($result) {
		print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>DELETE Data Sucessfully</div>";
	} else {
		print "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>DELETE Data  Unsucessfully</div>";
	}
}
//Search Like value
function search_by_two_column_value($tablename, $field1st, $value1st, $field2nd, $value2nd)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM $tablename where $field1st like '%" . $value1st . "%' OR $field2nd like '%" . $value2nd . "%'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}

//Search Like value
function sum_coloumn($tablename, $column_name)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT SUM($column_name) FROM $tablename");
	return array('query' => $query);
}
//single condition select by sorting
function single_condition_sort($tablename, $field, $value, $sort_field, $sort_type, $limit)
{
	global $conn;
	$limit_sta = "";
	if ($limit != "") {
		$limit_sta = "LIMIT $limit";
	}
	$query = mysqli_query($conn, "SELECT * FROM $tablename where $field = '$value' ORDER BY `$tablename`.`$sort_field` $sort_type $limit_sta");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}

//Sum Coloumn Where
function sum_coloumn_wher($tablename, $column_name, $where_coloumn, $where_value)
{
	global $conn;
	$query = mysqli_query($conn, "SELECT SUM($column_name) as sum$column_name FROM $tablename WHERE $where_coloumn=$where_value");
	return array('query' => $query);
}
//Double NotIn condition checking function
function single_not_double_check($tablename1, $column_name, $column_value, $first_column, $first_value, $not_column, $not_value)
{
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM $tablename1 WHERE $column_name = '$column_value' AND $first_column = '$first_value' AND $not_column != '$not_value'");
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//Custome Query
function manual_query(string $queryck)
{
	global $conn;
	$query = mysqli_query($conn, $queryck);
	$count = mysqli_num_rows($query);
	//multiple variable return
	return array('count' => $count, 'query' => $query);
}
//Custome DELETE Query
function manual_delete_query(string $queryck)
{
	global $conn;
	$query = mysqli_query($conn, $queryck);
	//multiple variable return
}

//Branch Balance Update
function branch_balance_update($branch_id)
{
	$branch_sql = manual_query("SELECT `branch`.*, `branch_account`.`id` AS b_ac_id, `branch_account`.`ac_type`, `branch_account`.`ac_number`, `branch_account`.`current_balance`, `branch_account`.`status` FROM `branch` LEFT JOIN `branch_account` ON `branch`.`id` = `branch_account`.`branch_id` WHERE `branch`.`id`='$branch_id'");
	$ac_balance_update = array();
	$ac_balance_update['cash'] = 0;
	$ac_balance_update['bkash'] = 0;
	$ac_balance_update['nagad'] = 0;
	$ac_balance_update['bank'] = 0;

	while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
		if($branch_res['ac_type'] == "cash"){
			$ac_id = $branch_res['b_ac_id'];
			$account_sum_sql = manual_query("SELECT SUM(earnings) as total_earnings, SUM(expense) as total_expense FROM branch_transection WHERE ac_id ='$ac_id'");
			$account_sum_res = mysqli_fetch_assoc($account_sum_sql['query']);
			$ac_balance_update['cash_earn'] = $account_sum_res['total_earnings'];
			$ac_balance_update['cash_expense'] = $account_sum_res['total_expense'];
			$ac_balance_update['cash'] = $account_sum_res['total_earnings']-$account_sum_res['total_expense'];
			updatethis(array("id"=>$ac_id), array("current_balance"=>$ac_balance_update['cash']), "branch_account");
		}
		if($branch_res['ac_type'] == "bkash"){
			$ac_id = $branch_res['b_ac_id'];
			$account_sum_sql = manual_query("SELECT SUM(earnings) as total_earnings, SUM(expense) as total_expense FROM branch_transection WHERE ac_id ='$ac_id'");
			$account_sum_res = mysqli_fetch_assoc($account_sum_sql['query']);
			$ac_balance_update['bkash_earn'] = $account_sum_res['total_earnings'];
			$ac_balance_update['bkash_expense'] = $account_sum_res['total_expense'];
			$ac_balance_update['bkash'] = $account_sum_res['total_earnings']-$account_sum_res['total_expense'];
			updatethis(array("id"=>$ac_id), array("current_balance"=>$ac_balance_update['bkash']), "branch_account");
		}
		if($branch_res['ac_type'] == "nagad"){
			$ac_id = $branch_res['b_ac_id'];
			$account_sum_sql = manual_query("SELECT SUM(earnings) as total_earnings, SUM(expense) as total_expense FROM branch_transection WHERE ac_id ='$ac_id'");
			$account_sum_res = mysqli_fetch_assoc($account_sum_sql['query']);
			$ac_balance_update['nagad_earn'] = $account_sum_res['total_earnings'];
			$ac_balance_update['nagad_expense'] = $account_sum_res['total_expense'];
			$ac_balance_update['nagad'] = $account_sum_res['total_earnings']-$account_sum_res['total_expense'];
			updatethis(array("id"=>$ac_id), array("current_balance"=>$ac_balance_update['nagad']), "branch_account");
		}
		if($branch_res['ac_type'] == "bank"){
			$ac_id = $branch_res['b_ac_id'];
			$account_sum_sql = manual_query("SELECT SUM(earnings) as total_earnings, SUM(expense) as total_expense FROM branch_transection WHERE ac_id ='$ac_id'");
			$account_sum_res = mysqli_fetch_assoc($account_sum_sql['query']);
			$ac_balance_update['bank_earn'] = $account_sum_res['total_earnings'];
			$ac_balance_update['bank_expense'] = $account_sum_res['total_expense'];
			$ac_balance_update['bank'] = $account_sum_res['total_earnings']-$account_sum_res['total_expense'];
			updatethis(array("id"=>$ac_id), array("current_balance"=>$ac_balance_update['bank']), "branch_account");
		}
	}
	$ac_balance_update['branch_current'] = $ac_balance_update['cash']+ $ac_balance_update['bkash'] + $ac_balance_update['nagad']+ $ac_balance_update['bank'];
	updatethis(array("id"=>$branch_id), array("current_balance"=>$ac_balance_update['branch_current']), "branch");
	return $ac_balance_update;
}

//SMS SENT to single members
function sms_to_member($number_array, $message)
{
	global $datetime;
	$return_message = array();
	
	if (is_array($number_array)) {
		$numbers_count = count($number_array);
		if ($numbers_count > 0 && $message != "") {
			//SMS Part
			// $uname = "nbpersonal";
			// $pass = "U8R3WHYD";			
			$url = "http://139.99.39.237/api/";
			$api_key = ""; //7v8QUuAmaySUFSykXngP
			$senderid = ""; //8809617617912
			$get_sms_sql = manual_query("SELECT * FROM settings WHERE s_category='SMS ID'");
			while ($get_sms_res = mysqli_fetch_assoc($get_sms_sql['query'])) {
				if ($get_sms_res['s_name'] == "sms_api_key") {
					$api_key = $get_sms_res['s_value'];
				} else if ($get_sms_res['s_name'] == "sms_senderid") {
					$senderid = $get_sms_res['s_value'];
				}
			}
			if ($api_key != "" || $senderid != "") {
				$avaliable_balance = 0;
				$get_balance = file_get_contents("{$url}getBalanceApi?api_key={$api_key}");
				$get_balance = json_decode($get_balance, true);
				if ($get_balance['response_code'] == 202) {
					$avaliable_balance = $get_balance['balance'];
				}
				// $available_sms = 300;
				if ($avaliable_balance > 0) {
					$all_numbers = implode(",", $number_array);
					//POST Method example

					$data = [
						"api_key" => $api_key,
						"senderid" => $senderid,
						"number" => $all_numbers,
						"message" => $message
					];
					$sms_sent_url = "{$url}smsapi";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $sms_sent_url);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$response = curl_exec($ch);
					curl_close($ch);
					$response = json_decode($response, true);

					// return $response;
					//Error Codes
					$sms_error_codes = array(
						'1001' => 'Invalid Number',
						'1002' => 'sender id not correct/sender id is disabled',
						'1003' => 'Please Required all fields/Contact Your System Administrator',
						'1005' => 'Internal Error',
						'1006' => 'Balance Validity Not Available',
						'1007' => 'Balance Insufficient',
						'1011' => 'User Id not found',
						'1012' => 'Masking SMS must be sent in Bengali',
						'1013' => 'Sender Id has not found Gateway by api key',
						'1014' => 'Sender Type Name not found using this sender by api key',
						'1015' => 'Sender Id has not found Any Valid Gateway by api key',
						'1016' => 'Sender Type Name Active Price Info not found by this sender id',
						'1017' => 'Sender Type Name Price Info not found by this sender id',
						'1018' => 'The Owner of this (username) Account is disabled',
						'1019' => 'The (sender type name) Price of this (username) Account is disabled',
						'1020' => 'The parent of this account is not found.',
						'1021' => 'The parent active (sender type name) price of this account is not found.',
					);
					// var_dump($response['response_code']);
					// var_dump($sms_error_codes);
					if ($response['response_code'] == '202') {
						$return_message['success'] = "Message Sent Successfully";
					} else {
						if (array_key_exists($response['response_code'], $sms_error_codes)) {
							$return_message['error'] =  $sms_error_codes[$response['response_code']];
						} else {
							$return_message['error'] = "Connection is not possible to establish";
						}
					}
				} else {
					$return_message['error'] = "Insufficiat Balance";
				}
			} else {
				$return_message['error'] = "API Key Or Sender ID not Valid";
			}
		} else {
			$return_message['error'] = "Number OR Mssage invalid";
		}
	} else {
		$return_message['error'] = "Number Format Must be array";
	}
	
	return $return_message;
}

<?php


if (isset($_POST['user_id'])) {

	$new_user_name = $_POST['user_name'];
	$ac_area = $_POST['ac_area'];
	$ac_id = $_POST['ac_id'];
	$online_power = $_POST['online_power'];
	$user_id = $_POST['user_id'];
	// $username_search = manual_query("SELECT * FROM users WHERE userid !='$user_id' AND email='$new_user_name'");
	if ($new_user_name == "" || $_POST['new_password'] == "") {
		echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Empty Field</div>";
	} else {
		if ($_POST['new_password'] == $_POST['re_password']) {
			$username_search = manual_query("SELECT * FROM users WHERE email='$new_user_name' AND userid !='$user_id'");
			if ($username_search['count'] > 0) {
				echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>This Username Already Exist</div>";
			} else {
				$password = password_protect($_POST['new_password']);

				$array  = array(
					"uid" =>  $uid,
					"ip" => $ip,
					"datetime" =>  date("Y-m-d H:i:s", strtotime($datetime)),
					"email" =>  $new_user_name,
					"password" =>  $password,
					// "actype" =>  $ac_area,
					// "ac_area" => $ac_area,
					// "ac_id" => $ac_id,
					"online_power" => $online_power,
				);
				$table_name = "users";

				$inserted = updatethis(array("userid" => $user_id), $array, "users");


				if (isset($inserted['edited_id'])) {
					// $staffid = $inserted['edited_id'];
					echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>সফল ভাবে ইউজার সম্পাদন সম্পন্ন হয়েছে </div>";
				} else {
					echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Data did not Inserted</div>";
				}
			}
		} else {
			echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Password Wrong</div>";
		}
	}
}
$table = 'users';
$name = "No Name Found";
$account_area = "Area Not Selected";
$online_on = "";
$online_off = "";
if (isset($_GET['id'])) {
	$edit_userid = $_GET['id'];

	// $user_sql = double_condition_select("users", "ac_area", "staff", "ac_id", $staffid);
	$user_sql = single_condition_select("users", "userid", $edit_userid);
	if ($user_sql['count'] > 0) {
		$user_res = mysqli_fetch_assoc($user_sql['query']);
		$ac_area = $user_res['ac_area'];
		$ac_id = $user_res['ac_id'];
		$user_id = $user_res['userid'];
		$user_name = $user_res['email'];
		if ($user_res['online_power'] == "on") {
			$online_on = "Selected";
		}
		if ($user_res['online_power'] == "off") {
			$online_off = "Selected";
		}
		if ($user_res['ac_area'] == 'members') {
			$account_area = "Member";
		} elseif ($user_res['ac_area'] == 'staff') {
			$account_area = "Staff";
		}
	} else {
		$ac_area = "staff";
		$ac_id = 0;
		$online_on = "Selected";
	}
}
?>

<div class="row justify-content-md-center mt-5">
	<div class="col-md-5">
		<form action="" method="post" class="ignore_input">
			<?php echo $ignore_csrf_input; ?>
			<div class="row">
				<input type="hidden" name="ac_area" value="<?php echo isset($ac_area) ? $ac_area : ""; ?>">
				<input type="hidden" name="ac_id" value="<?php echo isset($ac_id) ? $ac_id : ""; ?>">
				<input type="hidden" name="user_id" value="<?php echo isset($user_id) ? $user_id : ""; ?>">
				<div class="col-md-12">
					<h3 class="text-center font-bold"><?php echo isset($name) ? $name : ""; ?></h3>
				</div>
				<div class="col-md-12 mt-2">
					<h5 class="text-center"><?php echo isset($account_area) ? $account_area : ""; ?></h5>
				</div>
				<div class="col-md-4 mt-2">
					Email:
				</div>
				<div class="col-md-8 mt-2">
					<input class="form-control" type="email" name="user_name" value="<?php echo isset($user_name) ? $user_name : ""; ?>" required>
				</div>
				<div class="col-md-4 mt-2">
					New password:
				</div>
				<div class="col-md-8 mt-2">
					<input class="form-control" type="text" name="new_password" required>
				</div>
				<div class="col-md-4 mt-2">
					Retype password:
				</div>
				<div class="col-md-8 mt-2">
					<input class="form-control" type="text" name="re_password" required>
				</div>
				<div class="col-md-4 mt-2">
					Online Power:
				</div>
				<div class="col-md-8 mt-2 form-inline">
					<select name="online_power" id="online_power" class="form-control" required>
						<option value="on" <?php echo isset($online_on) ? $online_on : ""; ?>>On</option>
						<option value="off" <?php echo isset($online_off) ? $online_off : ""; ?>>Off</option>
					</select>
				</div>
				<div class="col-md-12 text-center mt-2">
					<input type="submit" value="Submit" class="btn btn-microsoft">
				</div>
		</form>
	</div>
</div>
</div>
<script>

</script>
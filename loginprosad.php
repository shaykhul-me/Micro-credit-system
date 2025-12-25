<?php
session_start();
include 'connect.php';
include 'main_function.php';

if (isset($_SESSION['username'])) {

	header("Location: index.php");
}


if (isset($_POST['submit'])) {
	$loginmsg = ""; //Error For Login Store and print anywhere
	$email = $conn->real_escape_string($_POST['email']);
	$password = $_POST['password'];
	$password = password_protect($_POST['password']);
	$sql = double_condition_select("users", "email", $email, "password", $password);
	
	if ($sql['count'] == 1) {

		$row = mysqli_fetch_assoc($sql['query']);

		$online_power = $row['online_power'];

		if ($online_power == "on") {
			// echo "on";
			// exit;
			$_SESSION["username"] = $row['email'];
			$_SESSION["userid"] = $row['userid'];
			$_SESSION["ac_role"] = $row['actype'];
			// $_SESSION["other_info"] = array(
			// 	"office_id" => "all",
			// 	"office_name" => "all",
			// 	"branch_id" => 0,
			// 	"branch_name" => "All",
			// );


			// print_r($_SESSION);
			// exit;
			header('Location:index.php');
		} else {
			$loginmsg = "<h3 class='text-danger'><center>আপনার একান্টটি এক্টিভ নয়</center></h3>";
		}
	} else {
		$loginmsg = "<h3 class='text-danger'><center>আপনি ভুল ইউজারনেম পাসওয়ার্ড দিয়েছেন</center></h3>";
	}
}

if (isset($_POST['forget_pass'])) {
	$actype = $_POST['actype'];
	$email = $_POST['email'];
	if ($email == "") {
		$loginmsg = "Please Enter Your Email";
	}



	if ($actype == "teacher") {
		$sql = "SELECT * FROM teachers where email='$email'";
	} else if ($actype == "staff") {
		$sql = "SELECT * FROM staffs where email='$email'";
	} else if ($actype == "student") {
		$sql = "SELECT * FROM students where email='$email'";
	} else if ($actype == "guardian") {
		$sql = "SELECT * FROM students where email='$email'";
	} else if ($actype == "admin") {
		$sql = "SELECT * FROM users where email='$email'";
	} else if ($actype == "editor") {
		$sql = "SELECT * FROM users where email='$email'";
	}



	$result = mysqli_query($conn, $sql);
	$rowcount = mysqli_num_rows($result);
	if ($rowcount > 0) {
		$row = mysqli_fetch_array($result);
		$getemail = $row['email'];
		if ($email == $getemail) {
			echo $viewpass = ranpass(8);
			$ranpass = password_protect($viewpass);
			echo "<br>email is matched";
			$testArray  = array("password"  =>  "$ranpass");
			updatethis(array("email" => $getemail), $testArray, "users");
			$to = "$getemail";
			$subject = "This is subject";

			$message = "Your New Password Is:  $viewpass</b>";
			$message .= "<h1>This is headline.</h1>";

			$header = "From:abc@gmail.com \r\n";
			$header .= "Cc:abc@gmail.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";

			$retval = mail($to, $subject, $message, $header);

			if ($retval == true) {
				$loginmsg = "Message sent successfully...";
			} else {
				$loginmsg = "Message could not be sent...";
			}
		}
	} else {
		$loginmsg = "Email does't Matched";
	}
}

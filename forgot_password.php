<?php
include('loginprosad.php');
include('header.php');
$message = '';


if (isset($_POST["submit_user_email"])) {
    if (empty($_POST["email"])) {
        $message = '<div class="alert alert-danger">Email Address required</div>';
    } else {

        // $username = $_POST['username'];
        $email = $_POST['email'];
        $userinfo = single_condition_select("users", "email", $email);
        if ($userinfo['count'] == 1) {
            if ($row = mysqli_fetch_array($userinfo['query'])) {
                // $ac_area = $row['ac_area'];
                // $ac_id = $row['ac_id'];
                // $useremail = single_condition_select($ac_area, "staffid", $ac_id);
                // if ($useremail['count'] == 1) {
                // if ($row2 = mysqli_fetch_array($useremail['query'])) {
                $foundemail = $email;
                if ($foundemail == $email) {
                    $user_otp = rand(100000, 999999);
                    $whereset = array(
                        'email' => $email
                    );
                    $setvalue = array(
                        "user_otp" => $user_otp
                    );
                    $user_update = updatethis($whereset, $setvalue, "users");
                    if ($user_update['edited_id']) {
                        // echo '<script>alert("Please Check Your Email for password reset code")</script>';
                        // echo '<script>window.location.replace("forgot_password.php?step2=1&username=' . $username . '")</script>';
                        require 'class/class.phpmailer.php';
                        $mail = new PHPMailer;
                        try{
                        $mail->IsSMTP();
                        $mail->Host = 'smtpout.secureserver.net';
                        $mail->Port = '80';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'xxxxxxxxxx';
                        $mail->Password = 'xxxxxxxxxx';
                        $mail->SMTPSecure = '';
                        $mail->From = 'tutorial@webslesson.info';
                        $mail->FromName = 'Webslesson';
                        $mail->AddAddress($email);
                        $mail->IsHTML(true);
                        $mail->Subject = 'Password reset request for your account';
                        $message_body = '<p>For reset your password, you have to enter this verification code when prompted: <b>' . $user_otp . '</b>.</p><p>Sincerely,</p>';
                        $mail->Body = $message_body;
                        $mail->Send();
                        // if () {
                            echo '<script>alert("Please Check Your Email for password reset code")</script>';
                            echo '<script>window.location.replace("forgot_password.php?step2=1&email=' . $email . '")</script>';
                        // }
                        }catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            // Log the error for debugging:
                            error_log("PHPMailer Error: " . $e->getMessage());
                        }
                        /*
                            include('smtp/PHPMailerAutoload.php');
                            $mail = new PHPMailer;
                            $mail->IsSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->Port = '587';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'mhmmdsajeeb@gmail.com';
                            $mail->Password = 'ecdh mjpk tvmg hkkf';
                            $mail->SMTPSecure = 'tls';
                            $mail->SetFrom('mhmmdsajeeb@gmail.com');
                            $mail->AddAddress($email);
                            $mail->WordWrap = 50;
                            $mail->IsHTML(true);
                            $mail->Subject = 'Reset Password link';

                            $message_body = '
                            <p>For reset password, please click to given link: <b>'.$url.'</b>.</p>
                            <p>Sincerely,</p>
                            ';
                            $mail->Body = $message_body;

                            if($mail->Send())
                            {
                                $sqlUpdate = "UPDATE user SET reset_code = '".$reset_code."' WHERE email = '".$email."'";
                                $resultUpdate = mysqli_query($conn, $sqlUpdate);
                                if ($resultUpdate) {
                                    echo '<script>alert("Please Check Your Email for reset password")</script>';
                                    header('Refresh:1; url=index.php');
                                }
                                else{
                                    echo "<script>alert('opss something wrong...');</script>";
                                }
                            }
                            else
                            {
                                $message = $mail->ErrorInfo;
                                echo '<script>alert("'.$message.'")</script>';
                            }


                        */
                    }
                } else {
                    $message = '<div class="alert alert-danger">Email Address Wrong</div>';
                }
                // }
                // } else {
                //     $message = "<div class='alert alert-danger'>Email not found</div>";
                // }
            }
        } else {
            $message = "<div class='alert alert-danger'>Email not found</div>";
        }
    }
}

if (isset($_POST["submit_otp"])) {
    if (empty($_POST["check_otp"]) || empty($_POST['email'])) {
        $message = '<div class="alert alert-danger">Enter OTP Number</div>';
    } else {
        $user_name = $_POST['email'];
        $user_otp2 = $_POST['check_otp'];
        $user_otp_check = double_condition_select("users", "email", $user_name, "user_otp", $user_otp2);
        if ($user_otp_check['count'] > 0) {
            echo '<script>window.location.replace("forgot_password.php?step3=1&email=' . $user_name . '&user_otp=' . $user_otp2 . '")</script>';
        } else {
            $message = '<div class="alert alert-danger">Wrong OTP Number</div>';
        }
    }
}

if (isset($_POST["submit_passwrod"])) {
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $username = $_POST['email'];
    $input_otp = $_POST['user_otp'];
    if (empty($new_password) || empty($confirm_password) || empty($username) || empty($input_otp)) {
        $message = '<div class="alert alert-danger">All Field Required</div>';
    } else {
        if ($new_password == $confirm_password) {
            $otp_check_pass = double_condition_select("users", "email", $username, "user_otp", $input_otp);
            if ($otp_check_pass['count'] == 1) {
                if ($row3 = mysqli_fetch_array($otp_check_pass['query'])) {
                    $found_user_id = $row3['userid'];
                }
                $new_pass = password_protect($new_password);
                $find_id = array(
                    'userid' => $found_user_id
                );
                $update_pass_value = array(
                    "user_otp" => "",
                    "password" => $new_pass
                );
                $update_password = updatethis($find_id, $update_pass_value, "users");
                if ($update_password['successmsg']) {
                    echo '<script>window.location.replace("login.php?reset_password=success")</script>';
                    // $message = '<div class="alert alert-success">Passowrd Change Successfully</div>';
                }
            }
        } else {
            $message = '<div class="alert alert-danger">Confirm Password is not match</div>';
        }
    }
}

?>

<div class="content">
    <div class="brand">
        <a class="link" href="index.php">Next Barisal</a>
    </div>
    <?php

    echo $message;

    if (isset($_GET["step1"])) {
    ?>
        <form id="forgot-form" action="" method="post">
            <h3 class="m-t-10 m-b-10">Forgot password</h3>
            <p class="m-b-20">Enter your email address below and we'll send you password reset instructions.</p>
            <!-- <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-user font-16"></i></div>
                    <input class="form-control" type="text" name="username" placeholder="Username" value="complete" required>
                </div>
            </div> -->
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-at font-16"></i></div>
                    <input class="form-control" type="email" name="email" placeholder="email" value="" required>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit" name="submit_user_email">Submit</button>
            </div>
        </form>
    <?php
    }
    if (isset($_GET["step2"])) {

    ?>
    <div class="alert alert-success">OTP sent Sucecssfully</div>
        <form id="forgot-form" action="" method="post">
            <h3 class="m-t-10 m-b-10">Forgot password</h3>
            <p class="m-b-20">Check your email and enter your OTP.</p>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-user font-16"></i></div>
                    <input class="form-control" type="text" name="check_otp" placeholder="OTP" required>
                    <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit" name="submit_otp">Submit</button>
            </div>
        </form>
    <?php
    }
    if (isset($_GET["step3"])) {
    ?>
        <form id="forgot-form" action="" method="post">
            <h3 class="m-t-10 m-b-10">Forgot password</h3>
            <p class="m-b-20">Enter Your New Password</p>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="new_password" placeholder="New Password" required>
                    <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>">
                </div>
                <div class="input-group-icon right mt-2">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <input type="hidden" name="user_otp" value="<?php echo isset($_GET['user_otp']) ? $_GET['user_otp'] : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit" name="submit_passwrod">Submit</button>
            </div>
        </form>
    <?php
    }
    ?>
</div>

<?php include('footer.php') ?>
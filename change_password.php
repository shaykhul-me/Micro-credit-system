<?php

if (isset($_POST["old_password"])) {

    $old_pass =  password_protect($_POST['old_password']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    //Check User previous Value
    $userinfo_row = single_condition_select("users", "userid", $uid);
    if ($row = mysqli_fetch_array($userinfo_row['query'])) {
        $old_pass_from =    $row['password'];
    }
    if ($new_pass == $confirm_pass) {
        $new_pass = password_protect($new_pass);
        if ($old_pass == $old_pass_from) {
            $array  = array(
                "password" =>  $new_pass
            );
            $login_userid = array(
                "userid" => $uid
            );
            $updatepassword = updatethis($login_userid, $array, "users");
            if ($updatepassword['edited_table']) {
                echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Password Updated Successfully</div>";
            } else {
                echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Password didn't Updated Successfully</div>";
            }
        } else {
            echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Old Password Didn't Match</div>";
        }
    } else {
        echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Confirm Password Wrong </div>";
    }
}

?>

<div class="content">
    <form method='post' action='' name='password_change' enctype="multipart/form-data">
        <div class="text-center font-weight-bold font-weight-bolder">
            <h3 class="font-weight-bold font-weight-bolder text-capitalize">Password Change</h3>
        </div>
        <div class="row">
            <label for="old_password" class="col-md-5 mt-2">Old Password<span style="color:red;">**</span></label>
            <div class="col-md-7 mt-2">
                <input type="password" class="form-control" placeholder="Enter Old Password" name='old_password' id="old_password" required>
            </div>
            <label for="new_password" class="col-md-5 mt-2">New Password<span style="color:red;">**</span></label>
            <div class="col-md-7 mt-2">
                <input type="password" class="form-control" placeholder="Enter New Password" name='new_password' id="new_password" required>
            </div>
            <label for="confirm_password" class="col-md-5 pr-0 mt-2">Confirm Password <span style="color:red;">**</span></label>
            <div class="col-md-7 mt-2">
                <input type="password" class="form-control" placeholder="Enter Confirm Password" name='confirm_password' id="password" required>
            </div>
            <div class="col-md-12 mt-2">
                <div class="text-center"><input type="submit" value="Update" class="btn btn-primary"></div>
            </div>
        </div>

    </form>
</div>
<?php
include('header.php');
if (isset($_POST["submit"])) {
    $name =  field_protect($_POST['name']);
    $password =  password_protect($_POST['password']);
    $mobile =  field_protect($_POST['mobile']);
    $email = field_protect($_POST['email']);
    $role =  field_protect($_POST['role']);
    // $photo = $_FILES["fileToUpload"]["name"];
    $photo = "";
    $per = $_POST['per'];
    // Use implode() function to join 
    // comma in the array 
    $permi = implode(', ', $per);
    //print_r($permi);
    $sql = "SELECT email FROM users WHERE email='$email'";
    $query = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);
    if ($count > 0) {
        echo "<center class='alert alert-danger'> Email Already Exists</center>";
    } else {
        if ($photo == "") {
            $photo = "0";
            $isql = "insert into users (uid,datetime,ip,name,password,mobile,email,photo,actype,permission,upid,updatetime,upip) values ('$uid','$datetime','$ip','$name','$password','$mobile','$email','$photo','$role','$permi','$uid','$datetime','$uid')";
            $idata = mysqli_query($conn, $isql);
            if ($idata) {
                print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Inserted Sucessfully</div>";
            } else {
                print "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>Inserted Unsucessfully</div>";
            }
        } else {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    //echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                //echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                //echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                } else {
                    //echo "Sorry, there was an error uploading your file.";
                }
            }
            $isql = "insert into users (uid,datetime,ip,name,password,mobile,email,photo,actype,permission,upid,updatetime,upip) values ('$uid','$datetime','$ip','$name','$password','88$mobile','$email','$photo','$role','$permi','$uid','$datetime','$upip')";
            $idata = mysqli_query($conn, $isql);
            if ($idata) {
                print "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Data Inserted Sucessfully</div>";
            } else {
                print "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>Inserted Unsucessfully</div>";
            }
        }
    }
}
?>

<div class="row">
    <div class="col-2"></div>
    <div class="col-8">

        <form method='post' action='' name='student_add' enctype="multipart/form-data">
            <?php //echo $csrf_input;
            ?>

            <div class="text-center font-weight-bold font-weight-bolder">
                <h3 class="font-weight-bold font-weight-bolder text-capitalize">User Registration Form</h3>
            </div><br>




            <div class="form-row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="name">Name <span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter Name" name='name' required="required">
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="class">Password<span style="color:red;">**</span></label>
                    <input type="password" class="form-control" placeholder="Enter password" name="password" required="required">
                </div>
            </div>



            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Mobile <span style="color:red;">**</span></label>
                    <input type="number" class="form-control" placeholder="Enter Mobile" name='mobile' required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="class">Email<span style="color:red;">**</span></label>
                    <input type="email" class="form-control" placeholder="Enter email" name="email" required="required">
                </div>
            </div>


            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">Role <span style="color:red;">**</span></label>
                    <select name='role' class="form-control" id="select" onchange="return showpermi();">
                        <option value='admin'>Admin</option>
                        <option value='editor'>Editor</option>
                    </select>
                </div>


            </div>
            <div class="form-row">
                <div class="form-group col-md-12">

                    <label for="name">Permissions <span style="color:red;">**</span>
                        <div style="display:none;" id="editor">
                            <?php
                            //collect array from fixed array via javascript
                            $perm_array = $staff_array;
                            foreach ($perm_array as $perm_array) {
                                echo "<input name='per[]' value='$perm_array' type='checkbox' checked='checked' style='width:5%;' />$perm_array";
                            }
                            ?>
                        </div>
                        <div style="display:none;" id="auditor">
                            <?php
                            //collect array from fixed array via javascript
                            $perm_array = $auditor_array;
                            foreach ($perm_array as $perm_array) {
                                echo "<input name='per[]' value='$perm_array' type='checkbox' checked='checked' style='width:5%;' />$perm_array";
                            }
                            ?>
                        </div>
                        <div style="display:none;" id="accountant">
                            <?php
                            //collect array from fixed array via javascript
                            $perm_array = $account_array;
                            foreach ($perm_array as $perm_array) {
                                echo "<input name='per[]' value='$perm_array' type='checkbox' checked='checked' style='width:5%;' />$perm_array";
                            }
                            ?>
                        </div>
                        <div style="display:none;" id="data entry">
                            <?php
                            //collect array from fixed array via javascript
                            $perm_array = $dataentry_array;
                            foreach ($perm_array as $perm_array) {
                                echo "<input name='per[]' value='$perm_array' type='checkbox' checked='checked' style='width:5%;' />$perm_array";
                            }
                            ?>
                        </div>
                        <div style="display:block;" id="admin">
                            <h4>Full Controling Power</h4>
                        </div>
                </div>
            </div>
            <center><input class='btn btn-primary' type='submit' name='submit'></center>

        </form>


    </div>
    <div class="col-2"></div>
</div>
<script>
    function showpermi() {
        var selectBox = document.getElementById('select');
        var userInput = selectBox.options[selectBox.selectedIndex].value;
        if (userInput == 'admin') {
            document.getElementById('admin').style.display = 'block';
            document.getElementById('editor').style.display = 'none';
            document.getElementById('auditor').style.display = 'none';
            document.getElementById('accountant').style.display = 'none';
            document.getElementById('data entry').style.display = 'none';
        }
        if (userInput == 'editor') {
            document.getElementById('admin').style.display = 'none';
            document.getElementById('editor').style.display = 'block';
            document.getElementById('auditor').style.display = 'none';
            document.getElementById('accountant').style.display = 'none';
            document.getElementById('data entry').style.display = 'none';
        }
        if (userInput == 'auditor') {
            document.getElementById('admin').style.display = 'none';
            document.getElementById('editor').style.display = 'none';
            document.getElementById('auditor').style.display = 'block';
            document.getElementById('accountant').style.display = 'none';
            document.getElementById('data entry').style.display = 'none';
        }
        if (userInput == 'accountant') {
            document.getElementById('admin').style.display = 'none';
            document.getElementById('editor').style.display = 'none';
            document.getElementById('auditor').style.display = 'none';
            document.getElementById('accountant').style.display = 'block';
            document.getElementById('data entry').style.display = 'none';
        }
        if (userInput == 'data entry') {
            document.getElementById('admin').style.display = 'none';
            document.getElementById('editor').style.display = 'none';
            document.getElementById('auditor').style.display = 'none';
            document.getElementById('accountant').style.display = 'none';
            document.getElementById('data entry').style.display = 'block';
        }
    }
</script>

<?php
include('footer.php');
?>
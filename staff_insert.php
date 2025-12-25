<?php

if (isset($_POST["submit"]) && $form_success_message === "") {
    $mobile_ck_sql = single_condition_select("staffs", "mobile", $_POST['mobile']);
    if ($mobile_ck_sql['count'] > 0) {
        echo "<div align='center' class='alert alert-danger' style='width:40%; font-size:20px; margin:0 auto;'>এই মোবাইল নাম্বার পূর্বে ব্যবহৃত হয়েছে</div>";
    } else {
        $name =  $_POST['name'];
        $name_bn =  $_POST['name_bangla'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $spause_name = $_POST['spause_name'];
        $nid = $_POST['nid'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $blood = $_POST['blood'];
        $religion = $_POST['religion'];
        $dob = $_POST['dob'];
        $education = $_POST['education'];
        $nominee_name = $_POST['nominee_name'];
        $nominee_nid = $_POST['nominee_nid'];
        $relation = $_POST['nominee_relation'];
        $nominee_mobile = $_POST['nominee_mobile'];
        $status = $_POST['status'];
        $office = isset($_POST['office'])?$_POST['office']:0;
        $branch = isset($_POST['branch'])?$_POST['branch']:0;
        //New From member
        $joining_date = $_POST['joining_date'];
        $position = $_POST['position'];
        $basic_pay = $_POST['basic_pay'];
        $commission = $_POST['commission'];
        // $primary_balance = $_POST['primary_balance'];
        // $primary_due = $_POST['primary_due'];
        //Present Address
        $present_address = $_POST['present_address'];
        $present_upazila = $_POST['present_upazila'];
        $present_district = $_POST['present_district'];
        //Permanent Address
        $permanent_address = $_POST['permanent_address'];
        $permanent_upazila = $_POST['permanent_upazila'];
        $permanent_district = $_POST['permanent_district'];

        //SMS Have to work
        $sms_status = isset($_POST['member_confirm_sms']) ? $_POST['member_confirm_sms'] : 0;

        //Upload Photo info
        $member_img = "";   //Member Photo Default Null
        $m_nid_front = "";   //Member NID Front Photo Default Null
        $m_nid_back = "";   //Member NID Back Photo Default Null
        $m_signature = "";     //Member Signature Default Null
        $nominee_photo = "";     //Nominee Photo Default Null
        $m_nominee_nid_front = "";     //Nominee NID Front
        $m_nominee_nid_back = "";     //Nominee NID BACK
        $target_dir = "assets/images/upload/"; //Default Image Upload Directory
        $allowedtype = array("jpg" => "image/jpg", "jpeg" => "image/jpeg"); //Allowed FIle Type
        $file_error = array();
        $file_error['member_img'] = "";
        $file_error['m_nid_front'] = "";
        $file_error['m_nid_back'] = "";
        $file_error['m_signature'] = "";
        $file_error['nominee_photo'] = "";
        $file_error['m_nominee_nid_front'] = "";
        $file_error['nominee_all_img'] = "";

        //Member Photo Upload
        if ($_FILES['member_img']['size'] > 0) {
            $filename = $_FILES["member_img"]['name']; //Uploaded File name
            $filetype = $_FILES["member_img"]["type"]; //Uploaded file Type
            $filesize = $_FILES["member_img"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $staff_img_name = "member_img_" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_staff_img_name = $target_dir . $staff_img_name . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['member_img'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['member_img'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_staff_img_name)) {     //Check File exist
                $file_error['member_img'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['member_img'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["member_img"]["tmp_name"], $final_staff_img_name)) {
                        $member_img = $final_staff_img_name; //Photo Name with extention set
                    }
                }
            }
        }
        //Staff NID Front image Upload
        if ($_FILES["m_nid_front"]['size'] > 0) {
            $filename = $_FILES["m_nid_front"]['name']; //Uploaded File name
            $filetype = $_FILES["m_nid_front"]["type"]; //Uploaded file Type
            $filesize = $_FILES["m_nid_front"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $staff_nid_img = "member_nid_front" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_staff_nid_img = $target_dir . $staff_nid_img . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['m_nid_front'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['m_nid_front'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_staff_nid_img)) {     //Check File exist
                $file_error['m_nid_front'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['m_nid_front'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["m_nid_front"]["tmp_name"], $final_staff_nid_img)) {
                        $m_nid_front = $final_staff_nid_img; //Photo Name with extention set
                    }
                }
            }
        }
        //Staff NID Back image Upload
        if ($_FILES["m_nid_back"]['size'] > 0) {
            $filename = $_FILES["m_nid_back"]['name']; //Uploaded File name
            $filetype = $_FILES["m_nid_back"]["type"]; //Uploaded file Type
            $filesize = $_FILES["m_nid_back"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $staff_nid_img = "member_nid_back" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_staff_nid_img = $target_dir . $staff_nid_img . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['m_nid_back'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['m_nid_back'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_staff_nid_img)) {     //Check File exist
                $file_error['m_nid_back'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['m_nid_back'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["m_nid_back"]["tmp_name"], $final_staff_nid_img)) {
                        $m_nid_back = $final_staff_nid_img; //Photo Name with extention set
                    }
                }
            }
        }
        //Member Signature image Upload
        if ($_FILES["m_signature"]['size'] > 0) {
            $filename = $_FILES["m_signature"]['name']; //Uploaded File name
            $filetype = $_FILES["m_signature"]["type"]; //Uploaded file Type
            $filesize = $_FILES["m_signature"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $staff_nid_img = "member_signature" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_staff_nid_img = $target_dir . $staff_nid_img . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['m_signature'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['m_signature'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_staff_nid_img)) {     //Check File exist
                $file_error['m_signature'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['m_signature'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["m_signature"]["tmp_name"], $final_staff_nid_img)) {
                        $m_signature = $final_staff_nid_img; //Photo Name with extention set
                    }
                }
            }
        }
        //Nominee image Upload
        if ($_FILES["nominee_photo"]['size'] > 0) {
            $filename = $_FILES["nominee_photo"]['name']; //Uploaded File name
            $filetype = $_FILES["nominee_photo"]["type"]; //Uploaded file Type
            $filesize = $_FILES["nominee_photo"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $nominee_img_name = "member_nominee_photo_" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_nominee_img_name = $target_dir . $nominee_img_name . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['nominee_photo'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['nominee_photo'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_nominee_img_name)) {     //Check File exist
                $file_error['nominee_photo'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['nominee_photo'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["nominee_photo"]["tmp_name"], $final_nominee_img_name)) {
                        $nominee_photo = $final_nominee_img_name; //Photo Name with extention set
                    }
                }
            }
        }
        //Nominee NID FRONT
        if ($_FILES["nominee_nid_front"]['size'] > 0) {
            $filename = $_FILES["nominee_nid_front"]['name']; //Uploaded File name
            $filetype = $_FILES["nominee_nid_front"]["type"]; //Uploaded file Type
            $filesize = $_FILES["nominee_nid_front"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $nominee_nid_front = "m_nominee_nid_f_" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_nominee_nid_front = $target_dir . $nominee_nid_front . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['m_nominee_nid_front'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['m_nominee_nid_front'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_nominee_nid_front)) {     //Check File exist
                $file_error['m_nominee_nid_front'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['m_nominee_nid_front'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["nominee_nid_front"]["tmp_name"], $final_nominee_nid_front)) {
                        $m_nominee_nid_front = $final_nominee_nid_front; //Photo Name with extention set
                    }
                }
            }
        }
        //Nominee NID Back
        if ($_FILES["nominee_nid_back"]['size'] > 0) {
            $filename = $_FILES["nominee_nid_back"]['name']; //Uploaded File name
            $filetype = $_FILES["nominee_nid_back"]["type"]; //Uploaded file Type
            $filesize = $_FILES["nominee_nid_back"]['size']; //Uploaded File size
            $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
            $staffform = "m_nominee_nid_b_" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
            $final_staffform = $target_dir . $staffform . "." . $file_ext;
            if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                $file_error['m_nominee_nid_back'] = "Only JPG and JPEG photo allowed";
            }
            if ($filesize > $maxfilesize) {                 //Check File Size
                $file_error['m_nominee_nid_back'] = "Maximum allowed File uploaded size 1 MB";
            }
            if (file_exists($final_staffform)) {     //Check File exist
                $file_error['m_nominee_nid_back'] = "Sorry File Already Exist! Please try Again";
            }
            if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                if (empty($file_error['m_nominee_nid_back'])) {            //Move Upload Files to directory if No error
                    if (move_uploaded_file($_FILES["nominee_nid_back"]["tmp_name"], $final_staffform)) {
                        $m_nominee_nid_back = $final_staffform; //Photo Name with extention set
                    }
                }
            }
        }

        // Start Value insert to database
        $array  = array(
            "created_by" =>  $uid,
            "created_ip" => "$ip",
            "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
            "name" =>  $name,
            "name_bn" =>  $name_bn,
            "father_name" => $fname,
            "mother_name" => $mname,
            "spouse_name" => $spause_name,
            "email" => $email,
            "mobile" =>  $mobile,
            "nid" => $nid,
            "gender" => $gender,
            "date_of_birth" => $dob,
            "blood_group" => $blood,
            "religion" =>  $religion,
            "educational_qualification" =>  $education,
            "nominee_name" => $nominee_name,
            "nominee_nid_no" => $nominee_nid,
            "nominee_relation" => $relation,
            "nominee_mobile" => $nominee_mobile,
            "status" => $status,
            "office" => $office,
            "branch" => $branch,
            "joining_date" => $joining_date,
            "position" => $position,
            "basic_pay" => $basic_pay,
            "commission" => $commission,
            "present_address" => $present_address,
            "present_thana" => $present_upazila,
            "present_district" => $present_district,
            "permanent_address" => $permanent_address,
            "permanent_thana" => $permanent_upazila,
            "permanent_district" => $permanent_district,
            "staff_photo" => $member_img,
            "staff_nid_front" => $m_nid_front,
            "staff_nid_back" => $m_nid_back,
            "staff_signature" => $m_signature,
            "s_nominee_img" => $nominee_photo,
            "s_nominee_nid_front" => $m_nominee_nid_front,
            "s_nominee_nid_back" => $m_nominee_nid_back,
        );
        $table_name = "staffs";

        $inserted = insert_data($conn, $array, $table_name);


        if (isset($inserted['last_id'])) {
            $staffid = $inserted['last_id'];

            // $actual_link = "index.php?action=staff_insert";
            // $_SESSION['msg'] = "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>সফল ভাবে নতুন স্টার্ফ তৈরী সম্পূর্ণ হয়েছে </div>";
            // echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
            // exit;
            
            $form_success_message =  "submission('$default_redirect_url', 'Status', 'সফল ভাবে নতুন স্টার্ফ তৈরী সম্পূর্ণ হয়েছে')";
        } else {
            echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>দুঃখিত! নতুন স্টার্ফ তৈরী করা সম্ভব নয়</div>";
        }
    }
}

?>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#member_img_show').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function nidFront(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#m_nid_front_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function nidBack(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#m_nid_back_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function signature(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#m_signature_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function nomineeImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#nominee_photo_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function n_nidfront(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#nominee_nid_front_img_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function n_nidback(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#nominee_nid_back_img_show')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_SESSION['sms'])) {
    echo $_SESSION['sms'];
    unset($_SESSION['sms']);
}
?>
<div class="row">
    <!-- <div class="col-1"></div> -->
    <div class="col-12">
        <form method='post' action='' name='student_add' enctype="multipart/form-data">
            <div class="text-center font-weight-bold font-weight-bolder">
                <h3 class="font-weight-bold font-weight-bolder text-capitalize">নতুন স্টাফ আবেদন ফরম</h3>
            </div><br>
            <div class="col-12">
                <h3 class="text-center">ব্যক্তিগত তথ্য</h3>
                <hr>
            </div>
            <!-- Name And Name (Bangla) -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="staffname">নাম (ইংরেজী)<span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter Name" name='name' id="staffname" required value="">
                </div>
                <div class="form-group col-md-6">
                    <label for="name_bangla">নাম (বাংলা)<span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter Name (বাংলা)" name='name_bangla' value="" id="name_bangla">
                </div>
            </div>
            <!-- Father's Name and Mother's Name -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="class">পিতার নাম</label>
                    <input type="text" class="form-control" placeholder="Enter Father's Name" name="fname">
                </div>
                <div class="form-group col-md-6">
                    <label for="name">মাতার নাম</label>
                    <input type="text" class="form-control" placeholder="Enter Mother's Name" name='mname'>
                </div>
            </div>
            <!-- Spouse's Name And NID -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="spause_name">স্ত্রীর নাম</label>
                    <input type="text" class="form-control" placeholder="Enter Spause's Name" name='spause_name' id="spause_name" value="">
                </div>
                <div class="form-group col-md-6">
                    <label for="class">এন,আই,ডি<span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter National ID" name='nid' required value="">
                </div>
            </div>
            <!-- Email And Cell Phone -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">ইমেইল</label>
                    <input type="email" class="form-control" placeholder="Enter Email" name='email' value="">
                </div>
                <div class="form-group col-md-6">
                    <label for="name">মোবাইল <span style="color:red;">**</span></label> <br>
                    <div class="form-inline">
                        <input class="form-control col-sm-1 col-2 pr-0" name="textfield" readonly="readonly" type="text" style="width:50px;display: inline;" value="+88">
                        <input class="form-control col-sm-11 col-10" name="mobile" type="text" size="11" placeholder="Enter Mobile Number" required value="">
                    </div>
                </div>
            </div>
            <!-- Blood Group and religion -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">রক্তের গ্রুপ</label>
                    <select class="form-control" placeholder="Enter Blood Group" name='blood'>
                        <option value="">Select Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="class">ধর্ম</label>
                    <select class="form-control" placeholder="Enter Religion" name='religion'>
                        <option value="">Select Religion</option>
                        <option value="Islam">Islam</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Christianity">Christianity</option>
                        <option value="Other ">Other</option>
                    </select>
                </div>
            </div>
            <!-- Date of Birth And Educational Qualification -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">জন্ম তারিখ</label>
                    <input type="date" class="form-control" placeholder="Enter Date of Birth" name='dob'>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">শিক্ষাগত যোগ্যতা</label>
                    <input type="text" class="form-control" placeholder="Enter Educational Qualification" name='education'>
                </div>
            </div>
            <!-- Nominee Name && Nominee NID Number -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_name">নমিনীর নাম</label>
                    <input type="text" class="form-control" placeholder="Enter Nominee Name" name='nominee_name' id="nominee_name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_nid">নমিনীর এন,আই,ডি</label>
                    <input type="text" class="form-control" placeholder="Enter Nominee NID" name='nominee_nid' id="nominee_nid" required>
                </div>
            </div>
            <!-- Relationship with Nominee && status-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_relation">নমিনীর সাথে সম্পর্ক</label>
                    <input type="text" class="form-control" placeholder="Enter Nominee Relation" name='nominee_relation' id="nominee_relation" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_mobile">নমিনীর মোবাইল</label>
                    <input type="text" class="form-control" placeholder="Enter Nominee Mobile" name='nominee_mobile' id="nominee_mobile" required>
                </div>
            </div>
            <!-- Joinning Date && Position-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="joining_date">যোগদানের তারিখ</label>
                    <input type="date" class="form-control" placeholder="Joining Date" name='joining_date' id="joining_date">
                </div>
                <div class="form-group col-md-6">
                    <label for="position">পদবি</label>
                    <select name="position" class="form-control" required="required" id="position" required>
                        <option value="">Select Position</option>
                        <option value="Uddokta">উদ্দোক্তা</option>
                        <option value="Office Incharge">অফিস ইনচার্জ</option>
                        <option value="Office Assistant">অফিস এসিস্ট্যান্ট</option>
                        <option value="Branch Incharge">ব্রাঞ্চ ইনচার্জ</option>
                        <option value="Branch Assistant">ব্রাঞ্চ এসিস্ট্যান্ট</option>
                        <option value="Clark">ক্লার্ক</option>
                        <option value="Others">অন্যান্য</option>
                    </select>
                </div>
            </div>
            <!-- Gender && Status && Reference-->
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="gender">লিঙ্গ<span style="color:red;">**</span></label>
                    <select name="gender" class="form-control" required="required" id="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">স্টাফ ধরণ<span style="color:red;">**</span></label>
                    <select name="status" class="form-control" required="required" id="status">
                        <option value="">- Select -</option>
                        <option value="active">নিয়মিত</option>
                        <option value="inactive">অনিয়মিত</option>
                        <option value="rejected">বাতিল</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="basic_pay">বেসিক পে</label>
                    <input type="number" class="form-control" placeholder="Basic Pay" name='basic_pay' id="basic_pay">
                </div>
                <div class="form-group col-md-3">
                    <label for="commission">কমিশন</label>
                    <input type="number" class="form-control" placeholder="Basic Pay" name='commission' id="commission">
                </div>
            </div>
            <!-- Office && Branch -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="office">অফিস <span style="color:red;">**</span></label>
                    <select name="office" class="form-control" required id="office">
                        <option value="">অফিস</option>
                        <?php
                        $office_sql = get_posts_sort("office", "name", "ASC");
                        $office_array = array();
                        while ($office_res = mysqli_fetch_assoc($office_sql['query'])) {
                            $office_array[] = $office_res['id'];
                            echo "<option value='{$office_res['id']}'>{$office_res['name']}</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="form-group col-md-6">
                    <label for="branch">ব্রাঞ্চ <span style="color:red;">**</span></label>
                    <select name="branch" class="form-control" required id="branch" disabled>
                        <option value="">Select Branch</option>
                    </select>
                </div>
            </div>
            <!-- Primary Balance && Primary DUE -->
            <!-- <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="primary_balance">Primary Balance</label>
                    <input type="text" class="form-control" name='primary_balance' id="primary_balance">
                </div>
                <div class="form-group col-md-6">
                    <label for="primary_due">Primary Due</label>
                    <input type="text" class="form-control" name='primary_due' id="primary_due">
                </div>
            </div> -->
            <div class="col-12">
                <h3 class="text-center">ঠিকানা</h3>
                <hr>
            </div>
            <div class="col-12 pl-0">
                <h5 class="text-leff ml-0 pl-0">বার্তমান</h5>
            </div>
            <!-- Present Address -->
            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="name">পাড়া/গ্রাম/পোষ্ট</label>
                    <input type="text" class="form-control" placeholder="Enter Present Address" name='present_address' id="present_address">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">উপজেলা</label>
                    <input type="text" class="form-control" placeholder="Enter Present Upazila" name='present_upazila' id="present_upazila">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">জেলা</label>
                    <select name="present_district" id="present_district" class="form-control">
                        <option value="">Select</option>
                        <option value="Barguna">Barguna</option>
                        <option value="Barishal">Barishal </option>
                        <option value="Bhola">Bhola </option>
                        <option value="Jhalokati">Jhalokati</option>
                        <option value="Patuakhali">Patuakhali </option>
                        <option value="Pirojpur">Pirojpur</option>
                        <option value="Bandarban">Bandarban</option>
                        <option value="Brahmanbaria">Brahmanbaria</option>
                        <option value="Chandpur">Chandpur </option>
                        <option value="Chattogram">Chattogram </option>
                        <option value="Cumilla">Cumilla</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Feni">Feni </option>
                        <option value="Khagrachhari">Khagrachhari</option>
                        <option value="Lakshmipur">Lakshmipur</option>
                        <option value="Noakhali">Noakhali</option>
                        <option value="Rangamati">Rangamati </option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Faridpur">Faridpur</option>
                        <option value="Gazipur">Gazipur</option>
                        <option value="Gopalganj">Gopalganj</option>
                        <option value="Kishoreganj">Kishoreganj</option>
                        <option value="Madaripur">Madaripur</option>
                        <option value="Manikganj">Manikganj</option>
                        <option value="Munshiganj">Munshiganj</option>
                        <option value="Narayanganj">Narayanganj</option>
                        <option value="Narsingdi">Narsingdi </option>
                        <option value="Rajbari">Rajbari</option>
                        <option value="Shariatpur">Shariatpur</option>
                        <option value="Tangail">Tangail</option>
                        <option value="Bagerhat">Bagerhat</option>
                        <option value="Chuadanga">Chuadanga</option>
                        <option value="Jessore">Jessore</option>
                        <option value="Jhenaidah">Jhenaidah</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Kushtia">Kushtia</option>
                        <option value="Magura">Magura</option>
                        <option value="Meherpur">Meherpur</option>
                        <option value="Narail">Narail</option>
                        <option value="Satkhira">Satkhira</option>
                        <option value="Jamalpur">Jamalpur</option>
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Netrokona">Netrokona</option>
                        <option value="Sherpur">Sherpur</option>
                        <option value="Bogra">Bogra</option>
                        <option value="Joypurhat">Joypurhat</option>
                        <option value="Naogaon">Naogaon</option>
                        <option value="Natore">Natore</option>
                        <option value="Chapai Nawabganj">Chapai Nawabganj</option>
                        <option value="Pabna">Pabna</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Sirajganj">Sirajganj</option>
                        <option value="Dinajpur">Dinajpur</option>
                        <option value="Gaibandha">Gaibandha </option>
                        <option value="Kurigram">Kurigram</option>
                        <option value="Lalmonirhat">Lalmonirhat</option>
                        <option value="Nilphamari">Nilphamari</option>
                        <option value="Panchagarh">Panchagarh </option>
                        <option value="Rangpur ">Rangpur </option>
                        <option value="Thakurgaon">Thakurgaon</option>
                        <option value="Habiganj">Habiganj</option>
                        <option value="Moulvibazar">Moulvibazar</option>
                        <option value="Sunamganj">Sunamganj</option>
                        <option value="Sylhet">Sylhet </option>
                    </select>
                </div>
            </div>
            <div class="col-12 pl-0">
                <div class="text-leff">
                    <span class="h5">স্থায়ী </span>
                    <!-- </div>
                <div class="form-check"> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" value="" id="sameaspresent">
                    <label class="form-check-label" for="sameaspresent">
                        স্থায়ী ঠিকানা ও বর্তমান ঠিকানা একই
                    </label>
                </div>
            </div>
            <!-- Permanent Address -->
            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="name">পাড়া/গ্রাম/পোষ্ট</label>
                    <input type="text" class="form-control" placeholder="Enter permanent Address" name='permanent_address' id="permanent_address">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">উপজেলা</label>
                    <input type="text" class="form-control" placeholder="Enter permanent Upazila" name='permanent_upazila' id="permanent_upazila">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">জেলা</label>
                    <select name="permanent_district" id="permanent_district" class="form-control">
                        <option value="">Select</option>
                        <option value="Barguna">Barguna</option>
                        <option value="Barishal">Barishal </option>
                        <option value="Bhola">Bhola </option>
                        <option value="Jhalokati">Jhalokati</option>
                        <option value="Patuakhali">Patuakhali </option>
                        <option value="Pirojpur">Pirojpur</option>
                        <option value="Bandarban">Bandarban</option>
                        <option value="Brahmanbaria">Brahmanbaria</option>
                        <option value="Chandpur">Chandpur </option>
                        <option value="Chattogram">Chattogram </option>
                        <option value="Cumilla">Cumilla</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Feni">Feni </option>
                        <option value="Khagrachhari">Khagrachhari</option>
                        <option value="Lakshmipur">Lakshmipur</option>
                        <option value="Noakhali">Noakhali</option>
                        <option value="Rangamati">Rangamati </option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Faridpur">Faridpur</option>
                        <option value="Gazipur">Gazipur</option>
                        <option value="Gopalganj">Gopalganj</option>
                        <option value="Kishoreganj">Kishoreganj</option>
                        <option value="Madaripur">Madaripur</option>
                        <option value="Manikganj">Manikganj</option>
                        <option value="Munshiganj">Munshiganj</option>
                        <option value="Narayanganj">Narayanganj</option>
                        <option value="Narsingdi">Narsingdi </option>
                        <option value="Rajbari">Rajbari</option>
                        <option value="Shariatpur">Shariatpur</option>
                        <option value="Tangail">Tangail</option>
                        <option value="Bagerhat">Bagerhat</option>
                        <option value="Chuadanga">Chuadanga</option>
                        <option value="Jessore">Jessore</option>
                        <option value="Jhenaidah">Jhenaidah</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Kushtia">Kushtia</option>
                        <option value="Magura">Magura</option>
                        <option value="Meherpur">Meherpur</option>
                        <option value="Narail">Narail</option>
                        <option value="Satkhira">Satkhira</option>
                        <option value="Jamalpur">Jamalpur</option>
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Netrokona">Netrokona</option>
                        <option value="Sherpur">Sherpur</option>
                        <option value="Bogra">Bogra</option>
                        <option value="Joypurhat">Joypurhat</option>
                        <option value="Naogaon">Naogaon</option>
                        <option value="Natore">Natore</option>
                        <option value="Chapai Nawabganj">Chapai Nawabganj</option>
                        <option value="Pabna">Pabna</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Sirajganj">Sirajganj</option>
                        <option value="Dinajpur">Dinajpur</option>
                        <option value="Gaibandha">Gaibandha </option>
                        <option value="Kurigram">Kurigram</option>
                        <option value="Lalmonirhat">Lalmonirhat</option>
                        <option value="Nilphamari">Nilphamari</option>
                        <option value="Panchagarh">Panchagarh </option>
                        <option value="Rangpur ">Rangpur </option>
                        <option value="Thakurgaon">Thakurgaon</option>
                        <option value="Habiganj">Habiganj</option>
                        <option value="Moulvibazar">Moulvibazar</option>
                        <option value="Sunamganj">Sunamganj</option>
                        <option value="Sylhet">Sylhet </option>
                    </select>
                </div>
            </div>
            <!--<div class="col-12">
                <h3 class="text-center">User Login Information</h3>
                <hr>
            </div>
             User Name && Password && Role -->
            <!-- <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="new_username">Username <span id="usermsg" class="alert"></span></label>
                    <input type="text" class="form-control" placeholder="Enter Username" name='new_username' id="new_username" autocomplete="off" value="">
                </div>
                <div class="form-group col-md-4">
                    <label for="new_password">Password </label>
                    <input type="password" class="form-control" placeholder="Enter password" name="new_password" id="new_password" value="" autocomplete="off">
                </div>
                <div class="form-group col-md-4">
                    <label for="name">Role </label>
                    <select name='actype' class="form-control">
                        <option value='member'>Member</option>
                    </select>
                </div>
            </div> -->
            <!-- Photo & NID & Nominee Photo & Nominee NID -->
            <div class="form-row">
                <div class="form-group col-md-3 mb-2 border">
                    <label for="member_img">মেম্বার ফটো</label>
                    <div class="text-center"><img height='100px' class="photo_show" src="assets/images/img/demo.jpg" alt="your image" id="member_img_show"></div>
                    <!-- <input name="member_img" id="member_img" class="inputphoto" type="file" accept="image/*" onchange="readURL(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="member_img" name="member_img" onchange="readURL(this);">
                        <label class="custom-file-label" for="member_img">মেম্বার ফটো</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 border">
                    <label for="m_nid_front">মেম্বার এন,আই,ডি সামনের অংশ</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="assets/img/nid_front.jpg" alt="Member NID Front" id="m_nid_front_show">
                    </div>
                    <!-- <input id="m_nid_front" type="file" accept="image/*" name="m_nid_front" onchange="nidFront(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="m_nid_front" name="m_nid_front" onchange="nidFront(this);">
                        <label class="custom-file-label" for="m_nid_front">মেম্বার এন,আই,ডি সামনের অংশ</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 border">
                    <label for="m_nid_back">মেম্বার এন,আই,ডি পিছনের অংশ</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="assets/img/nid_back.jpg" alt="Member NID Front" id="m_nid_back_show">
                    </div>
                    <!-- <input id="m_nid_back" type="file" accept="image/*" name="m_nid_back" onchange="nidBack(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="m_nid_back" name="m_nid_back" onchange="nidBack(this);">
                        <label class="custom-file-label" for="m_nid_back">মেম্বার এন,আই,ডি পিছনের অংশ</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 border">
                    <label for="m_signature">মেম্বার স্বাক্ষর</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" width='100px' src="assets/images/img/sign.png" alt="Member NID Front" id="m_signature_show">
                    </div>
                    <!-- <input id="m_signature" type="file" accept="image/*" name="m_signature" onchange="signature(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="m_signature" name="m_signature" onchange="signature(this);">
                        <label class="custom-file-label" for="m_signature">মেম্বার স্বাক্ষর</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 mt-3 border">
                    <label for="nominee_photo">নমিনী ছবি</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="assets/images/img/demo.jpg" alt="your image" id="nominee_photo_show">
                    </div>
                    <!-- <input name="nominee_photo" id="nominee_photo" type="file" accept="image/*" onchange="nomineeImg(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="nominee_photo" name="nominee_photo" onchange="nomineeImg(this);">
                        <label class="custom-file-label" for="nominee_photo">নমিনী ছবি</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 mt-3 border">
                    <label for="nominee_nid_front">নমিনী এন,আই,ডি সামনের অংশ</label>
                    <div class="text-center">
                        <img class="photo_show rounded" height='100px' src="assets/img/nid_front.jpg" alt="Member form" id="nominee_nid_front_img_show">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="nominee_nid_front" name="nominee_nid_front" onchange="n_nidfront(this);">
                        <label class="custom-file-label" for="nominee_nid_front">নমিনী এন,আই,ডি সামনের অংশ</label>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-2 mt-3 border">
                    <label for="nominee_nid_back">নমিনী এন,আই,ডি পিছনের অংশ</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="assets/img/nid_back.jpg" alt="your image" id="nominee_nid_back_img_show">
                    </div>
                    <!-- <input name="nominee_all_img" id="nominee_all_img" type="file" accept="image/*" multiple> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="nominee_nid_back" name="nominee_nid_back" onchange="n_nidback(this);">
                        <label class="custom-file-label" for="nominee_nid_back">নমিনী এন,আই,ডি পিছনের অংশ</label>
                    </div>
                </div>
            </div>
            <!-- <div class="text-center">
                <div class="form-check">
                    <input id="member_confirm_sms" class="form-check-input" type="checkbox" name="member_confirm_sms" value="1">
                    <label for="member_confirm_sms" class="form-check-label">Send Confirmation SMS</label>
                </div>
            </div> -->
                <?php echo isset($csrf_input)?$csrf_input:"";?>
            <div class="text-center mt-3"><input class='btn btn-primary' type='submit' name='submit'></div>
        </form>


    </div>
    <!-- <div class="col-1"></div> -->
</div>
<script>
    $(document).ready(function() {
        $("#new_username").on('keyup change', function() {
            $username = $(this).val();
            $.ajax({
                type: "post",
                url: "controllers/index.php",
                data: {
                    'checkuser': $username
                },
                success: function(response) {
                    response = JSON.parse(response);
                    // console.log(response);
                    if (response.exist) {
                        $("#usermsg").html(response.exist);
                    } else {
                        $("#usermsg").html(response.available);
                    };
                },
                error: function(response) {
                    console.log(response);
                }
            });
        })

        $("#sameaspresent").on("click", function() {
            if ($(this).prop("checked")) {
                $("#permanent_address").val($("#present_address").val());
                $("#permanent_upazila").val($("#present_upazila").val());
                $("#permanent_district").val($("#present_district").val());
            } else {
                $("#permanent_address").val('');
                $("#permanent_upazila").val('');
                $("#permanent_district").val('');
            }
        })

        function valempty() {
            $('#reference_id').val("");
            $('#staff_photo').attr("src", 'assets/images/img/demo.jpg');
        };
        $("#reference").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "controllers/index.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        searchrefer: request.term
                    },
                    success: function(data) {
                        valempty();
                        response(data);
                    },
                    error: function(d) {
                        // console.log(d);
                    }
                });
            },
            select: function(event, ui) {
                $('#staff_photo').attr("src", ui.item.image);
                $('#reference').val(ui.item.label);
                $('#reference_id').val(ui.item.value);
                return false;
            }
        });
        //Office Change Branch Appear Start
        var branch = new Array();
        <?php
        foreach ($office_array as $key => $value) {
            $branch_sql = single_condition_sort("branch", "office_code", "$value", "branch_name", "ASC", "");

            $options = "";
            while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
                $options .= "<option value=\"{$branch_res['id']}\">{$branch_res['branch_name']}</option>";
            }
            echo "branch[{$value}]='$options';";
        }
        ?>
        $("#office").on("change", function() {
            if ($(this).val() == "") {
                $("#branch").val("").attr('disabled', 'disabled')
            } else {
                $('#branch').attr('disabled', false).children('option:not(:first)').remove();
                var id = $(this).val();
                $("#branch").append(branch[id]);
            }
        })

        $("#position").on("change", function(){
            var check_position = $(this).val();
            if(check_position =="Uddokta"){
                $("#office").attr("required", false)
                $("#branch").attr("required", false)
            }else if(check_position =="Office Incharge"){
                $("#office").attr("required", true)
                $("#branch").attr("required", false)
            }else if(check_position =="Office Assistant"){
                $("#office").attr("required", true)
                $("#branch").attr("required", false)
            }else{
                $("#office").attr("required", true)
                $("#branch").attr("required", true)                
            }
        })

    });
</script>
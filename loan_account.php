<?php

// if (isset($_POST["submit"]) && $form_success_message === "") {
if (isset($_POST["submit"])) {

            $name =  $_POST['name'];
            $name_bn =  $_POST['name_bangla'];
            $fname = $_POST['fname'];
            $mname = $_POST['mname'];
            $spause_name = $_POST['spause_name'];
            $nid = $_POST['nid'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $dob = $_POST['dob'];
            $nominee_name = $_POST['nominee_name'];
            $nominee_nid_no = $_POST['nominee_nid_no'];
            $relation = $_POST['nominee_relation'];
            $nominee_mobile = $_POST['nominee_mobile'];
            // Loan
            $loan_type = $_POST['loan_type'];
            $loan_no = $_POST['loan_no'];
// Loan no check
$check_loan_account = single_condition_select("loan_account", "loan_no", $_POST['loan_no_search']);
//print_r($check_account);
if ($check_loan_account['count'] > 0 ) {
            
             echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Sorry! Loan No Already Exist. </div>";
            exit();
            
}
            
            $product_price = $_POST['product_price'];
            $profit_percent = $_POST['profit_percent'];
            $net_amount = $_POST['net_amount'];
            $total_installment = $_POST['total_installment'];
            $taka_per_installment = $_POST['taka_per_installment'];
            $actual_per_installment = $_POST['actual_per_installment'];
			$profit_per_inatallment = $_POST['profit_per_inatallment'];
            $status = "Pending";
            $comment = $_POST['comment'];
            
            
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
            $m_nid = "";   //Member NID Front Photo Default Null
            $m_signature = "";     //Member Signature Default Null
            $nominee_photo = "";     //Nominee Photo Default Null
            $m_nominee = "";     //Nominee NID Front
            $target_dir = "assets/images/upload/"; //Default Image Upload Directory
            $allowedtype = array("jpg" => "image/jpg", "jpeg" => "image/jpeg"); //Allowed FIle Type
            $file_error = array();
            $file_error['member_img'] = "";
            $file_error['m_nid'] = "";
            $file_error['m_signature'] = "";
            $file_error['nominee_photo'] = "";
            $file_error['m_nominee_nid'] = "";
            

            // if ($reference != "") {
            // $reference_sql = single_condition_select("staffs", "staffid", $reference);
            // if ($reference_sql['count'] == 1) {
            //     $reference_res = mysqli_fetch_assoc($reference_sql['query']);
            //     $refere_name = $reference_res['name'];
            //     if ($refere_name == $reference_name_given) {
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

            // NID Front image Upload
            if ($_FILES["m_nid"]['size'] > 0) {
                $filename = $_FILES["m_nid"]['name']; //Uploaded File name
                $filetype = $_FILES["m_nid"]["type"]; //Uploaded file Type
                $filesize = $_FILES["m_nid"]['size']; //Uploaded File size
                $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
                $staff_nid_img = "member_nid_front" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
                $final_staff_nid_img = $target_dir . $staff_nid_img . "." . $file_ext;
                if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                    $file_error['m_nid'] = "Only JPG and JPEG photo allowed";
                }
                if ($filesize > $maxfilesize) {                 //Check File Size
                    $file_error['m_nid'] = "Maximum allowed File uploaded size 1 MB";
                }
                if (file_exists($final_staff_nid_img)) {     //Check File exist
                    $file_error['m_nid'] = "Sorry File Already Exist! Please try Again";
                }
                if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                    if (empty($file_error['m_nid'])) {            //Move Upload Files to directory if No error
                        if (move_uploaded_file($_FILES["m_nid"]["tmp_name"], $final_staff_nid_img)) {
                            $m_nid = $final_staff_nid_img; //Photo Name with extention set
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

            //Nominee NID
            if ($_FILES["nominee_nid"]['size'] > 0) {
                $filename = $_FILES["nominee_nid"]['name']; //Uploaded File name
                $filetype = $_FILES["nominee_nid"]["type"]; //Uploaded file Type
                $filesize = $_FILES["nominee_nid"]['size']; //Uploaded File size
                $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
                $nominee_nid = "nominee_nid" . time() . "_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
                $final_nominee_nid = $target_dir . $nominee_nid . "." . $file_ext;
                if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
                    $file_error['m_nominee_nid'] = "Only JPG and JPEG photo allowed";
                }
                if ($filesize > $maxfilesize) {                 //Check File Size
                    $file_error['m_nominee_nid'] = "Maximum allowed File uploaded size 1 MB";
                }
                if (file_exists($final_nominee_nid)) {     //Check File exist
                    $file_error['m_nominee_nid'] = "Sorry File Already Exist! Please try Again";
                }
                if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
                    if (empty($file_error['m_nominee_nid'])) {            //Move Upload Files to directory if No error
                        if (move_uploaded_file($_FILES["nominee_nid"]["tmp_name"], $final_nominee_nid)) {
                            $nominee_nid = $final_nominee_nid; //Photo Name with extention set
                        }
                    }
                }
            }


            // Start Value insert to database
            $array  = array(
                
                "created_by" =>  $uid,
                "created_ip" => $ip,
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
                "nominee_name" => $nominee_name,
                "nominee_nid_no" => $nominee_nid_no,
                "nominee_relation" => $relation,
                "nominee_mobile" => $nominee_mobile,
                "status" => $status,
                "present_address" => $present_address,
                "present_thana" => $present_upazila,
                "present_district" => $present_district,
                "permanent_address" => $permanent_address,
                "permanent_thana" => $permanent_upazila,
                "permanent_district" => $permanent_district,
                "member_photo" => $member_img,
                "member_nid" => $m_nid,
                "member_signature" => $m_signature,
                "nominee_img" => $nominee_photo,
                "nominee_nid" => $nominee_nid,
				"loan_type" => $loan_type,
				"loan_no" => $loan_no,
				"product_price" => $product_price,
				"profit_percent" => $profit_percent,
				"net_amount" => $net_amount,
				"total_installment" => $total_installment,
				"taka_per_installment" => $taka_per_installment,
				"actual_per_installment" => $actual_per_installment,
				"profit_per_inatallment" => $profit_per_inatallment,
				"comment" => $comment
 
            );
            $table_name = "loan_account";

            $inserted = insert_data($conn, $array, $table_name);


            if (isset($inserted['last_id'])) {
                $staffid = $inserted['last_id'];

                $form_success_message =  "submission('$default_redirect_url', 'Status', 'সফল ভাবে নতুন  লোন একাউন্ট তৈরী  হয়েছে')";
            } else {
                echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Data did not Inserted</div>";
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
                $('#m_nid_show')
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
                $('#nominee_nid_img_show')
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
                <h3 class="font-weight-bold font-weight-bolder text-capitalize">লোন একাউন্ট এর বিস্তারিত   </h3>
            </div><br>
            <div class="col-12">
                <h3 class="text-center">ব্যক্তিগত তথ্য</h3>
                <hr>
            </div>
            <!-- Name And Name (Bangla) -->
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="member_id">সদস্য নং (যদি থাকে)<span style="color:red;"></span></label>
                    <input type="number" class="form-control" placeholder="Optional" name='' id="member_id">
                    </div>
                    
                 
                
                <div class="form-group col-md-5">
                    <label for="staffname">নাম (ইংরেজী)<span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter Name" name='name' id="staffname" required value="">
                </div>
                <div class="form-group col-md-5">
                    <label for="name_bangla">নাম (বাংলা)</label>
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
                    <label for="spause_name">স্বামী/স্ত্রীর নাম</label>
                    <input type="text" class="form-control" placeholder="Enter Spause's Name" name='spause_name' id="spause_name" value="">
                </div>
                <div class="form-group col-md-6">
                    <label for="class">এন,আই,ডি/জন্ম নিবন্ধন<span style="color:red;">**</span></label>
                    <input type="text" class="form-control" placeholder="Enter National ID" name='nid' value="">
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
                        <input class="form-control col-sm-11 col-10" name="mobile" type="text" size="11" placeholder="Enter Mobile Number" value="" required>
                    </div>
                </div>
            </div>
      
            <!-- Date of Birth And Educational Qualification -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">জন্ম তারিখ</label>
                    <input type="date" class="form-control" placeholder="Enter Date of Birth" name='dob'>
                </div>
                <div class="form-group col-md-6">
                    <label for="gender">লিঙ্গ<span style="color:red;">**</span></label>
                    <select name="gender" class="form-control" id="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
            <!-- Nominee Name && Nominee NID Number -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_name">নমিনীর নাম</label><span style="color:red;">**</span>
                    <input type="text" class="form-control" placeholder="Enter Nominee Name" name='nominee_name' id="nominee_name">
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_nid">নমিনীর এন,আই,ডি</label><span style="color:red;">**</span>
                    <input type="text" class="form-control" placeholder="Enter Nominee NID No" name='nominee_nid_no' id="nominee_nid_no">
                </div>
            </div>
            <!-- Relationship with Nominee && status-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_relation">নমিনীর সাথে সম্পর্ক</label><span style="color:red;">**</span>
                    <input type="text" class="form-control" placeholder="Enter Nominee Relation" name='nominee_relation' id="nominee_relation">
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_mobile">নমিনীর মোবাইল</label><span style="color:red;">**</span>
                    <input type="text" class="form-control" placeholder="Enter Nominee Mobile" name='nominee_mobile' id="nominee_mobile">
                </div>
            </div>


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
            <hr>
            <?php
            $settings= single_condition_select("settings","s_category","loan_type");
          $loan_type = "";  
            while ($type_name = mysqli_fetch_array($settings['query'])) {
                $loan_type_name = $type_name['s_name'];
	$loan_type .= "<option value='$loan_type_name' data-profitpercent='{$type_name['s_value']}'>$loan_type_name</option>";
	
}
            ?>
            <div class="col-12">
                <h3 class="text-center">লোন/ক্রয় সংক্রান্ত</h3>
                <hr>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-2">
                    <label for="name">ঋনের ধরন</label>
                    <select class="form-control" name="loan_type" id="loan_type">
                        <option value="">-- SELECT --</option>
                        <?php echo $loan_type; ?>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="loan_no" id="loan_result">লোন নং  </label>
                    <input type="number" class="form-control" name='loan_no' id="loan_no" placeholder="Loan No">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">পন্যের মূল্য/ঋনের পরিমান </label>
                    <input type="number" class="form-control" placeholder=" loan_amount" name='product_price' id="product_price">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">লাভের % </label>
                    <input type="number" class="form-control" placeholder="profit_percent" name="profit_percent" id="profit_percent">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">লাভ </label>
                    <input type="number" class="form-control"  name='profit' id="profit" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">মোট মূল্য </label>
                    <input type="number" class="form-control"  name="net_amount" id="net_amount" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তির পরিমান </label>
                    <input type="number" class="form-control" name="total_installment" id="total_installment">
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি টাকা </label>
                    <input type="number" class="form-control" name="taka_per_installment" id="taka_per_installment" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি আসল </label>
                    <input type="number" class="form-control" name="actual_per_installment" id="actual_per_installment" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি লাভ </label>
                    <input type="number" class="form-control" name="profit_per_inatallment" id="profit_per_inatallment" readonly>
                </div>
                
                
                </div>
            <hr>
            <div class="col-12">
                <h3 class="text-center">ডকুমেন্ট </h3>
                <hr>
            </div>
            <!-- Photo & NID & Nominee Photo & Nominee NID -->
            <div class="form-row">
                <div class="form-group col-md-4 mb-2 border">
                    <label for="member_img">মেম্বার ফটো</label>
                    <div class="text-center"><img height='100px' class="photo_show" src="assets/images/img/demo.jpg" alt="your image" id="member_img_show"></div>
                    <!-- <input name="member_img" id="member_img" class="inputphoto" type="file" accept="image/*" onchange="readURL(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="member_img" name="member_img" onchange="readURL(this);">
                        <label class="custom-file-label" for="member_img">মেম্বার ফটো</label>
                    </div>
                </div>
                <div class="form-group col-md-4 mb-2 border">
                    <label for="m_nid">মেম্বার এন,আই,ডি  </label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="assets/img/nid_front.jpg" alt="Member NID Front" id="m_nid_show">
                    </div>
                    <!-- <input id="m_nid" type="file" accept="image/*" name="m_nid" onchange="nidFront(this);"> -->
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="m_nid" name="m_nid" onchange="nidFront(this);">
                        <label class="custom-file-label" for="m_nid">মেম্বার এন,আই,ডি  </label>
                    </div>
                </div>
               
                <div class="form-group col-md-4 mb-2 border">
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
                <div class="form-group col-md-4 mb-2 mt-3 border">
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
                <div class="form-group col-md-4 mb-2 mt-3 border">
                    <label for="nominee_nid">নমিনী এন,আই,ডি  </label>
                    <div class="text-center">
                        <img class="photo_show rounded" height='100px' src="assets/img/nid_front.jpg" alt="Member form" id="nominee_nid_img_show">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="nominee_nid" name="nominee_nid" onchange="n_nidfront(this);">
                        <label class="custom-file-label" for="nominee_nid">নমিনী এন,আই,ডি  </label>
                    </div>
                </div>
                
                <div class="form-group col-md-4 mb-2 mt-3 border">
                    <label>মন্তব্য</label>
                    <textarea name="comment" cols="80" rows="5"></textarea>
                    
                </div>    
                
            </div>
            
            <?php echo isset($csrf_input) ? $csrf_input : ""; ?>
            <div class="text-center mt-3"><input class='btn btn-primary' type='submit' name='submit'></div>
        </form>


    </div>
    <!-- <div class="col-1"></div> -->
</div>
<script>
    $(document).ready(function() {
        
        // loan no check
        $("#loan_no").on('keyup', function() {
            
            var loanType = $("#loan_type").val();
            console.log(loanType);
            var loanNo = $(this).val();
            $.ajax({
                type: "post",
                url: "controllers/index.php",
                data: {
                    'loan_no_search': loanNo,
                    'loan_type' : loanType
                },
                success: function(response) {
                    
                    var response = JSON.parse(response);
                    // console.log(response);
                    $("#loan_result").html(response);
                    
                    
                },
                error: function(response) {
                    console.log(response);
                }
            });
        })
        
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
                        console.log(data);
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
        // if ($ac_type == "admin") {
        // foreach ($office_array as $key => $value) {
        //     $branch_sql = single_condition_sort("branch", "office_code", "$value", "branch_name", "ASC", "");

        //     $options = "";
        //     while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
        //         $options .= "<option value=\"{$branch_res['id']}\">{$branch_res['branch_name']}</option>";
        //     }
        //     echo "branch[{$value}]='$options';";
        // }
        ?>

        // $("#office").on("change", function() {
        //     if ($(this).val() == "") {
        //         $("#branch").val("").attr('disabled', 'disabled')
        //     } else {
        //         $('#branch').attr('disabled', false).children('option:not(:first)').remove();
        //         var id = $(this).val();
        //         $("#branch").append(branch[id]);
        //     }
        // })
        <?php
        // } else {
        //     echo '$("#office").attr("readonly", true);$("#branch").attr("disabled", false).attr("readonly", true);';
        // }
        ?>

    });
    
// When loan type changes, update profit percent automatically

$("#loan_type").on('change', function() {
    var profitPercent = $(this).find(':selected').data('profitpercent') || 0;
    $("#profit_percent").val(profitPercent).trigger('change'); // Update and trigger recalculation
    $("#actual_per_installment").trigger('change');
    $("#profit_per_inatallment").trigger('change');
});
$("#product_price, #profit_percent, #total_installment").on('keyup change', function() {
    var loan_amount = parseFloat($("#product_price").val()) || 0;
    var profit_percent = parseFloat($("#profit_percent").val()) || 0;
    var total_installment = parseFloat($("#total_installment").val()) || 0;

    var profit = loan_amount * (profit_percent / 100);
    var net_amount = loan_amount + profit;
    var taka_per_installment = total_installment > 0 ? net_amount / total_installment : 0;

    $("#profit").val(profit.toFixed(2));
    $("#net_amount").val(net_amount.toFixed(2));
    $("#taka_per_installment").val(taka_per_installment.toFixed(2));
    //var actual_in_an_installment = loan_amount / total_installment;
    //var profit_in_an_installment = profit / total_installment;
    $("#actual_per_installment").val((loan_amount / total_installment).toFixed(2));
    $("#profit_per_inatallment").val((profit / total_installment).toFixed(2));
});
</script>
<?php
$message = "";
// $savings_settings_sql = manual_query("SELECT * FROM settings WHERE s_category='savings_commission'");
$account_numbers = manual_query("SELECT * FROM settings WHERE s_category='account_type'");
$savings_staff_com = 0;
$savings_branch_com = 0;
// while ($settings_res = mysqli_fetch_assoc($savings_settings_sql['query'])) {
//     if ($settings_res['s_name'] == 'staff') {
//         $savings_staff_com = (int) $settings_res['s_value'];
//     }
//     if ($settings_res['s_name'] == 'branch') {
//         $savings_branch_com = (int) $settings_res['s_value'];
//     }
// }
$accounts_code_type = array();
while ($settings_res = mysqli_fetch_assoc($account_numbers['query'])) {
    if ($settings_res['s_name'] == 'Savings') {
        // $savings_code = $settings_res['s_value'];
        $accounts_code_type[$settings_res['s_name']]= (int)$settings_res['s_value'];
    }
    if ($settings_res['s_name'] == 'Share') {
        $accounts_code_type[$settings_res['s_name']]= (int)$settings_res['s_value'];
    }
}

if (isset($_POST['member_id_savings'])) {
    if ($_POST['member_id_savings'] != "") {
        $member_id = $_POST['member_id_savings'];
        $memberinfo = single_condition_select("members", "id", $member_id);
        //Office & Staff
        // $office = $_POST['office'];
        // $branch = $_POST['branch'];
        // $staff_ref = $_POST['staff'];
        // $savings_commission = $_POST['savings_commission'];
        //SMS Have to work
        $sms_status = isset($_POST['savingsSMS']) ? $_POST['savingsSMS'] : 0;
        // $sms_status = 0;
        (int) $savings_starting_balance = isset($_POST['savings_starting_balance']) ? $_POST['savings_starting_balance'] : 0;

        if ($memberinfo['count'] ==1 ) {
            $member_info_res = mysqli_fetch_assoc($memberinfo['query']);
            // $pre_savings_sql = single_condition_select("accounts", "member_id", $member_id);
            // $pre_savings_sql = manual_query("SELECT * FROM accounts WHERE member_id='$member_id' AND account_type='savings' AND status='active'");
            // if ($pre_savings_sql['count'] > 0) {
            //     $message = "<div class='alert alert-danger'>This member has created a savings account already</div>";
            // } else {
            $savings_type = $_POST['savings_type'];
            $savings_amount = 0; //$_POST['savings_amount']
            $opening_date = $_POST['opening_date'];
            $details = $_POST['details'];
            $status_savings = "active";
            $array = array(
                "account_title" =>  $member_info_res['name'],
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "member_id" => $member_id,
                "status" => $status_savings,
                "account_type" => $savings_type,
                "payment_type" => $savings_type,
                "basic_amount" => $savings_amount,
                "detils" => $details,
                "opening_date" => $opening_date,
                // "office" => $office,
                // "branch" => $branch,
                // "staff_ref" => $staff_ref,
            );
            $new_savings = insert_data($conn, $array, "accounts");
            if ($new_savings['last_id']) {
                // $office_code = sprintf("%02d", $member_info_res['office']);
                // $branch_code = sprintf("%03d", $member_info_res['branch']);
                // $refer = sprintf("%03d", $member_info_res['reference']);
                // $account_type = sprintf("%02d", 1);
                $account_type = sprintf("%03d", $accounts_code_type[$savings_type]);
                $account_number = sprintf("%05d", $new_savings['last_id']);
                // $final_ac_number = "{$office_code}-{$branch_code}-{$refer}-{$account_type}-{$account_number}";
                $final_ac_number = $account_type.$account_number;
                $update_savings = updatethis(array("id" => $new_savings['last_id']), array("account_no" => $final_ac_number), "accounts");
                if ($update_savings['edited_id']) {
                    //SMS Part Have to check
                    if ($sms_status == 1) {
                        if (strlen($member_info_res['mobile']) > 10) {
                            $sms_message = "New Savings Account created successfully. Your AC NO-$final_ac_number, , Thank You";
                            $sms_sent = sms_to_member(array($member_info_res['mobile']), $sms_message);
                            if (isset($sms_sent['success'])) {
                                $_SESSION['sms'] = "<div class='alert alert-success'>SMS Sent Successfully</div>";
                            } else {
                                $_SESSION['sms'] = "<div class='alert alert-danger'>{$sms_sent['error']}</div>";
                            }
                        } else {
                            $_SESSION['sms'] = "<div class='alert alert-danger'>Member Mobile Number Not Valid</div>";
                        }
                    }
                    //Branch Account Information Start
                    // $branch_ac = single_condition_select("branch_account", "branch_id", $branch);
                    // while ($branch_ac_res = mysqli_fetch_assoc($branch_ac['query'])) {
                    //     if ($branch_ac_res['ac_type'] == "cash") {
                    //         $account_info['cash'] = $branch_ac_res['current_balance'];
                    //         $account_info['cash_id'] = $branch_ac_res['id'];
                    //     }
                    //     if ($branch_ac_res['ac_type'] == "bkash") {
                    //         $account_info['bkash'] = $branch_ac_res['current_balance'];
                    //         $account_info['bkash_has'] = "";
                    //         $account_info['bkash_id'] = $branch_ac_res['id'];
                    //     }
                    //     if ($branch_ac_res['ac_type'] == "nagad") {
                    //         $account_info['nagad'] = $branch_ac_res['current_balance'];
                    //         $account_info['nagad_has'] = "";
                    //         $account_info['nagad_id'] = $branch_ac_res['id'];
                    //     }
                    //     if ($branch_ac_res['ac_type'] == "bank") {
                    //         $account_info['bank_has'] = "";
                    //         $account_info['bank'] = $branch_ac_res['current_balance'];
                    //         $account_info['bank_id'] = $branch_ac_res['id'];
                    //     }
                    // }
                    if ($savings_starting_balance > 0) {
                        $account_id = $new_savings['last_id'];
                        $payment_for = $savings_type;
                        $basic_amount = $savings_amount;
                        // $profit = isset($_POST['profit_tk']) ? $_POST['profit_tk'] : 0;
                        // $installment_no = isset($_POST['installment_number']) ? $_POST['installment_number'] : 1;
                        // $fine = $_POST['fine_tk'];
                        $total_tk = $savings_starting_balance;

                        $installment_no = 1;
                        $installment_time = date("Y-m", strtotime($datetime));

                        $array = array(
                            "created_by" =>  $uid,
                            "created_at" =>   date("Y-m-d H:i:s", strtotime($datetime)),
                            "created_ip" => $ip,
                            "account_no" => $account_id,
                            "account_no_full" => $final_ac_number,
                            "basic_amount" => $basic_amount,
                            "payment_for" => $payment_for,
                            "installment_no" => $installment_no,
                            "installment_time" => $installment_time,
                            // "payment_type" => $payment_type,
                            // "payment_info" => $payment_info,
                            // "payment_photo" => $payment_photo,
                            // "fine" => $fine,
                            "total_tk" => $total_tk,
                            "short_details" => "Starting",
                            // "profit" => $profit,
                            "status" => "paid",
                            // "branch_id" => $branch
                        );

                        $new_transection = insert_data($conn, $array, "transections");
                    }
                    //Staff Payement Transections



                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $_SESSION['msg'] = "<div class='alert alert-success'>Account created successfully!! Account Number <b>{$final_ac_number}</b></div>";
                    echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
                    exit;
                    // $form_success_message =  "submission('$default_redirect_url', 'Status', 'Savings account created successfully!!')";
                }
            }
            // }
        } else {
            $message = "<div class='alert alert-danger'>Member did not found!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>All field required</div>";
    }
}
/*
    // if (isset($_POST['member_id_deposit']) && $form_success_message === "") {
    //     if ($_POST['member_id_deposit'] != "") {
    //         $member_id = $_POST['member_id_deposit'];
    //         $memberinfo = single_condition_select("members", "id", $member_id);
    //         //SMS Have to work
    //         $sms_status = isset($_POST['depositSMS']) ? $_POST['depositSMS'] : 0;
    //         if ($memberinfo['count'] > 0) {
    //             $deposit_type = $_POST['deposit_type']; //Month
    //             $deposit_amount = $_POST['deposit_amount'];
    //             $profit_rate = ""; //$_POST['profit_rate']
    //             $start_date = $_POST['start_date'];
    //             $end_date = $_POST['end_date'];
    //             $details = $_POST['details_deposit'];
    //             $opening_date_deposit = $_POST['opening_date_deposit'];
    //             $deposit_status = "active";
    //             //Office & Staff
    //             $office = $_POST['office'];
    //             $branch = $_POST['branch'];
    //             $staff_ref = $_POST['staff'];
    //             //Transections
    //             $payment_by = ""; //$_POST['payment_type']
    //             $payment_info = ""; //$_POST['payment_type']
    //             $cash = (int)$_POST['cash'];
    //             $bkash_amount = (int)$_POST['bkash_amount'];
    //             $bkash_transect_id = $_POST['bkash_transect_id'];
    //             $nagad_amount = (int)$_POST['nagad_amount'];
    //             $nagad_transect_id = $_POST['nagad_transect_id'];
    //             $bank_amount = (int)$_POST['bank_amount'];
    //             //Balnce Check
    //             $account_info = array();
    //             $account_info['cash'] = 0;
    //             $account_info['bkash'] = 0;
    //             $account_info['nagad'] = 0;
    //             $account_info['bank'] = 0;
    //             $account_info['bkash_has'] = "readonly";
    //             $account_info['nagad_has'] = "readonly";
    //             $account_info['bank_has'] = "readonly";
    //             $branch_ac = single_condition_select("branch_account", "branch_id", $branch);
    //             while ($branch_ac_res = mysqli_fetch_assoc($branch_ac['query'])) {
    //                 if ($branch_ac_res['ac_type'] == "cash") {
    //                     $account_info['cash'] = $branch_ac_res['current_balance'];
    //                     $account_info['cash_id'] = $branch_ac_res['id'];
    //                 }
    //                 if ($branch_ac_res['ac_type'] == "bkash") {
    //                     $account_info['bkash'] = $branch_ac_res['current_balance'];
    //                     $account_info['bkash_has'] = "";
    //                     $account_info['bkash_id'] = $branch_ac_res['id'];
    //                 }
    //                 if ($branch_ac_res['ac_type'] == "nagad") {
    //                     $account_info['nagad'] = $branch_ac_res['current_balance'];
    //                     $account_info['nagad_has'] = "";
    //                     $account_info['nagad_id'] = $branch_ac_res['id'];
    //                 }
    //                 if ($branch_ac_res['ac_type'] == "bank") {
    //                     $account_info['bank_has'] = "";
    //                     $account_info['bank'] = $branch_ac_res['current_balance'];
    //                     $account_info['bank_id'] = $branch_ac_res['id'];
    //                 }
    //             }
    //             // if ($cash > $account_info['cash']) {
    //             //     $paid_status['error'] = "Cash Has not sufficent Balance";
    //             // }
    //             if ($bkash_amount > 0 && !isset($account_info['bkash_id'])) {
    //                 $paid_status['error'] = "Bkash Account Does not found";
    //             }
    //             if ($nagad_amount > 0 && !isset($account_info['nagad_id'])) {
    //                 $paid_status['error'] = "Nagad Account Does not Found";
    //             }
    //             if ($bank_amount > 0 && !isset($account_info['bank_id'])) {
    //                 $paid_status['error'] = "Bank Account Does not found";
    //             }
    //             $total_paid = $cash + $bkash_amount + $nagad_amount + $bank_amount;

    //             if ($payment_by == "cash") {
    //                 $payment_info .=  "cash ;";
    //             } elseif ($payment_by == "bkash") {
    //                 $payment_info .= $_POST['transection_id'] . "; ";
    //             } elseif ($payment_by == "nagad") {
    //                 $payment_info .= $_POST['transection_id'] . "; ";
    //             } elseif ($payment_by == "bank") {
    //                 $payment_info .= $_POST['bank_name_no'] . "; ";
    //             }

    //             $array = array(
    //                 "created_by" =>  $uid,
    //                 "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
    //                 "created_ip" => $ip,
    //                 "account_type" => "deposit",
    //                 "status" => $deposit_status,
    //                 "member_id" => $member_id,
    //                 "payment_type" => $deposit_type,
    //                 "basic_amount" => $deposit_amount,
    //                 "profit" => $profit_rate,
    //                 "start_date" => $start_date,
    //                 "end_date" => $end_date,
    //                 "detils" => $details,
    //                 "opening_date" => $start_date,
    //                 "office" => $office,
    //                 "branch" => $branch,
    //                 "staff_ref" => $staff_ref,
    //                 "payment_by" => $payment_by,
    //                 "payment_info" => $payment_info,
    //             );
    //             if (!isset($paid_status['error'])) {
    //                 $new_savings = insert_data($conn, $array, "accounts");
    //                 if ($new_savings['last_id']) {


    //                     //Branch Transection Update
    //                     $target_dir = "assets/images/upload/";
    //                     $allowedtype = array("jpg" => "image/jpg", "jpeg" => "image/jpeg"); //Allowed FIle Type
    //                     if ($_FILES["bank_payment_photo"]['size'] > 0) {
    //                         $filename = $_FILES["bank_payment_photo"]['name']; //Uploaded File name
    //                         $filetype = $_FILES["bank_payment_photo"]["type"]; //Uploaded file Type
    //                         $filesize = $_FILES["bank_payment_photo"]['size']; //Uploaded File size
    //                         $maxfilesize = 1 * 1024 * 1024; //Maximum FIle size 1MB
    //                         $photo_name = "new_loan_{$new_savings['last_id']}_" . rand(1000, 9999); //Staff Image name will be staff_img_1612931897_1548
    //                         $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); //Grab file extention
    //                         $final_photo_name = $target_dir . $photo_name . "." . $file_ext;
    //                         if (!array_key_exists($file_ext, $allowedtype)) {                 //Check File Extention
    //                             $message = "<div class='alert alert-danger'>Only JPG and JPEG photo allowed</div>";
    //                         }
    //                         // if ($filesize > $maxfilesize) {                 //Check File Size
    //                         //     $message = "<div class='alert alert-danger'>Maximum allowed File uploaded size 1 MB</div>";
    //                         // }
    //                         if (file_exists($final_photo_name)) {     //Check File exist
    //                             $message = "<div class='alert alert-danger'>Sorry File Already Exist! Please try Again</div>";
    //                         }
    //                         if (in_array($filetype, $allowedtype)) {      //Check Myme type of file
    //                             //Move Upload Files to directory if No error
    //                             if (move_uploaded_file($_FILES["payment_photo"]["tmp_name"], $final_photo_name)) {
    //                                 $payment_photo = $final_photo_name; //Photo Name with extention set
    //                                 $message = "<div class='alert alert-success'>Deposit account created successfully</div>";
    //                             }
    //                         }
    //                         updatethis(array("id" => $new_savings['last_id']), array("payment_photo" => $payment_photo), "accounts");
    //                     }


    //                     $office_code = sprintf("%02d", $office);
    //                     $branch_code = sprintf("%03d", $branch);
    //                     $refer = sprintf("%03d", $staff_ref);
    //                     $account_type = sprintf("%02d", 2);;
    //                     $account_number = sprintf("%05d", $new_savings['last_id']);
    //                     $final_ac_number = "{$office_code}-{$branch_code}-{$refer}-{$account_type}-{$account_number}";
    //                     $update_savings = updatethis(array("id" => $new_savings['last_id']), array("account_no" => $final_ac_number), "accounts");
    //                     if ($update_savings['edited_id']) {
    //                         //Transection_list Update
    //                         $account_id = $new_savings['last_id'];
    //                         $array = array(
    //                             "created_by" => $uid,
    //                             "created_at" => date("Y-m-d H:i:s", strtotime($datetime)),
    //                             "created_ip" => $ip,
    //                             "account_no" => $account_id,
    //                             "account_no_full" => $final_ac_number,
    //                             "basic_amount" => $deposit_amount,
    //                             "payment_for" => "deposit",
    //                             "installment_no" => 0,
    //                             "installment_time" => "",
    //                             // "payment_type" => $payment_type,
    //                             // "payment_info" => $payment_info,
    //                             // "payment_photo" => $payment_photo,
    //                             // "fine" => $fine,
    //                             "total_tk" => $deposit_amount,
    //                             "short_details" => "deposit",
    //                             // "profit" => $profit,
    //                             "status" => "paid",
    //                             "branch_id" => $branch
    //                         );
    //                         $new_transection = insert_data($conn, $array, "transections");

    //                         //Branch Transections Table
    //                         if ($cash > 0) {
    //                             $cash_transect = array(
    //                                 "created_at" => date("Y-m-d H:i:s", strtotime($datetime)),
    //                                 "created_by" => $uid,
    //                                 "created_ip" => $ip,
    //                                 "branch_id" => $branch,
    //                                 "ac_id" => $account_info['cash_id'],
    //                                 "category" => "deposit",
    //                                 "earnings" => $cash,
    //                                 "ref_table" => "transections",
    //                                 "ref_id" => $new_transection['last_id'],
    //                                 "transect_id" => "Cash",
    //                                 "transect_photo" => "",
    //                                 "comment" => "",
    //                             );
    //                             $cash_insert = insert_data($conn, $cash_transect, "branch_transection");
    //                         }
    //                         if ($bkash_amount > 0) {
    //                             $bkash_transect = array(
    //                                 "created_at" => date("Y-m-d H:i:s", strtotime($datetime)),
    //                                 "created_by" => $uid,
    //                                 "created_ip" => $ip,
    //                                 "branch_id" => $branch,
    //                                 "ac_id" => $account_info['bkash_id'],
    //                                 "category" => "deposit",
    //                                 "earnings" => $bkash_amount,
    //                                 "ref_table" => "transections",
    //                                 "ref_id" => $new_transection['last_id'],
    //                                 "transect_id" => $bkash_transect_id,
    //                                 "transect_photo" => "",
    //                                 "comment" => "",
    //                             );
    //                             $bkash_insert = insert_data($conn, $bkash_transect, "branch_transection");
    //                         }
    //                         if ($nagad_amount > 0) {
    //                             $nagad_transect = array(
    //                                 "created_at" => date("Y-m-d H:i:s", strtotime($datetime)),
    //                                 "created_by" => $uid,
    //                                 "created_ip" => $ip,
    //                                 "branch_id" => $branch,
    //                                 "ac_id" => $account_info['nagad_id'],
    //                                 "category" => "deposit",
    //                                 "earnings" => $nagad_amount,
    //                                 "ref_table" => "transections",
    //                                 "ref_id" => $new_transection['last_id'],
    //                                 "transect_id" => $nagad_transect_id,
    //                                 "transect_photo" => "",
    //                                 "comment" => "",
    //                             );
    //                             $nagad_insert = insert_data($conn, $nagad_transect, "branch_transection");
    //                         }
    //                         if ($bank_amount > 0) {
    //                             $bank_transect = array(
    //                                 "created_at" => date("Y-m-d H:i:s", strtotime($datetime)),
    //                                 "created_by" => $uid,
    //                                 "created_ip" => $ip,
    //                                 "branch_id" => $branch,
    //                                 "ac_id" => $account_info['bank_id'],
    //                                 "category" => "deposit",
    //                                 "earnings" => $bank_amount,
    //                                 "ref_table" => "transections",
    //                                 "ref_id" => $new_transection['last_id'],
    //                                 "transect_photo" => $payment_photo,
    //                                 "comment" => "",
    //                             );
    //                             $bank_insert = insert_data($conn, $bank_transect, "branch_transection");
    //                         }

    //                         $branch_update_res = branch_balance_update($branch);

    //                         //SMS Part Have to check
    //                         if ($sms_status == 1) {
    //                             if (strlen($member_info_res['mobile']) > 10) {
    //                                 $sms_message = "New Deposit Account created successfully. Your AC NO-$final_ac_number, , Thank You";
    //                                 $sms_sent = sms_to_member(array($member_info_res['mobile']), $sms_message);
    //                                 if (isset($sms_sent['success'])) {
    //                                     $_SESSION['sms'] = "<div class='alert alert-success'>SMS Sent Successfully</div>";
    //                                 } else {
    //                                     $_SESSION['sms'] = "<div class='alert alert-danger'>{$sms_sent['error']}</div>";
    //                                 }
    //                             } else {
    //                                 $_SESSION['sms'] = "<div class='alert alert-danger'>Member Mobile Number Not Valid</div>";
    //                             }
    //                         }
    //                         // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //                         // $_SESSION['msg'] = "<div class='alert alert-success'>Deposit account created successfully!! Account Number <b>{$final_ac_number}</b></div>";
    //                         // echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
    //                         // exit;
    //                         $form_success_message =  "submission('$default_redirect_url', 'Status', 'Deposit account created successfully!! Account Number <b>{$final_ac_number}</b>')";
    //                     }
    //                     $message = "<div class='alert alert-success'>Deposit account created successfully!! Account Number <b>{$new_savings['last_id']}</b></div>";
    //                 }
    //             } else {
    //                 echo "<div class='alert alert-danger'>{$paid_status['error']}</div>";
    //             }
    //         } else {
    //             $message = "<div class='alert alert-danger'>Member did not found!</div>";
    //         }
    //     } else {
    //         $message = "<div class='alert alert-danger'>All field required</div>";
    //     }
    // }
*/
?>
<div class="mt-4">
    <div id="showallmsg">
        <?php
        if (isset($message)) {
            echo $message;
        }
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['sms'])) {
            echo $_SESSION['sms'];
            unset($_SESSION['sms']);
        }
        ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">সঞ্চয়</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">মেয়াদী আমানত</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">ঋণ</a>
                </li> -->
            </ul>
            <hr>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form id="savings_form" enctype="multipart/form-data" method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="member_select" class="control-label">সদস্য নং</label>
                                    <input class="form-control member_autocomplete" type="text" name="member_select" id="member_select" placeholder="Search Members by name or id" required>
                                    <input type="hidden" name="member_id_savings" id="member_id_savings" class="member_id">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="savings_type" for="saving_mode">সঞ্চয়ের প্রকার</label>
                                    <select name="savings_type" class="custom-select mr-sm-2" id="savings_type" required="">
                                        <option value="Savings">সঞ্চয়</option>
                                        <option value="Share">শেয়ার</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="savings_amount">সঞ্চয়ের পরিমাণ</label>
                                    <input type="number" name="savings_amount" min="0" step="any" data-parsley-type="digits" class="form-control" id="savings_amount" placeholder="Enter saving amount" autocomplete="off" required>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="opening_date">তারিখ</label>
                                    <input type="date" class="form-control dates" name="opening_date" placeholder="Enter date" autocomplete="off" required="" value="<?php echo date("Y-m-d") ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="savings_starting_balance">শুরুর ব্যলেন্স</label> <!-- ধার্যকৃত সঞ্চয়ের পরিমাণ -->
                                    <input type="number" name="savings_starting_balance" min="0" step="any" class="form-control" id="savings_starting_balance" placeholder="Enter starting amount" autocomplete="off">
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="savings_commission">প্রারম্ভিক খরচ</label>
                                    <input type="text" name="savings_commission" class="form-control" id="savings_commission" autocomplete="off" value="<?php //echo $savings_staff_com + $savings_branch_com; 
                                                                                                                                                        ?> TK" readonly>
                                </div>
                            </div> -->
                        </div>
                        <div class="form-group">
                            <label for="details">বিবরণ</label>
                            <textarea class="form-control" name="details" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_savings">অবস্থা</label>
                                    <select name="status_savings" id="status_savings" class="form-control">
                                        <option value="regular">নিয়মিত</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="savingsSMS" name="savingsSMS" value="1">
                            <label class="custom-control-label" for="savingsSMS">SMS</label>&nbsp;&nbsp;
                            <label class="" for="savingsSMS">(Your Savings account has been created)</label>
                        </div>
                        <?php echo isset($csrf_input) ? $csrf_input : ""; ?>
                        <button class="btn btn-primary" type="submit" id="new_saving" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" name="new_saving">Submit</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form id="deposit_form" enctype="multipart/form-data" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member_select_deposit" class="control-label">সদস্য নং</label>
                                    <input class="form-control member_autocomplete" type="text" name="member_select" id="member_select_deposit" placeholder="Search Members by name or id" required>
                                    <input type="hidden" name="member_id_deposit" id="member_id_deposit" class="member_id">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="loan_type" for="deposit_type">আমানতের সময়সীমা</label>
                                    <input type="number" name="deposit_type" class="form-control" id="deposit_type" required placeholder="Month">
                                    <!-- <select name="deposit_type" class="custom-select mr-sm-2" id="deposit_type" required="">
                                        <option value="" selected="">Select one</option>
                                        <option value="১ বছর" data-month_no="12">১ বছর</option>
                                        <option value="২ বছর" data-month_no="24">২ বছর</option>
                                        <option value="৩ বছর" data-month_no="36">৩ বছর</option>
                                        <option value="৪ বছর" data-month_no="৪৮">৪ বছর</option>
                                        <option value="৫ বছর" data-month_no="৬০">৫ বছর</option>
                                    </select> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="deposit_amount">আমানতের পরিমাণ</label>
                                    <input type="number" name="deposit_amount" min="0" step="any" data-parsley-type="digits" class="form-control" id="deposit_amount" placeholder="Deposite amount" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="profit_rate">মুনাফার হার</label>
                                    <input type="text" class="form-control dates" name="profit_rate" placeholder="" autocomplete="off" id="profit_rate" disabled>
                                </div>
                            </div> -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="start_date">আমানতের মেয়াদকাল</label>
                                    <div class="input-daterange input-group-append">
                                        <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="start_date" autocomplete="off" required="" id="start_date" value="<?php echo date("Y-m-d") ?>">
                                        <span class="input-group-text">থেকে</span>
                                        <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="end_date" autocomplete="off" required="" id="end_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- Transection Part -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cash">
                                        ক্যাশ &nbsp;&nbsp;
                                        <span type="button" data-container="body" data-toggle="popover" data-placement="top" data-content="Balance : <?php //echo isset($account_info['cash']) ? $account_info['cash'] : 0; 
                                                                                                                                                        ?>">
                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                        </span>
                                    </label>
                                    <input type="number" name="cash" id="cash" class="form-control pay_amount" placeholder="Cash Amount"><!-- max="<?php //echo $account_info['cash']; 
                                                                                                                                                    ?>" -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bkash_amount">
                                        বিকাশ &nbsp;&nbsp;
                                        <span type="button" data-container="body" data-toggle="popover" data-placement="top" data-content="Balance : <?php //echo isset($account_info['bkash']) ? $account_info['bkash'] : 0; 
                                                                                                                                                        ?>">
                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                        </span>
                                    </label>
                                    <input type="number" readonly name="bkash_amount" id="bkash_amount" class="form-control pay_amount" placeholder="Bkash Amount" <?php //echo $account_info['bkash_has']; 
                                                                                                                                                                    ?> max="<?php //echo $account_info['bkash'];  
                                                                                                                                                                            ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bkash_transect_id">বিকাশ ট্রানজেকশন</label>
                                    <input type="text" readonly name="bkash_transect_id" id="bkash_transect_id" class="form-control" placeholder="Bkash Transection ID" <?php //echo $account_info['bkash_has']; 
                                                                                                                                                                        ?>>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nagad_amount">নগদ</label>
                                    <input type="number" readonly name="nagad_amount" id="nagad_amount" class="form-control pay_amount" placeholder="Nagad Amount" <?php //echo $account_info['nagad_has'];
                                                                                                                                                                    ?> max="<?php //echo $account_info['nagad'];
                                                                                                                                                                            ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nagad_transect_id">নগদ ট্রানজেকশন</label>
                                    <input type="text" readonly name="nagad_transect_id" id="nagad_transect_id" class="form-control" placeholder="Nagad Transection ID" <?php //echo $account_info['nagad_has']; 
                                                                                                                                                                        ?>>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank_amount">ব্যাংক</label>
                                    <input type="number" readonly name="bank_amount" id="bank_amount" class="form-control pay_amount" placeholder="Bank Amount" <?php //echo $account_info['bank_has']; 
                                                                                                                                                                ?> max="<?php //echo $account_info['bank'];
                                                                                                                                                                        ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank_photo">চেক ছবি</label>
                                    <input type="file" name="bank_payment_photo" id="bank_payment_photo" <?php //echo $account_info['bank_has'];
                                                                                                            ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_paid_amount">মোট টাকা</label>
                                    <input type="number" name="total_paid_amount" id="total_paid_amount" class="form-control" max="<?php //echo isset($loan_application_res['loan_amount']) ? $loan_application_res['loan_amount'] : 0;
                                                                                                                                    ?>" min="<?php //echo isset($loan_application_res['loan_amount']) ? $loan_application_res['loan_amount'] : 00;
                                                                                                                                                ?>" required>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="deposit_status">অবস্থা</label>
                                    <select name="deposit_status" id="deposit_status" class="form-control">
                                        <option value="regular">নিয়মিত</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="opening_date_deposit">তারিখ</label>
                                    <input type="date" class="form-control dates" name="opening_date_deposit" id="opening_date_deposit" value="<?php echo date("Y-m-d") ?>" placeholder="Enter date" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="details_deposit">বিবরণ</label>
                                    <textarea class="form-control" name="details_deposit" rows="1" id="details_deposit"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="depositSMS" name="depositSMS" value="1">
                            <label class="custom-control-label" for="depositSMS">SMS</label>&nbsp;&nbsp;
                            <label class="" for="depositSMS">(Your Deposit account has been created)</label>
                        </div>
                        <?php echo isset($csrf_input) ? $csrf_input : ""; ?>
                        <button class="btn btn-primary" type="submit" id="new_deposit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" name="new_deposit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <ul id="memberDetails" class="list-group">
                    <li class="list-group-item">
                        <img src="assets/images/img/demo.jpg" id="member_image" class="align-self-start mr-3" style="width: 35%;">
                    </li>
                    <li class="list-group-item">সদস্য নং : </span> <span id="member_id2"></span></li>
                    <li class="list-group-item">নাম : </span> <span id="member_name_txt"></span></li>
                    <li class="list-group-item">মোবাইল : </span> <span id="member_mobile"></span></li>
                    <!-- <li class="list-group-item">সঞ্চয় হিসাব নং : <span id="savings_list"></span></li>
                    <li class="list-group-item">মেয়াদী হিসাব নং : <span id="deposit_list"></span></li>
                    <li class="list-group-item">ঋণ এর হিসাব নং : <span id="loan_list"></span></li> -->
                </ul>
            </div>
        </div>
    </div>


</div>
<script>
    $(document).ready(function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            clearfield();
        })

        function clearfield() {
            $('.member_id').each(function() {
                $(this).val("");
            })
            $('#savings_list').html("");
            $('#deposit_list').html("");
            $('#loan_list').html("");

            $('#showallmsg').html("");

            $('#member_name').html("");
            $('#member_id2').html("");
            $('#member_mobile').html("");
            $('#member_branch').html("");
            $('#member_center').html("");
            $('#member_image').attr("src", "assets/images/img/demo.jpg");
            $('#savings_form').trigger("reset");
            $('#deposit_form').trigger("reset");
            $('#loan_form').trigger("reset");
            if ($ac_type == "admin") {
                $("#staff").val("").attr('disabled', 'disabled');
            }
        }

        $(document).on("focus change", ".member_autocomplete", function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "controllers/index.php",
                        type: 'post',
                        dataType: "json",
                        data: {
                            searchmember_only: request.term
                        },
                        success: function(data) {
                            response(data);
                        },
                        error: function(d) {
                            console.log(d);
                        }
                    });
                },
                select: function(event, ui) {
                    // Swal.fire({
                    //     title: "Please Wait!",
                    //     didOpen: () => {
                    //         Swal.showLoading();
                    //     }
                    // });
                    $(this).val(ui.item.label);

                    // $('#savings_list').html(ui.item.data.savings);
                    // $('#deposit_list').html(ui.item.data.deposit);
                    // $('#loan_list').html(ui.item.data.loan);

                    $('#member_name').html(ui.item.label);
                    $('#member_name_txt').html(ui.item.label);
                    $(this).parent().find('.member_id').val(ui.item.value);
                    $('#member_id2').html(ui.item.value);
                    $('#member_mobile').html(ui.item.data.mobile);
                    // $('#member_branch').html(ui.item.data.branch);
                    // $('#member_center').html(ui.item.data.center);
                    $('#member_image').attr("src", ui.item.data.image);
                    // console.log(ui.item.data);
                    // Swal.close();
                    return false;
                }
            });
        });

        //Transection Information Change function
        $("#bkash_field").hide();
        $("#bank_field").hide();
        $("#staff_id").attr("required", "required");
        $("#payment_type").on("change", function() {
            var pay_type = $(this).val();
            if (pay_type == "bank") {
                $("#staff_field").removeAttr("required").hide();
                $("#staff_id").removeAttr("required");
                $("#transection_id").removeAttr("required");
                $("#bkash_field").hide();
                $("#bank_field").show();
                $("#bank_name_no").attr("required", "required");
            } else if (pay_type == "bkash") {
                $("#staff_field").hide();
                $("#staff_id").removeAttr("required");
                $("#bank_name_no").removeAttr("required");
                $("#bkash_field").show();
                $("#transection_id").attr("required", "required");
                $("#bank_field").hide();
            } else if (pay_type == "nagad") {
                $("#staff_field").hide();
                $("#staff_id").removeAttr("required");
                $("#bank_name_no").removeAttr("required");
                $("#bkash_field").show();
                $("#transection_id").attr("required", "required");
                $("#bank_field").hide();
            } else {
                $("#staff_field").show();
                $("#staff_id").attr("required", "required");
                $("#bkash_field").hide();
                $("#bank_name_no").removeAttr("required");
                $("#bank_field").hide();
                $("#transection_id").removeAttr("required");
            }
        })


        //Deposite end Time calculation
        function depostite_end_date() {
            var start_date = new Date($("#start_date").val());

            // var number_of_month = $("#deposit_type").find(':selected').data('month_no');
            var number_of_month = $("#deposit_type").val();
            var d = start_date.getDate();
            start_date.setMonth(start_date.getMonth() + +number_of_month);
            if (start_date.getDate() != d) {
                start_date.setDate(0);
            }
            var end_date = start_date

            var end_date = new Date(start_date),
                month = '' + (end_date.getMonth() + 1),
                day = '' + end_date.getDate(),
                year = end_date.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            end_date = [year, month, day].join('-');
            $("#end_date").val(end_date);
        }
        $("#deposit_type").on("change", function() {
            depostite_end_date()
        });
        $("#start_date").on("change", function() {
            depostite_end_date()
        });

        //Deposit Amount
        $("#deposit_amount").on("keyup change", function() {
            var deposit_amount = $(this).val();
            // var taken_total = $("#total_paid_amount").attr("max",deposit_amount).attr("min", deposit_amount);
        })

        //Deposit Submit Check
        $("#new_deposit").on("click", function() {
            var deposit_amount = $("#deposit_amount").val();
            var taken_total = $("#total_paid_amount").val();
            if (deposit_amount != taken_total) {
                alert("Total Taka Must be equal to deposit amount");
                return false
            }
        })

        //Amount Calculation        
        //Total Amount Script
        $(".pay_amount").on("keyup change", function() {
            var sum = 0;
            $('.pay_amount').each(function() {
                sum += $(this).val() ? parseFloat($(this).val()) : 0; // Or this.innerHTML, this.innerText
            });
            var max_ck = $("#deposit_amount").val();
            if (sum > max_ck) {
                alert("Amount Can't be greater than Deposit amount")
                $("#total_paid_amount").val(sum);
                // $("#new_deposit").attr("disabled", true);
            } else {
                $("#total_paid_amount").val(sum);
                // $("#new_deposit").attr("disabled", false);
            }
        })
        $("#total_paid_amount").on("keyup hover focus", function() {
            var sum = 0;
            $('.pay_amount').each(function() {
                sum += $(this).val() ? parseFloat($(this).val()) : 0; // Or this.innerHTML, this.innerText
            });
            var max_ck = $(this).attr("max");
            if (sum > max_ck) {
                alert("Amount Can't be greater than Deposit amount")

                $(this).val(sum);
            } else {
                $(this).val(sum);
            }
        })


    });
</script>
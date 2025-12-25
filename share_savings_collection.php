<?php
$message = "";
$staff = get_posts("staffs");
$delay_fine_sql = manual_query("SELECT * FROM settings WHERE id in (48,49)"); // Settings FOR manual ID
$savings_fine = 0;
$loan_fine = 0;
$savings_month = 1;
$loan_month = 1;
// while ($delay_fine_res = mysqli_fetch_assoc($delay_fine_sql['query'])) {
//     if ($delay_fine_res['s_category'] == "savings_installment_fine") {
//         $savings_fine = $delay_fine_res['s_value'];
//         $savings_month = $delay_fine_res['s_name'];
//     }
//     if ($delay_fine_res['s_category'] == "loan_installment_fine") {
//         $loan_fine = $delay_fine_res['s_value'];
//         $loan_month = $delay_fine_res['s_name'];
//     }
// }

if (isset($_POST['new_transection'])) {

    $error_status = "";

    $member_id = $_POST['member_number_search'];
    $account_title = $_POST['account_title'];

    //Member Search
    $get_member_info = manual_query("SELECT * FROM members WHERE id='$member_id'");
    $member_result = mysqli_fetch_assoc($get_member_info["query"]);
    if ($get_member_info['count'] != 1) {
        $error_status = "Member Not Found";
    }



    //Savings 
    $savings_installment = (int)$_POST['savings_installment'];
    $savings_installment_amount = $_POST['savings_installment_amount'];
    $savings_installment_total = (int)$_POST['savings_installment_total'];
    $savings_installment_ac = (int)$_POST['savings_installment_ac'];
    $savings_new_months = "";
    if ($savings_installment_total > 0) {
        $check_savings_ac = manual_query("SELECT * FROM accounts WHERE member_id='$member_id' AND account_type='Savings'");
        $check_savings_ac_res = mysqli_fetch_assoc($check_savings_ac['query']);
        if ($check_savings_ac['count'] != 1) {
            $error_status = "Savings Not found";
        }
        $check_savings_last_transect = manual_query("SELECT * FROM transections_details WHERE account_id='{$check_savings_ac_res['id']}'
        
        AND month_name !='' ORDER BY id DESC LIMIT 1");
        $check_savings_last_transect_res = mysqli_fetch_assoc($check_savings_last_transect['query']);

        $sv_paid_months = explode(",", $check_savings_last_transect_res['month_name']);
        $eleminate_last_months = array_pop($sv_paid_months);
        $sv_last_paid_month = end($sv_paid_months);

        for ($i = 1; $i < $savings_installment + 1; $i++) {
            $savings_new_months .= date("Y-m", strtotime("+$i month", strtotime($sv_last_paid_month))) . ',';
        }
    }

    //Admission Fee
    $admission_fee = (int)$_POST['admission_fee'];

    //Share
    $share_installment = (int)$_POST['share_installment'];
    $share_installment_amount = $_POST['share_installment_amount'];
    $share_installment_total = (int)$_POST['share_installment_total'];
    $share_installment_ac = (int)$_POST['share_installment_ac'];
    $share_new_months = "";
    if ($share_installment_total > 0) {
        $check_share_ac = manual_query("SELECT * FROM accounts WHERE member_id='$member_id' AND account_type='Share'");
        $check_share_ac_res = mysqli_fetch_assoc($check_share_ac['query']);
        if ($check_share_ac['count'] != 1) {
            $error_status = "share Not found";
        }
        $check_share_last_transect = manual_query("SELECT * FROM transections_details WHERE account_id='{$check_share_ac_res['id']}' AND month_name !='' ORDER BY id DESC LIMIT 1");
        $check_share_last_transect_res = mysqli_fetch_assoc($check_share_last_transect['query']);

        $sv_paid_months = explode(",", $check_share_last_transect_res['month_name']);
        $eleminate_last_months = array_pop($sv_paid_months);
        $sv_last_paid_month = end($sv_paid_months);

        for ($i = 1; $i < $share_installment + 1; $i++) {
            $share_new_months .= date("Y-m", strtotime("+$i month", strtotime($sv_last_paid_month))) . ',';
        }
    }

    //Fine    
    $total_fine = (int)$_POST['total_fine'];

    //Others    
    $others_fee = (int)$_POST['others_fee'];


    //Total Amount
    $total_transect_amount = $share_installment_total + $admission_fee + $savings_installment_total + $total_fine + $others_fee;

    //New Transection Create
    $category = "Member Transections";
    $details = "Member ID- $member_id";
    $transections_add = array(
        "created_by" =>  $uid,
        "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
        "created_ip" => $ip,
        "entry_date" => $today,
        "member_id" => $member_id,
        "category" => $category,
        "details" => $details,
        "cash_in" => $total_transect_amount,
        "status" => "Paid",
    );
    if ($error_status == "") {
        $transections_submit = insert_data($conn, $transections_add, "transections");
        $transections_id = $transections_submit['last_id'];
        if ($savings_installment_total > 0) {
            $category = "Savings";
            $details = "Member ID- $member_id , AC ID- $savings_installment_ac";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => $savings_installment_ac,
                "account_title" => $member_result['name'],
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => $savings_installment_amount,
                "months" => $savings_installment,
                "month_name" => $savings_new_months,
                "cash_in" => $savings_installment_total,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }
        if ($share_installment_total > 0) {
            $category = "Share";
            $details = "Member ID- $member_id , AC ID- $share_installment_ac";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => $share_installment_ac,
                "account_title" => $member_result['name'],
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => $share_installment_amount,
                "months" => $share_installment,
                "month_name" => $share_new_months,
                "cash_in" => $share_installment_total,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }

        if ($admission_fee > 0) {
            $category = "Admission Fee";
            $details = "Member ID- $member_id";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => "",
                "account_title" => "",
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => "",
                "months" => "",
                "month_name" => "",
                "cash_in" => $admission_fee,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }
        if ($total_fine > 0) {
            $category = "Fine";
            $details = "Member ID- $member_id";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => "",
                "account_title" => "",
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => "",
                "months" => "",
                "month_name" => "",
                "cash_in" => $total_fine,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }
        if ($others_fee > 0) {
            $category = "Others";
            $details = "Member ID- $member_id";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => "",
                "account_title" => "",
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => "",
                "months" => "",
                "month_name" => "",
                "cash_in" => $others_fee,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }
        
        // Cash Increase
        if($total_transect_amount > 0){
            $cash_increase = array(
                "transection_id" => $transections_id,
                "entry_date" => $today,
                "category" => $category,
                "title" => $details,
                "increase" => $total_transect_amount,
                "decrease" => 0
            );
            
            insert_data($conn, $cash_increase, "cash");
        }

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $_SESSION['msg'] = "<div class='alert alert-success'>Transection Created Successfully</div>";
        echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>$error_status</div>";
    }
}
?>
<div class="mt-4">
    <h3 class="text-center">জমা</h3>
    <div id="showallmsg">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['sms'])) {
            echo $_SESSION['sms'];
            unset($_SESSION['sms']);
        }
        if (isset($message)) {
            echo $message;
        }
        ?>
    </div>
    <form action="" method="post" id="check_validation" enctype="multipart/form-data">
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-group">
                    <label for="account_number_search">একাউন্ট নম্বর</label>
                    <input class="form-control" name="account_number_search" id="account_number_search" required>
                </div>
            </div> -->
            <!-- <div class="col-md-6">
                <div class="form-group">
                    <label for="account_no">হিসাব নং</label>
                    <select name="account_no" id="account_no" class="form-control" required>
                        <option value="">Select account no</option>
                    </select>
                </div>
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-8 calculation_area">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="member_number_search">সদস্য আইডি      </label>
                            <input class="form-control" name="member_number_search" id="member_number_search"placeholder="Members Whose Accounts" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">নাম</label>
                            <input type="text" name="account_title" id="account_title" class="form-control" readonly required>
                        </div>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr class="bg-blue-light-800 color-white">
                            <th class="text-center" width='80px'>ক্রঃ নং</th>
                            <th class="text-center">বিবরণ</th>
                            <th class="text-center" width='200px'>টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">১।</td>
                            <td>
                                সঞ্চয় আমানত,
                                মাসিক-&nbsp; <span id="savings_installment_amount_txt" class="installment_amount_for_cal">0
                                </span> TK,<input type="hidden" name="savings_installment_amount" id="savings_installment_amount" value="0">
                                মাস-&nbsp;<input type="number" name="savings_installment" id="savings_installment" class="form-control installment_no_for_cal" style="width:80px; display:inline;" value="0">
                                <input type="hidden" name="savings_installment_due_fine" class="installment_due_status" id="savings_installment_due_fine" value="0">
                                <input type="hidden" name="savings_installment_ac" id="savings_installment_ac" value="0">
                            </td>
                            <td>
                                <input type="number" name="savings_installment_total" id="savings_installment_total" class="form-control installment_cal_total total_for_final_calculation" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">২।</td>
                            <td>ভর্তি ফি</td>
                            <td>
                                <input type="number" name="admission_fee" id="admission_fee" class="form-control total_for_final_calculation">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">৩।</td>
                            <td>
                                শেয়ার
                                মাসিক-&nbsp; <span id="share_installment_amount_txt" class="installment_amount_for_cal">0
                                </span> TK,<input type="hidden" name="share_installment_amount" id="share_installment_amount" value="0">
                                মাস-&nbsp;<input type="number" name="share_installment" id="share_installment" class="form-control installment_no_for_cal" style="width:80px; display:inline;" value="0">
                                <input type="hidden" name="share_installment_due_fine" class="installment_due_status" id="share_installment_due_fine" value="0">
                                <input type="hidden" name="share_installment_ac" id="share_installment_ac" value="0">
                            </td>
                            <td>
                                <input type="number" name="share_installment_total" id="share_installment_total" class="form-control installment_cal_total total_for_final_calculation" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">৪।</td>
                            <td>জরিমানা
                            </td>
                            <td>
                                <input type="number" name="total_fine" id="total_fine" class="form-control total_for_final_calculation">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">৫।</td>
                            <td>বিবিধ</td>
                            <td>
                                <input type="number" name="others_fee" id="others_fee" class="form-control total_for_final_calculation">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">মোট</td>
                            <td>
                                <input type="number" name="total_fees" id="total_fees" class="form-control" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="detils">বিবরণ</label>
                            <textarea type="text" name="detils" id="detils" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="depositSMS" name="depositSMS" value="1">
                    <label class="custom-control-label" for="depositSMS">SMS</label>&nbsp;&nbsp;
                    <!-- <label class="" for="depositSMS">(Your Savings account has been created)</label> -->
                </div>
                <div class="text-center">
                    <?php //echo isset($csrf_input) ? $csrf_input : ""; 
                    ?>
                    <input type="submit" value="Submit" name="new_transection" class="btn btn-success" id="new_transection">
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <ul id="memberDetails" class="list-group">
                                <li class="list-group-item">
                                    <!-- Media top -->
                                    <div class="media">
                                        <img src="assets/images/img/demo.jpg" id="member_image" class="align-self-start mr-3" height="150px">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <ul id="accountinfo" class="list-group">
                                <li class="list-group-item">
                                    <!-- Media top -->
                                    <div class="media">
                                        <div class="media-body">
                                            <p>
                                                <strong><span id="member_name"></span></strong><br>
                                                <span>সদস্য নং : </span> <span id="member_id"></span><br>
                                                <span>মোবাইল : </span> <span id="member_mobile"></span><br>
                                            </p>
                                            <p>
                                                <span>সঞ্চয় কিস্তি: </span> <span id="savings_installment_text2"></span> <span id="savings_side_edit_btn"></span><br>
                                                <span>সঞ্চয় ব্যালেন্স: </span> <span id="savings_balance"></span><br>
                                                <span>সঞ্চয় সর্ব শেষ : </span> <span id="savings_last_paid"></span><br>
                                                <span>সঞ্চয় বকেয়া : </span> <span id="savings_due"></span> মাস<br>
                                                <span id="savings_details"></span>
                                            </p>
                                            <p>
                                                <span>শেয়ার কিস্তি: </span> <span id="share_installment_text2"></span> <span id="share_side_edit_btn"></span><br>
                                                <span>শেয়ার মোট: </span> <span id="share_balance"></span><br>
                                                <span>শেয়ার সর্ব শেষ : </span> <span id="share_last_paid"></span><br>
                                                <span>শেয়ার বকেয়া : </span> <span id="share_due"></span> মাস<br>
                                                <span id="share_details"></span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>
<script>
    $(document).ready(function() {
        var saving_fine = <?php echo $savings_fine; ?>;
        var saving_fine_month = <?php echo $savings_month; ?>;
        var loan_fine = <?php echo $loan_fine; ?>;
        var loan_fine_month = <?php echo $loan_month; ?>;
        $("#bkash_field").hide();
        $("#bank_field").hide();
        $("#staff_id").attr("required", "required");

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            clearfield();
        })

        function clearfield() {
            $('.member_id').each(function() {
                $(this).val("");
            })

            $("#account_type").val("");
            $("#account_title").val("");
            $("#total_balance").val("");
            $("#account_no_val").val("");


            $('#member_id2').html("");
            $('#member_mobile').html("");



            clear_acc_info();
            $('#savings_list').html("");
            $('#deposit_list').html("");
            $('#loan_list').html("");

            $('#showallmsg').html("");

            $('#member_name').html("");
            $('#member_id2').html("");
            $('#member_mobile').html("");
            $('#member_branch').html("");
            $('#member_center').html("");
            // $('#last_transection_date').html("");
            $('#member_image').attr("src", "assets/images/img/demo.jpg");
            $('#savings_form').trigger("reset");
            $('#deposit_form').trigger("reset");
            $('#loan_form').trigger("reset");
        }

        function clear_acc_info() {
            $('#account_created').html("");
            $('#last_transection_date').html("");
            $('#total_installment').html("");
            $('#total_installment_paid').html("");

            $("#account_type").val("");
            $("#account_title").val("");
            $("#total_balance").val("");
            $("#account_no_val").val("");


            $('#member_id2').html("");
            $('#member_mobile').html("");
        }

        $("#member_number_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "controllers/index.php",
                        type: 'post',
                        dataType: "json",
                        data: {
                            // searchmember_4_pay: request.term
                            member_search_for_earnings: request.term,
                            account_search_type: ["Savings", "Share"]
                        },
                        success: function(data) {
                            console.log(data)
                            response(data);
                        },
                        error: function(d) {
                            console.log(d);
                        }
                    });
                },
                select: function(event, ui) {
                    // clearfield();
                    $(this).val(ui.item.value);
                    account_infos(ui.item.value)
                    // $('#savings_list').html(ui.item.data.savings);
                    // $('#deposit_list').html(ui.item.data.deposit);
                    // $('#loan_list').html(ui.item.data.loan);

                    // $('#account_no').find('option').remove().end().append(ui.item.data.option).val('');

                    // $('#member_name').html(ui.item.label);
                    // $(this).parent().find('.member_id').val(ui.item.value);
                    // $('#member_id2').html(ui.item.value);
                    // $('#member_mobile').html(ui.item.data.mobile);
                    // $('#member_branch').html(ui.item.data.branch);
                    // $('#member_center').html(ui.item.data.center);
                    // $('#member_image').attr("src", ui.item.data.image);
                    // console.log(ui.item.data);
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.label + "<br><small>" + item.desc + "</small></div>")
                    .appendTo(ul);
            };;

        //Account Info
        function account_infos(account_number) {
            clear_acc_info()
            var accoutn_no = account_number;
            $.ajax({
                type: "POST",
                url: "controllers/index.php",
                data: {
                    details_account_info: accoutn_no
                },
                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response);
                    sortvalues(response);
                },
                error: function(e) {
                    console.log(e);
                }
            });

        }
        window.account_infos = account_infos;

        function sortvalues(e) {
            if (e.error == "") {
                var total_due_amount = 0;

                // CLEAR PREVIOUS DATA (Fixing Cache Issue)
                $("#savings_installment_amount").html("0");
                $("#savings_installment_amount_txt").html("0");
                $("#savings_installment_text2").html("0");
                $("#savings_balance").html("");
                $("#savings_last_paid").html("");
                $("#savings_due").html("");
                $("#savings_installment_ac").val("0");
                $("#savings_details").html("");
                $("#savings_side_edit_btn").html(""); // Clear side edit button

                $("#share_installment_amount").html("0");
                $("#share_installment_amount_txt").html("0");
                $("#share_installment_text2").html("0");
                $("#share_balance").html("");
                $("#share_last_paid").html("");
                $("#share_due").html("");
                $("#share_installment_ac").val("0");
                $("#share_details").html("");
                $("#share_side_edit_btn").html(""); // Clear side edit button


                if (e.Savings) {
                   // $("#savings_installment_total").val(e.Savings.installment)
                    $("#savings_installment_amount").html(e.Savings.installment)
                    $("#savings_installment_amount_txt").html(e.Savings.installment)
                    $("#savings_installment_text2").html(e.Savings.installment)
                    $("#savings_balance").html(e.Savings.currentBalance)
                    $("#savings_last_paid").html(e.Savings.last_paid_month)
                    $("#savings_due").html(e.Savings.due_months)
                    // $("#savings_installment_due_fine").val(e.Savings.due_amount)
                    $("#savings_installment_ac").val(e.Savings.id)
                    total_due_amount += parseInt(e.Savings.due_amount);
                    
                   var savings_id = parseInt(e.Savings.id);
                   
                     $("#savings_details").html(
  "<a target='_blank' class='btn btn-sm btn-primary mb-1' href='index.php?action=account_info&id=" +
  savings_id +
  "'>View</a>"
);
                    // Add Edit Button for Savings
                    var savings_edit_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-info ml-1" onclick="openEditInstallmentModal('+e.Savings.id+', '+e.Savings.installment+', \'Savings\')"><i class="fa fa-edit"></i></a>';
                    $("#savings_side_edit_btn").html(savings_edit_btn);

                }
                if (e.Share) {
                //    $("#share_installment_total").val(e.Share.installment)
                    $("#share_installment_amount").html(e.Share.installment)
                    $("#share_installment_amount_txt").html(e.Share.installment)
                    $("#share_installment_text2").html(e.Share.installment)
                    $("#share_balance").html(e.Share.currentBalance)
                    $("#share_last_paid").html(e.Share.last_paid_month)
                    $("#share_due").html(e.Share.due_months)
                   // $("#share_installment_due_fine").val(e.Share.due_amount)
                    $("#share_installment_ac").val(e.Share.id)
                    total_due_amount += parseInt(e.Share.due_amount);
                  
                  var share_id = parseInt(e.Share.id);
                   
                     $("#share_details").html(
  "<a target='_blank' class='btn btn-sm btn-primary mb-1' href='index.php?action=account_info&id=" +
  share_id +
  "'>View</a>"
);

                  // Add Edit Button for Share
                  var share_edit_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-info ml-1" onclick="openEditInstallmentModal('+e.Share.id+', '+e.Share.installment+', \'Share\')"><i class="fa fa-edit"></i></a>';
                  $("#share_side_edit_btn").html(share_edit_btn);
                  
                  
                  
                  
                  
                }
                var first_fine = 0;
                // $("#account_type").val(e.success.account_type);
                $("#account_title").val(e.members_info.name);
               // $("#total_fine").val(total_due_amount);
                // $("#total_balance").val(e.currentBalance);
                // $("#account_no_val").val(e.success.id);


                $('#member_name').html(e.members_info.name);
                $('#member_id').html(e.members_info.id);
                $('#member_mobile').html(e.members_info.mobile);
                
                // Set member image if available
                if(e.members_info.member_photo && e.members_info.member_photo != '') {
                    $('#member_image').attr("src", e.members_info.member_photo);
                } else {
                    $('#member_image').attr("src", "assets/images/img/demo.jpg");
                }
                /*

                $("#payment_for_ac_no").val(e.success.account_no);
                $("#payment_for").val(e.success.payment_type);
                $("#account_no_val").val(e.success.id);
                $("#no_of_due_month").val(e.due_payment_month);
                $('#account_created').html(e.success.opening_date);
                $('#last_transection_date').html(e.date_last_trn);
                $('#total_installment').html(e.total_installment);
                $('#total_installment_paid').html(e.transection_no);
                $('#total_installment_time_pass').html(e.show_time_passed);
                // if (e.success.account_type == "savings") {
                //     $("#basic_amount").val(e.success.basic_amount);
                //     $("#profit_tk").val(0);
                //     $("#profit_tk").attr("disabled", true);
                //     $("#installment_number").val(1);
                //     if (saving_fine_month < e.due_payment_month) {
                //         first_fine = saving_fine;
                //     }
                //     $total_amount = parseInt(e.success.basic_amount) + first_fine;
                //     $("#total_tk").val($total_amount);
                // } else {
                //     $("#profit_tk").attr("disabled", false);
                //     var basict_amount = parseInt(e.success.installment);
                //     var profit = parseInt(e.success.profit) / parseInt(e.success.installment);

                //     $("#basic_amount").val(e.success.installment);
                //     $("#profit_tk").val(profit);
                //     $("#installment_number").val(1);
                //     if (loan_fine_month < e.due_payment_month) {
                //         first_fine = Math.ceil((e.success.installment * loan_fine) / 100);
                //     }
                //     // console.log(basict_amount);
                //     var round_figure = (basict_amount + profit + first_fine).toFixed(0);
                //     $("#total_tk").val(round_figure);
                // }
                // $("#fine_tk").val(first_fine);
                submit_enable()
                */
            } else {
                $("#payment_for_ac_no").val("");
                $("#payment_for").val("");
                $("#account_no_val").val("");
                $("#account_type").val("");
                $("#no_of_due_month").val("");
                $('#account_created').html("");
                $('#last_transection_date').html("");
                $('#total_installment').html("");
                $('#total_installment_paid').html("");
                $("#basic_amount").val("");
                $("#profit_tk").val(0);
                $("#profit_tk").attr("disabled", true);
                $("#installment_number").val(0);
                $("#total_tk").val(0);
                $('#total_installment_time_pass').html("");
                $("#fine_tk").val(0);
                // submit_enable()
            }
            calculate_money()
        }
        $("#installment_number").on("change keyup", function() {
            calculate_money();
        });
        $("#basic_amount").on("change keyup", function() {
            calculate_money();
        });
        $("#profit_tk").on("change keyup", function() {
            calculate_money();
        });
        $(".calculation_area input").on("change keyup", function() {
            calculate_money();
        });
        $(".installment_no_for_cal").on("change keyup", function() {
            var installment_amount = parseInt($(this).closest("tr").find(".installment_amount_for_cal").html());
            var installment_no = $(this).val();
            $(this).closest("tr").find(".installment_cal_total").val(installment_no * installment_amount);
            calculate_money();
        });


        function calculate_money() {
            var basic = parseInt(($("#basic_amount").val()) < 1 ? 1 : $("#basic_amount").val());
            var installment = parseInt($("#installment_number").val());
            var no_of_due_month = parseFloat($("#no_of_due_month").val());
            var ac_type = $("#account_type").val();
            var fine_tk = 0;
            if (ac_type == "savings") {
                var ck_fine_month = no_of_due_month - saving_fine_month;
                if (saving_fine_month < no_of_due_month) {
                    for (let index = 1; index <= installment; index++) {
                        if (index <= ck_fine_month) {
                            fine_tk += saving_fine;
                        }
                    }
                }
            } else {
                var ck_fine_month = no_of_due_month - loan_fine_month;
                ck_fine_month = (Math.ceil(ck_fine_month))
                if (loan_fine_month < no_of_due_month) {
                    for (let index = 1; index <= installment; index++) {
                        if (index <= ck_fine_month) {
                            // fine_tk += loan_fine;
                            fine_tk += Math.ceil((basic * loan_fine) / 100);
                            // $ck_fine_percent = Math.ceil((basic*loan_fine)/100);
                            // console.log(loan_fine);
                        }
                    }
                }
            }

            $("#fine_tk").val(fine_tk);
            // var fine_tk = parseInt(($("#fine_tk").val()) < 0 ? 0 : $("#fine_tk").val());
            $("#total_tk").val((basic * installment) + fine_tk);
            var total_fine_value = 0;
            $(".installment_no_for_cal").each(function() {
                if ($(this).val() > 0) {
                    total_fine_value += parseInt($(this).closest("tr").find(".installment_due_status").val()) || 0;
                }
            })

           // $("#total_fine").val(total_fine_value);

            var total_for_final_calculation = 0;
            $(".total_for_final_calculation").each(function() {
                total_for_final_calculation += parseInt($(this).val()) || 0;
            })
            $("#total_fees").val(total_for_final_calculation);
            submit_enable()
        }
        //Enable Submit Button
        function submit_enable() {
            var receivable = $("#total_fees").val();
            var paid = $("#total_paid_amount").val();
            if (receivable > 0) {
                $("#new_transection").attr("disabled", false)
            } else {
                $("#new_transection").attr("disabled", true)
            }
        }


        // $("#new_transection").attr("disabled", true)
    });

    // Edit Installment Modal Functions
    var currentEditAccountId = 0;
    
    function openEditInstallmentModal(accountId, currentInstallment, type) {
        currentEditAccountId = accountId;
        $('#editInstallmentModal').modal('show');
        $('#edit_installment_val').val(currentInstallment);
        $('#editInstallmentModalLabel').text(type + ' কিস্তির পরিমাণ সম্পাদনা করুন');
    }

    function saveInstallment() {
        var newInstallment = $('#edit_installment_val').val();
        
        // Validation
        if(newInstallment == '' || newInstallment <= 0) {
            alert('দয়া করে একটি বৈধ পরিমাণ লিখুন');
            return;
        }

        // AJAX call to update installment
        $.ajax({
            url: 'update_installment.php', // Adjusted path to relative current directory (since index.php is in assets/)
            type: 'POST',
            data: {
                account_id: currentEditAccountId,
                installment: newInstallment
            },
            success: function(response) {
                var result = response;
                if (typeof result === 'string') {
                    try {
                        result = JSON.parse(result);
                    } catch (e) {
                         // Fallback if already object
                    }
                }

                if(result.success) {
                    alert('মাসিক কিস্তির পরিমাণ সফলভাবে আপডেট হয়েছে');
                    $('#editInstallmentModal').modal('hide');
                    // Refresh the account info to show new values
                    var memberId = $("#member_number_search").val();
                    if(memberId) {
                         // Extract ID if it's in "ID - Name" format, though autocomplete usually gives value as ID
                         // Based on select: function(event, ui) { $(this).val(ui.item.value); ... }
                         // The value in the input is likely just the ID.
                         // account_infos(memberId);
                         // Actually, let's just trigger the search select event or call account_infos directly
                         // memberId might be "100" or "100 - Name". 
                         // The code calls account_infos(ui.item.value). 
                         // Let's assume input text is the ID.
                         account_infos(memberId);
                    }
                } else {
                    alert('ত্রুটি: ' + (result.message || 'অজানা ত্রুটি'));
                }
            },
            error: function() {
                alert('আপডেট করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
            }
        });
    }
</script>

<!-- Edit Installment Modal -->
<div class="modal fade" id="editInstallmentModal" tabindex="-1" role="dialog" aria-labelledby="editInstallmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editInstallmentModalLabel">কিস্তির পরিমাণ সম্পাদনা করুন</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_installment_val">নতুন মাসিক কিস্তির পরিমাণ (টাকা):</label>
                    <input type="number" class="form-control" id="edit_installment_val" placeholder="মাসিক কিস্তির পরিমাণ লিখুন" min="0" step="0.01">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-primary" onclick="saveInstallment()">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>
</div>
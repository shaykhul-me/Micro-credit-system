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

if (isset($_POST['account_number_search']) && isset($_POST['account_title'])) {
    if ($_POST['account_number_search'] != "") {
        $account_id = $_POST['account_number_search'];
        $account_title = $_POST['account_title'];
        
        $withdraw_amount = $_POST['withdraw_amount'];
        $detils = $_POST['detils'];
        $payment_type = ""; 

        $payment_photo = "";
        
        
        $payment_for_val = "";
        $payment_for_short = "";
        $paid_status = array();

        //SMS Have to work
        $sms_status = isset($_POST['depositSMS']) ? $_POST['depositSMS'] : 0;
        
        


        $accountinfo = single_condition_select("accounts", "account_no", $account_id);
        $category = "";

        if ($accountinfo['count'] == 1) {
            $account_res = mysqli_fetch_assoc($accountinfo['query']);
            $account_no = $account_res['account_no'];
           
            
            $installment_profit = 0;
            $final_ac_no = $account_res['account_no'];

            $array = array(
                "created_by" =>  $uid,
                "created_at" =>   date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => $account_res['id'],
                "account_no" => $final_ac_no,
                "account_title" =>$account_res['account_title'],
                // "basic_amount" => $basic_amount,
                // "payment_for" => $payment_for_val,
                // "installment_no" => $installment_no,
                // "installment_time" => $installment_time,
                // "payment_type" => $payment_type,
                // "short_details" => $payment_for_short,
                "details" => $detils,
                // "payment_info" => $payment_info,
                // "payment_photo" => $payment_photo,
                // "fine" => 0, //$fine
                "cash_out" => $withdraw_amount,
                // "profit" => $profit,
                "status" => "Paid",
                // "branch_id" => $branch
            );
            //Branch Account Information Start


        
            
            if (!array_key_exists('error', $paid_status)) {

                $new_transection = insert_data($conn, $array, "transections");
                if ($new_transection['last_id']) {
                    
                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $_SESSION['msg'] = "<div class='alert alert-success'>Transection Created Successfully</div>";
                    echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
                    exit;
                }
            } else {
                echo "<div class='alert alert-danger'>{$paid_status['error']}</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Account did not found</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Account NO not Selected Properly</div>";
    }
}
?>
<div class="mt-4">
    <h3 class="text-center text-danger rounded">উত্তোলন</h3>
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
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_number_search">একাউন্ট নম্বর</label>
                            <input class="form-control" name="account_number_search" id="account_number_search" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_type">একাউন্টের ধরণ</label>
                            <input type="text" name="account_type" id="account_type" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="account_title">একাউন্টের শিরোনাম</label>
                            <input type="text" name="account_title" id="account_title" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_balance">বর্তমান ব্যলেন্স</label>
                            <input type="text" name="total_balance" id="total_balance" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="withdraw_amount">উত্তোলনের পরিমাণ</label>
                            <input type="text" name="withdraw_amount" id="withdraw_amount" class="form-control" required>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="basic_amount">ধার্যকৃত টাকা</label>
                            <input type="number" name="basic_amount" id="basic_amount" class="form-control" required readonly>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="profit_tk">লভ্যাংশের টাকা</label>
                            <input type="number" name="profit_tk" id="profit_tk" class="form-control" readonly>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="installment_number">কিস্তি</label>
                            <input type="number" name="installment_number" id="installment_number" class="form-control" required>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="fine_tk">জরিমানা</label>
                            <input type="number" name="fine_tk" id="fine_tk" class="form-control" readonly>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_tk">মোট টাকা</label>
                            <input type="number" name="total_tk" id="total_tk" class="form-control" required readonly>
                        </div>
                    </div> -->
                </div>
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
                                                <span>সদস্য নং : </span> <span id="member_id2"></span><br>
                                                <span>মোবাইল : </span> <span id="member_mobile"></span><br>
                                                <!-- <span>অফিসের নাম : </span> <span id="member_branch"></span><br>
                                                <span>ব্রাঞ্চের নাম : </span> <span id="member_center"></span><br> -->
                                                <!-- <span>সঞ্চয় হিসাব নং : <span id="savings_list"></span></span><br>
                                                <span>মেয়াদী হিসাব নং : <span id="deposit_list"></span></span><br>
                                                <span>ঋণ এর হিসাব নং : <span id="loan_list"></span></span><br> -->
                                                <!-- <span>হিসাব শুরু : </span> <span id="account_created"></span><br>
                                                <span>সর্বশেষ জমা : </span> <span id="last_transection_date"></span><br>
                                                <span>সর্বমোট কিস্তি : </span> <span id="total_installment"></span><br>
                                                <span>সর্বমোট কিস্তি পরিশোধ : </span> <span id="total_installment_paid"></span><br> -->
                                                <!-- <span>সময়: </span> <span id="total_installment_time_pass"></span><br>                                                 -->
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
        $("#account_number_search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "controllers/index.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        // searchmember_4_pay: request.term
                        account_search_for_earnings: request.term,
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
                account_infos(ui.item.data.id)
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
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div>" + item.desc + "<br><small>" + item.label + "</small></div>" )
            .appendTo( ul );
        };
        ;

        //Account Info
        function account_infos(account_number) {
            clear_acc_info()
            var accoutn_no = account_number;
            $.ajax({
                type: "POST",
                url: "controllers/index.php",
                data: {
                    account_info_4_withdraw: accoutn_no
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

        function sortvalues(e) {
            if (e.error == "") {
                var first_fine = 0;
                $("#account_type").val(e.success.account_type);
                $("#account_title").val(e.success.account_title);
                $("#total_balance").val(e.currentBalance);
                $("#account_no_val").val(e.success.id);


                $('#member_id2').html(e.members_info.id);
                $('#member_mobile').html(e.members_info.mobile);
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
        $("#total_tk").on("change keyup", function() {
            var basic = parseInt(($("#basic_amount").val()) < 1 ? 1 : $("#basic_amount").val());
            var totaltk = parseInt(($(this).val()) < 1 ? 1 : $(this).val());
            var profit_tk = parseInt(($("#profit_tk").val()) < 0 ? 0 : $("#profit_tk").val());
            var fine_tk = parseInt(($("#fine_tk").val()) < 0 ? 0 : $("#fine_tk").val());
            $("#installment_number").val(((totaltk - fine_tk) - profit_tk) / basic);
            submit_enable()
        })

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
            submit_enable()
        }
        //Total Paid Calculate
        $(".pay_amount").on("keyup change", function() {
            // console.log("hello");
            var sum = 0;
            $('.pay_amount').each(function() {
                sum += $(this).val() ? Math.abs(parseFloat($(this).val())) : 0; // Or this.innerHTML, this.innerText
            });
            var max_ck = $("#total_tk").val();
            if (sum > max_ck) {
                alert("Amount Can't be greater than Deposit amount")
                $("#total_paid_amount").val(sum);
                // $("#new_deposit").attr("disabled", true);
            } else {
                $("#total_paid_amount").val(sum);
                // $("#new_deposit").attr("disabled", false);
            }
            var bkash = $("#bkash_amount").val();
            var nagad = $("#nagad_amount").val();
            var bank = $("#bank_amount").val();
            if (bkash > 0) {
                $("#bkash_transect_id").attr("required", true);
            } else {
                $("#bkash_transect_id").attr("required", false);
            }
            if (nagad > 0) {
                $("#nagad_transect_id").attr("required", true);
            } else {
                $("#nagad_transect_id").attr("required", false);
            }
            if (bank > 0) {
                $("#bank_payment_photo").attr("required", true);
            } else {
                $("#bank_payment_photo").attr("required", false);
            }


            submit_enable()
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
            submit_enable()
        })
        //Enable Submit Button
        function submit_enable() {
            // var receivable = $("#total_tk").val();
            // var paid = $("#total_paid_amount").val();
            // if (receivable == paid) {
            // $("#new_transection").attr("disabled", false)
            // } else {
            //     $("#new_transection").attr("disabled", true)
            // }
        }


        // $("#new_transection").attr("disabled", true)
    });
</script>
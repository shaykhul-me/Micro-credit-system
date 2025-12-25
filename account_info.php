<?php
$id = $_GET['id'];
//Only for close account
if (isset($_GET['ac_status']) && $_GET['ac_status'] == 'close_account') {
    echo "<h3 style='color:red;text-align:center;'>XX CLOSE ACCOUNT XX</h3>";
}
$account_info_sql = single_condition_select("accounts", "id", $id);
$account_info_result = mysqli_fetch_assoc($account_info_sql['query']);
// if ($ac_type != "admin" && $user_other_info['branch_id'] != $account_info_result['branch']) {
//     echo "<div class='alert alert-danger'>Sorry You have no permission to see this</div>";
// } else {

    $member_info = single_condition_select("members", "id",$account_info_result['member_id']);
    $member_result = mysqli_fetch_assoc($member_info['query']);

    $transection_sql = single_condition_select("transections_details", "account_id", $id);

    // $profit_sql = single_condition_select("account_profits", "ac_id", $id);

    // $withdraw_sql = single_condition_select("withdraw_profit", "account_no", $id);
    // $withdraw_sql = double_condition_select("withdraw_profit", "account_no", $id, "withdraw_type", "profit_withdraw");

    // $withdraw_main_sql = double_condition_select("withdraw_profit", "account_no", $id, "withdraw_type", "Main Balance Withdraw");
    // $withdraw_main_sql = manual("withdraw_profit", "account_no", $id, "withdraw_type", "Main Balance Withdraw");

    //    $withdraw_main_balance = double_condition_select("withdraw_profit", "account_no", $id,"withdraw_type","Main Balance Withdraw");


    // $office_sql = single_condition_select("office", "id", $account_info_result['office']);
    // $office_res = mysqli_fetch_assoc($office_sql['query']);

    // $branch_sql = single_condition_select("branch", "id", $account_info_result['branch']);
    // $branch_res = mysqli_fetch_assoc($branch_sql['query']);

    // $staff_ref_sql = single_condition_select("staffs", "staffid", $account_info_result['staff_ref']);
    // $staff_ref_res = mysqli_fetch_assoc($staff_ref_sql['query']);
?>
    <style>
        .accordion_title {
            color: #007F83;
            background-color: #E4E1E7;
            border-radius: 10px 10px 0px 0px;
            cursor: pointer;
        }

        #accordian_body {
            background-color: #F7FFFE;
            border-radius: 0px 0px 10px 10px;
        }
    </style>
    <div class='row'>
        <div id='printableArea' class='col-md-12'>
            <div class='text-center font-weight-bold font-weight-bolder'>
                <h3 class='font-weight-bold font-weight-bolder text-capitalize'><?php echo $account_info_result['account_type']; ?></u></h3>
                <h5>Name : <a target="_blank" href="index.php?action=member_view_single&id=<?php echo $member_result['id']; ?>"><u><?php echo isset($member_result['name']) ? $member_result['name'] : ""; ?></u></a>&nbsp;&nbsp; Mobile : <?php echo isset($member_result['mobile']) ? $member_result['mobile'] : ""; ?></h5>
            </div>
            <div class="row">
                <div class="col-md-4">হিসাবের ধরনঃ <span class="font-weight-bold"><?php echo $account_info_result['account_type'] ?></span></div>
                <div class="col-md-4">শুরুর দিনঃ <span class="font-weight-bold"><?php echo $account_info_result['opening_date'] ?> </span></div>
                <div class="col-md-4">মাসিক কিস্তির পরিমাণঃ <span class="font-weight-bold"><?php echo $account_info_result['installment'] ?></span> টাকা 
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary ml-2" onclick="openEditInstallmentModal()" title="Edit Installment">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <div class="col-md-4">জমাঃ <span id="total_paid" class="font-weight-bold"></span> টাকা</div>
                <!-- <div class="col-md-4">মুনাফাঃ <span id="total_profit" class="font-weight-bold"></span> টাকা</div> -->
                <!-- <div class="col-md-4">মুনাফা উত্তোলন/কর্তনঃ <span id="withdraw_profit" class="font-weight-bold"></span> টাকা</div> -->
                <!-- <div class="col-md-4">মোট জমাঃ <span id="total_paid_profit" class="font-weight-bold"></span> টাকা</div> -->
                <div class="col-md-4">জমা উত্তোলন : <span id="maintaka_withdraw" class="font-weight-bold"></span> টাকা</div>
                <div class="col-md-4">নীট জমাঃ <span id="net_taka" class="font-weight-bold"></span> টাকা</div>
                <!-- <div class="col-md-4">নীট মুনাফাঃ <span id="net_profit" class="font-weight-bold"></span> টাকা</div> -->

                <!-- <div class="col-md-4">অফিসঃ <span class="font-weight-bold"><?php //echo $office_res['name']; ?></span></div>
                <div class="col-md-4">ব্রাঞ্চঃ <span class="font-weight-bold"><?php //echo $branch_res['branch_name']; ?></span></div>
                <div class="col-md-4">রেফার সদস্যঃ <span class="font-weight-bold"><?php //echo $staff_ref_res['name']; ?></span></div> -->
            </div>
            <hr>
        </div>
    </div>
    <h3 class="text-center">অর্থ লেনদেনের তালিকা</h3>
    <div class="table-responsive">
        <table class="table table-light table-striped table-hover" id="transect_table">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>Memo ID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Month name</th>
                    <th>Installments</th>
                    <th>Fine</th>
                    <th>Total Tk</th>
                    <th>Withdraw</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $check_working_info = array();
                $all_installment = "";
                $jj = 1;
                $ck_status_rd = array();
                $fine = 0;
                $total_savings_sum = 0;
                $total_profit = 0;
                $check_working_info['time'] = "";
                $fine_deduction = 0;
                $withdraw_main_balance_sum = 0;
                $withdraw_profit_from_transection = 0;
                $withdraw_total_from_transection = 0;
                while ($transection_result = mysqli_fetch_assoc($transection_sql['query'])) {
                    $month_names = "";
                    $withdraw_total_from_transection += $transection_result['cash_out'];
                $months = $transection_result['months'];
                    if ($transection_result['months'] > 0) {
                        $all_installment .= $transection_result['month_name'] . ",";


                        //Other Raugh Claculation;

                        $ck_status = $transection_result['month_name'];
                        $ck_status_nd = explode(",", $ck_status);
                        $check_working_info['time'] .= $ck_status;
                        foreach ($ck_status_nd as $key => $value) {
                            $ck_status_rd[$value] = $transection_result['status'];
                            $month_names .= date("F-Y", strtotime($value)) . "&nbsp;";
                        }
                    }

                    $color = "";
                    if ($transection_result['status'] == "approved") {
                        $color = "table-success";
                    } else {
                        $color = "table-secondary";
                    }
                    if ($transection_result['details'] == "main_balance_withdraw") {
                        $withdraw_main_balance_sum += $transection_result['cash_out'];
                    }
                    if ($transection_result['details'] == "profit_withdraw") {
                        $withdraw_profit_from_transection += $transection_result['cash_out'];
                    }
                ?>
                    <tr class="text-center <?php echo $color; ?>">
                        <td><?php echo $transection_result['id']; ?></td>
                        <td><?php echo $transection_result['created_at']; ?></td>
                        <td><?php echo $transection_result['details']; ?></td>
                        <td><?php echo $month_names; ?></td>
                        <td><?php echo $months; ?></td>
                        <td><?php //echo $transection_result['fine']; ?></td>
                        <td><?php echo $transection_result['cash_in']; ?></td>
                        <td><?php echo $transection_result['cash_out']; ?></td>
                        <td><?php echo $transection_result['status']; ?></td>
                        <td></td><!--  <a href="javascript:void(0)" class="btn btn-info btn-sm">View</a>-->
                    </tr>
                <?php
                    if ($transection_result['months'] < 1) {
                        $total_profit += $transection_result['cash_in'];
                    }
                    //$fine += $transection_result['fine'];
                    $total_savings_sum += $transection_result['cash_in'];
                    $jj++;
                }
                // $total_savings = $total_savings_sum - $fine;
                $total_savings = $total_savings_sum;
                ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- <div id="accordion" class="mb-2">
        <h5 class="mb-0 accordion_title p-3" data-toggle="collapse" data-target="#accordian_body" aria-expanded="true" aria-controls="accordian_body">
            মাস অনুযায়ী পরিশোধের তথ্য <i class="fa fa-arrow-down float-right mt-1" aria-hidden="true" id="accordion_arrow"></i>
        </h5>
        <div id="accordian_body" class="collapse p-3" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="row">
                <?php
                // $array_installment = explode(",", $all_installment);
                // $last_one =  end($array_installment);
                // $last_second = prev($array_installment);

                // $month = strtotime($account_info_result['opening_date']);
                // if ($last_second > $datetime) {
                //     $end = strtotime($last_second);
                //     $end_chk = $last_second;
                // } else {
                //     $end = strtotime($datetime);
                // }
                // $other_ck = date("Y-m", strtotime("+1 month", $end));
                // $other_ck = strtotime($other_ck);
                // $i = 0;
                // while ($month < $other_ck) {

                //     $find_date = date('Y-m', $month);
                //     $end_date_ck = date('Y-m', $end);
                //     if (in_array($find_date, $array_installment)) {
                //         if ($find_date > $datetime) {
                //             $color = "bg-success";
                //             $status = "Advanced";
                //         } else {
                //             $color = "bg-info";
                //             $status = "Paid";
                //         }
                //     } else {
                //         $color = "bg-danger";
                //         $status = "Not Paid";
                //     }
                //     $check_date_for_month_error = date("d", $month);
                //     if ($check_date_for_month_error > 28) {
                //         $month = strtotime("-3 day", $month);
                //     }
                ?>
                    <div class="col-md-1 col-3 p-1">
                        <div class="card text-white mb-3 <?php //echo $color; ?>">
                            <div class="card-header p-1 text-center font-weight-bold"><?php //echo date('Y', $month), PHP_EOL; ?><br><?php //echo date('F', $month), PHP_EOL; ?></div>
                            <div class="card-body p-1 m-0">
                                <p class="card-text text-center">
                                    <?php 
                                    // echo $status;
                                    // echo "<br>";
                                    // $ck_date = date("Y-m", $month);

                                    // if (array_key_exists($ck_date, $ck_status_rd)) {
                                    //     if ($ck_status_rd[$ck_date] == "approved") {
                                    //         echo "<span class=''>$ck_status_rd[$ck_date]</span>";
                                    //     }
                                    // } else {
                                    //     echo "<span class='text-dark'>Unpaid</span>";
                                    // }
                                    // $i++;
                                    ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                //     $month = strtotime("+1 month", $month);
                // }
                ?>
            </div>
        </div>
    </div> -->
    <hr>
    <div class="row">
        <!--<div class="col-md-6">
        <h3 class="text-center">মুনাফা আয়ের তালিকা</h3>
        <table class="table table-light table-striped table-hover table-responsive">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>Sl No</th>
                    <th>Date</th>
                    <th>Total Profit</th>
                    <th>Balance</th>
                    <th>Profit Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // $jj = 1;
                // $total_profit = 0;
                // while ($profit_res = mysqli_fetch_assoc($profit_sql['query'])) {
                ?>
                    <tr class="text-center">
                        <td><?php //echo $jj; 
                            ?></td>
                        <td><?php //echo date("d-m-Y", strtotime($profit_res['created_at'])); 
                            ?></td>
                        <td><?php //echo $profit_res['total_profit']; 
                            ?></td>
                        <td><?php //echo $profit_res['current_balance']; 
                            ?></td>
                        <td><?php //echo $profit_res['profit_amount']; 
                            ?></td>
                    </tr>
                <?php
                //     $total_profit += $profit_res['profit_amount'];
                //     $jj++;
                // }
                ?>
            </tbody>
        </table>
    </div>
     <div class="col-md-6">
        <h3 class="text-center">মুনাফা উত্তোলনের তালিকা</h3>
        <table class="table table-light table-striped table-hover table-responsive">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>Sl No</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // $jj = 1;
                // $withdraw_profit = 0;
                // while ($withdraw_res = mysqli_fetch_assoc($withdraw_sql['query'])) {
                ?>
                    <tr class="text-center">
                        <td><?php //echo $jj; 
                            ?></td>
                        <td><?php //echo date("d-m-Y", strtotime($withdraw_res['created_at'])); 
                            ?></td>
                        <td><?php //echo $withdraw_res['amount']; 
                            ?></td>
                    </tr>
                <?php
                //     $withdraw_profit += $withdraw_res['amount'];
                //     $jj++;
                // }
                // $available_profit = $total_profit - $withdraw_profit;
                ?>
            </tbody>
        </table>
    </div> -->
    </div>


    <?php
    // $jj = 1;
    $withdraw_profit = 0;
    // // while ($withdraw_res = mysqli_fetch_assoc($withdraw_sql['query'])) {
    // //     $withdraw_profit += $withdraw_res['amount'];
    // //     $jj++;
    // // }

    // $withdraw_main = 0;
    // // while ($withdraw_main_res = mysqli_fetch_assoc($withdraw_main_sql['query'])) {
    // //     $withdraw_main += $withdraw_main_res['amount'];
    // //     $jj++;
    // // }
    // $available_profit = ($total_profit - $withdraw_profit) - $fine;
    // // echo $net_taka = ($total_savings_sum - 0) - $withdraw_main;
    // $total_savings_sum = number_format($total_savings_sum, 3, '.', '');
    // $net_taka = ($total_savings_sum - $withdraw_profit) - $withdraw_main;
    // // echo $withdraw_main-($total_profit);
    // $withdraw_main_cal = 0;
    $net_profit_cal = 0;
    // if ($withdraw_main > $total_profit) {
    //     // $withdraw_main_cal = $withdraw_main-($withdraw_profit+$fine);
    //     // $withdraw_main_cal = $withdraw_main;
    //     $withdraw_main_cal =  number_format($withdraw_main, 3, '.', '');
    //     $net_profit_cal = ($total_savings - $withdraw_total_from_transection);
    // } else {
    //     $net_profit_cal = $total_profit - ($withdraw_profit + $fine);
    // }
    ?>
    <script>
        $(document).ready(function() {
            //accordion script
            $('#accordian_body').on('hide.bs.collapse', function() {
                $("#accordion_arrow").removeClass("fa-arrow-up").addClass("fa-arrow-down");
            })
            $('#accordian_body').on('show.bs.collapse', function() {
                $("#accordion_arrow").removeClass("fa-arrow-down").addClass("fa-arrow-up");
            })

            //Total status Information 
            $("#total_paid").html("<?php echo $total_savings - $total_profit; ?>");
            $("#total_profit").html("<?php echo $total_profit; ?>");
            $("#withdraw_profit").html("<?php echo $withdraw_profit + $fine; ?>");
            // $("#withdraw_profit").html("<?php //echo (($total_savings-$total_profit)-$withdraw_main_cal)-($withdraw_profit+$fine); 
                                            ?>");
            // $("#net_profit").html("<?php //echo $available_profit; 
                                        ?>");
            // $("#net_profit").html("<?php //echo ($total_savings-$withdraw_main_cal)-($withdraw_profit+$fine); 
                                        ?>");
            $("#net_profit").html("<?php echo $net_profit_cal; ?>");
            // $("#net_taka").html("<?php //echo $net_taka; 
                                    ?>");
            $("#net_taka").html("<?php echo number_format(($total_savings - $withdraw_total_from_transection), 3, '.', ''); ?>");
            $("#maintaka_withdraw").html("<?php echo $withdraw_total_from_transection; ?>");
            $("#total_paid_profit").html("<?php echo $total_savings; ?>");

            //Transection Table
            $("#transect_table").dataTable();

        });

        // Edit Installment Modal Functions
        function openEditInstallmentModal() {
            $('#editInstallmentModal').modal('show');
            $('#edit_installment').val('<?php echo $account_info_result['installment']; ?>');
        }

        function saveInstallment() {
            var newInstallment = $('#edit_installment').val();
            var accountId = <?php echo $id; ?>;
            
            // Validation
            if(newInstallment == '' || newInstallment <= 0) {
                alert('দয়া করে একটি বৈধ পরিমাণ লিখুন');
                return;
            }

            // AJAX call to update installment
            $.ajax({
                url: 'update_installment.php',
                type: 'POST',
                data: {
                    account_id: accountId,
                    installment: newInstallment
                },
                success: function(response) {
                    var result = response;
                    // Check if response is a string and parse it if necessary
                    if (typeof result === 'string') {
                        try {
                            result = JSON.parse(result);
                        } catch (e) {
                            console.error('JSON Parse Error:', e);
                            alert('Server response parsing failed.');
                            return;
                        }
                    }

                    if(result.success) {
                        alert('মাসিক কিস্তির পরিমাণ সফলভাবে আপডেট হয়েছে');
                        $('#editInstallmentModal').modal('hide');
                        location.reload(); // Reload to show updated value
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
                    <h5 class="modal-title" id="editInstallmentModalLabel">মাসিক কিস্তির পরিমাণ সম্পাদনা করুন</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_installment">নতুন মাসিক কিস্তির পরিমাণ (টাকা):</label>
                        <input type="number" class="form-control" id="edit_installment" placeholder="মাসিক কিস্তির পরিমাণ লিখুন" min="0" step="0.01">
                    </div>
                    <div class="alert alert-info">
                        <strong>বর্তমান পরিমাণ:</strong> <?php echo $account_info_result['installment']; ?> টাকা
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                    <button type="button" class="btn btn-primary" onclick="saveInstallment()">সংরক্ষণ করুন</button>
                </div>
            </div>
        </div>
    </div>
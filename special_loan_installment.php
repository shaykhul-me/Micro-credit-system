<?php
$message = "";

if (isset($_POST['new_transection'])) {
$installments_print = "";
    $error_status = "";
    $loan_id = $_POST['loan_id'];
    $loan_no = $_POST['loan_no_search'];
    $loan_type = $_POST['loan_type_select'];
    $installment_amount = (int)$_POST['installment_amount2'];
    $installment = (int)$_POST['installment'];
    $actual_total = (int)$_POST['actual_total'];
    $profit_total = (int)$_POST['profit_total'];
    $installment_total = (int)$_POST['installment_total'];
    $total_fine = (int)$_POST['total_fine'];
    $others_fee = (int)$_POST['others_fee'];
    $comment = $_POST['details'];
    $installment_print = "";
    $current_profit_amount = isset($_POST['current_profit_amount'])?$_POST['current_profit_amount']:0;
    //Total Amount
    $total_transect_amount = $installment_total + $total_fine + $others_fee;

    //New Transection Create
    $category = "$loan_type";
    $details = "Loan No: $loan_no Loan ID- $loan_id";
    $transections_add = array(
        "created_by" =>  $uid,
        "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
        "created_ip" => $ip,
        "category" => $category,
        "details" => $details,
        "cash_in" => $total_transect_amount,
        "status" => "Paid",
    );
    
    if ($error_status == "") {
        $transections_submit = insert_data($conn, $transections_add, "transections");
        $transections_id = $transections_submit['last_id'];
        
    if($transections_id){  
        
        // Cash Increase
     if($total_transect_amount > 0){
    $cash_increase = array(
        "transection_id" => $transections_id,
        "entry_date" => $today,
        "category" => "Loan",
        "title" => $loan_type,
        "increase" => $total_transect_amount
        
    );
    
        $cash_submit = insert_data($conn, $cash_increase, "cash");
    $cash_id = $cash_submit['last_id'];
     }
    
    // send data to transaction details    
        if ($installment_total > 0) {
            // Last installment search
            $todays_installment_start =0;
             $loan_transaction = manual_query("SELECT * FROM transections_details WHERE account_id='$loan_id' AND category = '$loan_type' ORDER BY id DESC LIMIT 1");
             $count_data = $loan_transaction['count'];
             if($count_data==1){
             $last_row = mysqli_fetch_array($loan_transaction['query']);

        $paid_installments = array_filter(explode(",", $last_row['month_name']));
        
        $todays_installment_start = end($paid_installments);
        
           
             }
             
             
                 // installment displaying with comma
            for ($i = 0; $i < $installment; $i++) {
              $todays_installment_start  += 1;
            $installments_print .=  "$todays_installment_start,";
            
        }
             
            
            
             $category = "$loan_type";
            $details = "Loan Type: $loan_type Loan No: $loan_no Loan ID- $loan_id";
            $tr_details_insert_array = array(
                "created_by" =>  $uid,
                "created_at" =>  date("Y-m-d H:i:s", strtotime($datetime)),
                "created_ip" => $ip,
                "account_id" => $loan_id,
                "account_no" => $loan_no,
                "account_title" => $_POST['account_name'],
                "transaction_id" => $transections_id,
                "category" => $category,
                "details" => $details,
                "monthly" => $installment_amount,
                "months" => $installment,
                "month_name" => $installments_print,
                "cash_in" => $installment_total,
                "actual_amount" => $actual_total,
                //"profit_amount" => $profit_total,
                "profit_amount" => $current_profit_amount,
                "status" => "Paid",
            );
            insert_data($conn, $tr_details_insert_array, "transections_details");
        }
        
        if ($total_fine > 0) {
            $category = "Fine";
            $details = "Loan Type: $loan_type Loan No: $loan_no Loan ID- $loan_id";
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
            $details = "Loan Type: $loan_type Loan No: $loan_no Loan ID- $loan_id";
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
    <h3 class="text-center">বিশেষ ঋন পরিশোধ</h3>
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
            
        </div>
        <div class="row">
            <div class="col-md-8 calculation_area">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
            <label for="member_number_search">লোনের ধরন </label>
                <select class="form-control" name="loan_type_select" id="loan_type_select" required>
                    <option value="বিশেষ ঋন">-- বিশেষ ঋন --</option>
                   
                        <?php $loan_type_option = option_collect("loan_type", "");
                       // echo $loan_type_option['options'];
                        ?>
                        
                    </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="loan_no">লোন নম্বর</label>
                            <input class="form-control" name="loan_no_search" id="loan_no_search" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="account_title">নাম</label>
                            <input type="text" name="account_name" id="account_title" class="form-control" readonly required>
                            <input type="text" name="loan_id" id="loan_id" class="form-control" readonly required>
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
                                
                                কিস্তি প্রতি-&nbsp; <span id="installment_amount" class="installment_amount_for_cal">0
                                </span> টাকা,
                                <input type="hidden" name="installment_amount2" id="installment_amount2" class="form-control " value="0">
                                কিস্তি-<input type="number" name="installment" id="installment" class="form-control installment_no_for_cal" style="width:80px; display:inline;" value="0">
                                <input type="hidden" name="actual_total" id="actual_total" class="form-control installment_no_for_cal" style="width:80px; display:inline;" value="0">
                               <input type="hidden" name="profit_total" id="profit_total" class="form-control installment_no_for_cal" style="width:80px; display:inline;" value="0">
                                
                            </td>
                            <td>
                                <input type="number" name="installment_total" id="installment_total" class="form-control installment_cal_total total_for_final_calculation" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>প্রোফিট</td>
                            <td><input type="number" name="current_profit_amount" id="current_profit_amount" class="form-control installment_cal_total total_for_final_calculation" value="0"></td>
                        </tr>
                        
                        
                        <tr>
                            <td class="text-center">২।</td>
                            <td>জরিমানা
                            </td>
                            <td>
                                <input type="number" name="total_fine" id="total_fine" class="form-control total_for_final_calculation" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">৩।</td>
                            <td>বিবিধ</td>
                            <td>
                                <input type="number" name="others_fee" id="others_fee" class="form-control total_for_final_calculation" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">মোট</td>
                            <td>
                                <input type="number" name="total" id="total" class="form-control" value="0" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="details">বিবরণ</label>
                            <textarea type="text" name="details" id="details" class="form-control"></textarea>
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
                                                <strong><span id="loan_holder_name"></span></strong><br>
                                                <span>মোবাইল : </span> <span id="loan_holder_mobile"></span><br>
                                                <span>লোনের ধরন  : </span> <span id="loan_type"></span><br>
                                                <span>লোন নং : </span> <span id="loan_no"></span><br>
                                                
                                            </p>
                                            <p>
                                                
                                                <span>লোনের পরিমান: </span> <span id="loan_amount"></span><br>
                                                <span>লাভের পারসেন্ট : </span> <span id="profit_percent"></span><br>
                                                <span>লাভ : </span> <span id="profit"></span><br>
                                                <span>মোট প্রদেয় : </span> <span id="total_payable"></span><br> <br> 
                                                
                                                <span> কিস্তি প্রতি: </span> <span id="installment"></span>টাকা<br>
                                                <span>কিস্তি প্রতি আসল  : </span> <span id="actual_installment"></span> টাকা<br>
                                                 <span>আসল পরিশোধ  : </span> <span id="total_actual_paid"></span> টাকা<br><br> 
                                                 <span>সর্বমোট কিস্তি : </span> <span id="total_installment"></span><br>

                                                <span>বকেয়া : </span> <span id="due_installment"></span> কিস্তি<br>
                                                
                                                
                                            </p>
                                            <p>
                                                <span> সর্বশেষ কিস্তি নং : </span> <span id="last_installment_no"></span><br>
                                                <span> সর্বশেষ  প্রদেয় তারিখ : </span> <span id="last_installment_date"></span><br>
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


        
// loan suggestion search

    $("#loan_no_search").autocomplete({
        source: function(request, response) {
            var loantype = $("#loan_type_select").val();

            if (!loantype) {
                alert("Please select a loan type first.");
                return;
            }

            $.ajax({
                url: "controllers/index.php", // Your endpoint
                type: "POST",
                dataType: "json",
                data: {
                    loan_no: request.term,
                    loan_type: loantype
                },
                success: function(data) {
                    response(data); // Expect array of objects with label, value, name
                },
                error: function(data) {
                    console.error(data.responseText);
                }
            });
        },
        select: function(event, ui) {
            console.log(ui.item);
            $(this).val(ui.item.value); // Loan number
            $("#account_title").val(ui.item.data.name); // Member name
            $("#loan_id").val(ui.item.data.id);
            $("#loan_holder_mobile").html(ui.item.data.mobile);
            $("#loan_type").html(ui.item.data.loan_type);
            $("#loan_holder_name").html(ui.item.data.name);
            $("#loan_no").html(ui.item.data.loan_no);
            $("#loan_amount").html(ui.item.data.product_price);
            $("#profit_percent").html(ui.item.data.profit_percent);
            $("#profit").html(ui.item.data.profit);
            $("#total_payable").html(ui.item.data.net_amount);
            $("#total_installment").html(ui.item.data.total_installment);
            //$("#installment,#installment_amount").html(ui.item.data.taka_per_installment);
            $("#installment,#installment_amount").html(ui.item.data.actual_per_installment);
            $("#installment_amount2").val(ui.item.data.taka_per_installment);
            $("#actual_installment").html(ui.item.data.actual_per_installment	);
            //$("#total_actual_paid").html(ui.item.data.total_actual_paid);
            $("#total_actual_paid").html(ui.item.transect_details.total_actual_paid);
            $("#last_installment_no").html(ui.item.last_installment);
            $("#last_installment_date").html(ui.item.last_installment_date);
           
            $("#due_installment").html(ui.item.data.total_installment - ui.item.last_installment);
            
            
            return false; // Prevent default behavior
        }
    });

// total fee calculation
function calculateTotals() {
        // Get values as numbers (or 0 if invalid)
        let installmentAmount = parseFloat($('#installment_amount').html()) || 0;
        let actual_installment = parseFloat($('#actual_installment').html()) || 0;
        let profit_installment = parseFloat($('#profit_installment').html()) || 0;
        let installment = parseFloat($('#installment').val()) || 0;
        let totalFine = parseFloat($('#total_fine').val()) || 0;
        let othersFee = parseFloat($('#others_fee').val()) || 0;
        console.log(installmentAmount);

        // Calculate installment total, actual and profit
        let installmentTotal = installmentAmount * installment;
        $('#installment_total').val(installmentTotal.toFixed(2));
        
        let actualTotal = actual_installment * installment;
        $('#actual_total').val(actualTotal.toFixed(2));
        
        let profitTotal = profit_installment * installment;
        $('#profit_total').val(profitTotal.toFixed(2));

        // Calculate total fees
        let totalFees = installmentTotal + totalFine + othersFee;
        $('#total').val(totalFees.toFixed(2));
    }

    // Trigger calculation when installment changes
    //$('#installment').on('change', calculateTotals);

    // Optional: recalculate if other inputs change
    $('#installment, #total_fine, #others_fee').on('input', calculateTotals);

        
        // $("#new_transection").attr("disabled", true)
    });
</script>
<?php
$start = date("Y-m-01", strtotime($datetime));
$end = date("Y-m-t", strtotime($datetime));
if (!isset($_GET['member_id'])) {
    echo "<div class='alert alert-danger' role='alert'>Member ID Required</div>";
} else {

    $savings_account_form = true;
    $share_accounts_form = true;
    $memer_id = $_GET['member_id'];
    $get_member_info = manual_query("SELECT * FROM members WHERE id='{$memer_id}'");
    if ($get_member_info['count'] != 1) {
        echo "<div class='alert alert-danger' role='alert'>Member Not Found</div>";
    } else {
        $member_result = mysqli_fetch_assoc($get_member_info['query']);

        if (isset($_POST['create_new_account'])) {
            $main_tr_category = "New Account";
            $main_tr_details = "New Account Created";
            $savings_installment = isset($_POST['savings_installment']) ? (int)$_POST['savings_installment'] : 0;
             $opening_date = isset($_POST['opening_date']) ? $_POST['opening_date'] : 0;
            $savings_last_paid = isset($_POST['savings_last_paid']) ? $_POST['savings_last_paid'] : "";
            $savings_last_balance = isset($_POST['savings_last_balance']) ? $_POST['savings_last_balance'] : 0;
            $share_installment = isset($_POST['share_installment']) ? (int)$_POST['share_installment'] : 0;
            $opening_date = isset($_POST['opening_date']) ? $_POST['opening_date'] : 0;
            $share_last_paid = isset($_POST['share_last_paid']) ? $_POST['share_last_paid'] : "";
            $share_last_balance = isset($_POST['share_last_balance']) ? $_POST['share_last_balance'] : 0;
            if (($savings_installment + $share_installment) > 0) {
                $transections_array = array(
                    "created_at" => $datetime,
                    "created_by" => $uid,
                    "created_ip" => $ip,
                    "category" => $main_tr_category,
                    "details" => $main_tr_details,
                    "cash_in" => $savings_installment + $share_installment,
                    "cash_out" => 0,
                    "status" => "Paid",
                );
                $main_transections_id = insert_data($conn, $transections_array, "transections");
            }
            else{
                // new line
                if ($savings_installment == 0) {
                $new_savings_array = array(
                        "created_at" => $datetime,
                        "created_by" => $uid,
                        "created_ip" => $ip,
                        "account_type" => "Savings",
                        "account_title" => $member_result['name'],
                        "member_id" => $memer_id,
                        "installment" => $savings_installment,
                         "opening_date" => $datetime,
                        "status" => "active",
                    );
                    $new_savings_insert = insert_data($conn, $new_savings_array, "accounts");
                }
                
                 if ($share_installment == 0) {

                    $new_share_array = array(
                        "created_at" => $datetime,
                        "created_by" => $uid,
                        "created_ip" => $ip,
                        // "account_no" => $datetime,
                        "account_type" => "Share",
                        "account_title" => $member_result['name'],
                        "member_id" => $memer_id,
                         "opening_date" => $datetime,
                        // "total_pay_time" => $datetime,
                        // "profit" => $datetime,
                        "installment" => $share_installment,
                        // "total_amount" => $datetime,
                        // "start_date" => $datetime,
                        // "end_date" => $datetime,
                        // "opening_date" => $datetime,
                        "status" => "active",
                    );
                    $new_share_insert = insert_data($conn, $new_share_array, "accounts");
                 }
                
                
            }
            if ($main_transections_id['last_id'] > 0) {

                if ($savings_installment > 0) {

                    $new_savings_array = array(
                        "created_at" => $datetime,
                        "created_by" => $uid,
                        "created_ip" => $ip,
                        // "account_no" => $datetime,
                        "account_type" => "Savings",
                        "account_title" => $member_result['name'],
                        "member_id" => $memer_id,
                        // "payment_type" => $datetime,
                        // "total_pay_time" => $datetime,
                        // "profit" => $datetime,
                        "installment" => $savings_installment,
                        // "total_amount" => $datetime,
                        // "start_date" => $datetime,
                        // "end_date" => $datetime,
                        // "opening_date" => $datetime,
                        "status" => "active",
                    );
                    $new_savings_insert = insert_data($conn, $new_savings_array, "accounts");
                    if ($new_savings_insert['last_id'] > 0) {
                        $main_tr_category = "New Savings";
                        $details = "New Savings-{$new_savings_insert['last_id']}, Member ID-$memer_id";
                        $sav_account_id = $new_savings_insert['last_id'];
                        $transections_array = array(
                            "created_at" => $datetime,
                            "created_by" => $uid,
                            "created_ip" => $ip,
                            "account_id" => $new_savings_insert['last_id'],
                            "account_no" => $new_savings_insert['last_id'],
                            "account_title" => $member_result['name'],
                            "category" => "Savings",
                            "details" => "",
                            "monthly" => $savings_installment,
                            "months" => 1,
                            "month_name" => date("Y-m", strtotime($savings_last_paid)).',',
                            "cash_in" => $savings_last_balance,
                            "cash_out" => 0,
                            "status" => "Paid",
                        );
                        $insert_new_savings = insert_data($conn, $transections_array, "transections_details");
                    }
                }

                if ($share_installment > 0) {

                    $new_share_array = array(
                        "created_at" => $datetime,
                        "created_by" => $uid,
                        "created_ip" => $ip,
                        // "account_no" => $datetime,
                        "account_type" => "Share",
                        "account_title" => $member_result['name'],
                        "member_id" => $memer_id,
                        // "payment_type" => $datetime,
                        // "total_pay_time" => $datetime,
                        // "profit" => $datetime,
                        "installment" => $share_installment,
                        // "total_amount" => $datetime,
                        // "start_date" => $datetime,
                        // "end_date" => $datetime,
                        // "opening_date" => $datetime,
                        "status" => "active",
                    );
                    $new_share_insert = insert_data($conn, $new_share_array, "accounts");
                    if ($new_share_insert['last_id'] > 0) {
                        $main_tr_category = "New Share";
                        $details = "New Share-{$new_share_insert['last_id']}, Member ID-$memer_id";
                        $sav_account_id = $new_share_insert['last_id'];
                        $transections_array = array(
                            "created_at" => $datetime,
                            "created_by" => $uid,
                            "created_ip" => $ip,
                            "account_id" => $new_share_insert['last_id'],
                            "account_no" => $new_share_insert['last_id'],
                            "account_title" => $member_result['name'],
                            "category" => "Share",
                            "details" => "",
                            "monthly" => $share_installment,
                            "months" => 1,
                            "month_name" => date("Y-m", strtotime($share_last_paid)).',',
                            "cash_in" => $share_last_balance,
                            "cash_out" => 0,
                            "status" => "Paid",
                        );
                        $insert_new_share = insert_data($conn, $transections_array, "transections_details");
                    }
                }
            }
            echo "<div class='alert alert-success' role='alert'>Account Create Successfully</div>";
        }
        $table_tbody_cont = "";
        $savings = manual_query("SELECT `accounts`.* FROM `accounts` WHERE `accounts`.`status` = 'active' AND member_id='$memer_id'");
        $col_sum_1 = 0;
        $col_sum_2 = 0;
        $col_sum_3 = 0;
        while ($row = mysqli_fetch_array($savings['query'])) {

            $transections_sql = manual_query("SELECT `transections_details`.*, COALESCE(SUM(`transections_details`.`cash_in` - `transections_details`.`cash_out`), 0) AS total_deposite,
                            COALESCE(SUM(CASE
                                WHEN `transections_details`.`created_at` BETWEEN '2025-03-01' AND '2025-03-31'
                                THEN `transections_details`.`cash_in` - `transections_details`.`cash_out`
                                ELSE 0
                            END), 0) AS this_month FROM `transections_details` WHERE `transections_details`.`account_id`='{$row['id']}' GROUP BY `account_id`");
            $this_ac_this_month = 0;
            $this_ac_total_dep = 0;
            while ($transectons_res = mysqli_fetch_assoc($transections_sql['query'])) {
                // $accounts_transections_res[$transectons_res['account_no']]['this_month'] = $transectons_res['this_month'];
                // $accounts_transections_res[$transectons_res['account_no']]['total_deposite'] = $transectons_res['total_deposite'];
                $this_ac_this_month = $transectons_res['this_month'];;
                $this_ac_total_dep = $transectons_res['total_deposite'];
            }

            // if ($due_only == true && $row['this_month'] > 0) {
            //     continue;
            // }
            // $this_ac_this_month = isset($accounts_transections_res[$row['account_no']]['this_month']) ? $accounts_transections_res[$row['account_no']]['this_month'] : 0;
            // $this_ac_total_dep = isset($accounts_transections_res[$row['account_no']]['total_deposite']) ? $accounts_transections_res[$row['account_no']]['total_deposite'] : 0;


            $col_sum_1 += $row['installment'];
            $col_sum_2 += $this_ac_this_month;
            $col_sum_3 += $this_ac_total_dep;

            if($row['account_type'] == "Share"){
                $share_accounts_form = false;                
            }
            if($row['account_type'] == "Savings"){
                $savings_account_form = false;                
            }


            $table_tbody_cont .=  "<tr>
                                <td>{$row['account_type']}</td>
                                <td>{$row['account_title']}</td>
                                <td>{$row['opening_date']}</td>
                                <td>{$this_ac_this_month}</td>
                                <td>{$this_ac_total_dep}</td>
                                <td>{$row['status']}</td>
                                <td class='hiddenp text-center p-2'>
                                    <a target='_blank' class='btn btn-sm btn-primary mb-1' href='index.php?action=account_info&id={$row['id']}'>View</a>
                                </td>
                            </tr>";
        }

?>

        <div class="mt-4">
            <?php
            if ($share_accounts_form || $savings_account_form) {
            ?>
                <form method="POST">
                    <div class="row">
                        <?php
                        if ($savings_account_form) {
                        ?>
                            <div class="col-md-6 border-right border-dark">
                                <h3 class="">নতুন সঞ্চয়</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="savings_installment">কিস্তির পরিমাণ</label>
                                            <input class="form-control" type="number" name="savings_installment" id="savings_installment" placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="savings_last_paid">সর্বশেষ পরিশোধিত মাস</label>
                                            <input class="form-control" type="month" name="savings_last_paid" id="savings_last_paid" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="savings_last_balance">সর্বশেষ ব্যালেন্স</label>
                                            <input class="form-control" type="number" name="savings_last_balance" id="savings_last_balance" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        if ($share_accounts_form) {
                        ?>
                            <div class="col-md-6">
                                <h3 class="">নতুন শেয়ার</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="share_installment">কিস্তির পরিমাণ</label>
                                            <input class="form-control" type="number" name="share_installment" id="share_installment" placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="share_last_paid">সর্বশেষ পরিশোধিত মাস</label>
                                            <input class="form-control" type="month" name="share_last_paid" id="share_last_paid" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="share_last_balance">সর্বশেষ ব্যালেন্স</label>
                                            <input class="form-control" type="number" name="share_last_balance" id="share_last_balance" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <input type="submit" value="Create" class="btn btn-info" name="create_new_account">

                </form>
            <?php
            }
            ?>
            <hr>
            <?php
            if ($table_tbody_cont != "") {
            ?>
                <h3 class="text-center" id="header_first">Accounts List</h3>
                <div class="table-responsive">
                    <table class="table table-light" id="savings_list_table" class="display">
                        <thead class="thead-light">
                            <tr>
                                <th>একাউন্ট ধরণ</th>
                                <th>সদস্য নাম</th>
                                <th>তারিখ</th>
                                <th>চলতি মাসে</th>
                                <th>মোট জমা</th>
                                <th>অবস্থা</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $table_tbody_cont; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>একাউন্ট ধরণ</th>
                                <th>সদস্য নাম</th>
                                <th>তারিখ</th>
                                <th><?php echo $col_sum_2; ?></th>
                                <th><?php echo $col_sum_3; ?></th>
                                <th>অবস্থা</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php
            }
            ?>
        </div>
        <script>
            $(document).ready(function() {
                var header_first = $("#header_first").html();
                var header_second = $("#header_second").html();
                var only_text_first = $("#header_first").text();
                var only_text_second = $("#header_second").text();
                /*
                $("#savings_list_table").dataTable({
                    dom: 'lBfrtip',
                    pageLength: 100,
                    "columns": [{
                            className: "text-center"
                        },
                        {
                            className: "text-center"
                        },
                        {
                            className: "text-center"
                        },
                        {
                            className: "text-center"
                        },
                        null,
                        null,
                        {
                            className: "text-center"
                        }
                    ],

                    buttons: [{
                            extend: 'print',
                            text: 'Print',
                            className: "btn-sm",
                            title: '',
                            autoPrint: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            },
                            customize: function(win) {
                                $(win.document.body)
                                    .css('font-size', '10pt')
                                    .prepend(
                                        '<h3 class="text-center">' + header_first + '</h3><h5 class="text-center">' + header_second + '</h5>'
                                    );

                                $(win.document.body).find('table')
                                    .addClass('table table-striped')
                                    .css('font-size', 'inherit');
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            autoFilter: true,
                            sheetName: 'Exported data',
                            className: "btn-sm",
                            title: only_text_first + "\n" + only_text_second,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            className: "btn-sm",
                            title: only_text_first + "\n" + only_text_second,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            extend: 'colvis',
                            className: "btn-sm"
                        },
                    ],
                });
                */
                //Office Change Branch Appear Start
                var branch = new Array();
                <?php
                // foreach ($office as $key => $value) {
                //     $branch_sql = single_condition_sort("branch", "office_code", "$key", "branch_name", "ASC", "");

                //     $options = "";
                //     while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
                //         $options .= "<option value=\"{$branch_res['id']}\">{$branch_res['branch_name']}</option>";
                //     }
                //     echo "branch[{$key}]='$options';";
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
            });
        </script>
<?php
    }
}
?>
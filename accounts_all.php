<?php
$start = date("Y-m-01", strtotime($datetime));
$end = date("Y-m-t", strtotime($datetime));
// if ($ac_type == "admin") {
//     $office = array();
//     $office_sql = manual_query("SELECT * FROM office");
//     while ($office_res = mysqli_fetch_assoc($office_sql['query'])) {
//         $office[$office_res['id']] = $office_res['name'];
//     }
//     $branch = array();
//     $branch_sql = manual_query("SELECT * FROM branch");
//     while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
//         $branch[$branch_res['id']] = array("name" => $branch_res['branch_name'], "office" => $branch_res['office_code']);
//     }
//     $staff = array();
//     $staff_sql = manual_query("SELECT * FROM staffs");
//     while ($staff_res = mysqli_fetch_assoc($staff_sql['query'])) {
//         $staff[$staff_res['staffid']] = $staff_res['name'];
//     }

//     $office_condition = "";
//     $branch_condition = "";
//     $office_result_show = "ALL";
//     $branch_result_show = "ALL";
//     $due_only = false;
//     $payment_status = "ALL";
//     if (isset($_POST['custome_result'])) {
//         if ($_POST['office'] != "") {
//             $office_condition = "AND `account_create`.`office` = '{$_POST['office']}'";
//             $office_result_show = $office[$_POST['office']];
//         }
//         if (isset($_POST['branch'])) {
//             if ($_POST['branch'] != "") {
//                 $office_condition = "AND `account_create`.`branch` = '{$_POST['branch']}'";
//                 $branch_result_show = $branch[$_POST['branch']]['name'];
//             }
//         }
//         if ($_POST['start'] != "") {
//             $start = date("Y-m-01", strtotime($_POST['start']));
//             $end = date("Y-m-t", strtotime($_POST['start']));
//         }
//         if (isset($_POST['due_only'])) {
//             $due_only = true;
//             $payment_status = "Due Only";
//         }
//     }
// } else {
//     $office = array();
//     $office[$user_other_info['office_id']] = $user_other_info['office_name'];
//     $office_condition = "AND `account_create`.`office` = '{$user_other_info['office_id']}'";
//     $office_result_show = $user_other_info['office_name'];
//     $branch = array();
//     $branch[$user_other_info['branch_id']] = array("name" => $user_other_info['branch_name'], "office" => $user_other_info['office_id']);
//     $branch_condition = "AND `account_create`.`branch` = '{$user_other_info['branch_id']}'";
//     $branch_result_show = $user_other_info['branch_name'];
//     $staff = array();
//     $staff_sql = manual_query("SELECT * FROM staffs");
//     while ($staff_res = mysqli_fetch_assoc($staff_sql['query'])) {
//         $staff[$staff_res['staffid']] = $staff_res['name'];
//     }

//     if (isset($_POST['start'])) {
//         if ($_POST['start'] != "") {
//             $start = date("Y-m-01", strtotime($_POST['start']));
//             $end = date("Y-m-t", strtotime($_POST['start']));
//         }
//     }
//     $due_only = false;
//     $payment_status = "ALL";
//     if (isset($_POST['due_only'])) {
//         $due_only = true;
//         $payment_status = "Due Only";
//     }
// }

$savings = manual_query("SELECT `accounts`.* FROM `accounts` WHERE `accounts`.`status` = 'active' GROUP BY `accounts`.`id` ORDER BY account_no ASC");
$accounts_transections_res = array();
$transections_sql = manual_query("SELECT `transections`.*, COALESCE(SUM(`transections`.`cash_in` - `transections`.`cash_out`), 0) AS total_deposite,
    COALESCE(SUM(CASE
        WHEN `transections`.`created_at` BETWEEN '2025-03-01' AND '2025-03-31'
        THEN `transections`.`cash_in` - `transections`.`cash_out`
        ELSE 0
    END), 0) AS this_month FROM `transections` GROUP BY `account_no`");
while ($transectons_res = mysqli_fetch_assoc($transections_sql['query'])) {
    $accounts_transections_res[$transectons_res['account_no']]['this_month'] = $transectons_res['this_month'];
    $accounts_transections_res[$transectons_res['account_no']]['total_deposite'] = $transectons_res['total_deposite'];
}

?>

<div class="mt-4">
    <?php
    // if ($ac_type == "admin") {
    ?>
    <form action="" method="post">

        <?php //echo $ignore_csrf_input;
        ?>
        <div class="row justify-content-md-center">
            <div class="col-md-3">
                <div class="row">
                    <label for="start" class="col-md-3 pt-2">Start</label>
                    <input class="form-control col-md-9" type="month" name="start" id="start">
                </div>
            </div>
            <!-- <div class="col-md-3">
                    <input id="due_only" class="mr-2 align-middle" type="checkbox" name="due_only" value="true">
                    <label for="due_only" class="pt-1 align-middle h5">শুধুমাত্র বকেয়া</label>
                </div> -->
        </div>
        <div class="text-center mt-1"><button class="btn btn-primary" type="submit" name="custome_result">Search</button></div>
    </form>
    <hr>
    <?php
    // }
    ?>
    <h3 class="text-center" id="header_first">সঞ্চয়ী আমানতের তথ্য</h3>
    <h5 class="text-center" id="header_second">Month: <span class="color-blue"><?php echo date("F-Y", strtotime($start)); ?></span></h5>
    <!-- , Payment Status: <span class="color-blue"><?php //echo $payment_status; 
                                                    ?></span> -->
    <hr>
    <div class="table-responsive">
        <table class="table table-light" id="savings_list_table" class="display">
            <thead class="thead-light">
                <tr>
                    <th>একাউন্ট নং</th>
                    <th>সদস্য নাম</th>
                    <th>তারিখ</th>
                    <!-- <th>অফিস</th>
                    <th>ব্রাঞ্চ</th>
                    <th>রেফার</th> -->
                    <!-- <th>কিস্তির পরিমান</th> -->
                    <th>চলতি মাসে</th>
                    <th>মোট জমা</th>
                    <th>অবস্থা</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $col_sum_1 = 0;
                $col_sum_2 = 0;
                $col_sum_3 = 0;
                while ($row = mysqli_fetch_array($savings['query'])) {
                    // if ($due_only == true && $row['this_month'] > 0) {
                    //     continue;
                    // }
                    $this_ac_this_month = isset($accounts_transections_res[$row['account_no']]['this_month'])?$accounts_transections_res[$row['account_no']]['this_month']:0;
                    $this_ac_total_dep = isset($accounts_transections_res[$row['account_no']]['total_deposite'])?$accounts_transections_res[$row['account_no']]['total_deposite']:0;


                    $col_sum_1 += $row['basic_amount'];
                    $col_sum_2 += $this_ac_this_month;
                    $col_sum_3 += $this_ac_total_dep;
                ?>
                    <tr>
                        <td><?php echo isset($row['account_no']) ? $row['account_no'] : ""; ?></td>
                        <!-- <td><a target="_blank" href="index.php?action=member_view_single&id=<?php //echo isset($row['member_id']) ? $row['member_id'] : ""; ?>"><?php //echo isset($row['name']) ? $row['name'] : ""; ?></a></td> -->
                        <td><?php echo isset($row['account_title']) ? $row['account_title'] : ""; ?></td>
                        <td><?php echo isset($row['opening_date']) ? $row['opening_date'] : ""; ?></td>
                        <!-- <td><?php //echo isset($row['office']) ? $office[$row['office']] : ""; ?></td>
                        <td><?php //echo isset($row['branch']) ? $branch[$row['branch']]['name'] : ""; ?></td>
                        <td><?php //echo isset($row['staff_ref']) ? $staff[$row['staff_ref']] : ""; ?></td> -->
                        <!-- <td><?php //echo isset($row['basic_amount']) ? $row['basic_amount'] : 0; ?></td> -->
                        <td><?php echo isset($this_ac_this_month) ? $this_ac_this_month : 0; ?></td>
                        <td><?php echo isset($this_ac_total_dep) ? $this_ac_total_dep : 0;; ?></td>
                        <td><?php echo isset($row['status']) ? $row['status'] : ""; ?></td>
                        <td class='hiddenp text-center p-2'>
                            <a target="_blank" class='btn btn-sm btn-primary mb-1' href='index.php?action=account_info&id=<?php echo $row['id'] ?>'>View</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>একাউন্ট নং</th>
                    <th>সদস্য নাম</th>
                    <th>তারিখ</th>
                    <!-- <th>অফিস</th>
                    <th>ব্রাঞ্চ</th>
                    <th>রেফার</th> -->
                    <!-- <th><?php //echo $col_sum_1; ?></th> -->
                    <th><?php echo $col_sum_2; ?></th>
                    <th><?php echo $col_sum_3; ?></th>
                    <th>অবস্থা</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        var header_first = $("#header_first").html();
        var header_second = $("#header_second").html();
        var only_text_first = $("#header_first").text();
        var only_text_second = $("#header_second").text();
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
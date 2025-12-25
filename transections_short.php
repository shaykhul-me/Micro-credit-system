<?php
$start = $end = $today;
 
if(isset($_GET['delete_id'])){
    $delete_id = $_GET['delete_id'];
    manual_query("DELETE FROM `transections` WHERE id='$delete_id'");
    manual_query("DELETE FROM `transections_details` WHERE transaction_id='$delete_id'");
    manual_query("DELETE FROM `cash` WHERE transection_id='$delete_id'");
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/index.php?action=transections_short";
        $_SESSION['msg'] = "<div class='alert alert-success'>Transection Deleted Successfully</div>";
        echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
        exit;
}


$accounts_transections_res = array();
if(isset($_POST['start'])){
 $start = $_POST['start'];
$end = $_POST['end']; 
$transections_sql = manual_query("SELECT `transections`.* FROM `transections` WHERE entry_date BETWEEN '$start' AND '$end' ");
}else{
    $transections_sql = manual_query("SELECT `transections`.* FROM `transections` WHERE entry_date BETWEEN '$start' AND '$end' ");
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
                    <input class="form-control col-md-9" type="date" name="start" id="start">
                    <label for="start" class="col-md-3 pt-2">End</label>
                    <input class="form-control col-md-9" type="date" name="end" id="end">
                </div>
            </div>
          
        </div>
        <div class="text-center mt-1"><button class="btn btn-primary" type="submit" name="custome_result">Search</button></div>
    </form>
    <hr>
    <?php
    // }
    ?>
    <h3 class="text-center" id="header_first">দৈনিক লেনদেনের তথ্য</h3>
    <h5 class="text-center" id="header_second">Date: <span class="color-blue"><?php echo date("d-m-Y", strtotime($start)); ?></span> To <span class="color-blue"><?php echo date("d-m-Y", strtotime($end)); ?></span></h5>
    <!-- , Payment Status: <span class="color-blue"><?php //echo $payment_status; 
                                                    ?></span> -->
    <hr>
    <div class="table-responsive">
        <table class="table table-light" id="savings_list_table" class="display">
            <thead class="thead-light">
                <tr>
                    <th>আইডি</th>
                    <th>তারিখ</th>
                    <th>বিবরণ</th>
                    <th>বিস্তারিত</th>
                    <th>গ্রহন</th>
                    <th>প্রদান</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $col_sum_1 = 0;
                $col_sum_2 = 0;
                $col_sum_3 = 0;
                while ($row = mysqli_fetch_array($transections_sql['query'])) {

                    // $col_sum_1 += $row['basic_amount'];
                    $col_sum_2 += $row['cash_in'];
                    $col_sum_3 += $row['cash_out'];
                ?>
                    <tr>
                        <td><?php echo isset($row['id']) ? $row['id'] : ""; ?></td>
                        <td><?php echo isset($row['entry_date']) ? date("d-m-Y", strtotime($row['entry_date'])) : ""; ?></td>
                        <td><?php echo isset($row['category']) ? $row['category'] : ""; ?></td>
                        <td><?php echo isset($row['details']) ? $row['details'] : ""; ?></td>
                        <!-- <td><?php //echo isset($row['account_title']) ? $row['account_title'] : ""; ?></td> -->
                        <!-- <td><?php //echo isset($row['office']) ? $office[$row['office']] : ""; ?></td>
                        <td><?php //echo isset($row['branch']) ? $branch[$row['branch']]['name'] : ""; ?></td>
                        <td><?php //echo isset($row['staff_ref']) ? $staff[$row['staff_ref']] : ""; ?></td> -->
                        <!-- <td><?php //echo isset($row['basic_amount']) ? $row['basic_amount'] : 0; ?></td> -->
                        <td><?php echo isset($row['cash_in']) ? $row['cash_in'] : 0; ?></td>
                        <td><?php echo isset($row['cash_out']) ? $row['cash_out'] : 0; ?></td>
                        <td class='hiddenp text-center p-2'>
                            <?php
                            if(isset($_SESSION['admin_access'])){
                            ?>
                            <a target="_blank" class='btn btn-sm btn-danger mb-1' href='index.php?action=transections_short&delete_id=<?php echo $row['id'] ?>'>Delete</a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    
                    <th colspan="4">মোট</th>
                    <th><?php echo $col_sum_2; ?></th>
                    <th><?php echo $col_sum_3; ?></th>
                    <th>Action</th>
                    
                </tr>
            </tfoot>
        </table>
>
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
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: "btn-sm",
                    title: only_text_first + "\n" + only_text_second,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
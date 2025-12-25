<?php
if (isset($_GET['id'])) {
    $data = single_condition_select("loan_account", "id", $_GET['id']);
} else {
    $data = get_posts_sort("loan_account", "loan_no", "DESC");
}
$rowdata = "";
while ($row = mysqli_fetch_array($data['query'])) {
    $softid =  $row['id'];
    $name =  $row['name'];
    $loan_no =  $row['loan_no'];
    $mobile = $row['mobile'];
    $loan_type = $row['loan_type'];
    $product_price = $row['product_price'];
    $status = "Pending";
    $photo = $row['member_photo'];
    if ($photo == "") {
        $photo = "<img height='80px' width='80px' src='assets/images/img/demo.jpg'>";
    } else {
        if (file_exists($photo)) {
            $photo = "<img height='80px' width='80px' src='$photo'>";
        } else {
            $photo = "<img height='80px' width='80px' src='assets/images/img/demo.jpg'>";
        }
    }
    $rowdata .= "<tr>
                <td>$loan_no</td>
                <td>$photo</td>
                <td>$name</td>
                <td>$mobile</td>
                <td>$loan_type</td>
                <td>$product_price</td>
                <td><a href='index.php?action=loan_update&loansoftid=$softid'>EDIT</a></td>
            </tr>";
}
?>

<div id='wrap'>
    <div id='printableArea' class='container'>
        <h3 class='maintitle text-center' id="header_first">মেম্বারদের তালিকা</h3>
        <table class='datatable table table-striped table-bordered table-responsive-md' id="members_list">
            <thead>
                <tr>
                    <th width='15px'>লোন নং</th>
                    <th>ছবি</th>
                    <th>নাম</th>
                    <th>মোবাইল</th>
                    <th>লোনের ধরন</th>
                    <th>লোনের পরিমান</th>
                    <th class='hiddenp' width="80px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $rowdata; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var header_first = $("#header_first").html();
        var only_text_first = $("#header_first").text();

        $("#members_list").DataTable({
            dom: 'lBfrtip',
            order: [],
            pageLength: 100,
            "columns": [
                null,
                null,
                null,
                null,
                null,
                null,
                { "orderable": false } // Action column sortable না
            ],
            buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    className: "btn-sm",
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend('<h3 class="text-center">' + header_first + '</h3>');
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
                    title: only_text_first,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: "btn-sm",
                    title: only_text_first,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'colvis',
                    className: "btn-sm"
                },
            ],
        });
    });
</script>

<?php
// Delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $table = "transections";
    $delete_info = deletedata($table, $id);
    if(isset($delete_info['msg'])){
        echo "<div class='alert alert-warning'>$id নং তথ্য সফলভাবে মুছে ফেলা হয়েছে</div>";
    } else {
        echo "<div class='alert alert-danger'>$id নং তথ্য মুছা সম্ভব নয়</div>";
    }
}

// Show Data
$all_data = "";
if(isset($_GET['id'])){
    $data = single_condition_select("transections", "id", $_GET['id']);
} else {
    $data = single_condition_select("transections", "category", "Administrative Expense");
}

while ($row = mysqli_fetch_array($data['query'])) {
    $id = $row['id'];
    $entry_date = $row['entry_date'];
    $category = $row['category'];
    $details = $row['details'];
    $taka = $row['cash_out'];
    
    $all_data .= "<tr>
        <td>$entry_date</td>
        <td>$category</td>
        <td>$taka</td>
        <td>$details</td>
        <td>
            <a href='?delete=$id' onclick=\"return confirm('Are you sure to delete?');\" class='btn btn-sm btn-danger'>Delete</a>
        </td>
    </tr>";
}
?>

<div class="container mt-3">
    <h3 class="text-center mb-3" id="header_first">অন্যান্য ব্যয়সমূহ</h3>
    <table class="table table-striped table-bordered nowrap" id="members_list">
        <thead class="table-light">
            <tr>
                <th>তারিখ</th>
                <th>ক্যাটেগরি</th>
                <th>টাকা</th>
                <th>মন্তব্য</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?= $all_data ?>
        </tbody>
    </table>
</div>

<!-- DataTables + Buttons + Dependencies -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    var header_first = $("#header_first").html();

    $("#members_list").DataTable({
        dom: 'lBfrtip',
        pageLength: 25,
        order: [],
        columnDefs: [
            { orderable: false, targets: 4 } // Actions column non-sortable
        ],
        buttons: [
            {
                extend: 'print',
                text: '🖨 Print',
                autoPrint: true,
                exportOptions: { columns: [0,1,2,3] },
                customize: function(win){
                    $(win.document.body).prepend('<h3 class="text-center">'+header_first+'</h3>');
                    $(win.document.body).find('table').addClass('table table-bordered').css('font-size','inherit');
                }
            },
            {
                extend: 'excelHtml5',
                text: '📊 Excel',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'pdfHtml5',
                text: '📄 PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0,1,2,3] }
            },
            {
                extend: 'colvis',
                text: 'Columns'
            }
        ]
    });
});
</script>

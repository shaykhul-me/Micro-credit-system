<?php
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $table = "members";
    $delete_info = deletedata($table, $id);
    if(isset($delete_info['msg'])){
        echo "<div class='alert alert-warning'>$id নং মেম্বারের তথ্য সফলভাবে মুছে ফেলা হয়েছে</div>";
    } else {
        echo "<div class='alert alert-danger'>$id নং মেম্বারের তথ্য মুছা সম্ভব নয়</div>";
    }
}

// ডাটাবেস থেকে মেম্বার লোড
$data = get_posts_sort("members", "member_no", "DESC");
?>
<div class="container mt-3">
    <h3 class="text-center mb-3" id="header_first">মেম্বারদের তালিকা</h3>
    <table class="table table-striped table-bordered nowrap" id="members_list">
        <thead class="table-light">
            <tr>
                <th>সদস্য নং</th>
                <th>সদস্য আইডি</th>
                <th>ছবি</th>
                <th>নাম</th>
                <th>Type</th>
                <th>status</th>
                <th>মোবাইল</th>
                <th>ঠিকানা</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($data['query'])): 
                $membersid = $row['id'];
                $member_no = $row['member_no'];
                $member_type = $row['member_type'];
                $status = $row['status'];
                $photo = $row['member_photo'];
                $name = $row['name_bn'];
                $name_en = $row['name'];
                $mobile = $row['mobile'];
                $total_present_address = "{$row['present_address']}, {$row['present_thana']}, {$row['present_district']}";
            ?>
            <tr>
                <td><?= $member_no ?></td>
                <td><?= $membersid ?></td>
                <td>
                    <?php 
                    if($photo && file_exists($photo)){
                        echo "<img src='$photo' height='80' width='80'>";
                    } else {
                        echo "<img src='assets/images/img/demo.jpg' height='80' width='80'>";
                    }
                    ?>
                </td>
                <td><?= $name ?><br><?= $name_en ?></td>
                <td><?= $member_type ?></td>
                <td><?= $status ?></td>
                <td><?= $mobile ?></td>
                <td><?= $total_present_address ?></td>
                <td>
                    <a class="btn btn-sm btn-primary mb-1" href='index.php?action=member_view_single&id=<?= $membersid ?>'>View</a>
                    <a class="btn btn-sm btn-success mb-1" href='index.php?action=member_accounts_add&member_id=<?= $membersid ?>'>Accounts</a>
                    <a class="btn btn-sm btn-info mb-1" href='index.php?action=member_edit&id=<?= $membersid ?>'>Edit</a>
                    <a class="btn btn-sm btn-danger mb-1" href='?action=members_view&delete=<?= $membersid ?>' onclick="return confirm('Are you want to Delete <?= $name ?>?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
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
    var only_text_first = $("#header_first").text();

    $("#members_list").DataTable({
        dom: 'lBfrtip',
        pageLength: 25,
        order: [],
        responsive: true,
        columnDefs: [
            { orderable: false, targets: 8 } // Actions column
        ],
        buttons: [
            {
                extend: 'print',
                text: '🖨 Print',
                autoPrint: true,
                exportOptions: { columns: [0,1,2,3,4,5,6,7] },
                customize: function(win){
                    $(win.document.body).prepend('<h3 class="text-center">'+header_first+'</h3>');
                    $(win.document.body).find('table').addClass('table table-bordered').css('font-size','inherit');
                }
            },
            {
                extend: 'excelHtml5',
                text: '📊 Excel',
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            },
            {
                extend: 'pdfHtml5',
                text: '📄 PDF',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            },
            {
                extend: 'colvis',
                text: 'Columns'
            }
        ]
    });
});
</script>

<?php
// Delete action
if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$table = "transections";
	$delete_info = deletedata($table, $id);
	if(isset($delete_info['msg'])){
		echo "<div class='alert alert-warning'>$id নং মেম্বারের তথ্য সফলভাবে মুছে ফেলা হয়েছে</div>";
	}else{
		echo "<div class='alert alert-danger'>$id নং মেম্বারের তথ্য মুছা সম্ভব নয়</div>";
	}
}

//show Data
$all_data = "";
 $memberid = $_GET['member_id'];
$memberinfo = single_condition_select("members", "id", $member_id);

$data = get_posts("accounts_profit");

while ($row = mysqli_fetch_array($data['query'])) {
					$member_id = $row['member_id'];
					$entry_date = $row['entry_date'];
					$category = $row['category'];
				    $taka = $row['profit'];
				    $Comment = $row['comment'];
				
				$all_data .= "<tr>
				<td>$entry_date</td>
				<td>$category</td>
				<td>$taka</td>
				<td>$Comment</td>
				<td>button</td>
		 	    </tr>";	
					
					
}


?>

<div id='wrap '>
	<div id='printableArea' class='container'>
		<h3 class='maintitle text-center' id="header_first">profit</h3>
		<table class='datatable table table-striped table-bordered table-responsive-md' id="members_list">
			<thead>
				<tr>
					<th width='15px'>তারিখ </th>
					<th>ক্যাটেগরি</th>
					<th>টাকা</th>
					<th>মন্তব্য</th>
					<th class='hiddenp' width="80px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $all_data; ?>
			</tbody>
		</table>
	</div>
</div>
<script>
	$(document).ready(function() {
		// $("#members_list").dataTable({
		// 	"order": [],
		// })
		var header_first = $("#header_first").html();
		// var header_second = $("#header_second").html();
		var only_text_first = $("#header_first").text();
		// var only_text_second = $("#header_second").text();
		$("#members_list").dataTable({
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
			],

			buttons: [{
					extend: 'print',
					text: 'Print',
					className: "btn-sm",
					title: '',
					autoPrint: false,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					},
					customize: function(win) {
						$(win.document.body)
							.css('font-size', '10pt')
							.prepend(
								'<h3 class="text-center">' + header_first + '</h3>'
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
					title: only_text_first,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'pdf',
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
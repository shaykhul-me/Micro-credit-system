<?php
if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$table = "staffs";
	deletedata($table, $id);
}
?>

<?php
$data = get_posts("staffs");
$office_array = array();
$branch_array = array();
$office_sql = get_posts("office");
while ($office_res = mysqli_fetch_assoc($office_sql['query'])) {
	$office_array[$office_res['id']] = $office_res['name'];
}
$branch_sql = get_posts("branch");
while ($branch_res = mysqli_fetch_assoc($branch_sql['query'])) {
	$branch_array[$branch_res['id']] = $branch_res['branch_name'];
}
?>
<div id='wrap '>
	<div id='printableArea' class='container'>
		<h3 class='maintitle text-center mt-4' id="header_first">স্টাফদের তালিকা</h3>
		<table class='table table-striped table-responsive' id="staffs_list">
			<thead>
				<tr>
					<th>সদস্য ID</th>
					<th>ছবি</th>
					<th>নাম</th>
					<th>পদবি</th>
					<th>মোবাইল</th>
					<th>রেফারেন্সকৃত সদেস্য</th>
					<th>বেসিক পে</th>
					<th>কমিশন</th>
					<th>ব্যালেন্স</th>
					<th>অফিস</th>
					<th>ব্রাঞ্চ</th>
					<th class='hiddenp'>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($row = mysqli_fetch_array($data['query'])) {
					$staffid =	$row['staffid'];
					$name =	$row['name'];
					$photo = $row['staff_photo'];
					$mobile = $row['mobile'];
					$designation = $staff_desig_array[$row['position']];
					$refer_members = 0;
					$basic_pay = $row['basic_pay'];
					$commission = $row['commission'];
					$office = ($row['office'] > 0) ? $office_array[$row['office']] : "";
					$branch = ($row['branch'] > 0) ? $branch_array[$row['branch']] : "";
					//Refer Member
					$refer_sql = manual_query("SELECT COUNT(*) as total_refer FROM members WHERE reference='$staffid'");
					$refer_res = mysqli_fetch_assoc($refer_sql['query']);
					$refer_members = $refer_res['total_refer'];
					$verify_status = "text-danger";
					$verify_link = "<a href='index.php?action=mobile_verify&staff_id=$staffid'>Change/verify</a>";
					$staff_balance_sql = manual_query("SELECT COALESCE(SUM(earnings), 0) AS deposit, COALESCE(SUM(expense), 0) AS withdraw FROM staff_transections WHERE staff_id='$staffid'");
					$staff_balance_res = mysqli_fetch_assoc($staff_balance_sql['query']);
					(int)$staff_balance = $staff_balance_res['deposit']-$staff_balance_res['withdraw'];

					if ($row['verify_status'] == 1) {
						$verify_status = "text-success";
						//$verify_link=="";
					}
				?>
					<tr>
						<td><?php echo $staffid; ?></td>
						<td>
							<?php
							if ($photo == "") {
								echo "<img width='50px' width='100px' src='assets/images/img/demo.jpg'>";
							} else {
								if (file_exists($photo)) {
									echo "<img class='modal_image_item' width='50px' width='100px' src='$photo'>";
								} else {
									echo "<img width='50px' width='100px' src='assets/images/img/demo.jpg'>";
								}
							}
							?>
						</td>
						<td><?php echo $name; ?></td>
						<td><?php echo $designation; ?></td>
						<td class="<?php echo $verify_status; ?>"><?php echo isset($mobile) ? $mobile : ""; ?><br><?php echo $verify_link; ?></td>
						<td><?php echo $refer_members; ?></td>
						<td><?php echo $basic_pay; ?></td>
						<td><?php echo $commission; ?></td>
						<td><?php echo (int)$staff_balance; ?></td>
						<td><?php echo $office; ?></td>
						<td><?php echo $branch; ?></td>
						<td class='hiddenp'>
							<div class="btn-group dropright">
								<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action</button>
								<div class="dropdown-menu">
									<a class='dropdown-item' href='index.php?action=staff_view_single&id=<?php echo $staffid ?>'>View</a>
									<a onclick="return confirm('Are you want to Edit? <?php echo $name ?> ');" class='dropdown-item' href='index.php?action=staff_edit&id=<?php echo $staffid ?>'>Edit</a>
									<a onclick="return confirm('Are you want to Delete? <?php echo $name ?> ');" class='dropdown-item' href='?action=staff_view&delete=<?php echo $staffid ?>'>Delete</a>
									<a class='dropdown-item' href='?action=user_edit&staff_id=<?php echo $staffid ?>'>Login Access</a>
								</div>
							</div>
						</td>

					</tr>
				<?php
				}
				?>
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
		$("#staffs_list").dataTable({
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
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
					}
				},
				{
					extend: 'pdf',
					orientation: 'landscape',
					pageSize: 'A4',
					className: "btn-sm",
					title: only_text_first,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
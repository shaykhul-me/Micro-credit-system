<?php

if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$table = "members";
	$delete_info = deletedata($table, $id);
	if(isset($delete_info['msg'])){
		echo "<div class='alert alert-warning'>$id নং মেম্বারের তথ্য সফলভাবে মুছে ফেলা হয়েছে</div>";
	}else{
		echo "<div class='alert alert-danger'>$id নং মেম্বারের তথ্য মুছা সম্ভব নয়</div>";
	}
}
?>

<?php
$select_branch = "";
// if ($user_other_info['branch_id'] > 0) {
	// $data = manual_query("SELECT * FROM members WHERE branch='{$user_other_info['branch_id']}' ORDER BY id DESC");
// }else{	
$data = get_posts_sort("members", "member_no", "DESC");
// }
?>
<div id='wrap '>
	<div id='printableArea' class='container'>
		<h3 class='maintitle text-center' id="header_first">মেম্বারদের তালিকা</h3>
		<table class='datatable table table-striped table-bordered table-responsive-md' id="members_list">
			<thead>
				<tr>
					<th width='15px'>সদস্য নং</th>
						<th width='15px'>সদস্য আইডি  </th>
					<th>ছবি</th>
					<th>নাম</th>
					<th>Type</th>
					<th>status</th>
					<th>মোবাইল</th>
					<th>ঠিকানা</th>
					<th class='hiddenp' width="80px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$staff_info = array();
				while ($row = mysqli_fetch_array($data['query'])) {
				    $membersid = $row['id'];
					$member_no = $row['member_no'];
					$member_type = $row['member_type'];
					$status = $row['status'];
					$photo = $row['member_photo'];
					$name =	$row['name_bn'];
					$name_en =	$row['name'];
					$dob =	$row['date_of_birth'];
					$mobile =	$row['mobile'];
					
					
					//present address
					$present_address = $row['present_address'];
					$present_thana = $row['present_thana'];
					$present_district = $row['present_district'];
					$total_present_address = "	$present_address, $present_thana, $present_district";
					//permanent
					$permanent_address = $row['permanent_address'];
					$permanent_thana = $row['permanent_thana'];
					$permanent_district = $row['permanent_district'];
					// $verify_status = "text-danger";
					$verify_status = "";
					$verify_link = "<a href='index.php?action=mobile_verify&memberid=$membersid'>Change/verify</a>";
					if ($row['verify_status'] == 1) {
						// $verify_status = "text-success";
						//$verify_link=="";
					}
					// $refer_sql = single_condition_select("staffs", "staffid", $row['reference']);
					// $staff_res = mysqli_fetch_assoc($refer_sql['query']);
					// $staff_name = $staff_res['name'];
					// $office_sql = single_condition_select("office", "id", $row['office']);
					// $office_res = mysqli_fetch_assoc($office_sql['query']);
					// $office_name = $office_res['name'];
					
					// $branch_sql = single_condition_select("branch", "id", $row['branch']);
					// $branch_res = mysqli_fetch_assoc($branch_sql['query']);
					// $branch_name = $branch_res['branch_name'];

				?>
					<tr>
						<td>
							<?php
							echo $member_no;
							?>
						</td>
						
							<td>
							<?php
							echo $membersid;
							?>
						</td>
						
						<td>
							<?php
							if ($photo == "") {
								echo "<img height='80px' width='80px' src='assets/images/img/demo.jpg'>";
							} else {
								if (file_exists($photo)) {
									echo "<img height='80px' width='80px' src='$photo'>";
								} else {
									echo "<img height='80px' width='80px' src='assets/images/img/demo.jpg'>";
								}
							}
							?>
						</td>
						<td><?php echo isset($name) ? $name : ""; ?><br><?php echo isset($name_en) ? $name_en : ""; ?></td>
						<td><?php echo isset($member_type) ? $member_type : ""; ?></td>
							<td><?php echo isset($status) ? $status : ""; ?></td>
						<td class="<?php echo $verify_status; ?>"><?php echo isset($mobile) ? $mobile : ""; ?></td>
						<td><?php echo isset($total_present_address) ? $total_present_address : ""; ?></td>
						<td class='hiddenp text-center p-2'>
							<a class='btn btn-sm btn-primary mb-1' href='index.php?action=member_view_single&id=<?php echo $membersid ?>'>View</a>
							<a class='btn btn-sm btn-success mb-1' href='index.php?action=member_accounts_add&member_id=<?php echo $membersid ?>'>Accounts</a>
							<a onclick="return confirm('Are you want to Edit? <?php echo $name ?> ');" class='btn btn-sm btn-info mb-1' href='index.php?action=member_edit&id=<?php echo $membersid ?>'>Edit</a>
							<?php
							if($ac_type=="admin"){
							?>
							<a onclick="return confirm('Are you want to Delete? <?php echo $name ?> ');" class='btn btn-sm btn-danger mb-1' href='?action=members_view&delete=<?php echo $membersid ?>'>Delete</a>
							
							<!-- <a onclick="return confirm('Are you want to Delete? <?php //echo $name ?> ');" class='btn btn-sm btn-secondary mb-1' href='?action=user_edit&member_id=<?php //echo $membersid ?>'>Login Access</a> -->
							<?php
							}
							?>
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
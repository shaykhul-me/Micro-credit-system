<?php
//This Process only for users 
// $row['verify_status'] = 1;

//Userinfo
// $password = "S@a@admiN";
// $actype = "member";

// $password = password_protect($password);
// $sql = "insert into users (uid, datetime, ip, username, password, actype, ac_id, ac_area ) values ('1', '1', 'Local', 'sajal', '$password', 'admin', 'admin', 'admin')";
// mysqli_query($conn, $sql);

// if (isset($_GET['delete'])) {
// 	if ($_GET['delete'] == $_SESSION['userid']) {
// 		echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Sorry You can't delete this user</div>";
// 	} else {
// 		$id = $_GET['delete'];
// 		$table = "users";
// 		deletedata($table, $id);
// 	}
// }

$data = get_posts("users");
$usercount = $data['count'];
/*
	echo "<div id='wrap '>
	<div id='printableArea' class='container'>
	<h3 align='center' class='maintitle'>User View</h3>
	<table cellpadding='0' cellspacing='0' border='0' class='datatable table table-striped table-bordered'>
	<thead>
		<tr>
	<td>Userid</td>
	<td>Name</td>
	<td>Mobile</td>
	<td>Email</td>
	<td>Photo</td>
	<td>Role</td>
	<th class='hiddenp'>Actions</th>
	</tr>	
	</thead>
	<tbody>";
*/
if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$table = "users";
	$delete_info = deletedata($table, $id);
	if(isset($delete_info['msg'])){
		echo "<div class='alert alert-warning'>$id নং মেম্বারের তথ্য সফলভাবে মুছে ফেলা হয়েছে</div>";
	}else{
		echo "<div class='alert alert-danger'>$id নং মেম্বারের তথ্য মুছা সম্ভব নয়</div>";
	}
}
?>
<div id='wrap '>
	<div id='printableArea' class='container'>
		<h3 align='center' class='maintitle'>User View</h3>
		<div class="mb-2">
			<a class="btn btn-primary" href="index.php?action=user_add" role="button"><i class="fa fa-plus"></i>&nbsp;Add New User</a>
		</div>
		<div class="table-responsive">
		<table cellpadding='0' cellspacing='0' border='0' class='datatable table table-striped table-bordered'>
			<thead>
				<tr>
					<th width="80px" class="text-center">User Id</th>
					<th class="text-center">Name</th>
					<th class="text-center">Mobile</th>
					<th class="text-center">Email</th>
					<th class="text-center">Account Type</th>
					<th class="hiddenp text-center" width='200px'>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($row = mysqli_fetch_array($data['query'])) {
					$userid = $row['userid'];
					$name = $row['name'];
					$role =	$row['actype'];
					$email =	$row['email'];
					$mobile =	$row['mobile'];
					$online_power =	$row['online_power'];
				?>
					<tr>
						<td><?php echo isset($userid) ? $userid : ""; ?></td>
						<td><?php echo isset($name) ? $name : ""; ?></td>
						<td><?php echo isset($mobile) ? $mobile : ""; ?></td>
						<td><?php echo isset($email) ? $email : ""; ?></td>
						<td><?php echo isset($role) ? $role : ""; ?></td>
						<td><?php echo isset($online_power) ? $online_power : ""; ?></td>
						<td>
							<a class='btn btn-primary' href='user_view_single.php?id=<?php echo $userid ?>'>View</a> <a onclick="return confirm('Are you want to Edit? <?php echo $name ?> ');" class='btn btn-info' href='index.php?action=user_edit&id=<?php echo $userid ?>'>Edit</a> <a onclick="return confirm('Are you want to Delete? <?php echo $name ?>');" class='btn btn-danger' href='?action=user_view&&delete=<?php echo $userid ?>'>Delete</a>
						</td>
						<!-- 
						<?php
						if ($ac_type == "student") {
							echo "<td class='hiddenp'><a class='btn btn-primary' href=user_view_single.php?id=$userid'>View</a></td>";
						} else if ($ac_type == "guardian") {
							echo "<td class='hiddenp'><a class='btn btn-primary' href='user_view_single.php?id=$userid'>View</a></td>";
						} else if ($ac_type == "teacher") {
							echo "<td class='hiddenp'><a class='btn btn-primary' href='user_view_single.php?id=$userid'>View</a></td>";
						} else if ($ac_type == "staff") {
							echo "<td class='hiddenp'><a class='btn btn-primary' href='user_view_single.php?id=$userid'>View</a></td>";
						} else if ($ac_type == "editor") {

						?>
							<td class='hiddenp'><a class='btn btn-primary' href='user_view_single.php?id=<?php echo $userid ?>'>View</a> <a onclick="return confirm('Are you want to Edit? <?php echo $name ?> ');" class='btn btn-info' href='user_edit.php?id=<?php echo $userid ?>'>Edit</a>
							<?php
						} else {
							?>
							<td class='hiddenp'><a class='btn btn-primary' href='user_view_single.php?id=<?php echo $userid ?>'>View</a> <a onclick="return confirm('Are you want to Edit? <?php echo $name ?> ');" class='btn btn-info' href='user_edit.php?id=<?php echo $userid ?>'>Edit</a>

								<?php
								if ($usercount == "1") {
								} else {
								?>
									<a onclick="return confirm('Are you want to Delete? <?php echo $name ?>');" class='btn btn-danger' href='?action=user_view&&delete=<?php echo $userid ?>'>Delete</a>
								<?php
								}
								?>
							</td>
						<?php
						}
						?> 
						-->
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$(".datatable").dataTable();
	});
</script>
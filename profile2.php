<?php 
include('header.php');
?>



<?php

		if($ac_type=="teacher"){
			$adsql="SELECT * FROM teachers WHERE teacherid='$uid'";
		}
	 else if($ac_type=="staff"){	
		$adsql="SELECT * FROM staffs WHERE staffid='$uid'";
	 }
	  else if($ac_type=="admin"){
			$adsql="SELECT * FROM users WHERE userid='$uid'";
	 }
	  else  if($ac_type=="student"){
		$adsql="SELECT * FROM students WHERE studentid='$uid'";
	 }
	 else if ($ac_type=="guardian"){
		$adsql="SELECT * FROM students WHERE studentid='$uid'";
	 }
	 
$adresult = mysqli_query($conn, $adsql);   
 echo "<div class='table-responsive'><table class='table table-bordered'>";
if ($row = mysqli_fetch_assoc($adresult)){ 
		
		$photo	=$row['photo'];
		$name	=$row['name'];
		$mobile	=$row['mobile'];
		$email	=$row['email'];

echo "

<tr><td>Profile Photo</td>		<td><img height='100px' width='100px' src='uploads/$photo'></td></tr>
<tr><td>Name</td>		<td>$name</td></tr>
<tr><td>Mobile</td>		<td>$mobile</td></tr>
<tr><td>Email</td>		<td>$email</td></tr>

";
}
 echo "</table></div>";
 ?>

<?php 
include('footer.php');
?>
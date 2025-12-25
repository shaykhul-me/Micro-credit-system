<?php 
// include('header.php');
$id=$_GET['id'];
$data = single_post_view("users", $id);
if ($row = mysqli_fetch_array($data['query'])){ 
			$uid=	$row['uid'];
			$datetime=	$row['datetime'];
			$ip=	$row['ip'];
			$userid=	$row['userid'];
			$name=	$row['username'];
			// $mobile=	$row['mobile'];
			// $email=	$row['email'];
			// $photo=	$row['photo'];
			$password=	$row['password'];
			$ac_area=	$row['ac_area'];
			$upid=	$row['upid'];
			$updatetime=	$row['updatetime'];
			$upip=	$row['upip'];

echo "
<div class='row' >
  <div class='col-md-2'></div>
  <div id='printableArea' class='col-md-8 '>
           <div class='text-center font-weight-bold font-weight-bolder'><h3 class='font-weight-bold font-weight-bolder text-capitalize'>User $name Biography</h3></div><br>
       <div class='table-responsive'><table class='table table-bordered table-responsive'>
      <tr><td><label>Name:</label>&emsp;$name</td>  <td rowspan='3'><img height='100px' width='100px' src='uploads/'></td></tr>
    
      <tr><td><label>Mobile</label>&emsp;</td>    </tr>
      <tr><td><label>Email</label>&emsp;</td></tr>
      <tr><td><label>Account From : </label>&emsp;$ac_area</td><td><label></label>&emsp;</td></tr>

      
      </table></div>
  </div>
  <div class='col-md-2'></div>
</div>
";
}
 ?>
<center><button class="btn btn-primary"  onclick="printDiv('printableArea')">Print</button></center>
<?php 
include('footer.php');
?>
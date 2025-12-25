<?php
include "connect.php";

/*
$servername = "localhost";
$dbname="babugcol_2025";
$username = "babugcol_2025user";
$password = "NBdb343#bsl";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
*/
// field Direction Print
echo "<h3>Submit the below data fields in Excel csv(comma delimited) .</h3>";
echo "name,father_name,mobile,present_address,member_no<br>";

//CSV file upload code
if ( isset($_POST["submit"]) ) {
    
	//$department = mysqli_real_escape_string($conn,$_POST['department']);  
 if($_FILES['file']['name'])
 {
	 // this line for checking that is it csv
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if($ext == 'csv')
  {
   $handle = fopen($_FILES['file']['tmp_name'], "r");
   $totaldata = 0;
   while($data = fgetcsv($handle))
   {
	   
	   $totaldata += 1;
	   //column value from csv file.
        $name = mysqli_real_escape_string($conn, $data[0]);
		$father_name = mysqli_real_escape_string($conn, $data[1]);
		$mobile = mysqli_real_escape_string($conn, $data[2]);
        //education lebel means class
        $present_address= mysqli_real_escape_string($conn, $data[3]);
        	$member_no = mysqli_real_escape_string($conn, $data[4]);
     
        //Insert Data into CSV_data table
 $query = "INSERT into members (name,father_name,mobile,present_address,member_no) values ('$name','$father_name','$mobile','$present_address','$member_no')";
        
        
        $succ=mysqli_query($conn, $query);
		if($succ){
			echo "$totaldata Data Uploaded Successfully";
			//header("refresh: 3");
			echo "<meta http-equiv='refresh' content='3'>";
		}
		else{
			echo "<div style='color:red;'>Sorry! Data $totaldata Not Uploaded.</div>";
		}
   }
  }
 } 
} 
   ?>
   
<table width="600" cellpadding='20px'>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

<tr>
<td width="30%">Select file</td>
<td width="70%"><input type="file" name="file" id="file" required></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" /></td>
</tr>

</form>
</table>
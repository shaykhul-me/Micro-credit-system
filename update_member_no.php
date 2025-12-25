
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("connect.php");


// আপডেট কুয়েরি
$sql = "UPDATE members SET member_no = id";

if (mysqli_query($conn, $sql)) {
    echo "<div style='color: green; font-weight: bold;'>✅ স <    /div>";
} else {
    echo "<div style='color: red; font-weight: bold;'>❌ সমস্যা হয়েছে: " . mysqli_error($conn) . "</div>";
}

mysqli_close($conn);
?>

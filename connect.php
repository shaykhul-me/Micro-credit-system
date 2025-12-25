<?php
$servername = "localhost";
$dbusername = "itaddb_nbm";
$dbpassword = "0aZaZ]Guvk3]";
$dbname="itaddb_nbm";//shrefocre_soft
// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($servername, $dbusername, $dbpassword,$dbname);
mysqli_select_db($conn,$dbname);
mysqli_set_charset($conn, 'utf8');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>
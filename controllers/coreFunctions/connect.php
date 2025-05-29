<?php
//error_reporting(0);

//local server
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "indento";

//live server
$servername = "localhost";
$username = "dhonnoba_iss_user";
$password = "iss_user@#inalYZe202$";
$dbname = "dhonnoba_inalyze_software_suite";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, $dbname);
mysqli_set_charset($conn, 'utf8'); // <- add this too
mysqli_query($conn, "SET NAMES 'utf8';");
mysqli_query($conn, "SET CHARACTER SET 'utf8';");
// mysqli_query($conn, "SET SESSION collation_connection =’utf8_general_ci';");
?>
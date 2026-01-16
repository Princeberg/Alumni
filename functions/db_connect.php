<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "alumni";
$conn = mysqli_connect($servername,$username, $password,$database);
if(!$conn){
	echo "die('could not connect My Sql: '.mysql_error())"; 
}
// else 
// {
// 	echo "connected";
// }
?>

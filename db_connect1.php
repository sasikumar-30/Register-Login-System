<?php 

// declare variable and store database data :

$db_host="localhost";
$db_user="root";
$db_pass="sasikumar30";
$db_name="user_db";

// mysqli method: 

$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);

// check connection work or failure :

if($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}else{
    echo("DataBase Connected Successfully !👌");
}
?>
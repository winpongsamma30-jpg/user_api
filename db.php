<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crud_api"; // ubah dari user_api ke crud_api

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>

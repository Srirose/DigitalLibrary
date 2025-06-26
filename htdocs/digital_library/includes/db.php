
<?php
$host = "localhost:3307";
$user = "root";
$password = "";
$db = "digital_library";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

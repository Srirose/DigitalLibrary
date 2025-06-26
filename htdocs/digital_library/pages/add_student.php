<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $class = $_POST['class'];
    $reg_no = $_POST['register_number'];

    $conn->query("INSERT INTO Students (name, email, phone, class, register_number) 
                  VALUES ('$name', '$email', '$phone', '$class', '$reg_no')");

    $msg = "Student added successfully!";
}
?>
<style>
     .bottom-button {
      margin-top: auto;
      text-align: center;
      padding-top: 20px;
    }

    .bottom-button a {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
    }

    .bottom-button a:hover {
      background-color: #45a049;
    }
</style>

<!DOCTYPE html>
<html>
<head>
  <title>Add Student</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
   <div class="header">
  <h3 style="background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      text-align: center;">Digital-Library</h3>
</div>
<div class="container">
  <h2>Add Student</h2>
  <?php if (isset($msg)) echo "<p>$msg</p>"; ?>
  <form method="POST">
    <input type="text" name="name" placeholder="Student Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="text" name="class" placeholder="Class" required>
    <input type="text" name="register_number" placeholder="Register Number" required>
    <button type="submit">Add Student</button>
  </form>
   <div class="bottom-button">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</div>
</body>
</html>

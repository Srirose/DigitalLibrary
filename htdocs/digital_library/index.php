<?php
session_start();
include 'includes/db.php';

$conn = new mysqli("localhost:3307", "root", "", "digital_library");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $query = $conn->query("SELECT * FROM Admins WHERE username='$username' AND password='$password'");
    if ($query->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: pages/dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Digital Library Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    .background {
      position: fixed;
      width: 100%;
      height: 100%;
      background: url('images/bg.jpg') no-repeat center center/cover;
      animation: floatBackground 20s infinite linear;
      filter: brightness(0.7);
      z-index: -1;
    }

    @keyframes floatBackground {
      0% { background-position: 50% 50%; }
      50% { background-position: 48% 52%; }
      100% { background-position: 50% 50%; }
    }

    .container {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: black;
      text-align: center;
    }

    .title {
      font-size: 3em;
      font-weight: bold;
      margin-bottom: 30px;
      animation: fadeInSlide 2s ease-out forwards;
    }

    @keyframes fadeInSlide {
      0% {
        opacity: 0;
        transform: translateY(-40px);
      }
      100% {
        opacity: 1;
        transform: translateY(0px);
      }
    }

    form {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 10px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      animation: bounceIn 2s ease-out;
    }

    @keyframes bounceIn {
      0% { transform: scale(0.9); opacity: 0; }
      60% { transform: scale(1.05); opacity: 1; }
      100% { transform: scale(1); }
    }

    input {
      display: block;
      width: 250px;
      margin: 10px auto;
      padding: 10px;
      border-radius: 5px;
      border: none;
      outline: none;
      font-size: 16px;
    }

    button {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 10px 25px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #45a049;
    }

    .error {
      color: #ff6961;
      margin-bottom: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="background"></div>

<div class="container">
  <div class="title">📚 Digital Library</div>

  <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="POST">
    <input type="text" name="username" placeholder="Enter Username" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>

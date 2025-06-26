
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
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
  <h2>Admin Dashboard</h2>
  <a href="issue_book.php"><button>Issue Book</button></a>
  <a href="return_book.php"><button>Return Book</button></a>
  <a href="books_list.php"><button>View Books</button></a>
  <a href="add_student.php"><button>Add Student</button></a>
  <a href="overdue_report.php"><button>Overdue Report</button></a>


</div>
<div class ="button-footer">
    <a class="logout" href="../logout.php">Logout</a>
  </div>
</body>
</html>

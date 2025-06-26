<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$overdues = $conn->query("
  SELECT IssueLogs.issue_id, Students.name AS student, Books.title AS book, IssueLogs.issue_date
  FROM IssueLogs
  JOIN Students ON IssueLogs.student_id = Students.student_id
  JOIN Books ON IssueLogs.book_id = Books.book_id
  WHERE IssueLogs.status = 'issued' AND DATEDIFF(CURDATE(), issue_date) > 15
");
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
  <title>Overdue Report</title>
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
  <h2>Overdue Books</h2>
  <table border="1" width="100%" cellpadding="5">
    <tr>
      <th>Issue ID</th>
      <th>Student</th>
      <th>Book</th>
      <th>Issue Date</th>
      <th>Days Overdue</th>
    </tr>
    <?php while ($row = $overdues->fetch_assoc()) {
      $days_overdue = (new DateTime())->diff(new DateTime($row['issue_date']))->days - 3;
    ?>
      <tr>
        <td><?= $row['issue_id'] ?></td>
        <td><?= $row['student'] ?></td>
        <td><?= $row['book'] ?></td>
        <td><?= $row['issue_date'] ?></td>
        <td><?= $days_overdue ?></td>
      </tr>
    <?php } ?>
  </table>
</div>
<div class="bottom-button">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>

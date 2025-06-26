<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
if ($book_id <= 0) {
    echo "Invalid Book ID";
    exit();
}

// Fetch book details
$book = $conn->query("SELECT * FROM Books WHERE book_id = $book_id")->fetch_assoc();

// Fetch issue history
$history = $conn->query("
    SELECT s.name, s.student_id, i.issue_date, i.return_date
    FROM IssueLogs i
    JOIN Students s ON i.student_id = s.student_id
    WHERE i.book_id = $book_id
    ORDER BY i.issue_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Book History</title>
  <link rel="stylesheet" href="../css/styles.css">
  <style>
    .container {
      max-width: 800px;
      margin: 50px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px gray;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }

    a {
      text-decoration: none;
      color: #4CAF50;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Issue History for: <?= htmlspecialchars($book['title']) ?> (Book ID: <?= $book_id ?>)</h2>

  <table>
    <tr>
      <th>Student Name</th>
      <th>Student ID</th>
      <th>Issue Date</th>
      <th>Return Date</th>
    </tr>
    <?php while ($row = $history->fetch_assoc()) { ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['student_id'] ?></td>
      <td><?= $row['issue_date'] ?></td>
      <td><?= $row['return_date'] ?? '-' ?></td>
    </tr>
    <?php } ?>
  </table>

  <div class="bottom-button" style="text-align:center; margin-top:20px;">
    <a href="books_list.php">← Back to Book List</a>
  </div>
</div>
</body>
</html>

<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_id = intval($_POST['issue_id']); // Sanitize input

    // First, check if issue_id exists
    $result = $conn->query("SELECT book_id FROM IssueLogs WHERE issue_id = $issue_id");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $book_id = intval($row['book_id']);

        // Update return status in IssueLogs
        $updateIssue = $conn->query("UPDATE IssueLogs SET return_date = CURDATE(), status = 'returned' WHERE issue_id = $issue_id");

        // Update available copies in Books table
        $updateBook = $conn->query("UPDATE Books SET available_copies = available_copies + 1 WHERE book_id = $book_id");

        if ($updateIssue && $updateBook) {
            $msg = "✅ Book returned successfully.";
        } else {
            $msg = "❌ Error updating records: " . $conn->error;
        }

    } else {
        $msg = "⚠️ Invalid Issue ID.";
    }
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
  <title>Return Book</title>
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
  <h2>Return Book</h2>
  <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
  <form method="POST">
    <input type="number" name="issue_id" placeholder="Issue ID" required>
    <button type="submit">Return</button>
  </form>
</div>
<div class="bottom-button">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>

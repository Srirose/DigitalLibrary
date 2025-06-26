<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$issue_details = null;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];

    if (is_numeric($student_id) && is_numeric($book_id)) {
        // Check if student exists
        $student_check = $conn->query("SELECT * FROM Students WHERE student_id = $student_id");

        if ($student_check && $student_check->num_rows > 0) {
            $student = $student_check->fetch_assoc();

            // Check if book exists
            $book_check = $conn->query("SELECT * FROM Books WHERE book_id = $book_id");

            if ($book_check && $book_check->num_rows > 0) {
                $book = $book_check->fetch_assoc();

                if ($book['available_copies'] > 0) {
                    // Set dates
                    $issue_date = date('Y-m-d');
                    $return_date = date('Y-m-d', strtotime('+7 days'));

                    // Insert issue log
                    $conn->query("INSERT INTO IssueLogs (student_id, book_id, issue_date, return_date) 
                                  VALUES ($student_id, $book_id, '$issue_date', '$return_date')");

                    // Update available copies
                    $conn->query("UPDATE Books SET available_copies = available_copies - 1 WHERE book_id = $book_id");

                    // Prepare data for display
                    $issue_details = [
                        'student_name' => $student['name'],
                        'student_id' => $student_id,
                        'book_title' => $book['title'],
                        'book_id' => $book_id,
                        'issue_date' => $issue_date,
                        'return_date' => $return_date
                    ];

                    $msg = "✅ Book issued successfully.";
                } else {
                    $msg = "⚠️ Book not available.";
                }
            } else {
                $msg = "❌ Invalid Book ID. Please enter a valid Book ID.";
            }
        } else {
            $msg = "❌ Invalid Student ID. Please enter a valid Student ID.";
        }
    } else {
        $msg = "❌ Invalid input. IDs must be numeric.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Issue Book</title>
  <link rel="stylesheet" href="../css/styles.css">
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

    .msg { font-weight: bold; margin-top: 15px; }
    .details { margin-top: 10px; background: #f9f9f9; padding: 10px; border-radius: 5px; }
  </style>
</head>
<body>
<div class="header">
  <h3 style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center;">Digital-Library</h3>
</div>
<div class="container">
  <h2>Issue Book</h2>

  <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>


  <?php if ($issue_details): ?>
  <div class="details">
    <p><strong>Student Name:</strong> <?= htmlspecialchars($issue_details['student_name']) ?></p>
    <p><strong>Student ID:</strong> <?= $issue_details['student_id'] ?></p>
    <p><strong>Book Title:</strong> <?= htmlspecialchars($issue_details['book_title']) ?></p>
    <p><strong>Book ID:</strong> <?= $issue_details['book_id'] ?></p>
    <p><strong>Issue Date:</strong> <?= $issue_details['issue_date'] ?></p>
    <p><strong>Return Date:</strong> <?= $issue_details['return_date'] ?></p>
  </div>
<?php endif; ?>


  <form method="POST">
    <input type="number" name="student_id" placeholder="Student ID" required>
    <input type="number" name="book_id" placeholder="Book ID" required>
    <button type="submit">Issue</button>
  </form>

  <div class="bottom-button">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</div>
</body>
</html>

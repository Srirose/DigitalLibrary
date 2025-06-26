<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch books with optional search
$sql = "SELECT * FROM Books";
if ($search != '') {
    $sql .= " WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR category LIKE '%$search%' OR isbn LIKE '%$search%'";
}
$books = $conn->query($sql);
if (!$books) {
    die("Query failed: " . $conn->error);
}

// Get list of book_ids issued today
$today = date('Y-m-d');
$issuedTodayResult = $conn->query("SELECT DISTINCT book_id FROM IssueLogs WHERE issue_date = '$today'");
$issuedTodayBooks = [];
while ($row = $issuedTodayResult->fetch_assoc()) {
    $issuedTodayBooks[] = $row['book_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Books List</title>
  <link rel="stylesheet" href="../css/styles.css">
  <style>
    .container {
      width: 90%;
      max-width: 1000px;
      margin: 50px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px gray;
      display: flex;
      flex-direction: column;
      min-height: 600px;
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

    tr.issued-today {
      background-color: #ffefc0; /* Light yellow for issued books */
    }

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

    .search-bar {
      display: flex;
      justify-content: flex-end;
      margin-top: 10px;
    }

    .search-bar input[type="text"] {
      padding: 6px;
      border-radius: 4px;
      border: 1px solid #ccc;
      margin-left: auto;
    }

    .search-bar button {
      margin-left: 10px;
      padding: 6px 12px;
      border: none;
      background-color: #4CAF50;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }

    .search-bar button:hover {
      background-color: #45a049;
    }

    a.book-link {
      color: #007BFF;
      text-decoration: none;
    }

    a.book-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="header">
  <h3 style="background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      text-align: center;">Book List</h3>
</div>

<div class="container">
  <h2>All Books</h2>

  <form class="search-bar" method="GET">
    <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
  </form>

  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Author</th>
      <th>Category</th>
      <th>ISBN</th>
      <th>Total</th>
      <th>Available</th>
    </tr>
    <?php while ($row = $books->fetch_assoc()) {
      $highlight = in_array($row['book_id'], $issuedTodayBooks) ? 'issued-today' : '';
    ?>
      <tr class="<?= $highlight ?>">
        <td><a class="book-link" href="book_history.php?book_id=<?= $row['book_id'] ?>"><?= htmlspecialchars($row['book_id']) ?></a></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['isbn']) ?></td>
        <td><?= htmlspecialchars($row['total_copies']) ?></td>
        <td><?= htmlspecialchars($row['available_copies']) ?></td>
      </tr>
    <?php } ?>
  </table>

  <div class="bottom-button">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</div>
</body>
</html>

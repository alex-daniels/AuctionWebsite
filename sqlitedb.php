<?php # sqlite.php - creates a handle to your database.
  $dbname = "database.db";
  try {
    $db = new PDO("sqlite:" . $dbname);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }
?>
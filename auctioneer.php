<?php 
/*
auctioneer.php

This is the main landing page for the auctioneer website
*/
include('./sqlitedb.php');
include ('./navbar.html');
?>

<html>
<head>
<title>Auctioneer</title>
</head>
</html>
<!--<?php
/*
  try{
    $db->beginTransaction();
    $query = "select currentTime from Time";
    $result = $db->query($query);
    $row = $result->fetch();
    $db->commit();
    echo "<h3>Current Time</h3>";
    echo $row['currentTime'];
  }catch(PDOException $e){
    $db->rollback();
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }
  $db = null;

?>*/

//</html>


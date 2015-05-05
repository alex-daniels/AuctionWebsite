<?php 
/*
currtime.php
-------------------------------------------------------------------------------
This file displays the current time from the database
Note: this is not the current time in real life, it is the current time in the
auctionbase universe.  The time can be set using selecttime.php.
*/
  include('./sqlitedb.php');
?>

<html>
<head>
<title>AuctionCentral</title>
</head>

<?php
  include ('./navbar.html');
?>

<center>
<h3>Current Time</h3> 

<?php
  try{
    $db->beginTransaction();
    $query = "select currentTime from Time";
    $result = $db->query($query);
    $row = $result->fetch();
    $db->commit();
    echo $row['currentTime'];
  }catch(PDOException $e){
    $db->rollback();
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }

  $db = null;
?>
</center>
</html>


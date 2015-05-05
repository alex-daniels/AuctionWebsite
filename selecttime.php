<?php 
/*
selectTime.php
-------------------------------------------------------------------
This file lets an admin select the time for the auctionbase universe

*/
  include('./sqlitedb.php');
?>
<html>
<head>
<title>AuctionBase</title>
</head>

<?php
  include ('./navbar.html');
?>

<center>
<h3>Select a Time</h3> 

  <form method="POST" action="selecttime.php">
  <?php 
    include ('./timetable.html');
  ?>
  </form>

  <?php
    if (isset($_POST["MM"])) {
      $MM = $_POST["MM"];
      $dd = $_POST["dd"];
      $yyyy = $_POST["yyyy"];
      $HH = $_POST["HH"];
      $mm = $_POST["mm"];
      $ss = $_POST["ss"];    
    
      $selectedtime = $yyyy."-".$MM."-".$dd." ".$HH.":".$mm.":".$ss;

      try {
        $db->beginTransaction();
        $query = "update Time set currentTime = '$selectedtime';";
        $result = $db->query($query);
        $db->commit();
      } catch (PDOException $e) {
        try {
          $db->rollBack();
        } catch (PDOException $pe) {}
        echo "SQLite connection failed: " . $e->getMessage();
      }

      $db = null;

    }
?>
    
</center>
</html>
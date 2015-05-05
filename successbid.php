<?php
  include('./navbar.html');
  $id = $_GET['itemID'];
  $bid = $_SESSION['bids'];
  $winPrice = $_SESSION['amount'];
  echo "<br>";
  echo $bid;
  echo "<br>".$winPrice;

  
  if($bid != null){
    if((float)$bid <= (float)$winPrice)
      $error = "Error bid must be larger than the current price";
    else{
      try{
        $db->beginTransaction();
        $time = "select * from Time";
        $timeresult = $db->query($time);
        $timefind = $timeresult->fetch();
        $time = $timefind['currentTime'];
        $db->commit();

        //echo $time;
        $string = $id.", '".$user."', '".$time."', ".(float)$bid.")";
        $user = $_SESSION['username'];
        $db->beginTransaction();
        $query = "insert into Bid values(".$string;
        $result = $db->query($query);
        //$rows = $result->fetch();
        $db->commit();
      }catch(PDOException $e){
          $db->rollback();
          echo "SQLite connection failed: " . $e->getMessage();
          exit();
      }
    }
}
?>

<meta http-equiv="refresh" content=<?php echo $string ?> >

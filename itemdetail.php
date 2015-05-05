<?php
  include('./navbar.html');
  include('./sqlitedb.php');

  $bid = null;
  $error = null;
  $_SESSION['error'] = "";
  $id = $_GET['itemID'];
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['bid']))
      $biderr = "BID REQUIRED";
    else {
      $bid = $_POST['bid'];
      echo "<meta http-equiv='refresh' content='0; url=itemdetail.php?itemID=".$id."'>";
    }
  }

  try{
    $db->beginTransaction();
    $query = "select * from Item where itemID = ".$id;
    $result = $db->query($query);
    $row = $result->fetch();
    $db->commit();
    if($row['buy_price'] == "NULL")
      $row['buy_price'] = "None";

    if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
      $row['currently'] = number_format($row['currently'], 2);

    if(strlen(substr(strrchr($row['first_bid'], "."), 1)) == 1)
      $row['first_bid'] = number_format($row['first_bid'], 2);
    
    $db->beginTransaction();
    $query = "select * from Time";
    $result = $db->query($query);
    $row2 = $result->fetch();
    $db->commit();

    if($row['number_of_bids'] > 0){
    $db->beginTransaction();
    $query = "select max(amount), bidderID from Bid where itemID = ".$id;
    $result = $db->query($query);
    $maxBidder = $result->fetch();
    $db->commit();
    }
    else{
      $winPrice = $row['currently'];
      if(strlen(substr(strrchr($winPrice, "."), 1)) == 1)
            $winPrice = number_format($winPrice, 2);
    }

    if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price']){
      $status = "CLOSED";
      if($row['number_of_bids'] > 0){
        $winner = $maxBidder['bidderID'];
        $winPrice = $maxBidder['max(amount)'];
        if(strlen(substr(strrchr($winPrice, "."), 1)) == 1)
            $winPrice = number_format($winPrice, 2);
      }
    }
    else
    {
      $status = "OPEN";
      if($row['number_of_bids'] > 0){
        $winPrice = $maxBidder['max(amount)'];
        if(strlen(substr(strrchr($winPrice, "."), 1)) == 1)
          $winPrice = number_format($winPrice, 2);
      }
    }

  }catch(PDOException $e){
    $db->rollback();
    echo "SQLite connection failed: ".$e->getMessage();
    exit();
  }

  
  if($bid != null){
    if((float)$bid <= (float)$winPrice)
    {
      $error = "Error bid must be larger than the current price";
      $_SESSION['error'] = $error;
    }
    else{
      try{
        $_SESSION['error'] = null;
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

  echo "<table>";
  echo "<tr><th>Status: ".$status."</th></tr>";
  echo "</table>";
  echo "<table>";
  echo "<tr><td>ItemID:</td><td>".$row['itemID']."</td></tr>";
  echo "<tr><td>Name:</td><td>".$row['name']."</td></tr>";
  echo "<tr><td>Current Price: </td><td>$".$row['currently']."</td></tr>";
  echo "<tr><td>Starting Price: </td><td>$".$row['first_bid']."</td></tr>";
  echo "<tr><td>Buy Price: </td><td>".$row['buy_price']."</td></tr>";
  echo "<tr><td>Number of Bids: </td><td>".$row['number_of_bids']."</td></tr>";
  echo "<tr><td>Seller: </td><td>".$row['sellerID']."</td></tr>";
  echo "<tr><td>Bidding Started: </td><td>".$row['started']."</td></tr>";
  echo "<tr><td>Bidding Ends: </td><td>".$row['ends']."</td></tr>";
  echo "<tr><td><br></td></tr>";

  if($status == "CLOSED"){
    if($row['number_of_bids'] > 0){
      echo "<tr><td>Congratulations </td><td>".$winner.", You Won!</td></tr>";
      echo "<tr><td>Winning Bid: </td><td>$".$winPrice."</td></tr>";
    }
    else
      echo "<tr><td>There were no bids :(</td></tr>";
  }

  if($status == "OPEN" && $_SESSION['login'] == 1)
  {
   // echo "<a href='bid.php?itemID='".$id."'>Click here to bid</a>";
    echo "<table>";
    echo "<tr><td>";
    echo "Place a bid!";
    echo "<form id='bid' action='' method='post'>
          <input type = 'hidden' name='bid' id='bid' value='bid'/>
          <label for='bid'>Bid (must be greater than $".$winPrice."):</label>
          <input type='text' name='bid' id='bid' maxlenght='12' />
          <input type='submit' name='Submit' value='Bid!'/>
          </form>";
    echo "</td></tr>";
    if(empty($_SESSION['error']))
      $_SESSION['error'] = null;
    if($_SESSION['error'] != null)
      echo "<tr><td>".$_SESSION['error']."</td></tr>";
  }
  else if($status == "OPEN" && $_SESSION['login'] != 1)
    echo "<tr><td>Must be logged in to Bid</td></tr>";

  echo "</table>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<table>";
  echo "<tr><th>Information</th></tr><tr><td>".$row['description']."</td></tr>";
  echo "</table>";

 ?>
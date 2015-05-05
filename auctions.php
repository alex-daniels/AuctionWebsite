<?php 

/*
currtime.php
-------------------------------------------------------------------------------
This file displays the current time from the database
Note: this is not the current time in real life, it is the current time in the
auctionbase universe.  The time can be set using selecttime.php.
*/
  include('./navbar.html');
  include('./sqlitedb.php');
  include('./search.php');
?>

<html>
<head>
<title>Auctions Landing Page</title>
</head>

<?php
  $itemsPerPage = 100;
  $page = null;
  if(isset($_GET['page'])){$page = $_GET['page']; } else {$page = 1;};
  $start = ($page - 1) * $itemsPerPage; 
  $status = null;
  //main page with no search terms entered
  try{
    if(empty($itemID) && empty($price) && empty($category) && empty($status1)){
      $db->beginTransaction();
      $query = "select * from Item LIMIT ".$start.",".$itemsPerPage;
      $result = $db->query($query);
      $arrayRow = $result->fetchall(PDO::FETCH_ASSOC);
      $db->commit();
      $db->beginTransaction();
      $query = "select currentTime from Time";
      $result = $db->query($query);
      $row2 = $result->fetch();
      $db->commit();
      echo "<div class='table-responsive'>";
      echo "<table class='table table-bordered'>";
      echo "<tbody>";
      echo "<tr><td>ItemID</td><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids<td>Time Start</td>
            <td>Time End</td><td>Seller</td><td>Status</td></tr>";
      foreach($arrayRow as $row){
        if($row['buy_price'] == "NULL")
          $row['buy_price'] = "None";

        if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
          $status = "CLOSED";
        else
          $status = "OPEN";
        
        if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
          $row['currently'] = number_format($row['currently'], 2);
        if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
          $row['buy_price'] = number_format($row['buy_price'], 2);

        if($row['buy_price'] != "None")
          $row['buy_price'] = "$".$row['buy_price'];

        echo "<tr><td>".$row['itemID']."</td><td>"."<a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
        $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
        $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</td></tr>";
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";

      $query = "select count(itemID) from Item";
      $result = $db->query($query);
      $row = $result->fetch(PDO::FETCH_NUM);
      $totalItems = $row[0];
      $totalPages = ceil($totalItems / $itemsPerPage);
      echo "<p align:center>";
      for($i = 1; $i <= $totalPages; $i++){
        if($i >= 1 || $i <= 3){
          if($i < $totalPages){
            echo "<a href='auctions.php?page=".$i."'>".$i.", "."</a>";
          }else{
            echo "<a href='auctions.php?page=".$i."'>".$i."</a>";
          }
        }
      }
      echo "</p>";
    }
    //itemId search
    else if(empty($price) && empty($category) && empty($status1)){
      $db->beginTransaction();
      $query = "select * from Item where itemID = ".$itemID;
      $result = $db->query($query);
      $row = $result->fetch();
      $db->commit();

      $db->beginTransaction();
      $query = "select * from Time";
      $result = $db->query($query);
      $row2 = $result->fetch();
      $db->commit();
      if($row['buy_price'] == "NULL")
        $row['buy_price'] = "None";
      else
        $row['buy_price'] = "$".$row['buy_price'];

      if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
        $status = "CLOSED";
      else
        $status = "OPEN";

      if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
        $row['currently'] = number_format($row['currently'], 2);
      if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
        $row['buy_price'] = number_format($row['buy_price'], 2);

      echo "<div class='table-responsive'>";
      echo "<table class='table table-bordered'>";
      echo "<tbody>";
      echo "<tr><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids
            <td>Time Start</td><td>Time End</td><td>Seller</td><td>Status</td></tr>";

      echo "<tr><td><a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
      $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
      $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</tr>";
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }
    //price search
    else if(empty($category) && empty($status1)){
      //echo $price;
      $db->beginTransaction();
      $query = "select * from Item where ".$price;//." LIMIT ".$start.", ".$itemsPerPage;
      $result = $db->query($query);
      $rowarray= $result->fetchall(PDO::FETCH_ASSOC);
      $db->commit();

      $db->beginTransaction();
      $query = "select * from Time";
      $result = $db->query($query);
      $row2 = $result->fetch();
      $db->commit();

      echo "<table>";
      if($price == "currently <= 5.00")
        echo "<tr><td><h3>Prices less than $5.00<h3></td></tr>";
      else if($price == "currently >= 5.01 and currently <= 10.00")
        echo "<tr><td><h3>Prices between $5.00 and $10.00<h3></td></tr>";
      else if($price == "currently >= 10.01 and currently <= 20.00")
        echo "<tr><td><h3>Prices between $10.00 and $20.00</h3></td></tr>";
      else if($price == "currently >= 20.01 and currently <= 30.00")
        echo "<tr><td><h3>Prices between $20.00 and $30.00</h3></td></tr>";
      else if($price == "currently >= 30.01 and currently <= 40.01")
        echo "<tr><td><h3>Prices between $30.00 and $40.00</h3></td></tr>";
      else if($price == "currently >= 40.01 and currently <= 50.00")
        echo "<tr><td><h3>Prices between $40.00 and $50.00</h3></td></tr>";
      else if($price == "currently >= 50.01 and currently <= 60.00")
        echo "<tr><td><h3>Prices between $50.00 and $60.00</h3></td></tr>";
      else if($price == "currently >= 60.01 and currently <= 70.00")
        echo "<tr><td><h3>Prices between $60.00 and $70.00</h3></td></tr>";
      else if($price == "currently >= 70.01 and currently <= 80.00")
        echo "<tr><td><h3>Prices between $70.00 and $80.00</h3></td></tr>";
      else if($price == "currently >= 80.01 and currently <= 90.00")
        echo "<tr><td><h3>Prices between $80.00 and $90.00</h3></td></tr>";
      else if($price == "currently >= 90.01 and currently <= 100.00")
        echo "<tr><td><h3>Prices between $90.00 and $100.00</h3></td></tr>";
      else if($price == "currently >= 100.01")
        echo "<tr><td><h3>Prices greater than $100.00</h3></td></tr>";
      echo "</table>";

      echo "<div class='table-responsive'>";
      echo "<table class='table table-bordered'>";
      echo "<tbody>";
      echo "<tr><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids<td>Time Start</td>
            <td>Time End</td><td>Seller</td><td>Status</td></tr>";

      foreach($rowarray as $row){
        if($row['buy_price'] == "NULL")
          $row['buy_price'] = "None";

        if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
          $status = "CLOSED";
        else
          $status = "OPEN";

        if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
          $row['currently'] = number_format($row['currently'], 2);

        if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
        {
          $row['buy_price'] = number_format($row['buy_price'], 2);
        }
        
        if($row['buy_price'] != "None")
          $row['buy_price'] = "$".$row['buy_price'];

        echo "<tr><td>"."<a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
        $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
        $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</td></tr>";
        }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
      } 
      //category search
      else if(empty($status1)){
        //echo $status1;
        $db->beginTransaction();
        $query = "select * from Item, Category where Item.itemID = Category.itemID and categories = '".$category."'";
        $result = $db->query($query);
        $rowarray= $result->fetchall(PDO::FETCH_ASSOC);
        $db->commit();

        $db->beginTransaction();
        $query = "select * from Time";
        $result = $db->query($query);
        $row2 = $result->fetch();
        $db->commit();

        echo "<table>";
        echo "<tr><td><h3>Category: ".$category."</he></td></tr>";
        echo "</table>";

        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        echo "<tbody>";
        echo "<tr><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids<td>Time Start</td>
              <td>Time End</td><td>Seller</td><td>Status</td></tr>";
        foreach($rowarray as $row){
          if($row['buy_price'] == "NULL")
            $row['buy_price'] = "None";

          if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
            $status = "CLOSED";
          else
            $status = "OPEN";

        if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
          $row['currently'] = number_format($row['currently'], 2);
        if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
          $row['buy_price'] = number_format($row['buy_price'], 2);

        if($row['buy_price'] != "None")
          $row['buy_price'] = "$".$row['buy_price'];

          echo "<tr><td>"."<a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
          $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
          $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</td></tr>";
          }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
      }
      //all open search
      else if($status1 == "open"){
        //echo $status1;
        $db->beginTransaction();
        $query = "select * from Item ";//LIMIT ".$start.",".$itemsPerPage;
        $result = $db->query($query);
        $arrayRow = $result->fetchall(PDO::FETCH_ASSOC);
        $db->commit();

        $db->beginTransaction();
        $query = "select * from Time";
        $result = $db->query($query);
        $row2 = $result->fetch();
        $db->commit();

        echo "<table>";
        echo "<tr><td><h3>Status: ".$status1."</h3></td></tr>";
        echo "</table>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        echo "<tbody>";
        echo "<tr><td>ItemID</td><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids<td>Time Start</td>
              <td>Time End</td><td>Seller</td><td>Status</td></tr>";

        foreach($arrayRow as $row){
          if($row['buy_price'] == "NULL")
            $row['buy_price'] = "None";

          if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
            $status = "CLOSED";
          else
            $status = "OPEN";

          if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
            $row['currently'] = number_format($row['currently'], 2);
          if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
            $row['buy_price'] = number_format($row['buy_price'], 2);

          if($row['buy_price'] != "None")
            $row['buy_price'] = "$".$row['buy_price'];


          if($status == "OPEN" && $status1 == "open"){
            echo "<tr><td>".$row['itemID']."</td><td>"."<a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
            $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
            $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</td></tr>";
          }
          else if($status == "CLOSED"){
            echo "";
          }
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
      }
      //all closed search
      else if($status1 == "closed"){
        $db->beginTransaction();
        $query = "select * from Item";// LIMIT ".$start.",".$itemsPerPage;
        $result = $db->query($query);
        $arrayRow = $result->fetchall(PDO::FETCH_ASSOC);
        $db->commit();

        $db->beginTransaction();
        $query = "select * from Time";
        $result = $db->query($query);
        $row2 = $result->fetch();
        $db->commit();

        echo "<table>";
        echo "<tr><td><h3>Status: ".$status1."</h3></td></tr>";
        echo "</table>";

        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered' width='75%'>";
        echo "<tbody>";
        echo "<tr><td>ItemID</td><td>Name</td><td>Price</td><td>Buy Price</td><td>Bids<td>Time Start</td>
              <td>Time End</td><td>Seller</td><td>Status</td></tr>";
        foreach($arrayRow as $row){
          if($row['buy_price'] == "NULL")
            $row['buy_price'] = "None";
          if($row['ends'] <= $row2['currentTime'] || $row['started'] > $row2['currentTime'] || $row['currently'] == $row['buy_price'])
            $status = "CLOSED";
          else
            $status = "OPEN";

          if(strlen(substr(strrchr($row['currently'], "."), 1)) == 1)
            $row['currently'] = number_format($row['currently'], 2);
          if(strlen(substr(strrchr($row['buy_price'], "."), 1)) == 1)
            $row['buy_price'] = number_format($row['buy_price'], 2);

          if($row['buy_price'] != "None")
            $row['buy_price'] = "$".$row['buy_price'];


          if($status == "CLOSED" && $status1 == "closed"){
            echo "<tr><td>".$row['itemID']."</td><td>"."<a href='itemdetail.php?itemID=".$row['itemID']."'>".$row['name']."</td></a><td>$".
            $row['currently']."</td><td>".$row['buy_price']."</td><td>".$row['number_of_bids']."</td><td>".
            $row['started']."</td><td>".$row['ends']."</td><td>".$row['sellerID']."</td><td>".$status."</td></tr>";
          }
          else if($status == "OPEN" && $status1 == "open"){
            echo "<tr><td></td></tr>";
          }
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
      }
  }catch(PDOException $e){
    $db->rollback();
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }
  /*
  try{
    if(empty($itemID)){
      $query = "select count(itemID) from Item";
      $result = $db->query($query);
      $row = $result->fetch(PDO::FETCH_NUM);
      $totalItems = $row[0];
      $totalPages = ceil($totalItems / $itemsPerPage);
      echo "<p align:center>";
      for($i = 1; $i <= $totalPages; $i++){
        if($i >= 1 || $i <= 3){
          if($i < $totalPages){
            echo "<a href='auctions.php?page=".$i."'>".$i.", "."</a>";
          }else{
            echo "<a href='auctions.php?page=".$i."'>".$i."</a>";
          }
        }
      }
      echo "</p>";
    }
  }catch(PDOException $e){
    $db ->rollback();
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }*/

  $db = null;
?>
</html>




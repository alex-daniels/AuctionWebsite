<h1>Auctions</h1>
<h2>Search</h2>

<?php
  $itemID = null;
  $category = null;
  $price = null;
  $itemIDErr = " ";
  $catErr = null;
  $priceerr = null;
  $status1 = null;
  $_SESSION['session'] = null;
  $change = null;

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["itemID"]))
      $itemIDErr = "ID required";
    else{
      $itemID = $_POST['itemID'];
    }
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['price']))
      $priceerr = "Must enter price";
    else{
      $price = $_POST['price'];
   }
 }

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['category']))
      $itemIDErr = "ID required";
    else{
      $category = $_POST['category'];
    }
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['status']))
      $itemIDErr = "ID required";
    else{
      $status1 = $_POST['status'];
    }
  }

  echo "
  <div class='table-responsive'>
  <table class='table table-bordered'>
  <tbody>
  <tr><td align='center'>
  <form method='post'>
    ItemID: <input type='text' name='itemID' value=".$itemID.">
    <div style='text-align:center'>  
        <input type='submit' value='Search' />  
    </div>  
  </form>
  </td>

  <td align='center'>
  <form actions='auctions.php' method='post'>
    Price:
    <select name='price'>
    <option></option>
    <option value='currently <= 5.00'>Less than $5.00</option>
    <option value='currently >= 5.01 and currently <= 10.00'>$5.01 to $10.00</option>
    <option value='currently >= 10.01 and currently <= 20.00'>$10.01 to $20.00</option>
    <option value='currently >= 20.01 and currently <= 30.00'>$20.01 to $30.00</option>
    <option value='currently >= 30.01 and currently <= 40.00'>$30.01 to $40.00</option>
    <option value='currently >= 40.01 and currently <= 50.00'>$40.01 to $50.00</option>
    <option value='currently >= 50.01 and currently <= 60.00'>$50.01 to $60.00</option>
    <option value='currently >= 60.01 and currently <= 70.00'>$60.01 to $70.00</option>
    <option value='currently >= 70.01 and currently <= 80.00'>$70.01 to $80.00</option>
    <option value='currently >= 80.01 and currently <= 90.00'>$80.01 to $90.00</option>
    <option value='currently >= 90.01 and currently <= 100.00'>$90.01 to $100.00</option>
    <option value='currently >= 100.01'>Over $100.00</option>
    </select>
    <div style='text-align:center'>  
        <input type='submit' value='Search' />  
    </div>  
  </form>
  </td>

  <td align='center'>
    <form actions='auctions.php' method='post'>
    Status:
    <select name='status'>
    <option selected></option>
    <option value='open'>Open</option>
    <option value='closed'>Closed</option>
    </select>
    <div style='text-align:center'>  
        <input type='submit' value='Search' />  
    </div>  
  </form>
  </td>";

  try{
    $db->beginTransaction();
    $query = "select distinct categories from Category";
    $result = $db->query($query);
    $catrow = $result->fetchall(PDO::FETCH_ASSOC);
    $db->commit();
    //echo "<table>";
    echo "<td align='center'>";
    echo "<form action='auctions.php' method='POST'>";
    echo "Category:";
    echo "<select name='category'>";
    echo "<option selected></option>";
    foreach($catrow as $rows){
      echo "<option value='".$rows['categories']."'>".$rows['categories']."</option>";
    }
    echo "</select>";
    echo "
        <div style='text-align:center'>  
            <input type='submit' value='Search' />  
        </div>  ";
    //echo "<input type='submit' value='Select'>";
    echo "</form>";
    echo "</td></tr>";
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
  }catch(PDOExeption $e){
    $db->rollback();
    echo "SQLite connection failed: " . $e->getMessage();
    exit();
  }


?>

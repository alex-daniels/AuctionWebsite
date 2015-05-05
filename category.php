<?php
include ('./sqlitedb.php');
try{
  	$db->beginTransaction();
  	$query = "select distinct categories from Category";
  	$result = $db->query($query);
  	$catrow = $result->fetchall(PDO::FETCH_ASSOC);
    $db->commit();
  	//echo "<table>";
  	echo "<td>";
    echo "<form action='auctions.php' method='POST'>";
    echo "Category:";
  	echo "<select name='category'>";
    echo "<option selected></option>";
  	foreach($catrow as $rows){
  		echo "<option value='".$rows['categories']."'>".$rows['categories']."</option>";
  	}
  	echo "</select>";
  	echo "<input type='submit' value='Select'>";
    echo "</form>";
  	echo "</td></tr>";
  	echo "</table>";
  }catch(PDOExeption $e){
  	$db->rollback();
  	echo "SQLite connection failed: " . $e->getMessage();
  	exit();
  }

?>

<?php
	include('./navbar.html');
?>
<br>

<table>
<tr>
<td>
<Form action='createuser.php' method ="post">
<label for='username' >Username*:</label>
<input type='text' name='username' id='username'  maxlength="50" />
</td></tr>
<tr><td>
<label for='password' >Password*:</label>
<input type='password' name='password' id='password'  maxlength="50" />
</td></tr>
<tr><td>
<label for='rating' >Rating:   </label>
<input type='text' name='rating' id='rating'  maxlength="50" />
</td></tr>
<tr><td>
<label for='location' >Location*:</label>
<input type='text' name='location' id='location'  maxlength="50" />
</td></tr>
<tr><td>
<label for='country' >Country*: </label>
<input type='text' name='country' id='country'  maxlength="50" />
</td></tr>
<tr><td>
<input type='submit' name='Submit' value='Submit' />
</td></tr>
</table>

<?php
  include('./sqlitedb.php');

  $username = "";
  $password = "";
  $salt = "a13x";
  $rating = "";
  $location = "";
  $country = "";

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rating = $_POST['rating'];
    $location = $_POST['location'];
    $country = $_POST['country'];

    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    $password = md5($salt.$password);
    $rating = intval($rating);

    try{
      $db->beginTransaction();
      $query = "insert into UserLogin values('".$username."', '".$password."')";
      $result = $db->query($query);

      if(!$result)
        echo "Cannot create account";
      else{
        $db->commit();
        $db->beginTransaction();
        $query = "insert into User values('".$username."', ".$rating.", '".$location."', '".$country."')";
        $result = $db->query($query);
        if(!$result)
          echo "Error";
        else{
          $db->commit();
          $db = null;
          header("Location: login.php");
        }
      }
    }catch(PDOException $e){
      $db->rollback();
      echo "SQLite connection failed: " . $e->getMessage();
      exit();
    }
  }
?>
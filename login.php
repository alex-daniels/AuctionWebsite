<?php
  include('./navbar.html');
?>

<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
 
<label for='username' >UserName*:</label>
<input type='text' name='username' id='username'  maxlength="50" />
 
<label for='password' >Password*:</label>
<input type='password' name='password' id='password' maxlength="50" />
 
<input type='submit' name='Submit' value='Submit' />
 
<a href='createuser.php'>New User?</a>

</fieldset>
</form>

<?php
  include('./sqlitedb.php');

  $username = "";
  $password = "";
  $errorMsg = "";
  $num_rows = "";
  $salt = "a13x";

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    $password = md5($salt.$password);

    //make sure the person is on the DB
    try{
      $db->beginTransaction();
      $query = "select count(*) from UserLogin where userID = '".$username."' and password = '".$password."'";
      $result = $db->query($query);
      $row = $result->fetch();
      $num_rows = $row[0];
      $db->commit();
      //echo $num_rows;

      $db->beginTransaction();
      $query = "select rating from User where userID = '".$username."'";
      $result1 = $db->query($query);
      $rating = $result1->fetch();
      $rating = $rating['rating'];
      $db->commit();
      
      if($result){
        if($num_rows == 1){
          $_SESSION['login'] = "1";
          $_SESSION['username'] = $username;
          $_SESSION['rating'] = $rating;
          header("Location: auctions.php");
        }
        else{
          $errorMsg = "Invalid Credentials";
          echo $errorMsg;
          $_SESSION['login'] = null;
        }
      }
      else{
        $errorMsg = "Error logging on";
        echo $errorMsg;
        }
      } 
      catch(PDOException $e){
        $db->rollback();
        echo "SQLite connection failed: " . $e->getMessage();
        exit();
      }
  }
 ?>
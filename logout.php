<?php
  include('./navbar.html');
  session_destroy();
  session_start();
  $_SESSION['login'] = null;
  header("Location: auctioneer.php");
?>
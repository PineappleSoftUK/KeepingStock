<?php
//You can change the code below to point to your own log-in system, or delete it if you already have this taken care of.

//This file will be loaded into all of the KeepingStock system's pages/files.

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  header( "refresh:2;url=pineappleaccesscontrol/login.php" );
  echo 'You must log in to view this page, You will now be redirected automatically, or, click <a href="pineappleaccesscontrol/login.php">here</a>.';
  exit();
} else {
  echo "<a href='pineappleaccesscontrol/logout.php'>Log out</a>";
}
?>
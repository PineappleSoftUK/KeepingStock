<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  echo 'You must log in to view this page';
  exit();
}
?>


<!-- html goes here -->
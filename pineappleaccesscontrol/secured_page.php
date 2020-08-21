<?php
//Include everything between these php tags to secure a page, ensure the page ends in .php and not .html or similar!

session_start();

//Check if logged in...
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  
  //The next line will store the current url, when a user logs in they will be directed back here
  $_SESSION['refer'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  
  //The message on the next line shows if a user is not logged in
  echo 'You must log in to view this page';
  
  //The next line is essential, it ends processing as the user is not logged in.
  exit();
}
?>


<!-- html goes here -->

<p>Example of a secured page, if you can read this you are logged in!</p>
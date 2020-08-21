<?php
/**
* Authenticates a user by checking the provided username and plaintext password and checking against hashed password in database.
* this will be called by login.php
**/

//Security for includes
$includes = TRUE;

//Include file to open database
$includeOpendbOk = include("open_db.php");
if (!$includeOpendbOk) {
  echo "An important file is missing or cannot be accessed: open_db.php";
  exit();
}


session_start();

$refer = "index.php";

//Look for referal page
if (isset($_SESSION['refer'])) {
  $refer = $_SESSION['refer'];
}


//Process form and set variables...
$username = $_POST['usernameField'];
$password = $_POST['passwordField'];


//Fetch the user from the database
$stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
$stmt->bindValue(':username', $username);
$result = $stmt->execute();
$res = $result->fetchArray();

//Check for match
if (password_verify($password, $res['hash'])) {
  $_SESSION['loggedin'] = true;
  $_SESSION['username'] = $res['username'];
  $_SESSION['usertype'] = $res['userType'];
  header("Location: " . $refer);
  echo $refer;
  echo 'Log in successful, You will now be redirected automatically, or, click <a href="index.php">here</a>.';
  exit;
} 
?>

<?php
include("header.php");
?> 

<h1>Log in</h1>

<p style="color: red;">Incorrect credentials, please try again</p>

<form action="authenticate.php" method="post">
  <label for="usernameField">Username</label>
  <input type="text" id="usernameField" name="usernameField" placeholder="Username.." required>

  <label for="passwordField">Password</label>
  <input type="password" id="passwordField" name="passwordField" placeholder="Password.." required>

  <input type="reset" value="Reset">
  <input type="submit" value="Submit">
</form> 

<?php
include("footer.php");
?>
<?php
//Security for includes
$includes = TRUE;

//Include file to open database
$includeOpendbOk = include("open_db.php");
if (!$includeOpendbOk) {
  echo "An important file is missing or cannot be accessed: open_db.php";
  exit();
}

//Include check to ensure user is logged in
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  echo 'You must log in to view this page';
  exit();
}

?>

<?php
include("header.php");
?>
<h2>Change your password</h2>

<?php

//If form is not submitted..

if (!isset($_POST['submit'])) {
  ?>
  <form action="change_password.php" method="post">

    <label for="currentPasswordField">Current Password</label>
    <input type="password" id="currentPasswordField" name="currentPasswordField" placeholder="Password.."><br>

    <label for="newPasswordField">New Password</label>
    <input type="password" id="newPasswordField" name="newPasswordField" placeholder="Password.."><br>

    <label for="repeatPasswordField">Repeat New Password</label>
    <input type="password" id="repeatPasswordField" name="repeatPasswordField" placeholder="Password.."><br>

    <input type="submit" name="submit" value="Continue">
  </form>
  <?php
}

//If form is submitted...

if (isset($_POST['submit'])) {

  $currentPassword = filter_input(INPUT_POST, 'currentPasswordField', FILTER_SANITIZE_SPECIAL_CHARS);
  $newPassword = filter_input(INPUT_POST, 'newPasswordField', FILTER_SANITIZE_SPECIAL_CHARS);
  $repeatPassword = filter_input(INPUT_POST, 'repeatPasswordField', FILTER_SANITIZE_SPECIAL_CHARS);
  $username = $_SESSION['username'];

  //Check new passwords match
  if ($newPassword != $repeatPassword) {
    echo "<p class='red'>Your new passwords do not match, please <a href='change_password.php'>try again</a></p>";
    echo "</body></html>";
    exit();
  }

  //Check current password
  $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->bindValue(':username', $username);
  $result = $stmt->execute();
  $res = $result->fetchArray();

  if (!password_verify($currentPassword, $res['hash'])) {
    echo "<p class='red'>You entered your current password incorrectly, please <a href='change_password.php'>try again</a></p>";
    echo "</body></html>";
    exit();
  }

  //Hash the password src php manual
  $hash = password_hash($newPassword, PASSWORD_DEFAULT);

  $stmt = $db->prepare("UPDATE users SET hash = :hash WHERE username = :username");
  $stmt->bindValue(':hash', $hash);
  $stmt->bindValue(':username', $username);
  $result = $stmt->execute();
  ?>

  <p class="green">Your password has now been updated</p>

  <p><a href="index.php">Return to home</a></p>

  <?php
}

?>
<?php
include("footer.php");
?>
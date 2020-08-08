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

//Check to ensure user is Superuser
if ($_SESSION['usertype'] != "superuser") {
  header( "refresh:3;url=index.php" );
  echo 'You must be a superuser to view this page, You will now be redirected automatically, or, click <a href="index.php">here</a>.';
  exit();
}
?>

<?php
include("header.php");
?>
<h2>User management</h2>

<?php

//If form is not submitted..

if (!isset($_POST['submit'])) {
  //The following will clean up the 'id' variable passed un the URI...
  $userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_SPECIAL_CHARS);
  $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

  if (!isset($userId)) {
    echo "Invalid user ID, the user ID passed in the URI does not exist in the database";
    exit();
  }

  ?>
  <p class="red">Are you sure you wish to continue and delete the user: <?php echo $username;?></p>
  <form action="delete_user.php" method="post">

    <input type="hidden" id="userId" name="userId" value="<?php echo $userId;?>">

    <input type="submit" name="submit" value="Continue">
  </form>
  <?php
}

//If form is submitted...

if (isset($_POST['submit'])) {

  $userId = $_POST['userId'];

  $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
  $stmt->bindValue(':id', $userId);
  $result = $stmt->execute();
  ?>

  <p>User ID '<?php echo $userId;?>' was successfully deleted</p>

  <p><a href="index.php">Return to home</a></p>

  <?php
}

?>
<?php
include("footer.php");
?>
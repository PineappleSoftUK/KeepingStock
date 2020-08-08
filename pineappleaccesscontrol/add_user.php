<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
* A page for superusers to add new users
*/

//Security for includes
$includes = TRUE;

//Include file to open database
$includeOpendbOk = include("open_db.php");
if (!$includeOpendbOk) {
  echo "An important file is missing or cannot be accessed: open_db.php";
  exit();
}

//Check if user is logged in...
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
    

<h1>Add a user</h1>
<?php
//If form is not submitted then display the empty form..

if (!isset($_POST['submit'])) {
?>

  <form action="add_user.php" method="post">
    <label for="usernameField">Username (required)</label>
    <input type="text" id="usernameField" name="usernameField" placeholder="Username.." required>

    <label for="passwordField">Password (required)</label>
    <input type="password" id="passwordField" name="passwordField" placeholder="Password.." required>

    <fieldset id="faults">
      <legend>User Type (please select):</legend>
      <div>
        <input type="radio" id="normalUserRadio" name="userTypeRadio" value="normal" checked>
        <label for="normalUserRadio">Normal User</label>
        <br>
        <input type="radio" id="superuserRadio" name="userTypeRadio" value="superuser">
        <label for="superuserRadio">Superuser</label>
      </div>
    </fieldset>

    <input type="reset" value="Reset">
    <input type="submit" name="submit" value="Submit">
  </form>
    
<?php
}
 //If form is submitted then process its contents...

if (isset($_POST['submit'])) {

  //Process form and set variables...
  $username = $_POST['usernameField'];
  $password = $_POST['passwordField'];
  $userType = $_POST['userTypeRadio'];

  //Check for duplicates
  $stmt = $db->prepare('SELECT count(*) FROM users WHERE username = :username');
  $stmt->bindValue(':username', $username);
  $result = $stmt->execute();
  $res = $result->fetchArray(SQLITE3_ASSOC);
  $numRows = $res['count(*)'];

  if ($numRows >= 1) {
    echo "The chosen username: '" . $username . "' already exists, please <a href='add_user.php'>choose another</a>";
  } else {

  //Hash the password src php manual
  $hash = password_hash($password, PASSWORD_DEFAULT);

  //Insert variables into database
  $stmt = $db->prepare('INSERT INTO users (username, hash, userType) VALUES (:username, :hash, :userType)');
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':hash', $hash);
  $stmt->bindValue(':userType', $userType);
  $result = $stmt->execute();
  ?>

  <p>The user '<?php echo $username;?>' has been added successfully.</p>

  <p><a href="index.php">Return to home</a></p>

  <?php
  } 
}
?>

<?php
include("footer.php");
?>
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

<h1>User management</h1>

<div id="tableContainer">    
  <table id="faultTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>User Type</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>     
      <?php
      $res = $db->query('SELECT * FROM users');

      while ($row = $res->fetchArray()) {

        //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW

      ?>

        <tr>
          <td><?php echo $row['id'];?></td>
          <td><?php echo $row['username'];?></td>
          <td><?php echo $row['userType'];?></td>
          <td><a href="delete_user.php?userId=<?php echo $row['id'];?>&username=<?php echo $row['username'];?>">Delete User</a></td>
        </tr>

      <?php
      } //End of query!  
      ?>
  </tbody>
  </table>
</div>

<?php
include("footer.php");
?>
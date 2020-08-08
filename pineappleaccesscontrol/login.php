<?php
include("header.php");
?>
    
<h1>Log in</h1>

<form action="authenticate.php" method="post">
  <label for="usernameField">Username</label>
  <input type="text" id="usernameField" name="usernameField" placeholder="Username..">

  <label for="passwordField">Password</label>
  <input type="password" id="passwordField" name="passwordField" placeholder="Password..">

  <input type="reset" value="Reset">
  <input type="submit" value="Submit">
</form>  

<?php
include("footer.php");
?>
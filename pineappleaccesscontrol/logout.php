<?php
/*
* Script to log out user, destroying all session data
*/

session_start();
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header( "refresh:3;url=login.php" );
echo 'Logged out successful, You will now be redirected automatically, or, click <a href="login.php">here</a>.';
exit;
?>
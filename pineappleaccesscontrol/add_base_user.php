<?php
/*
* A script that adds a generic superuser account during database creation, 
* this will be called by open_db.php during the initial creation of tables.
*/

//Check if this is being called by the software
if(!$includes) {
  echo "You cannot open this file, it is part of the system and must be called by another file";
  exit();
}

$username = "admin";
$password = "basicpassword";
$userType = "superuser";

//Hash the password src php manual
$hash = password_hash($password, PASSWORD_DEFAULT);

//Insert variables into database
$stmt = $db->prepare('INSERT INTO users (username, hash, userType) VALUES (:username, :hash, :userType)');
$stmt->bindValue(':username', $username);
$stmt->bindValue(':hash', $hash);
$stmt->bindValue(':userType', $userType);
$result = $stmt->execute();
?>
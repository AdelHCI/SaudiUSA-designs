<?php
include 'timeout.php';
try {
  $db = new PDO("sqlite:sqlite.db");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

if (preg_match("/[a-zA-Z]+.*[a-zA-Z]*@saudiusa.com/", $_POST["username"]))
{
$findUser = $db->prepare("SELECT COUNT(*) FROM USERS WHERE USERNAME=:username");
    $findUser->bindParam(':username', $_POST["username"], SQLITE3_TEXT);
$findUser->execute();
    if((int)$findUser->fetchColumn()){
	echo -1;
exit();
	}
    else {
    $insert = $db->prepare("INSERT INTO USERS (USERNAME,PASSWORD,VERIFIED) VALUES (:username,:pwd,0)");
    $insert->bindParam(':username', $_POST["username"], SQLITE3_TEXT);
    $insert->bindParam(':pwd', sha1($_POST["pwd"]), SQLITE3_TEXT);
    $ret = $insert->execute();
    if (!$ret) echo $db->lastErrorMsg();
    else echo 1;
include "sendverify.php";
}
}
else echo 0;
$db = null;
?>
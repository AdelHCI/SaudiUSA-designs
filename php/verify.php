<?php
include 'timeout.php';
try {
  $db = new PDO("sqlite:sqlite.db");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

if (isset($_SESSION["hash"]))
{
    if ($_SESSION['hash'] == $_POST["code"]){
    $verifyUser = $db->prepare("UPDATE USERS SET VERIFIED = 1 WHERE USERNAME=:username");
    $verifyUser->bindParam(':username', $_POST["username"], SQLITE3_TEXT);
    $verifyUser->execute();
        echo 1;
    }
    else echo -1;
}
else echo 0;
$db = null;
?>

<?php
include 'timeout.php';
try {
    $db = new PDO("sqlite:sqlite.db");
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (preg_match("/[a-zA-Z]+.*[a-zA-Z]+@saudiusa.com/", $_POST["username"])) {
    if (!isset($_POST["pwd"])) {
        include "sendverify.php";
        echo 2;
    } else if ($_SESSION["hash"] == $_POST["code"]) {
        $update = $db->prepare("UPDATE USERS SET PASSWORD = :pwd WHERE USERNAME=:username");
        $update->bindParam(':username', $_POST["username"], SQLITE3_TEXT);
        $update->bindParam(':pwd', sha1($_POST["pwd"]), SQLITE3_TEXT);
        $ret = $update->execute();
        if (!$ret) echo $db->lastErrorMsg();
        else echo 1;
    } else echo -1;
} else echo 0;
$db = null;

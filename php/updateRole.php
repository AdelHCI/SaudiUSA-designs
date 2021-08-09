<?php
require 'timeout.php';
try {
    $db = new PDO("sqlite:sqlite.db");
} catch (PDOException $e) {
    echo $e->getMessage();
}
if ($_SESSION["user"][0] == "مشرف") {
    $changeRole = $db->prepare("UPDATE USERS SET USER_ROLE=:role WHERE USERNAME=:name");
    $changeRole->bindParam(':name', $_POST["name"], SQLITE3_TEXT);
    $changeRole->bindParam(':role', $_POST["role"], SQLITE3_TEXT);
    $changeRole->execute();
    echo 1;
} else echo -1;
$db = null;

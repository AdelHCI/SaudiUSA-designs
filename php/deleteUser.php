<?php
require 'timeout.php';
try {
    $db = new PDO("sqlite:sqlite.db");
} catch (PDOException $e) {
    echo $e->getMessage();
}
if ($_SESSION["user"][0] == "مشرف") {
    $changeRole = $db->prepare("DELETE FROM USERS WHERE USERNAME=:name");
    $changeRole->bindParam(':name', $_POST["name"], SQLITE3_TEXT);
    $changeRole->execute();
    echo 1;
} else echo json_encode($_SESSION["user"]);
$db = null;

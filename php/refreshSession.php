<?php
require 'timeout.php';
try {
    $db = new PDO("sqlite:sqlite.db");
} catch (PDOException $e) {
    echo $e->getMessage();
}
$findUserRole = $db->prepare("SELECT USER_ROLE FROM USERS WHERE USERNAME=:username");
$findUserRole->bindParam(':username', $_SESSION["user"][1], SQLITE3_TEXT);
$ret = $findUserRole->execute();
$row = $findUserRole->fetch(PDO::FETCH_ASSOC);
$_SESSION["user"] = [$row["USER_ROLE"], $_SESSION["user"][1]];

$db = null;

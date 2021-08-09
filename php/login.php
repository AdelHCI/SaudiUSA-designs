<?php
include 'timeout.php';
try {
    $db = new PDO("sqlite:sqlite.db");
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (preg_match("/[a-zA-Z]+.*[a-zA-Z]+@saudiusa.com/", $_POST["username"])) {
    $findUser = $db->prepare("SELECT * FROM USERS WHERE USERNAME=:username");
    $findUser->bindParam(':username', $_POST["username"], SQLITE3_TEXT);
    $ret = $findUser->execute();
    if (!$ret) echo $db->lastErrorMsg();
    $row = $findUser->fetch(PDO::FETCH_ASSOC);
    if ((int)$row['VERIFIED'] == 0) {
        include 'sendverify.php';
        echo 2;
        exit();
    } else if ($row['PASSWORD'] == sha1($_POST["pwd"])) {
        $_SESSION["user"] = [$row["USER_ROLE"], $row["USERNAME"]];
        echo json_encode($_SESSION["user"]);
    } else echo -1;
} else echo 0;

$db = null;

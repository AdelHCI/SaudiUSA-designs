<?php
// class MyDB extends SQLite3
// {
//     function __construct()
//     {
//         $this->open('sqlite.db');
//     }
// }



// $db = new MyDB();
// $db->exec('DROP TABLE USERS');
// $db->exec('CREATE TABLE USERS (USERNAME STRING, PASSWORD STRING, VERIFIED BOOLEAN CHECK (VERIFIED IN (0,1)))');
//$user_name = "Adelr989";
//$pwd = sha1("Pass1234");
//$ret = $db->exec("INSERT INTO users (USERNAME, PASSWORD,VERIFIED ) VALUES ('$user_name' , '$pwd' , '1')");
//if (!$ret) echo $db->lastErrorMsg();
//$db->exec("DELETE FROM users");
// $db->exec("UPDATE users SET USER_ROLE = 'مشرف'");

// $result = $db->query('SELECT * FROM users');
// while ($r = $result->fetchArray(SQLITE3_ASSOC)) {
//     echo nl2br($r["USERNAME"] . "\n" . $r["PASSWORD"] . "\n" . $r["VERIFIED"] . "\n" . $r["USER_ROLE"] . "\n");
// }

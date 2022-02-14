<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('sqlite.db');
    }
}

$db = new MyDB();
// $db->query("ALTER TABLE users_temp RENAME TO users;");
// $db->query("CREATE TABLE users_temp (USERNAME STRING, PASSWORD STRING, VERIFIED BOOLEAN CHECK (VERIFIED IN (0,1)), USER_ROLE DEFAULT محرر)");
// $db->query('INSERT INTO users_temp SELECT username,password, verified, user_role FROM users_old');
$result = $db->query('SELECT username,user_role FROM users');
// $result = $db->query('PRAGMA table_info(users)');

$array = array();
if ($result) {
    while ($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $array[] = $r;
    }
}
$db = null;
echo json_encode($array);

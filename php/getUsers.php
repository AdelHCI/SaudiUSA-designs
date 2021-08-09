<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('sqlite.db');
    }
}

$db = new MyDB();
$result = $db->query('SELECT USERNAME, USER_ROLE FROM users');
$array = array();
if ($result) {
    while ($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $array[] = $r;
    }
}
$db = null;
echo json_encode($array);

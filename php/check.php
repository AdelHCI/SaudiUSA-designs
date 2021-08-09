<?php
require 'timeout.php';
if (isset($_SESSION["user"])) {
    include 'refreshSession.php';
    echo json_encode($_SESSION["user"]);
} else echo -1;

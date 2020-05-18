<?php
require 'timeout.php';
if (!isset($_SESSION["user"])) echo 0;
    else echo 1;
?>
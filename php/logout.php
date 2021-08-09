<?php
session_start();
session_destroy();
session_start();
echo json_encode($_SESSION);

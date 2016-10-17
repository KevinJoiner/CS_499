<?php
DEFINE ('DB_USER','krjo232');
DEFINE ('DB_PASSWORD', 'WKDqgZsASjU=');
DEFINE ('DB_HOST','sweb.uky.edu');
DEFINE ('DB_NAME', 'krjo232');

$dbc = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
OR die('Could not connect to MYSQL'.mysqli_connect_error());

?>

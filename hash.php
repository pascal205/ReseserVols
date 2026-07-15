<?php
$password = "password20";

$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
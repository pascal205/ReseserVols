<?php
$password = "beans20";

$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
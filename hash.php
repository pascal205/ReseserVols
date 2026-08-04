<?php
$password = "azertyytreza";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
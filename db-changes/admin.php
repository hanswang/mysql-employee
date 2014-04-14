<?php

$pw = 'Admin1';

//$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
$salt = 'd03a16f12c669f9446a82a5255c162701aef6a0e291bf2109dc19420bae9e97e';

$saltpw = $pw . $salt;

$hashpw = hash('sha256', $saltpw);

echo 'password orgin: ' . $pw . "\n";
echo 'salt orgin: ' . $salt. "\n";
echo 'password final: ' . $hashpw. "\n";

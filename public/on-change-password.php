<?php

require "base.php";
global $BASE, $_POST;
require_once "$BASE/lib/functions.php";

$formToken = get_hash_from_ip_info($_SERVER);

$formValid = get_sanitized_form_input($_POST, 'fmid');

if (strcmp($formToken, $formValid) !== 0) {
    var_dump('Invalid login Form!');
    exit();
}

require_once "$BASE/classes/AuthHandler.php";

$passwd = get_sanitized_form_input($_POST, 'pnew1');
$confirm = get_sanitized_form_input($_POST, 'pnew2');

if (strcmp($passwd, $confirm) !== 0) {
    $errors[] = 'Typed 2 passworks don\'t match';
    header("Location: ./change-password.php?errors=" . urlencode(serialize($errors)));
    exit();
}

if (AuthHandler::auth()) {
    AuthHandler::updatePassword($passwd);
    header("Location: ./index.php");
    exit();
} else {
    $errors[] = 'Need login first before changing password.';
    header("Location: ./login.php?errors=" . urlencode(serialize($errors)));
    exit();
}


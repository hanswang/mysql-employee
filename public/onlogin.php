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

$name = get_sanitized_form_input($_POST, 'u');
$passwd = get_sanitized_form_input($_POST, 'p');

$errors = AuthHandler::login($name, $passwd);

if (is_array($errors) && count($errors) > 0) {
    header("Location: ./login.php?errors=" . urlencode(serialize($errors)));
    exit();
} else {
    header("Location: ./index.php");
    exit();
}


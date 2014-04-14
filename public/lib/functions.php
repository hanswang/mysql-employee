<?php
require "base.php";
global $BASE;
require_once "$BASE/conf/request.php";

function get_hash_from_ip_info($server) {
    if (!empty($server['HTTP_X_FORWARDED_FOR'])) {
        $ip = $server['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $server['REMOTE_ADDR'];
    }

    return hash('md5', $ip . formToken::VALID);
}

function get_sanitized_form_input($input, $name, $type = 'string') {
    if ($name == '' || !isset($input[$name])) {
        return false;
    }

    $value = mysql_real_escape_string($input[$name]);

    if ($type == 'numeric') {
        return is_numeric($value) ? $value : 0;
    } else {
        return strip_tags($value);
    }
}

function get_salt_hashed_passed($passwd, $salt) {
    $saltedPw = $passwd . $salt;
    return hash('sha256', $saltedPw);
}

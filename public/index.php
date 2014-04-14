<?php
require "base.php";
global $BASE;
require_once "$BASE/classes/AuthHandler.php";
require_once "$BASE/classes/User.php";

if (AuthHandler::auth()) {
    if ($_SESSION['pw_changed'] == 'N') {
        header("Location: ./change-password.php");
        exit();
    }
    if ($_SESSION['role'] == User::ADMIN_ACCESS) {
        header("Location: ./view-all.php");
        exit();
    } else if ($_SESSION['role'] == User::MANAGER_ACCESS) {
        header("Location: ./view-department.php");
        exit();
    } else {
        header("Location: ./view-user.php");
        exit();
    }
} else {
    header("Location: ./login.php");
    exit();
}

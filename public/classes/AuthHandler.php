<?php

require "base.php";
global $BASE;

require_once "$BASE/lib/functions.php";
require_once "$BASE/lib/common.php";
global $dbh;

class AuthHandler {

    public function __construct () {
    }

    static public function login ($name, $passwd) {
        global $dbh;
        session_start();

        $sth = $dbh->prepare('SELECT * FROM login WHERE username = :name');
        $sth->execute(array(':name' => $name));
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        if (is_array($row) && count($row) > 0) {

            $salt = $row['salt'];
            $hashedPw = get_salt_hashed_passed($passwd, $salt);

            if (strcmp($row['passwd'], $hashedPw) === 0) {
                $loginId = $row['id'];
                session_regenerate_id(true);
                $_SESSION['login_id'] = $loginId;
                $_SESSION['role'] = $row['role_id'];
                $_SESSION['pw_changed'] = $row['first_changed'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['lastactive'] = time();
                if ($row['emp_no'] != null) {
                    $_SESSION['emp_no'] = $row['emp_no'];
                }
                return true;
            } else {
                $errors[] = htmlspecialchars("Password is not correct.");
                return $errors;
            }

        } else {

            $names = explode('.', $name);
            $firstname = $names[0];
            $lastname = $names[1];

            $sth = $dbh->prepare('SELECT e.*, d.dept_no FROM employees e
                                    LEFT JOIN dept_manager d ON e.emp_no = d.emp_no
                                    WHERE e.first_name = :fname AND e.last_name = :lname');
            $sth->execute(array(':fname' => $firstname, ':lname' => $lastname));
            $row = $sth->fetch(PDO::FETCH_ASSOC);

            if (is_array($row) && count($row) > 0) {
                $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
                $saltedPw = get_salt_hashed_passed($row['emp_no'], $salt);

                $sth = $dbh->prepare('INSERT INTO login (username, emp_no, passwd, salt, first_changed, role_id)
                    VALUES (:username, :emp_no, :passwd, :salt, :first_changed, :role_id)');
                $data = array(':username' => $name,
                    ':emp_no' => $row['emp_no'],
                    ':passwd' => $saltedPw,
                    ':salt' => $salt,
                    ':first_changed' => 'N',
                    ':role_id' => is_null($row['dept_no']) ? '1' : '2');
                $sth->execute($data);

                if (strcmp($passwd, $row['emp_no']) === 0) {
                    $loginId = $dbh->lastInsertId();
                    session_regenerate_id(true);
                    $_SESSION['login_id'] = $loginId;
                    $_SESSION['emp_no'] = $row['emp_no'];
                    $_SESSION['role'] = is_null($row['dept_no']) ? '1' : '2';
                    $_SESSION['pw_changed'] = 'N';
                    $_SESSION['username'] = $name;
                    $_SESSION['lastactive'] = time();
                    return true;
                } else {
                    $errors[] = htmlspecialchars("Password is not correct.");
                    return $errors;
                }
            } else {
                $errors[] = htmlspecialchars("Username <i>$name</i> not exists");
                return $errors;
            }
        }
    }

    static public function updatePassword($passwd) {
        global $dbh;

        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $saltedPw = get_salt_hashed_passed($passwd, $salt);

        $sth = $dbh->prepare('UPDATE login SET passwd = :passwd, salt = :salt, first_changed = :first_changed WHERE emp_no = :id');
        $data = array(':passwd' => $saltedPw,
            ':first_changed' => 'Y',
            ':salt' => $salt,
            ':id' => $_SESSION['emp_no']);
        $sth->execute($data);
        unset($_SESSION['pw_changed']);
        $_SESSION['pw_changed'] = 'Y';
    }

    static public function auth() {
        session_start();
        if (isset($_SESSION['login_id'])) {
            self::keepalive();
            return true;
        } else {
            return false;
        }
    }

    static public function logout() {
        unset($_SESSION['login_id']);
        unset($_SESSION['role']);
        unset($_SESSION['pw_changed']);
        unset($_SESSION['username']);;
        unset($_SESSION['lastactive']);
        unset($_SESSION['emp_no']);
        session_unset();
        session_destroy(); 
        return true;
    }

    static public function keepalive() {
        $oldtime = $_SESSION['lastactive'];
        if (!empty($oldtime)) {
            $currenttime = time();
            // extend another 30 minutes
            $timeoutlength = 30 * 60;
            if ($oldtime + $timeoutlength >= $currenttime) {
                $_SESSION['lastactive'] = $currenttime;
            } else {
                self::logout();
            }
        }
    }
    // end of keepalive
}

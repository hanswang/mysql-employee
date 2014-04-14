<?php
require "base.php";
global $BASE;
require_once "$BASE/classes/AuthHandler.php";
require_once "$BASE/classes/User.php";

if (AuthHandler::auth()) {
    if ($_SESSION['role'] == User::ADMIN_ACCESS) {
        $title = 'You have to change your password!';
        $escapse = false;
    } else {
        $title = 'You are suggested to change your password!';
        $escapse = true;
    }
    $formToken = get_hash_from_ip_info($_SERVER);
    $errors = unserialize($_GET['errors']);
} else {
    header("Location: ./login.php");
    exit();
}

?>
<html>
    <body bgcolor=#ffffff alink=#0000be>
        <div style=" color: #D8000C;background-color: #FFBABA;">
<?php
    foreach($errors as $error) {
        echo html_entity_decode($error) . "<br>";
    }
?>
        </div>
        <b><?php echo($title); ?> </b>
        <br><br>
        <form method=post action="./on-change-password.php">
        <input type=hidden name="fmid" value="<?php echo($formToken); ?>">
            <table border=0>
                <tr>
                    <td>new password:</td>
                    <td><input type=password name="pnew1" size=20></td>
                </tr>
                <tr>
                    <td>confirm:</td>
                    <td><input type=password name="pnew2" size=20></td>
                </tr>
            </table>
            <br>
            <input type=submit value="submit change">
        </form>
        <br><br>
<?php
if ($escapse) {
    echo "<a href='./view-user.php'>Skip Change, and view profile</a>";
}
flush();
?>
    </body>
</html>

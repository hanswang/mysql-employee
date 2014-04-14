<?php
require "base.php";
global $BASE, $_GET;
require_once "$BASE/classes/AuthHandler.php";
if (AuthHandler::auth()) {
    header("Location: ./index.php");
    exit();
}

$formToken = get_hash_from_ip_info($_SERVER);
$errors = unserialize($_GET['errors']);

?>
<html>
    <body bgcolor=#ffffff alink=#0000be>
        <div style=" color: #D8000C;background-color: #FFBABA;">
<?php
    if (is_array($errors) && count($errors) > 0) {
        foreach($errors as $error) {
            echo html_entity_decode($error) . "<br>";
        }
    }
?>
        </div>
        <b>Login</b>
        <br><br>
        <form method=post action="./onlogin.php">
        <input type=hidden name="fmid" value="<?php echo($formToken); ?>">
            <table border=0>
                <tr>
                    <td>username:</td>
                    <td><input type=text name="u" size=20 autocorrect="off" autocapitalize="off"></td>
                </tr>
                <tr>
                    <td>password:</td>
                    <td><input type=password name="p" size=20></td>
                </tr>
            </table>
            <br>
            <input type=submit value="login">
        </form>
        <br><br>
    </body>
</html>

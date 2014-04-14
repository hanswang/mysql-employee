<?php

require_once "../public/classes/AuthHandler.php";

class AuthHandlerTest extends PHPUnit_Framework_TestCase {

    /**
     * test for login user with dummy password
     */
    public function testAuthHandlerLogin() {
        $name = 'Georgi.Facello';
        $passwd = 'updatetest';

        $wrong_passwd = 'testest1';

        $authed = AuthHandler::login($name, $passwd, false);

        $this->assertEquals($authed, true);

        $authed_again = AuthHandler::login($name, $wrong_passwd, false);

        $this->assertEquals($authed_again, array('Password is not correct.'));
    }

    /**
     * test for update user with dummy password
     */
    public function testAuthHandlerUpdatePasswd() {
        $name = 'Georgi.Facello';
        $passwd = 'updatetest';
        $employee_id = '10001';

        $new_passwd = 'testtest';

        AuthHandler::updatePassword($new_passwd, false, $employee_id);

        $authed = AuthHandler::login($name, $passwd, false);
        $this->assertEquals($authed, array('Password is not correct.'));

        $authed_again = AuthHandler::login($name, $new_passwd, false);
        $this->assertEquals($authed_again, true);

        AuthHandler::updatePassword($passwd, false, $employee_id);

        $authed = AuthHandler::login($name, $passwd, false);
        $this->assertEquals($authed, true);

        $authed_again = AuthHandler::login($name, $new_passwd, false);
        $this->assertEquals($authed_again, array('Password is not correct.'));
    }

}

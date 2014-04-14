<?php

require_once "../public/classes/User.php";
require_once "../public/classes/UserDAO.php";

class UserTest extends PHPUnit_Framework_TestCase {
    /**
     * test loads data
     */
    public function testLoadUser() {

        $user = new User(10001);
        if ($user->load()) {
            $this->assertEquals($user->first_name, 'Georgi');
            $this->assertEquals($user->last_name, 'Facello');
            $this->assertEquals($user->gender, 'M');
            $this->assertEquals($user->department, 'Development');
            $this->assertEquals($user->job_title, 'Senior Engineer');
            $this->assertEquals($user->hire_date, '1986-06-26');
            $this->assertEquals($user->years_serve, 27);
            $this->assertEquals($user->birth_date, '1953-09-02');
            $this->assertEquals($user->age, 60);
            $this->assertEquals($user->salary, '88958');
            $this->assertEquals($user->manager_id, '110567');
        }
    }
}

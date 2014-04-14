<?php

require "base.php";
global $BASE;

require_once "$BASE/classes/UserDAO.php";

class User {
    const ADMIN_ACCESS = 3;
    const MANAGER_ACCESS = 2;
    const EMPLOYEE_ACCESS = 1;

    public $employee_id;
    public $first_name;
    public $last_name;
    public $gender;
    public $department;
    public $job_title;
    public $hire_date;
    public $years_serve;
    public $birth_date;
    public $age;
    public $salary;

    public $manager_id;

    public function __construct($id = false) {
        if ($id) {
            $this->employee_id = $id;
        }
    }

    public function setEmployeeId($id) {
        $this->employee_id = $id;
    }

    public function load() {
        if (!isset($this->employee_id)) {
            trigger_error("Unable to load user without employee id");
            return false;
        }

        $raw = UserDAO::loadUserByEmployeeId($this->employee_id);
        if (is_array($raw) && count($raw) > 0) {
            self::setFromArray($raw);
            return true;
        }
        return false;
    }

    public function setFromArray($raw) {
        $this->first_name = $raw['first_name'];
        $this->last_name = $raw['last_name'];
        $this->gender = $raw['gender'];
        $this->department = $raw['dept_name'];
        $this->job_title = $raw['title'];
        $this->hire_date = $raw['hire_date'];
        $this->years_serve = $raw['serve_years'];
        $this->birth_date = $raw['birth_date'];
        $this->age = $raw['age'];
        $this->salary = $raw['salary'];
        $this->manager_id = $raw['manager_id'];
    }

}

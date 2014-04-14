<?php

require "base.php";
global $BASE;
require_once "$BASE/lib/functions.php";
require_once "$BASE/lib/common.php";
global $dbh;

class UserDAO {
    static public function loadUserByEmployeeId ($id) {
        global $dbh;
        $sth = $dbh->prepare('
            SELECT e.emp_no, e.birth_date, e.first_name, e.last_name, e.gender, e.hire_date,
            YEAR(NOW()) - YEAR(e.birth_date) - (DATE_FORMAT(NOW(), "%m%d") < DATE_FORMAT(e.birth_date, "%m%d")) as age,
            YEAR(NOW()) - YEAR(e.hire_date) - (DATE_FORMAT(NOW(), "%m%d") < DATE_FORMAT(e.hire_date, "%m%d")) as serve_years,
            d.dept_name, t.title, s.salary, dm.emp_no as manager_id
            FROM employees e
                JOIN dept_emp de ON de.emp_no = e.emp_no
                JOIN departments d ON de.dept_no = d.dept_no
                JOIN dept_manager dm on dm.dept_no = d.dept_no
                JOIN titles t ON t.emp_no = e.emp_no
                JOIN salaries s ON s.emp_no = e.emp_no
            WHERE e.emp_no = :id
            ORDER BY de.to_date DESC, t.to_date DESC, s.to_date DESC
            LIMIT 1
            ');
        $sth->execute(array(':id' => $id));
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
}

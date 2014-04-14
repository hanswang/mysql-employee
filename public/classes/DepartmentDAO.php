<?php

$BASE = dirname(__FILE__) . '/..';
require_once "$BASE/lib/functions.php";
require_once "$BASE/lib/common.php";

class DepartmentDAO {

    static public function loadDepartmentByManagerId ($id) {
        $dbh = SafePDO::connect();
        $sth = $dbh->prepare('
            SELECT d.dept_no, d.dept_name, dm.emp_no, dm.from_date, dm.to_date,
                COUNT(DISTINCT de.emp_no) AS employee_count
            FROM departments d
                JOIN dept_manager dm ON d.dept_no = dm.dept_no
                JOIN dept_emp de ON de.dept_no = d.dept_no
            WHERE dm.emp_no = :id
            ');
        $sth->execute(array(':id' => $id));
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    static public function loadDepartmentUsersByDeptNo ($dept_no, $offset = 0, $limit = 100) {
        $dbh = SafePDO::connect();
        $sth = $dbh->prepare('
            SELECT e.emp_no, e.first_name, e.last_name, e.gender, e.hire_date, t.title
            FROM employees e
                JOIN dept_emp de ON de.emp_no = e.emp_no
                JOIN titles t ON t.emp_no = e.emp_no
                LEFT JOIN titles t2 ON t.emp_no = t2.emp_no AND t2.to_date > t.to_date
            WHERE de.dept_no = :dept_no
                AND t2.title IS NULL
            ORDER BY e.emp_no DESC
            LIMIT :offset, :limit
            ');
        $sth->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sth->bindValue(':dept_no', $dept_no);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    static public function getAllCount () {
        $dbh = SafePDO::connect();
        $sth = $dbh->prepare('
            SELECT COUNT(DISTINCT(emp_no)) AS all_count FROM employees
            ');
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row['all_count'];
    }

    static public function loadAllUsers ($offset = 0, $limit = 100) {
        $dbh = SafePDO::connect();
        $sth = $dbh->prepare('
            SELECT e.emp_no, e.first_name, e.last_name, e.gender, e.hire_date, t.title
            FROM employees e
                JOIN titles t ON t.emp_no = e.emp_no
                LEFT JOIN titles t2 ON t.emp_no = t2.emp_no AND t2.to_date > t.to_date
            WHERE t2.title IS NULL
            ORDER BY e.emp_no DESC
            LIMIT :offset, :limit
            ');
        $sth->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    static public function loadCountByFilteredQuery ($query, $binds) {
        $dbh = SafePDO::connect();
        $combined_query = '
            SELECT COUNT(DISTINCT(e.emp_no)) AS count FROM employees e' . $query;
        $sth = $dbh->prepare($combined_query);
        $sth->execute($binds);
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row['count'];
    }

    static public function loadEmployeesByFilteredQuery ($query, $binds, $offset = 0, $limit = 100) {

        $dbh = SafePDO::connect();
        $combined_query = '
            SELECT e.emp_no, e.first_name, e.last_name, e.gender, e.hire_date, t.title
            FROM employees e' . $query .
            'ORDER BY e.emp_no DESC LIMIT :offset, :limit
            ';
        $sth = $dbh->prepare($combined_query);
        $sth->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
        foreach($binds as $name => $value) {
            $sth->bindValue($name, $value);
        }
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

}


<?php

$BASE = dirname(__FILE__) . '/..';
require_once "$BASE/classes/DepartmentDAO.php";

class Department {

    public $dept_id;
    public $dept_name;
    public $manager_id;
    public $manager_startdate;
    public $manager_enddate;
    public $employee_count;

    public function __construct($id = false) {
        if ($id) {
            $this->manager_id = $id;
        }
    }

    public function setManagerId($id) {
        $this->manager_id = $id;
    }

    public function load() {
        if (!isset($this->manager_id)) {
            trigger_error("Unable to load user without manager id");
            return false;
        }

        $raw = DepartmentDAO::loadDepartmentByManagerId($this->manager_id);
        if (is_array($raw) && count($raw) > 0) {
            self::setFromArray($raw);
            return true;
        }
        return false;
    }

    public function setFromArray($raw) {
        $this->dept_id = $raw['dept_no'];
        $this->dept_name = $raw['dept_name'];
        $this->manager_id = $raw['emp_no'];
        $this->manager_startdate = $raw['from_date'];
        $this->manager_enddate = $raw['to_date'];
        $this->employee_count = $raw['employee_count'];
    }

    public function getDepartmentNo() {
        return $this->dept_id;
    }

    public function getEmployeeCount() {
        return $this->employee_count;
    }
}


class AllEmployeeView {

    const LIMIT = 100;

    protected $offset;
    protected $page;

    public $pagecount;

    public function __construct() {
        $this->offset = 0;
        $this->page = 0;
    }

    public function loadPageCount() {
        $this->pagecount = ceil(DepartmentDAO::getAllCount() / self::LIMIT);
    }

    public function setPage($page) {
        if ($page > $this->pagecount - 1) {
            $this->page = $this->pagecount - 1;
        } else {
            $this->page = $page;
        }

        $this->offset = $page * self::LIMIT;

        return $this->page;
    }

    public function getPageCount() {
        return $this->pagecount;
    }

    public function getEmployeeList() {
        $users = DepartmentDAO::loadAllUsers($this->offset);
        return $users;
    }
}


class DepartmentView extends AllEmployeeView {

    protected $department;

    public function __construct($department) {
        parent::__construct();
        $this->department = $department;
    }

    public function loadPageCount() {
        $this->pagecount = ceil($this->department->getEmployeeCount() / self::LIMIT);
    }

    public function getEmployeeList() {
        $users = DepartmentDAO::loadDepartmentUsersByDeptNo($this->department->getDepartmentNo(), $this->offset);
        return $users;
    }
}


class SearchFilterView extends DepartmentView {
    
    private $by_firstname;
    private $by_lastname;
    private $by_gender;
    private $by_title;
    private $by_hiredate;

    public function __construct($department = false) {
        parent::__construct($department);

        $this->by_firstname = false;
        $this->by_lastname = false;
        $this->by_gender = false;
        $this->by_title = false;
        $this->by_hiredate = false;
    }

    public function setFilter($field, $value) {
        if ($field == 'firstname') {
            $this->by_firstname = $value;
        } else if ($field == 'lastname') {
            $this->by_lastname = $value;
        } else if ($field == 'gender') {
            $this->by_gender = $value;
        } else if ($field == 'title') {
            $this->by_title = $value;
        } else if ($field == 'hiredate') {
            $this->by_hiredate = $value;
        }
    }

    public function buildQuery(&$binds) {
        $sql = ' JOIN titles t ON t.emp_no = e.emp_no '
            .(($this->department != false) ? ' JOIN dept_emp de ON de.emp_no = e.emp_no ' : '')
            .' LEFT JOIN titles t2 ON t.emp_no = t2.emp_no AND t2.to_date > t.to_date
            WHERE t2.title IS NULL '
            .(($this->department != false) ? ' AND de.dept_no = :dept_no ' : '');
        $binds[':dept_no'] = $this->department->dept_id;

        if ($this->by_firstname) {
            $sql .= ' AND e.first_name like :search_fn ';
            $binds[':search_fn'] = '%'. $this->by_firstname .'%';
        }
        if ($this->by_lastname) {
            $sql .= ' AND e.last_name like :search_ln ';
            $binds[':search_ln'] = '%'. $this->by_lastname .'%';
        }
        if ($this->by_gender) {
            $sql .= ' AND e.gender = :search_gender ';
            $binds[':search_gender'] = $this->by_gender;
        }
        if ($this->by_title) {
            $sql .= ' AND t.title = :search_title ';
            $binds[':search_title'] = $this->by_title;
        }
        if ($this->by_hiredate) {
            $sql .= ' AND e.hire_date > :search_hiredate ';
            $binds[':search_hiredate'] = $this->by_hiredate;
        }

        return $sql;
    }

    public function loadPageCount($sql, $binds) {
        $this->pagecount = ceil(DepartmentDAO::loadCountByFilteredQuery($sql, $binds) / self::LIMIT);
    }

    public function getEmployeeList($sql, $binds) {
        $users = DepartmentDAO::loadEmployeesByFilteredQuery($sql, $binds, $this->offset);
        return $users;
    }
}


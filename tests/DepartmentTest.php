<?php

require_once "../public/classes/Department.php";

class DepartmentTest extends PHPUnit_Framework_TestCase {
    /**
     * test loads department
     */
    public function testLoadDepartment() {

        $department = new Department(110022);
        if ($department->load()) {
            $this->assertEquals($department->dept_id, 'd001');
            $this->assertEquals($department->dept_name, 'Marketing');
            $this->assertEquals($department->manager_id, '110022');
            $this->assertEquals($department->manager_startdate, '1985-01-01');
            $this->assertEquals($department->manager_enddate, '1991-10-01');
            $this->assertEquals($department->employee_count, '20211');
        }
    }

    /**
     * test all view loads fine
     */
    public function testLoadsAllView() {
        $view = new AllEmployeeView();
        $view->loadPageCount();

        $this->assertEquals($view->getPageCount(), 3001);

        $view->setPage(3);
        $users = $view->getEmployeeList();

        $this->assertEquals(count($users), 100);
        $this->assertEquals($users[0],
                 array('emp_no' => '499699',
                     'first_name' => 'Tesuya',
                     'last_name' => 'Murtha',
                     'gender' => 'F',
                     'hire_date' => '1985-06-08',
                     'title' => 'Senior Engineer'));
    }

    /**
     * test department view loads fine
     */
    public function testLoadsDepartmentView() {
        $department = new Department(110022);
        if ($department->load()) {
            $view = new DepartmentView($department);
            $view->loadPageCount();

            $this->assertEquals($view->getPageCount(), 203);

            $view->setPage(202);
            $users = $view->getEmployeeList();

            $this->assertEquals(count($users), 13);
            $this->assertEquals($users[3],
                array('emp_no' => '10259',
                     'first_name' => 'Susanna',
                     'last_name' => 'Vesel',
                     'gender' => 'M',
                     'hire_date' => '1986-06-25',
                     'title' => 'Senior Staff'));
        }
    }

    /**
     * test department view loads fine
     */
    public function testLoadsSearchFilterView() {
        $department = new Department(110022);
        if ($department->load()) {
            $view = new SearchFilterView($department);

            $first_name = "ha";
            $gender = "M";
            $view->setFilter('firstname', $first_name);
            $view->setFilter('gender', $gender);

            $binds = array();
            $sql = $view->buildQuery($binds);

            $this->assertEquals($sql, 
                ' JOIN titles t ON t.emp_no = e.emp_no  JOIN dept_emp de ON de.emp_no = e.emp_no  LEFT JOIN titles t2 ON t.emp_no = t2.emp_no AND t2.to_date > t.to_date
            WHERE t2.title IS NULL  AND de.dept_no = :dept_no  AND e.first_name like :search_fn  AND e.gender = :search_gender '
            );
            $this->assertEquals($binds, array(':dept_no' => 'd001',
                                              ':search_fn' => '%ha%',
                                              ':search_gender' => 'M'));

            $view->loadPageCount($sql, $binds);

            $this->assertEquals($view->getPageCount(), 10);

            $view->setPage(9);
            $users = $view->getEmployeeList($sql, $binds);

            $this->assertEquals(count($users), 26);
            $this->assertEquals($users[7],
                array('emp_no' => '15859',
                      'first_name' => 'Godehard',
                      'last_name' => 'Hennings',
                      'gender' => 'M',
                      'hire_date' => '1985-03-21',
                      'title' => 'Staff'));
        }
    }
}

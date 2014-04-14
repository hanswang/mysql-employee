<?php

require "base.php";
global $BASE;

require_once "$BASE/classes/AuthHandler.php";
require_once "$BASE/classes/User.php";
require_once "$BASE/classes/Department.php";

if (!AuthHandler::auth()) {
    header("Location: ./login.php");
    exit();
}

$errors = false;
if ($_SESSION['role'] == User::EMPLOYEE_ACCESS) {
    $errors[] = "you can't view employee list as common employee.";
}

if (is_array($errors) && count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<h2>$error</h2>";
    }
} else {
    $pg = get_sanitized_form_input($_POST, 'pg', 'numeric');
    if (!$pg) {
        $pg = 1;
    }

    if ($_SESSION['role'] == User::MANAGER_ACCESS) {
        $department = new Department($_SESSION['emp_no']);
        if ($department->load()) {
            $view_handler = new SearchFilterView($department);
        } else {
            $errors[] = "Load department fails.";
            foreach ($errors as $error) {
                echo "<h2>$error</h2>";
            }
        }
    } else {
        $view_handler = new SearchFilterView();
    }

    if (!$errors) {
        $first_name = get_sanitized_form_input($_POST, 'first_name');
        $last_name = get_sanitized_form_input($_POST, 'last_name');
        $gender = get_sanitized_form_input($_POST, 'gender');
        $title = get_sanitized_form_input($_POST, 'title');
        $hiredate = get_sanitized_form_input($_POST, 'hiredate');

        if ($first_name != '') {
            $view_handler->setFilter('firstname', $first_name);
        }
        if ($last_name != '') {
            $view_handler->setFilter('lastname', $last_name);
        }
        if ($gender != '') {
            $view_handler->setFilter('gender', $gender);
        }
        if ($title != '') {
            $view_handler->setFilter('title', $title);
        }
        if ($hiredate != '') {
            $view_handler->setFilter('hiredate', $hiredate);
        }

        $binds = array();
        $sql = $view_handler->buildQuery($binds);
        $view_handler->loadPageCount($sql, $binds);

        $pg = $view_handler->setPage($pg - 1) + 1;
        $users = $view_handler->getEmployeeList($sql, $binds);
?>
<form method=post action="./view-search.php">
    <h2> Employees list in search terms of below<?php if ($department) { echo(' for department '.$department->dept_name); } ?>: </h2>
    <table class="search-teams" style="width:30%">
        <tbody>
        <tr>
            <td>First name: (Partial Match)</td>
            <td><input type=text name="first_name" size=50 value="<?php echo($first_name); ?>">
        </tr>
        <tr>
            <td>Last name: (Partial Match)</td>
            <td><input type=text name="last_name" size=50 value="<?php echo($last_name); ?>">
        </tr>
        <tr>
            <td>Gender: (M or F)</td>
            <td><input type=text name="gender" size=30 value="<?php echo($gender); ?>">
        </tr>
        <tr>
            <td>Title: (Exact title match)</td>
            <td><input type=text name="title" size=50 value="<?php echo($title); ?>">
        </tr>
        <tr>
            <td>Hire Date: (After this date, e.g. 1990-06-20)</td>
            <td><input type=text name="hiredate" size=50 value="<?php echo($hiredate); ?>">
        </tr>
        </tbody>
    </table>
    <input type=submit value="search">
    <br>
    <br>
    <table class="employee-info" style="width:60%" border="1">
        <tbody>
        <tr><th>First Name</th><th>Last Name</th><th>Gender</th><th>Job Title</th><th>Hire Date</th></tr>
<?php
        foreach($users as $user) {
?>
        <tr>
            <td><a href="./view-user.php?id=<?php echo $user['emp_no']; ?>"><?php echo $user['first_name']; ?></a></td>
            <td><?php echo $user['last_name']; ?></td>
            <td><?php echo $user['gender']; ?></td>
            <td><?php echo $user['title']; ?></td>
            <td><?php echo $user['hire_date']; ?></td>
        </tr>
<?php
        }
?>
        </tbody>
    </table>

    <input type=text name="pg" size=3 value="<?php echo($pg); ?>">
    / of total page <?php echo($view_handler->getPageCount()); ?>
</form>
<?php
    }
}


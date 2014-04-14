<?php

require "base.php";
global $BASE;

require_once "$BASE/classes/AuthHandler.php";
require_once "$BASE/classes/User.php";

if (!AuthHandler::auth()) {
    header("Location: ./login.php");
    exit();
}

$id = get_sanitized_form_input($_GET, 'id', 'numeric');

$need_permission_check = false;

if ($id && $_SESSION['role'] == User::ADMIN_ACCESS) {
    // admin user get access to every one
    // $info = "You loged-in as admin, you have full access";
} else if ($id && $_SESSION['role'] == User::MANAGER_ACCESS) {
    // manager have the view access to his department employees
    $need_permission_check = true;
} else if (!$id || $_SESSION['role'] == User::EMPLOYEE_ACCESS) {
    if ($id) {
        // normal employee try to look at others' profile, warning only
        $info = "You don't have access to view other employee";
    }
    // still show this employee's own profile
    $id = $_SESSION['emp_no'];
}

$viewee = new User($id);
if ($viewee->load()) {
    if ($need_permission_check && $viewee->manager_id != $_SESSION['emp_no']) {
        $errors[] = "You as department manager, can only view employees belong to your department";
        $viewee = false;
    }
} else {
    $errors[] = "User load Fails";
}

if (is_array($errors) && count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<h2>$error</h2>";
    }
} else {
?>
<div style="color: #9F6000; background-color: #FEEFB3;">
<?php
    echo $info;
?>
</div>
<br>
<h2> This employee's infomation: </h2>
<table class="employee-info" style="width:60%" border="1">
    <tbody>
        <tr><th>Field</th><th>Value</th></tr>

        <tr><td>First Name</td><td><?php echo $viewee->first_name; ?></td></tr>
        <tr><td>Last Name</td><td><?php echo $viewee->last_name; ?></td></tr>
        <tr><td>Gender</td><td><?php echo $viewee->gender; ?></td></tr>
        <tr><td>Current Department</td><td><?php echo $viewee->department; ?></td></tr>
        <tr><td>Current Job Title</td><td><?php echo $viewee->job_title; ?></td></tr>
        <tr><td>Hire Date</td><td><?php echo $viewee->hire_date; ?></td></tr>
        <tr><td>Years with Company</td><td><?php echo $viewee->years_serve; ?></td></tr>
        <tr><td>Birth Date</td><td><?php echo $viewee->birth_date; ?></td></tr>
        <tr><td>Age</td><td><?php echo $viewee->age; ?></td></tr>
        <tr><td>Current Salary</td><td><?php echo $viewee->salary; ?></td></tr>
    </tbody>
</table>

<?php
}

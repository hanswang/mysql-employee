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

if ($_SESSION['role'] != User::ADMIN_ACCESS) {
    $errors[] = "Only admin user can see all employee list.";
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

    $view_handler = new AllEmployeeView();
    $view_handler->loadPageCount();
    $pg = $view_handler->setPage($pg - 1) + 1;

    $users = $view_handler->getEmployeeList();
?>
<h2> All employees list: </h2>
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

<form method=post action="./view-all.php">
    <input type=text name="pg" size=3 value="<?php echo($pg); ?>">
    / of total page <?php echo($view_handler->getPageCount()); ?>
</form>
<?php
}


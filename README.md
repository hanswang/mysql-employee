DB config
=========

**db-changes/**

1. setup.sql -- create user for downloaded db & application
2. login.sql -- create login table for user login
3. new-admin.sql -- create admin user
   admin.php -- generate admin password


Application
===========

**classes/**

- User (DAO)
    - normal employee
    - manager
    - admin
- Department (DAO)
    - employee list
    - manager ownership
    - filtering
- Auth handler (DAO + DTO)


**views**

1. login view
2. change password view
3. single user view
4. employee list view (department & all)


Unit Test
=========

0. install PHPUnit
1. test class on user
2. test class on department


Entry Point
===========


- [login](http://www.hanswang.info/mysql-employee/login.php)
- [change password](http://www.hanswang.info/mysql-employee/change-password.php)
- [view user](http://www.hanswang.info/mysql-employee/view-user.php)
- [view department](http://www.hanswang.info/mysql-employee/view-department.php)
- [view all](http://www.hanswang.info/mysql-employee/view-all.php)
- [view search filter](http://www.hanswang.info/mysql-employee/view-search.php)



DB config
=========

db-changes/
1. setup.sql -- create user for downloaded db & application
2. login.sql -- create login table for user login
3. new-admin.sql -- create admin user
   admin.php -- generate admin password


Application
===========

classes/
1. User (DAO)
    a). normal employee
    b). manager
    c). admin
2. Department (DAO)
    a). employee list
    b). manager ownership
    c). filtering
3. Auth handler (DAO + DTO)

views
1. login view
2. change password view
3. single user view
4. employee list view (department & all)


Unit Test
=========

1. on user
2. on department

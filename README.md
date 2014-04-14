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

1. on user
2. on department

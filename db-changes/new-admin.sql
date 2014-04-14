INSERT INTO login
    (username, passwd, salt, first_changed, role_id)
    values
    ("admin", "06ffa27d68bb4c9f858e625a6dba8650ec94f9b06d4d633e935aed33489a3386", "d03a16f12c669f9446a82a5255c162701aef6a0e291bf2109dc19420bae9e97e", "N", 3);

INSERT INTO role (access_type) values ("employee");
INSERT INTO role (access_type) values ("manager");
INSERT INTO role (access_type) values ("admin");

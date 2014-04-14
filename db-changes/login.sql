CREATE TABLE IF NOT EXISTS login (
    `id` INT(10) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `emp_no` INT(11),
    `passwd` VARCHAR(255) NOT NULL,
    `salt` VARCHAR(255) NOT NULL,
    `first_changed` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `role_id` INT(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY ind_username (`username`),
    KEY ind_employerid (`emp_no`),
    KEY ind_pwchanged (`first_changed`),
    KEY ind_roleid (`role_id`)
)Engine=InnoDB;

CREATE TABLE IF NOT EXISTS role (
    `id` INT(4) NOT NULL AUTO_INCREMENT,
    `access_type` VARCHAR(15) NOT NULL DEFAULT 'employee',
    PRIMARY KEY (`id`),
    KEY ind_accesstype (`access_type`)
)Engine=InnoDB;

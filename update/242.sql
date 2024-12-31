DROP TABLE `snmp_errors`;
CREATE TABLE `snmp_errors` ( `error_id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `device_id` INT UNSIGNED NOT NULL , `error_count` INT UNSIGNED NOT NULL DEFAULT '0' , `error_code` INT NOT NULL , `error_reason` VARCHAR(16) NOT NULL DEFAULT '' , `snmp_cmd_exitcode` TINYINT UNSIGNED NOT NULL , `snmp_cmd` ENUM('snmpget','snmpwalk') NOT NULL , `snmp_options` VARCHAR(16) NULL DEFAULT NULL , `mib_dir` VARCHAR(256) NULL DEFAULT NULL , `mib` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL , `oid` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,  PRIMARY KEY (`error_id`) ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `snmp_errors` ADD `updated` INT(11) UNSIGNED NOT NULL AFTER `oid`;
ALTER TABLE `snmp_errors` ADD `added` INT(11) UNSIGNED NOT NULL AFTER `oid`;

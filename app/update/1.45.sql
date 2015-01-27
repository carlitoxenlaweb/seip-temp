CREATE TABLE Configuration_audit (id INT NOT NULL, rev INT NOT NULL, group_id INT DEFAULT NULL, keyIndex VARCHAR(200) DEFAULT NULL, value VARCHAR(200) DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE BaseGroup_audit (id INT NOT NULL, rev INT NOT NULL, name VARCHAR(200) DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_user_audit (id INT NOT NULL, rev INT NOT NULL, parent_id INT DEFAULT NULL, fk_direction INT DEFAULT NULL, fk_complejo INT DEFAULT NULL, fk_gerencia INT DEFAULT NULL, fk_gerencia_second INT DEFAULT NULL, fk_cargo INT DEFAULT NULL, configuration_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) DEFAULT NULL, expired TINYINT(1) DEFAULT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(255) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, num_personal INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_result_audit (id INT NOT NULL, rev INT NOT NULL, parent_id INT DEFAULT NULL, objetive_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, typeResult INT DEFAULT NULL, typeCalculation INT DEFAULT NULL, lastDateCalculateResult DATETIME DEFAULT NULL, resultDetails_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_c_rol_audit (id INT NOT NULL, rev INT NOT NULL, fk_user_created_at INT DEFAULT NULL, fk_user_updated_at INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description VARCHAR(100) DEFAULT NULL, level INT DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, typeRol INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Variable_audit (id INT NOT NULL, rev INT NOT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_c_formula_audit (id INT NOT NULL, rev INT NOT NULL, fk_user_created_at INT DEFAULT NULL, fk_user_updated_at INT DEFAULT NULL, fk_formula_level INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description VARCHAR(300) DEFAULT NULL, equation LONGTEXT DEFAULT NULL, equationReal LONGTEXT DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, sourceEquationReal LONGTEXT DEFAULT NULL, sourceEquationPlan LONGTEXT DEFAULT NULL, typeOfCalculation INT DEFAULT NULL, variableToRealValue_id INT DEFAULT NULL, variableToPlanValue_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_objetive_audit (id INT NOT NULL, rev INT NOT NULL, fk_user_created_at INT DEFAULT NULL, fk_user_updated_at INT DEFAULT NULL, fk_complejo INT DEFAULT NULL, fk_gerencia INT DEFAULT NULL, fk_gerencia_second INT DEFAULT NULL, fk_tendency INT DEFAULT NULL, period_id INT DEFAULT NULL, fk_objetive_level INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, ref VARCHAR(15) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, goal DOUBLE PRECISION DEFAULT NULL, eval_objetive TINYINT(1) DEFAULT NULL, eval_indicator TINYINT(1) DEFAULT NULL, eval_arrangement_program TINYINT(1) DEFAULT NULL, eval_simple_average TINYINT(1) DEFAULT NULL, eval_weighted_average TINYINT(1) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, revisionDate DATETIME DEFAULT NULL, approvalDate DATETIME DEFAULT NULL, status INT DEFAULT NULL, resultOfObjetive DOUBLE PRECISION DEFAULT NULL, lastDateCalculateResult DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, reviewedBy_id INT DEFAULT NULL, approvedBy_id INT DEFAULT NULL, arrangementRange_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_indicator_audit (id INT NOT NULL, rev INT NOT NULL, fk_user_created_at INT DEFAULT NULL, fk_user_updated_at INT DEFAULT NULL, fk_formula INT DEFAULT NULL, fk_tendency INT DEFAULT NULL, period_id INT DEFAULT NULL, details_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, fk_indicator_level INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, lastDateCalculateResult DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, ref VARCHAR(15) DEFAULT NULL, refParent VARCHAR(15) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, totalPlan DOUBLE PRECISION DEFAULT NULL, goal DOUBLE PRECISION DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, tmp TINYINT(1) DEFAULT NULL, valueFinal DOUBLE PRECISION DEFAULT NULL, progressToDate DOUBLE PRECISION DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, typeOfCalculation INT DEFAULT NULL, frequencyNotificationIndicator_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_c_indicator_frequency_notification_audit (id INT NOT NULL, rev INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, textAbbr VARCHAR(20) DEFAULT NULL, days INT DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE revisions (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

-- Actualizacion de la tabla periodo
ALTER TABLE Period ADD dateStartClearanceNotificationArrangementProgram DATE DEFAULT NULL, ADD dateEndClearanceNotificationArrangementProgram DATE DEFAULT NULL;
-- Actualizacion del periodo para relacionarlo
ALTER TABLE Period ADD parent_id INT DEFAULT NULL;
ALTER TABLE Period ADD CONSTRAINT FK_C2141BF8727ACA70 FOREIGN KEY (parent_id) REFERENCES Period (id);
CREATE UNIQUE INDEX UNIQ_C2141BF8727ACA70 ON Period (parent_id);
CREATE TABLE Period_audit (id INT NOT NULL, rev INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, dateStart DATE DEFAULT NULL, dateEnd DATE DEFAULT NULL, status TINYINT(1) DEFAULT NULL, dateStartNotificationArrangementProgram DATE DEFAULT NULL, dateEndNotificationArrangementProgram DATE DEFAULT NULL, dateStartLoadArrangementProgram DATE DEFAULT NULL, dateEndLoadArrangementProgram DATE DEFAULT NULL, dateStartClearanceNotificationArrangementProgram DATE DEFAULT NULL, dateEndClearanceNotificationArrangementProgram DATE DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE Period ADD deletedAt DATETIME DEFAULT NULL;

-- PD: Crear periodo 2015 por administrador.
INSERT INTO `Configuration` (`id`, `group_id`, `keyIndex`, `value`, `description`, `active`, `createdAt`, `updatedAt`) VALUES (NULL, '1', 'PRE_PLANNING_ENABLE_PRE_PLANNING', '1', 'Habilita la pre planificacion del periodo siguiente', '1', '2015-01-22 00:00:00', NULL);

ALTER TABLE seip_indicator ADD arrangementRange_id INT DEFAULT NULL;
UPDATE seip_indicator i,seip_arrangement_range ar SET i.arrangementRange_id = ar.id WHERE ar.fk_indicator = i.id;

ALTER TABLE seip_indicator ADD CONSTRAINT FK_6092D6A69B33E358 FOREIGN KEY (arrangementRange_id) REFERENCES seip_arrangement_range (id);
CREATE UNIQUE INDEX UNIQ_6092D6A69B33E358 ON seip_indicator (arrangementRange_id);
ALTER TABLE seip_indicator_audit ADD arrangementRange_id INT DEFAULT NULL;
ALTER TABLE seip_arrangement_range DROP FOREIGN KEY FK_845D408CB06D4A23;
DROP INDEX UNIQ_845D408CB06D4A23 ON seip_arrangement_range;
ALTER TABLE seip_arrangement_range DROP fk_indicator;


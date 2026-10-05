DROP TABLE IF EXISTS `mst_expense_name`;

CREATE TABLE `mst_expense_name` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,

    `expense_type_id` INT(11) NOT NULL,
    `expense_name` VARCHAR(150) NOT NULL,

    `created_by` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by` VARCHAR(50) DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_flg` INT(11) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

INSERT INTO mst_expense_name
(
    expense_type_id,
    expense_name,
    created_by
)
VALUES
(1,'Cement','admin'),
(1,'Steel','admin'),
(1,'Blue Metal','admin'),
(1,'Sand','admin'),

(2,'Lorry Rent','admin'),
(2,'Auto Charges','admin'),

(3,'JCB Rent','admin'),
(3,'Crane Rent','admin'),

(4,'Diesel','admin'),
(4,'Petrol','admin'),

(5,'Breakfast','admin'),
(5,'Lunch','admin'),

(6,'EB Bill','admin'),

(7,'Water Can','admin'),

(8,'Office Expense','admin');
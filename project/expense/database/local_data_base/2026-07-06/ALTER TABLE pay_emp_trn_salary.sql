ALTER TABLE pay_emp_trn_salary
ADD COLUMN day TINYINT(2) NULL AFTER NET_salary;

ALTER TABLE pay_mst_ps_emp
ADD COLUMN day TINYINT(2) NULL AFTER month;

-- used below alter sql for the time of the payment time
-- ALTER TABLE pay_payslip_details
-- ADD COLUMN day TINYINT(2) NULL AFTER month;
-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2021 at 06:13 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrisv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit_trail`
--

CREATE TABLE `tbl_audit_trail` (
  `ID` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_approvers`
--

CREATE TABLE `tbl_benefits_approvers` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `user_id` text NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_approvers_role`
--

CREATE TABLE `tbl_benefits_approvers_role` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `role` text NOT NULL,
  `position` text NOT NULL,
  `cc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_car_balance`
--

CREATE TABLE `tbl_benefits_car_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_cep_balance`
--

CREATE TABLE `tbl_benefits_cep_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_eligibility`
--

CREATE TABLE `tbl_benefits_eligibility` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `parking` text NOT NULL,
  `gasoline` text NOT NULL,
  `car_maintenance` text NOT NULL,
  `medicine` text NOT NULL,
  `gym` text NOT NULL,
  `optical_allowance` text NOT NULL,
  `cep` text NOT NULL,
  `club_membership` text NOT NULL,
  `maternity` text NOT NULL,
  `others` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_form`
--

CREATE TABLE `tbl_benefits_form` (
  `ID` int(11) NOT NULL,
  `benefits_id` text NOT NULL,
  `amount` float NOT NULL,
  `remarks` text NOT NULL,
  `attachment` text NOT NULL,
  `cat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_gasoline_details`
--

CREATE TABLE `tbl_benefits_gasoline_details` (
  `ID` int(11) NOT NULL,
  `benefits_id` text NOT NULL,
  `requested_liters` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_gas_balance`
--

CREATE TABLE `tbl_benefits_gas_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_gym_balance`
--

CREATE TABLE `tbl_benefits_gym_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_maintenance`
--

CREATE TABLE `tbl_benefits_maintenance` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `jgs_id` text NOT NULL,
  `car_year1` float NOT NULL,
  `car_year2` float NOT NULL,
  `car_year3` float NOT NULL,
  `car_year4` float NOT NULL,
  `car_year5` float NOT NULL,
  `cep_annual` float NOT NULL,
  `cep_monthly` float NOT NULL,
  `gas_monthly` float NOT NULL,
  `gym_annual` float NOT NULL,
  `gym_monthly` float NOT NULL,
  `medical_annual` float NOT NULL,
  `medical_monthly` float NOT NULL,
  `optical_annual` float NOT NULL,
  `optical_monthly` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_medical_balance`
--

CREATE TABLE `tbl_benefits_medical_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_optical_balance`
--

CREATE TABLE `tbl_benefits_optical_balance` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `balance` float NOT NULL,
  `used` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_reimbursement`
--

CREATE TABLE `tbl_benefits_reimbursement` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `requestor` text NOT NULL,
  `payee` text NOT NULL,
  `amount` float NOT NULL,
  `payment_for` text NOT NULL,
  `special_instruction` text NOT NULL,
  `categories_applied` text NOT NULL,
  `hr_remarks` text NOT NULL,
  `status` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_benefits_total_amount`
--

CREATE TABLE `tbl_benefits_total_amount` (
  `ID` int(11) NOT NULL,
  `benefits_id` text NOT NULL,
  `total_amount` float NOT NULL,
  `cat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_car_registration`
--

CREATE TABLE `tbl_car_registration` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `employee_number` text NOT NULL,
  `model` text NOT NULL,
  `plate_number` text NOT NULL,
  `date_acquired` date NOT NULL,
  `description` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cep_bond`
--

CREATE TABLE `tbl_cep_bond` (
  `ID` int(11) NOT NULL,
  `benefits_id` text NOT NULL,
  `type` text NOT NULL,
  `premise` text NOT NULL,
  `bond` float NOT NULL,
  `remaining` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificate_requests`
--

CREATE TABLE `tbl_certificate_requests` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `employee_number` text NOT NULL,
  `requested_by` text NOT NULL,
  `certificate_type` text NOT NULL,
  `date_required` date NOT NULL,
  `purpose` text NOT NULL,
  `remarks` text NOT NULL,
  `hr_remarks` text NOT NULL,
  `acknowledged_by` text NOT NULL,
  `status` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificate_requests_approvers`
--

CREATE TABLE `tbl_certificate_requests_approvers` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `user_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_college`
--

CREATE TABLE `tbl_college` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `college` text NOT NULL,
  `from_date` text NOT NULL,
  `to_date` text NOT NULL,
  `degree` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_companies`
--

CREATE TABLE `tbl_companies` (
  `ID` int(11) NOT NULL,
  `company_name` text NOT NULL,
  `date_created` date NOT NULL,
  `is_deleted` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_companies`
--

INSERT INTO `tbl_companies` (`ID`, `company_name`, `date_created`, `is_deleted`) VALUES
(3, 'Sample Company', '2021-04-12', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company_benefits`
--

CREATE TABLE `tbl_company_benefits` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `parking` text NOT NULL,
  `gasoline` text NOT NULL,
  `car_maintenance` text NOT NULL,
  `medicine` text NOT NULL,
  `gym` text NOT NULL,
  `optical_allowance` text NOT NULL,
  `cep` text NOT NULL,
  `club_membership` text NOT NULL,
  `maternity` text NOT NULL,
  `others` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_departments`
--

CREATE TABLE `tbl_departments` (
  `ID` int(11) NOT NULL,
  `department` text NOT NULL,
  `company_id` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_documents`
--

CREATE TABLE `tbl_documents` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `attachment` text NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emergency_contacts`
--

CREATE TABLE `tbl_emergency_contacts` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `contact_name` text NOT NULL,
  `contact_number` text NOT NULL,
  `email_address` text NOT NULL,
  `relationship` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employment_information`
--

CREATE TABLE `tbl_employment_information` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `position_number` text NOT NULL,
  `position_title` text NOT NULL,
  `job_description` text NOT NULL,
  `date_hired` date NOT NULL,
  `company` text NOT NULL,
  `department` text NOT NULL,
  `job_grade_set` text NOT NULL,
  `job_grade` text NOT NULL,
  `employment_status` text NOT NULL,
  `account_status` text NOT NULL,
  `approver` text NOT NULL,
  `reporting_to` text NOT NULL,
  `vendor_id` text NOT NULL,
  `filing` text NOT NULL,
  `is_approver` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_government_id`
--

CREATE TABLE `tbl_government_id` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `sss` text NOT NULL,
  `philhealth` text NOT NULL,
  `pagibig` text NOT NULL,
  `tin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_holidays`
--

CREATE TABLE `tbl_holidays` (
  `ID` int(11) NOT NULL,
  `holiday_date` date NOT NULL,
  `type` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ids`
--

CREATE TABLE `tbl_ids` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `id_name` text NOT NULL,
  `id_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_grade`
--

CREATE TABLE `tbl_job_grade` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `job_grade` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_grade_set`
--

CREATE TABLE `tbl_job_grade_set` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `job_grade_set` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave_balances`
--

CREATE TABLE `tbl_leave_balances` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `VL` float NOT NULL,
  `SL` float NOT NULL,
  `EL` float NOT NULL,
  `MLWOP` float NOT NULL,
  `PL` float NOT NULL,
  `BL` float NOT NULL,
  `SPL` float NOT NULL,
  `SLBW` float NOT NULL,
  `WFH` float NOT NULL,
  `OB` float NOT NULL,
  `CSR` float NOT NULL,
  `SLWOP` float NOT NULL,
  `VLWOP` float NOT NULL,
  `ECU` float NOT NULL,
  `SLBANK` float NOT NULL,
  `MNCS` float NOT NULL,
  `MM` float NOT NULL,
  `PLA` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave_maintenance`
--

CREATE TABLE `tbl_leave_maintenance` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `jgs_id` text NOT NULL,
  `sl_monthly` float NOT NULL,
  `sl_annual` float NOT NULL,
  `vl_monthly` float NOT NULL,
  `vl_annual` float NOT NULL,
  `wfh_monthly` float NOT NULL,
  `wfh_annual` float NOT NULL,
  `el_monthly` float NOT NULL,
  `el_annual` float NOT NULL,
  `ecu_annual` float NOT NULL,
  `bl_annual` float NOT NULL,
  `pl_annual` float NOT NULL,
  `pla_annual` float NOT NULL,
  `spl_annual` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave_requests`
--

CREATE TABLE `tbl_leave_requests` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `requestor` text NOT NULL,
  `delegated_emp_number` text NOT NULL,
  `leave_type` text NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `total_day` text NOT NULL,
  `reason` text NOT NULL,
  `duration` text NOT NULL,
  `attachment` text NOT NULL,
  `approver` text NOT NULL,
  `approver_remarks` text NOT NULL,
  `status` text NOT NULL,
  `date_filed` date NOT NULL,
  `cancellation_reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loan_application`
--

CREATE TABLE `tbl_loan_application` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `employee_number` text NOT NULL,
  `amount` float NOT NULL,
  `type` text NOT NULL,
  `terms` int(11) NOT NULL,
  `attachment` text NOT NULL,
  `remarks` text NOT NULL,
  `amount_approved` float NOT NULL,
  `monthly_deduction` int(11) NOT NULL,
  `date_approved` date NOT NULL,
  `start_date` text NOT NULL,
  `hr_remarks` text NOT NULL,
  `date_created` date NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loan_approvers`
--

CREATE TABLE `tbl_loan_approvers` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `user_id` text NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loan_approvers_role`
--

CREATE TABLE `tbl_loan_approvers_role` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `role` text NOT NULL,
  `position` text NOT NULL,
  `cc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loan_max_value`
--

CREATE TABLE `tbl_loan_max_value` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `others_max_value` text NOT NULL,
  `max_value` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loan_status`
--

CREATE TABLE `tbl_loan_status` (
  `ID` int(11) NOT NULL,
  `loan_id` text NOT NULL,
  `monthly_deduction` float NOT NULL,
  `date` date NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_maintenance`
--

CREATE TABLE `tbl_maintenance` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `logo` text NOT NULL,
  `banner` text NOT NULL,
  `prefix` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ot_application`
--

CREATE TABLE `tbl_ot_application` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `employee_number` text NOT NULL,
  `month_of_ot` text NOT NULL,
  `total_hours` text NOT NULL,
  `remarks` text NOT NULL,
  `attachment` text NOT NULL,
  `approver` text NOT NULL,
  `approver_remarks` text NOT NULL,
  `approver_attachment` text NOT NULL,
  `status` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personal_information`
--

CREATE TABLE `tbl_personal_information` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `company_email` text NOT NULL,
  `last_name` text NOT NULL,
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `address` text NOT NULL,
  `personal_email` text NOT NULL,
  `contact_number` text NOT NULL,
  `account_name` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` text NOT NULL,
  `gender` text NOT NULL,
  `citizenship` text NOT NULL,
  `civil_status` text NOT NULL,
  `spouse_name` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_position_history`
--

CREATE TABLE `tbl_position_history` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `prev_position` text NOT NULL,
  `new_position` text NOT NULL,
  `date_promoted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_graduate`
--

CREATE TABLE `tbl_post_graduate` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `school` text NOT NULL,
  `from_date` text NOT NULL,
  `to_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prepay`
--

CREATE TABLE `tbl_prepay` (
  `ID` int(11) NOT NULL,
  `employee_number` text NOT NULL,
  `prepay_id` text NOT NULL,
  `loan_id` text NOT NULL,
  `prepay_remarks` text NOT NULL,
  `attachment` text NOT NULL,
  `status` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timekeeping`
--

CREATE TABLE `tbl_timekeeping` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `employee_number` text NOT NULL,
  `arsid` text NOT NULL,
  `from_date` date NOT NULL,
  `day` text NOT NULL,
  `timein` timestamp NULL DEFAULT NULL,
  `timeout` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_training`
--

CREATE TABLE `tbl_training` (
  `ID` int(11) NOT NULL,
  `admin_email` text NOT NULL,
  `company_id` text NOT NULL,
  `assigned_employee` text NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL,
  `date_of_request` date NOT NULL,
  `target_date` date NOT NULL,
  `attachment` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_training_approvers`
--

CREATE TABLE `tbl_training_approvers` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `user_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `ID` int(11) NOT NULL,
  `company_id` text NOT NULL,
  `email` text NOT NULL,
  `account_name` text NOT NULL,
  `role` text NOT NULL,
  `pending_task` text NOT NULL,
  `file201` text NOT NULL,
  `leave_management` text NOT NULL,
  `ot_management` text NOT NULL,
  `certificate_requests` text NOT NULL,
  `salary_loan_management` text NOT NULL,
  `benefits_reimbursement` text NOT NULL,
  `timekeeping` text NOT NULL,
  `training` text NOT NULL,
  `performance` text NOT NULL,
  `holiday_maintenance` text NOT NULL,
  `generate_reports` text NOT NULL,
  `password` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`ID`, `company_id`, `email`, `account_name`, `role`, `pending_task`, `file201`, `leave_management`, `ot_management`, `certificate_requests`, `salary_loan_management`, `benefits_reimbursement`, `timekeeping`, `training`, `performance`, `holiday_maintenance`, `generate_reports`, `password`, `date_created`) VALUES
(6, '3', 'carlosjohnharold@outlook.com', 'John Harold  Carlos', 'Admin', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 'b26986ceee60f744534aaab928cc12df', '2021-04-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_audit_trail`
--
ALTER TABLE `tbl_audit_trail`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_approvers`
--
ALTER TABLE `tbl_benefits_approvers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_approvers_role`
--
ALTER TABLE `tbl_benefits_approvers_role`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_car_balance`
--
ALTER TABLE `tbl_benefits_car_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_cep_balance`
--
ALTER TABLE `tbl_benefits_cep_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_eligibility`
--
ALTER TABLE `tbl_benefits_eligibility`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_form`
--
ALTER TABLE `tbl_benefits_form`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_gasoline_details`
--
ALTER TABLE `tbl_benefits_gasoline_details`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_gas_balance`
--
ALTER TABLE `tbl_benefits_gas_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_gym_balance`
--
ALTER TABLE `tbl_benefits_gym_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_maintenance`
--
ALTER TABLE `tbl_benefits_maintenance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_medical_balance`
--
ALTER TABLE `tbl_benefits_medical_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_optical_balance`
--
ALTER TABLE `tbl_benefits_optical_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_reimbursement`
--
ALTER TABLE `tbl_benefits_reimbursement`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_benefits_total_amount`
--
ALTER TABLE `tbl_benefits_total_amount`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_car_registration`
--
ALTER TABLE `tbl_car_registration`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_cep_bond`
--
ALTER TABLE `tbl_cep_bond`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_certificate_requests`
--
ALTER TABLE `tbl_certificate_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_certificate_requests_approvers`
--
ALTER TABLE `tbl_certificate_requests_approvers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_college`
--
ALTER TABLE `tbl_college`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_companies`
--
ALTER TABLE `tbl_companies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_company_benefits`
--
ALTER TABLE `tbl_company_benefits`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_emergency_contacts`
--
ALTER TABLE `tbl_emergency_contacts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_employment_information`
--
ALTER TABLE `tbl_employment_information`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_government_id`
--
ALTER TABLE `tbl_government_id`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_holidays`
--
ALTER TABLE `tbl_holidays`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_ids`
--
ALTER TABLE `tbl_ids`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_job_grade`
--
ALTER TABLE `tbl_job_grade`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_job_grade_set`
--
ALTER TABLE `tbl_job_grade_set`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_leave_balances`
--
ALTER TABLE `tbl_leave_balances`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_leave_maintenance`
--
ALTER TABLE `tbl_leave_maintenance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_leave_requests`
--
ALTER TABLE `tbl_leave_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_loan_application`
--
ALTER TABLE `tbl_loan_application`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_loan_approvers`
--
ALTER TABLE `tbl_loan_approvers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_loan_approvers_role`
--
ALTER TABLE `tbl_loan_approvers_role`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_loan_max_value`
--
ALTER TABLE `tbl_loan_max_value`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_loan_status`
--
ALTER TABLE `tbl_loan_status`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_maintenance`
--
ALTER TABLE `tbl_maintenance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_ot_application`
--
ALTER TABLE `tbl_ot_application`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_personal_information`
--
ALTER TABLE `tbl_personal_information`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_position_history`
--
ALTER TABLE `tbl_position_history`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_post_graduate`
--
ALTER TABLE `tbl_post_graduate`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_prepay`
--
ALTER TABLE `tbl_prepay`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_timekeeping`
--
ALTER TABLE `tbl_timekeeping`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_training`
--
ALTER TABLE `tbl_training`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_training_approvers`
--
ALTER TABLE `tbl_training_approvers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_audit_trail`
--
ALTER TABLE `tbl_audit_trail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `tbl_benefits_approvers`
--
ALTER TABLE `tbl_benefits_approvers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_benefits_approvers_role`
--
ALTER TABLE `tbl_benefits_approvers_role`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_benefits_car_balance`
--
ALTER TABLE `tbl_benefits_car_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_benefits_cep_balance`
--
ALTER TABLE `tbl_benefits_cep_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_benefits_eligibility`
--
ALTER TABLE `tbl_benefits_eligibility`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_benefits_form`
--
ALTER TABLE `tbl_benefits_form`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `tbl_benefits_gasoline_details`
--
ALTER TABLE `tbl_benefits_gasoline_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_benefits_gas_balance`
--
ALTER TABLE `tbl_benefits_gas_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_benefits_gym_balance`
--
ALTER TABLE `tbl_benefits_gym_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_benefits_maintenance`
--
ALTER TABLE `tbl_benefits_maintenance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_benefits_medical_balance`
--
ALTER TABLE `tbl_benefits_medical_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_benefits_optical_balance`
--
ALTER TABLE `tbl_benefits_optical_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_benefits_reimbursement`
--
ALTER TABLE `tbl_benefits_reimbursement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tbl_benefits_total_amount`
--
ALTER TABLE `tbl_benefits_total_amount`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_car_registration`
--
ALTER TABLE `tbl_car_registration`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_cep_bond`
--
ALTER TABLE `tbl_cep_bond`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_certificate_requests`
--
ALTER TABLE `tbl_certificate_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_certificate_requests_approvers`
--
ALTER TABLE `tbl_certificate_requests_approvers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_college`
--
ALTER TABLE `tbl_college`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_companies`
--
ALTER TABLE `tbl_companies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_company_benefits`
--
ALTER TABLE `tbl_company_benefits`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_emergency_contacts`
--
ALTER TABLE `tbl_emergency_contacts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_employment_information`
--
ALTER TABLE `tbl_employment_information`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_government_id`
--
ALTER TABLE `tbl_government_id`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_holidays`
--
ALTER TABLE `tbl_holidays`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_ids`
--
ALTER TABLE `tbl_ids`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_job_grade`
--
ALTER TABLE `tbl_job_grade`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_job_grade_set`
--
ALTER TABLE `tbl_job_grade_set`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_leave_balances`
--
ALTER TABLE `tbl_leave_balances`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_leave_maintenance`
--
ALTER TABLE `tbl_leave_maintenance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_leave_requests`
--
ALTER TABLE `tbl_leave_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_loan_application`
--
ALTER TABLE `tbl_loan_application`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_loan_approvers`
--
ALTER TABLE `tbl_loan_approvers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_loan_approvers_role`
--
ALTER TABLE `tbl_loan_approvers_role`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_loan_max_value`
--
ALTER TABLE `tbl_loan_max_value`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_loan_status`
--
ALTER TABLE `tbl_loan_status`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_maintenance`
--
ALTER TABLE `tbl_maintenance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_ot_application`
--
ALTER TABLE `tbl_ot_application`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_personal_information`
--
ALTER TABLE `tbl_personal_information`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_position_history`
--
ALTER TABLE `tbl_position_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_post_graduate`
--
ALTER TABLE `tbl_post_graduate`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_prepay`
--
ALTER TABLE `tbl_prepay`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_timekeeping`
--
ALTER TABLE `tbl_timekeeping`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_training`
--
ALTER TABLE `tbl_training`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_training_approvers`
--
ALTER TABLE `tbl_training_approvers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

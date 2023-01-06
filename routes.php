<?php
error_reporting(1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';


use Bramus\Router\Router;
$router = new Router();
$router->setNamespace('App\Http\Controllers');

$router->get('company', 'CompanyController@index');
$router->post('company', 'CompanyController@store');
$router->get('company/(\d+)/edit', 'CompanyController@edit');
$router->post('company/(\d+)/update', 'CompanyController@update');

$router->get('company/(\d+)/departments', 'CompanyController@getDepartments');
$router->post('company/(\d+)/departments', 'CompanyController@createDepartment');
$router->post('company/(\d+)/departments/(\d+)/update', 'CompanyController@updateDepartment');

$router->get('company/(\d+)/job-grades', 'CompanyController@getJobGrades');
$router->post('company/(\d+)/job-grades', 'CompanyController@createJobGrade');
$router->post('company/(\d+)/job-grades/(\d+)/update', 'CompanyController@updateJobGrade');


$router->get('company/(\d+)/job-grade-sets', 'CompanyController@getJobGradeSets');
$router->post('company/(\d+)/job-grade-sets', 'CompanyController@createJobGradeSet');
$router->post('company/(\d+)/job-grade-sets/(\d+)/update', 'CompanyController@updateJobGradeSet');

$router->get('company/(\d+)/benefits', 'CompanyController@getBenefits');
$router->post('company/(\d+)/benefits/update', 'CompanyController@updateBenefits');

$router->get('company/(\d+)/maintenance', 'CompanyController@updateMaintenance');
$router->post('company/(\d+)/maintenance/(\d+)/update', 'MaintenanceController@updateMaintenance');
$router->post('company/(\d+)/loan-value/(\d+)/update', 'MaintenanceController@updateLoanValue');
$router->post('company/(\d+)/leave-balance/update', 'MaintenanceController@updateLeaveBalance');

$router->get('company/(\d+)/leave-balance/get-company', 'MaintenanceController@getCompanyLeaveBalance');

$router->get('company/(\d+)/maintenance-benefits', 'MaintenanceController@getMaintenanceBenefits');

$router->post('company/(\d+)/maintenance-benefits/update', 'MaintenanceController@updateMaintenanceBenefits');


$router->get('payroll', 'PayrollController@getPayroll');


$router->get('account', 'AccountController@index');

$router->get('employee', 'EmployeeController@index');

$router->get('employee/create', 'EmployeeController@create');

$router->post('employee', 'EmployeeController@store');

$router->run();

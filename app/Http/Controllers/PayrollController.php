<?php


namespace App\Http\Controllers;


use App\Services\BenefitMaintenanceService;
use App\Services\JobGradeSetService;
use App\Services\LeaveMaintenanceService;
use App\Services\LoanMaxValueService;
use App\Services\MaintenanceService;

class PayrollController extends Controller
{
    public function getPayroll() {
        $this->render('payroll/index');
    }

}

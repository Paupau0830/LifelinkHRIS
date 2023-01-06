<?php


namespace App\Http\Controllers;


use App\Services\BenefitMaintenanceService;
use App\Services\JobGradeSetService;
use App\Services\LeaveMaintenanceService;
use App\Services\LoanMaxValueService;
use App\Services\MaintenanceService;

class MaintenanceController extends Controller
{
    protected $mainternanceService;
    protected $loanMaxValueService;
    protected $leaveMaintenanceService;
    protected $jobGradeSetService;
    protected $benefitMaintenanceService;

    public function __construct()
    {
        $this->mainternanceService = new MaintenanceService();
        $this->loanMaxValueService = new LoanMaxValueService();
        $this->leaveMaintenanceService = new LeaveMaintenanceService();
        $this->jobGradeSetService = new JobGradeSetService();
        $this->benefitMaintenanceService = new BenefitMaintenanceService();
        parent::__construct();
    }

    public function updateMaintenance($id, $maintenanceId)
    {
        $data = $_POST;
        if ($_FILES['logo']['name'] != "") {
            $data['logo'] = md5($_FILES['logo']['name']);
            $logo_tmp = $_FILES['logo']['tmp_name'];
            move_uploaded_file($logo_tmp, "uploads/" . $data['logo']);
        }
        if ($_FILES['banner']['name'] != "") {
            $data['banner'] = md5($_FILES['banner']['name']);
            $banner_tmp = $_FILES['banner']['tmp_name'];
            move_uploaded_file($banner_tmp, "uploads/" . $data['banner']);
        }
        $this->mainternanceService->updateBy('id', $maintenanceId, $data);
        $this->auditTrailService->insert([
            'description' => 'Updated company maintenance for company ID: ' . $id
        ]);
        $this->flash('success', 'Company Maintenance has been updated.');

        return $this->redirect('company/' . $id . '/maintenance');
    }

    /**
     * @param $id
     * @return false
     */
    public function updateLoanValue($id)
    {
        $this->loanMaxValueService->updateBy('company_id', $id, $_POST);
        $this->auditTrailService->insert([
            'description' => "Updated Loan Max Values for company ID: $id"
        ]);
        $this->flash('success', 'Loan Max Values has been updated.');
        return $this->redirect('company/' . $id . '/maintenance');
    }

    public function updateLeaveBalance($id)
    {
        $data = $_POST;
        if ($this->leaveMaintenanceService->existsBy([
            'company_id' => $id,
            'jgs_id' => $data['jgs_id']
        ])) {
            $this->leaveMaintenanceService->updateByAttributes([
                'company_id' => $id,
                'jgs_id' => $data['jgs_id']
            ], $data);
        } else {
            $this->leaveMaintenanceService->insert(array_replace($data, [
                'company_id' => $id
            ]));
        }

        return $this->redirect('company/' . $id . '/maintenance');
    }

    public function getCompanyLeaveBalance($id)
    {
        $data = $this->leaveMaintenanceService->getFirstByAttributres([
            'company_id' => $id,
            'jgs_id' => $_GET['jgs_id']
        ]);

        echo json_encode($data, JSON_FORCE_OBJECT);
    }

    public function getMaintenanceBenefits($id) {
        $data = $this->benefitMaintenanceService->getFirstByAttributres([
            'company_id' => $id,
            'jgs_id' => $_GET['jgs_id']
        ]);

        echo json_encode($data, JSON_FORCE_OBJECT);
    }

    public function updateMaintenanceBenefits($id) {
        $data = $_POST;
        if ($this->benefitMaintenanceService->existsBy([
            'company_id' => $id,
            'jgs_id' => $data['jgs_id']
        ])) {
            $this->benefitMaintenanceService->updateByAttributes([
                'company_id' => $id,
                'jgs_id' => $data['jgs_id']
            ], $data);
        } else {
            $this->benefitMaintenanceService->insert(array_replace($data, [
                'company_id' => $id
            ]));
        }

        return $this->redirect('company/' . $id . '/maintenance');
    }
}

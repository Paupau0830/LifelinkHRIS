<?php

namespace App\Http\Controllers;

use App\Services\AuditTrailService;
use App\Services\CompanyBenefitService;
use App\Services\DepartmentService;
use App\Services\JobGradeService;
use App\Services\JobGradeSetService;
use App\Services\LoanMaxValueService;
use App\Services\MaintenanceService;

class CompanyController extends Controller
{
    protected $departmentService;
    protected $jobGradeService;
    protected $jobGradeSetService;
    protected $benefitService;
    protected $maintenanceService;
    protected $loadnMaxValueService;

    public function __construct()
    {
        $this->departmentService = new DepartmentService();
        $this->jobGradeService = new JobGradeService();
        $this->jobGradeSetService = new JobGradeSetService();
        $this->benefitService = new CompanyBenefitService();
        $this->maintenanceService = new MaintenanceService();
        $this->loadnMaxValueService = new LoanMaxValueService();
        parent::__construct();
    }

    /**
     * Get companies list
     */
    public function index()
    {
        $companies = $this->companyService->get();

        $this->render('company/index', compact('companies'));
    }

    /**
     * Store companies
     */
    public function store()
    {
        $data = $_POST;
        if ($this->companyService->exists('company_name', $_POST['company_name'])) {
            $this->flash('danger', 'Submission failed. Company already exist.');
        } else {
            $this->companyService->insert($data);
            $this->flash('success', $data['company_name'] . ' has been added as Company');
        }

        $this->redirect('company');
    }

    /**
     * Edit company
     * @param int $id
     */
    public function edit(int $id)
    {
        $data = $this->getMasterData($id);

        $this->render('company/edit', $data);
    }

    public function getDepartments($id)
    {
        $data = $this->getMasterData($id);
        $data['departments'] = $this->departmentService->getBy('company_id', $id);

        $this->render('company/departments/index', $data);
    }

    protected function getMasterData($id)
    {
        $totalDepartment = $this->departmentService->countBy('company_id', $id);
        $totalJobGrade = $this->jobGradeService->countBy('company_id', $id);
        $totalJobGradeSet = $this->jobGradeSetService->countBy('company_id', $id);
        $company = $this->companyService->getCompany($id);

        return compact('totalJobGradeSet', 'totalJobGrade', 'totalDepartment', 'company', 'id');
    }

    /**
     * Update company
     */
    public function update($id)
    {
        $this->companyService->updateBy('id', $id, $_POST);
        $this->auditTrailService->insert([
            'description' => 'Edited Company details: ' . $id,
        ]);
        $this->flash('success', 'Company Name has been updated.');

        $this->redirect('company/' . $id . '/edit');
    }

    public function createDepartment($id)
    {
        return $this->createChild(
            'departmentService', 'department', "Added a department: " . $_POST['department'],
            'Create failed. Department already exist.', $id, 'company/' . $id . '/departments'
        );
    }

    public function updateDepartment($id, $departmentId)
    {
        return $this->updateChild(
            'departmentService', 'department', "updated a department: $departmentId",
            'Update failed. Department already exist.', $departmentId,
            'company/' . $id . '/departments'
        );
    }

    public function getJobGrades($id)
    {
        $data = $this->getMasterData($id);
        $data['jobGrades'] = $this->jobGradeService->getBy('company_id', $id);

        $this->render('company/job-grades/index', $data);
    }

    public function createJobGrade($id)
    {
        return $this->createChild(
            'jobGradeService', 'job_grade', "Added a job grade: " . $_POST['job_grade'],
            'Submission failed. Job Grade already exist.', $id, 'company/' . $id . '/job-grades'
        );
    }

    public function updateJobGrade($id, $jobGradeId)
    {
        return $this->updateChild(
            'jobGradeService', 'job_grade', "Updated a job grade: $jobGradeId",
            'Update failed. Job grade already exist.', $jobGradeId,
            'company/' . $id . '/job-grades'
        );
    }

    public function getJobGradeSets($id)
    {
        $data = $this->getMasterData($id);
        $data['jobGradeSets'] = $this->jobGradeSetService->getBy('company_id', $id);

        $this->render('company/job-grade-sets/index', $data);
    }

    public function createJobGradeSet($id)
    {
        return $this->createChild(
            'jobGradeSetService', 'job_grade_set', "Added a job grade set: " . $_POST['job_grade_set'],
            'Submission failed. Job Grade Set already exist.', $id, 'company/' . $id . '/job-grade-sets'
        );
    }

    /**
     * @param $id
     * @param $jobGradeSetId
     * @return false
     */
    public function updateJobGradeSet($id, $jobGradeSetId)
    {
        return $this->updateChild(
            'jobGradeSetService', 'job_grade_set', "Updated a job grade Set: $jobGradeSetId",
            "Update failed. Job grade Set already exist.", $jobGradeSetId,
            'company/' . $id . '/job-grade-sets'
        );
    }

    protected function updateChild($service, $fieldName, $description, $failDescription, $childId, $uri)
    {
        if ($this->{$service}->exists($fieldName, $_POST[$fieldName])) {
            $this->flash('danger', $failDescription);
            return $this->redirect($uri);
        }

        $this->{$service}->updateBy('id', $childId, [
            $fieldName => $_POST[$fieldName]
        ]);
        $this->auditTrailService->insert([
            'description' => $description,
        ]);
        $this->flash('success', 'Update successfully');
        return $this->redirect($uri);
    }

    protected function createChild($service, $fieldName, $description, $failDescription, $companyId, $uri)
    {
        $data = $_POST[$fieldName];
        if ($this->{$service}->exists($fieldName, $data)) {
            $this->flash('danger', $failDescription);
            return $this->redirect($uri);
        }

        $this->{$service}->insert([
            $fieldName => $data,
            'company_id' => $companyId,
            'date_created' => $this->jobGradeService->getCurrentDate()
        ]);
        $this->auditTrailService->insert([
            'description' => $description,
        ]);
        $this->flash('success', 'Create successfully');
        return $this->redirect($uri);
    }

    public function getBenefits($id)
    {
        $data = $this->getMasterData($id);
        $data['benefit'] = $this->benefitService->getFirstBy('company_id', $id);

        $this->render('company/benefits/index', $data);
    }

    public function updateBenefits($id)
    {
        $this->benefitService->updateBy('ID', $_POST['id'], $_POST);
        $this->flash('success', 'Benefits Eligibility has been updated.');

        return $this->redirect('company/' . $id . '/benefits');
    }

    public function updateMaintenance($id)
    {
        $data = $this->getMasterData($id);
        $data['maintenance'] = $this->maintenanceService->getFirstBy('company_id', $id);
        $data['maxLoan'] = $this->loadnMaxValueService->getFirstBy('company_id', $id);
        $data['jobGradeSets'] = $this->jobGradeSetService->pluck('job_grade_set', 'id')->toArray();

        $this->render('company/maintenance/index', $data);
    }
}

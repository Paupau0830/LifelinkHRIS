<?php


namespace App\Http\Controllers;


use App\Services\BenefitEligibilityeService;
use App\Services\CollegeService;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\DocumentService;
use App\Services\EmergencyContactService;
use App\Services\EmployeeInformationService;
use App\Services\GovernmentIdService;
use App\Services\IdService;
use App\Services\JobGradeService;
use App\Services\JobGradeSetService;
use App\Services\LeaveBalanceService;
use App\Services\PersonalInformationService;
use App\Services\PostGraduateService;
use App\Validators\CompanyValidator;

class EmployeeController extends Controller
{
    use CompanyValidator;

    protected $employeeInformationService;
    protected $personalInformationService;
    protected $companyService;
    protected $departmentService;
    protected $jobGradeSetService;
    protected $jobGradeService;
    protected $postGraduateService;
    protected $collegeService;
    protected $emergencyContactService;
    protected $governmentIdService;
    protected $idService;
    protected $documentService;
    protected $benefitsEligibilityService;
    protected $leaveBalanceService;

    public function __construct()
    {
        $this->employeeInformationService = new EmployeeInformationService();
        $this->personalInformationService = new PersonalInformationService();
        $this->companyService = new CompanyService();
        $this->departmentService = new DepartmentService();
        $this->jobGradeSetService = new JobGradeSetService();
        $this->jobGradeService = new JobGradeService();
        $this->postGraduateService = new PostGraduateService();
        $this->collegeService = new CollegeService();
        $this->emergencyContactService = new EmergencyContactService();
        $this->governmentIdService = new GovernmentIdService();
        $this->idService = new IdService();
        $this->documentService = new DocumentService();
        $this->benefitsEligibilityService = new BenefitEligibilityeService();
        $this->leaveBalanceService = new LeaveBalanceService();
        parent::__construct();
    }

    public function index()
    {
        $companyId = $_SESSION['hris_company_id'];

        $employees = $this->personalInformationService->getEmployees($companyId);

        $this->render('employee/index', compact('employees'));
    }


    public function create()
    {
        $companyId = $_SESSION['hris_company_id'];
        $approvers = $this->employeeInformationService->getApprove($companyId);
        $companies = $this->companyService->getCompanyById($companyId);
        $filters = ['company_id' => $companyId];
        $departments = $this->departmentService->pluck('department', 'ID', $filters);
        $jobGradeSets = $this->jobGradeSetService->pluck('job_grade_set', 'ID', $filters);
        $jobGrades = $this->jobGradeService->pluck('job_grade', 'ID', $filters);

        $this->render('employee/create', compact(
                'approvers', 'companies', 'departments', 'jobGradeSets', 'jobGrades')
        );
    }

    protected function mergeEmployeeNumber($data, $employeeNumber) {
        return array_replace($data, [
            'employee_number' => $employeeNumber
        ]);
    }

    public function store()
    {
        try {
            $data = $_POST;
            if ($this->emailExists($data['personal']['company_email'])) {
                $this->flash('danger', 'Email has been exists!');
                return $this->redirect('employee');
            }
            $employeeNumber = $this->personalInformationService->insert($data['personal']);

            $postGraduate = $this->mergeEmployeeNumber($data['post_graduate'], $employeeNumber);
            $this->postGraduateService->insert($postGraduate);

            $this->collegeService->insertColleges($data['college'], $employeeNumber);

            $this->emergencyContactService->insertContacts($data['emergency_contacts'], $employeeNumber);

            $governmentId = $this->mergeEmployeeNumber($data['government_id'], $employeeNumber);
            $this->governmentIdService->insert($governmentId);

            $this->idService->insertIds($data['ids'], $employeeNumber);

            $this->employeeInformationService->insertEmployeeInformation(
                $data['employment_information'], $employeeNumber
            );

            $this->documentService->uploadAndSave($_FILES['attachment'], $data['documents'], $employeeNumber);

            $this->benefitsEligibilityService->insertBenefit($data['benefits_eligibility'], $employeeNumber);
            $data['leaveBalances'] = [
              'employee_number' => $employeeNumber
            ];
            $this->leaveBalanceService->insert($data['leaveBalances']);

            $this->flash('success', 'Create successfully');

            $this->redirect('employee');
        } catch (\Exception $exception) {
            $this->flash('danger', 'Something went wrong, please correct all inputs');

            $this->redirect('employee');
        }

    }
}

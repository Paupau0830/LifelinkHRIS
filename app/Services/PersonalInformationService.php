<?php


namespace App\Services;


class PersonalInformationService extends BaseService
{
    protected $table = 'personal_information';

    protected $employeeInformationService;

    public function __construct()
    {
        $this->employeeInformationService = new EmployeeInformationService();
        parent::__construct();
    }

    /**
     * @param null $companyId
     * @return \Illuminate\Support\Collection
     */
    public function getEmployees($companyId = null)
    {
        $query = $this->table();
        $employeeNumbers = $companyId
            ? $this->employeeInformationService->getEmployeeNumbers($companyId)
            : [];
        $query->whereIn('employee_number', $employeeNumbers);

        return $query->get();
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $data['employee_number'] = $data['employee_number'] ?? 'xxx-xxx';
        $data['account_name'] = $data['account_name'] ?? ($data['first_name'] . ' ' . $data['middle_name'] . $data['last_name']);
        $data['age'] = $this->getAge($data['date_of_birth']);
        $data['date_created'] = $this->getCurrentDate();

        $personalId = parent::insert($data);
        $employeeNumber = sprintf("%04d", $personalId);
        $this->updateBy('ID', $personalId, [
            'employee_number' => $employeeNumber
        ]);

        return $employeeNumber;
    }

    public function getAge($dateOfBirth) {
        $bday = date('m-d-Y', strtotime($dateOfBirth));
        $bday = explode("-", $bday);

        return (date("md", date("U", mktime(0, 0, 0, $bday[0], $bday[1], $bday[2]))) > date("md")
            ? ((date("Y") - $bday[2]) - 1)
            : (date("Y") - $bday[2]));
    }
}

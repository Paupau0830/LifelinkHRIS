<?php


namespace App\Services;


class EmployeeInformationService extends BaseService
{
    protected $table = 'employment_information';

    public function getApprove($companyId)
    {
        return $this->table()->join(
            'personal_information',
            'employment_information.employee_number', '=', 'personal_information.employee_number'
        )
            ->where('employment_information.company', $companyId)
            ->where('is_approver', '=', 1)
            ->pluck('personal_information.account_name', 'employment_information.employee_number');
    }

    /**
     * @param $companyId
     * @return array
     */
    public function getEmployeeNumbers($companyId): array
    {
        return $this->table()
            ->where('company', $companyId)
            ->pluck('employee_number')
            ->toArray();
    }

    public function insertEmployeeInformation(array $data, $employeeNumber)
    {
        $data['filing'] = $data['filing'] ?? '0';
        $data['is_approver'] = $data['is_approver'] ?? '0';
        $data['position_number'] = 'PN-' . $employeeNumber;
        $data['employee_number'] = $employeeNumber;

        return parent::insert($data);
    }
}

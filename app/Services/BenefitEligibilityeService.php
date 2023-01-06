<?php


namespace App\Services;


class BenefitEligibilityeService extends BaseService
{
    protected $table = 'benefits_eligibility';

    public function insertBenefit(array $data, $employeeNumber)
    {
        $fields = [
            'parking',
            'gasoline',
            'car_maintenance',
            'medicine',
            'gym',
            'optical_allowance',
            'cep',
            'club_membership',
            'maternity',
            'others'
        ];
        foreach($fields as $field) {
            $data[$field] = $data[$field] ?? '0';
        }

        $data['employee_number'] = $employeeNumber;
        $this->insert($data);
    }
}

<?php


namespace App\Services;


class CollegeService extends BaseService
{
    protected $table = 'college';

    public function insertColleges(array $colleges, $employeeNumber)
    {
        foreach ($colleges['college'] as $key => $college) {
            $data['employee_number'] = $employeeNumber;
            $data['college'] = $college;
            $data['from_date'] = $colleges['from_date'][$key];
            $data['to_date'] = $colleges['to_date'][$key];
            $data['degree'] = $colleges['degree'][$key];
            $this->insert($data);
        }
    }
}

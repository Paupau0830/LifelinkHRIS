<?php


namespace App\Services;


class CompanyBenefitService extends BaseService
{
    protected $table = 'company_benefits';

    public function insert(array $data)
    {
        $data = array_merge($data, [
            'parking' => '0',
            'gasoline' => '0',
            'car_maintenance' => '0',
            'medicine' => '0',
            'gym' => '0',
            'optical_allowance' => '0',
            'cep' => '0',
            'club_membership' => '0',
            'maternity' => '0',
            'others' => '0'
        ]);
        parent::insert($data);
    }
}

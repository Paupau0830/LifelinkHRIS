<?php


namespace App\Services;


class LoanMaxValueService extends BaseService
{
    protected $table    = 'loan_max_value';

    public function insert(array $data)
    {
        $data = array_merge($data, [
            'others_max_value' => '0',
            'max_value' => '0'
        ]);
        parent::insert($data);
    }

}

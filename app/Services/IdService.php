<?php


namespace App\Services;


class IdService extends BaseService
{
    protected $table    = 'ids';

    public function insertIds(array $ids, $employeeNumber) {
        foreach ($ids['id_name'] as $key => $idName) {
            $data['employee_number'] = $employeeNumber;
            $data['id_name'] = $idName;
            $data['id_number'] = $ids['id_number'][$key];

            $this->insert($data);
        }
    }
}

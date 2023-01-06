<?php


namespace App\Services;


class AuditTrailService extends BaseService
{
    protected $table    = 'audit_trail';

    public function insert(array $data)
    {
        return parent::insert(array_replace($data, [
            'name' => $_SESSION['hris_account_name'],
            'date_created' => $this->getCurrentDate()
        ]));
    }
}

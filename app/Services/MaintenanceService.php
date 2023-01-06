<?php


namespace App\Services;


class MaintenanceService extends BaseService
{
    protected $table    = 'maintenance';

    public function insert(array $data)
    {
        $data = array_merge($data, [
            'logo' => 'default-logo.png',
            'banner' => 'default-banner.png',
        ]);
        parent::insert($data);
    }
}

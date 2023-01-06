<?php


namespace App\Services;


class LeaveBalanceService extends BaseService
{
    protected $table    = 'leave_balances';

    public function insert(array $data)
    {
        $data = array_replace($data, [
            'VL' => '0',
            'SL' => '0',
            'EL' => '5',
            'MLWOP' => '0',
            'PL' => '7',
            'BL' => '5',
            'SPL' => '7',
            'SLBW' => '60',
            'WFH' => '0',
            'OB' => '0',
            'CSR' => '0',
            'SLWOP' => '0',
            'VLWOP' => '0',
            'ECU' => '0',
            'SLBANK' => '0',
            'MNCS' => '105',
            'MM' => '60',
            'PLA' => '7'
        ]);

        return parent::insert($data);
    }
}

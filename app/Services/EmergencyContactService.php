<?php


namespace App\Services;


class EmergencyContactService extends BaseService
{
    protected $table = 'emergency_contacts';

    public function insertContacts($contacts, $employeeNumber)
    {
        foreach ($contacts['contact_name'] as $key => $contact) {
            $data['employee_number'] = $employeeNumber;
            $data['contact_name'] = $contact;
            $data['contact_number'] = $contacts['contact_number'][$key];
            $data['email_address'] = $contacts['email_address'][$key];
            $data['relationship'] = $contacts['relationship'][$key];
            $this->insert($data);
        }
    }
}

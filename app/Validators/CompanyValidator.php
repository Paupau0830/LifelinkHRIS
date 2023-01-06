<?php


namespace App\Validators;

use Illuminate\Database\Capsule\Manager as Capsule;

trait CompanyValidator
{
    public function emailExists($email)
    {
        return (new Capsule)
            ->table('personal_information')
            ->where('company_email', $email)
            ->exists();
    }
}

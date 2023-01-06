<?php


namespace App\Services;


class DocumentService extends BaseService
{
    protected $table = 'documents';

    public function uploadAndSave($files, $documents, $employeeNumber)
    {
        $attachment = $files['name'];
        $attachmentTmp = $files['tmp_name'];
        foreach ($attachment as $key => $file) {
            $name = md5($file);
            move_uploaded_file($attachmentTmp[$key], "uploads/" . $name);
            $data['attachment'] = $name;
            $data['employee_number'] = $employeeNumber;
            $data['remarks'] = $documents['attachment_remarks'][$key];
            $this->insert($data);
        }
    }
}

<?php


namespace App\Services;

use Illuminate\Support\Facades\DB;

class CompanyService extends BaseService
{
    protected $table = 'companies';
    protected $maintenanceService;
    protected $companyBenefitService;
    protected $loanMaxValueService;

    public function __construct()
    {
        $this->companyBenefitService = new CompanyBenefitService();
        $this->loanMaxValueService = new LoanMaxValueService();
        $this->maintenanceService = new MaintenanceService();
        parent::__construct();
    }

    public function getCompany($companyId)
    {
        return $this->table()
            ->find($companyId);
    }

    public function get()
    {
        return $this->table()->get();
    }

    public function insert(array $data)
    {
        $currentDate = $this->getCurrentDate();
        $data['date_created'] = $currentDate;
        $data['is_deleted'] = '0';
        $id = parent::insert($data);
        $this->companyBenefitService->insert([
            'company_id' => $id,
        ]);
        $this->loanMaxValueService->insert([
            'company_id' => $id,
            'date_created' => $currentDate,
        ]);
        $this->maintenanceService->insert([
            'company_id' => $id,
            'date_created' => $currentDate,
            'prefix' => $data['company_name']
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getCompanyById($id)
    {
        return $this->table()
            ->where('ID', $id)
            ->orderByDesc('ID')
            ->pluck('company_name', 'ID');
    }
}

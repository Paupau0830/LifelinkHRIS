<?php

namespace App\Http\Controllers;

use App\Services\AuditTrailService;
use App\Services\BaseService;
use App\Services\CompanyService;

include('inc/config.php');

class Controller
{
    protected $companyService;
    protected $auditTrailService;

    const VIEW_DIR = 'views/';
    const LAYOUTS_DIR = self::VIEW_DIR . 'layouts/';

    public function __construct()
    {
        $this->companyService = new CompanyService();
        $this->auditTrailService = new AuditTrailService();
        if (empty($_SESSION['hris_id'])) {
            header('Location: login');
        }
    }

    protected function render(string $view, array $data = [])
    {
        $data = array_replace($data, ['template' => THEMES]);
        extract($data);
        include_once self::LAYOUTS_DIR . 'template_start.php';
        include_once self::LAYOUTS_DIR . 'page_head.php';
        include self::LAYOUTS_DIR . 'template_scripts.php';
        include_once self::VIEW_DIR . $view . '.php';
        include self::LAYOUTS_DIR . 'page_footer.php';
        include_once self::LAYOUTS_DIR . 'template_end.php';
    }

    protected function redirect(string $url = 'index')
    {
        header('Location: ' . asset('/' . $url));

        return false;
    }

    protected function flash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
}

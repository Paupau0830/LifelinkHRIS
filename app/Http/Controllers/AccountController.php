<?php


namespace App\Http\Controllers;


use App\Services\UserService;

class AccountController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
        parent::__construct();
    }

    public function index()
    {
        $users = $this->userService->getBy('company_id', $_SESSION['hris_company_id']);

        $this->render('account/index', compact('users'));
    }

}

<?php

class Admins extends Controller
{
    private $adminModel;
    public function __construct()
    {
        $this->adminModel = $this->model('admin');
    }
    public function document()
    {
        $Message = '(API_Admins)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#ac878ddb-58e6-4ba6-82fc-48991a6ca4dd';
        userMessage($Status, $Message, $url);
        die();
    }
}
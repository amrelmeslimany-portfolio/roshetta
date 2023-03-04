<?php

class Pharmacists extends Controller
{
    private $pharmacistModel;
    public function __construct()
    {
        $this->pharmacistModel = $this->model('pharmacist');
    }
    public function document()
    {
        $Message = '(API_Pharmacists)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#e033cd90-661d-4a54-abe0-c1f2024e5f07';
        userMessage($Status, $Message, $url);
        die();
    }
}
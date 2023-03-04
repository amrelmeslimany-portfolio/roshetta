<?php

class Doctors extends Controller
{
    private $doctorModel;
    public function __construct()
    {
        $this->doctorModel = $this->model('doctor');
    }
    public function document()
    {
        $Message = '(API_Doctors)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#8dfbdfbd-2eb1-4bed-be0b-ab0e39dcb8b3';
        userMessage($Status, $Message, $url);
        die();
    }
}
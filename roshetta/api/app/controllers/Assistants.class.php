<?php

class Assistants extends Controller
{
    private $assistantModel;
    public function __construct()
    {
        $this->assistantModel = $this->model('assistant');
    }
    public function document()
    {
        $Message = '(API_Assistants)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#f5502a92-aae1-4466-8ce1-350b62f12f63';
        userMessage($Status, $Message, $url);
        die();
    }
}
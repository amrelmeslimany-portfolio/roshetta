<?php

class Pages extends Controller   // Extends The Controller
{
    public function document()
    {
        $Message = '(API_Pages)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA';
        userMessage($Status, $Message , $url);
        die();
    }

}

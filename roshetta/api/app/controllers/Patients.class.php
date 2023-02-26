<?php

class Patients extends Controller   // Extends The Controller
{
    private $patientModel;
    public function __construct()
    {
        $this->patientModel = $this->model('Patient'); //New Patient
    }
    public function index()
    {
        echo 'index';
    }

    public function edit($id)
    {
        $data = [
            "name" => "mohamed",
            "id" => $id
        ];
        $this->view('patients/edit',$data);
    }
}

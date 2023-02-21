<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {
            //Get From Patient Table

            $get_patient = $database->prepare("SELECT id,name,ssd,profile_img,role  FROM patient");
            $get_patient->execute();
            if ($get_patient->rowCount() > 0) {
                $data_patient = $get_patient->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_patient = null;
            }

            //Get From Doctor Table

            $get_doctor = $database->prepare("SELECT id,name,ssd,profile_img,role FROM doctor");
            $get_doctor->execute();
            if ($get_doctor->rowCount() > 0) {
                $data_doctor = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_doctor = null;
            }

            //Get From Pharmacist Table

            $get_pharmacist = $database->prepare("SELECT id,name,ssd,profile_img,role FROM pharmacist");
            $get_pharmacist->execute();
            if ($get_pharmacist->rowCount() > 0) {
                $data_pharmacist = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_pharmacist = null;
            }

            //Get From Assistant Table

            $get_assistant = $database->prepare("SELECT id,name,ssd,profile_img,role FROM assistant");
            $get_assistant->execute();
            if ($get_assistant->rowCount() > 0) {
                $data_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_assistant = null;
            }

            $data_all = [

                // Array Of All

                "data_patient"      => $data_patient,
                "data_doctor"       => $data_doctor,
                "data_pharmacist"   => $data_pharmacist,
                "data_assistant"    => $data_assistant

            ];

            $message = "تم جلب البيانات";
            print_r(json_encode(Message($data_all , $message , 200)));
    
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
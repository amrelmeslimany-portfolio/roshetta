<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {
            //Get From Patient Table

            $get_patient = $database->prepare("SELECT id as patient_id , patient_name , ssd as patient_ssd , profile_img  FROM patient");
            $get_patient->execute();
            if ($get_patient->rowCount() > 0) {
                $data_patient = $get_patient->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_patient = array(["Error" => "لا يوجد مريض"]);
            }

            //Get From Doctor Table

            $get_doctor = $database->prepare("SELECT id as doctor_id , doctor_name , ssd as doctor_ssd , profile_img FROM doctor");
            $get_doctor->execute();
            if ($get_doctor->rowCount() > 0) {
                $data_doctor = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_doctor = array(["Error" => "لا يوجد دكتور"]);
            }

            //Get From Pharmacist Table

            $get_pharmacist = $database->prepare("SELECT id as pharmacist_id , pharmacist_name , ssd as pharmacist_ssd , profile_img FROM pharmacist");
            $get_pharmacist->execute();
            if ($get_pharmacist->rowCount() > 0) {
                $data_pharmacist = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_pharmacist = array(["Error" => "لا يوجد صيدلى"]);
            }

            //Get From Assistant Table

            $get_assistant = $database->prepare("SELECT id as assistant_id , assistant_name , ssd as assistant_ssd , profile_img FROM assistant");
            $get_assistant->execute();
            if ($get_assistant->rowCount() > 0) {
                $data_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_assistant = array(["Error" => "لا يوجد مساعد"]);
            }

            $data_all = array(

                // Array Of All

                "data_patient"      => $data_patient,
                "data_doctor"       => $data_doctor,
                "data_pharmacist"   => $data_pharmacist,
                "data_assistant"    => $data_assistant

            );

            print_r(json_encode($data_all));
    
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية لعرض الاحصائيات"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
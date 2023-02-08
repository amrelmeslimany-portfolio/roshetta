<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor'])) {

        $doctor_id = $_SESSION['doctor']->id;

        //Check Activation Doctor

        $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
        $checkActivation->bindparam("id", $doctor_id);
        $checkActivation->execute();

        if ($checkActivation->rowCount() > 0) {

            $Activation = $checkActivation->fetchObject();

            if ($Activation->isactive == 1) {

                //Get From Clinic Table
                $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,start_working,end_working FROM clinic WHERE doctor_id = :doctor_id ORDER BY start_working ");
                $get_clinic->bindparam("doctor_id", $doctor_id);
                $get_clinic->execute();

                if ($get_clinic->rowCount() > 0) {

                    $data_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
                    $Message = "تم جلب البيانات ";
                    print_r(json_encode(Message($data_clinic, $Message, 200)));

                } else {
                    $Message = "لم يتم العثور على عيادة";
                    print_r(json_encode(Message(null, $Message, 204)));
                }
            } else {
                $Message = "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل المشرف";
                print_r(json_encode(Message(null,$Message,202)));
            }
        } else {
            $Message = "يجب تفعيل الحساب";
            print_r(json_encode(Message(null,$Message,202)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
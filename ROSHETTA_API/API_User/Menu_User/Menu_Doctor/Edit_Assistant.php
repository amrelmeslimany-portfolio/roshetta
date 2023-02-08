<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        $doctor_id = $_SESSION['doctor']->id;
        $clinic_id = $_SESSION['clinic']->id;

        if (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            //Filter Data 'Int'

            $assistant_id = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);

            //Check Assistant

            $check_assistant = $database->prepare("SELECT * FROM assistant WHERE assistant.id = :assistant_id ");
            $check_assistant->bindparam("assistant_id", $assistant_id);
            $check_assistant->execute();

            if ($check_assistant->rowCount() > 0) {

                // UpDate Clinic Table

                $Update = $database->prepare("UPDATE clinic SET assistant_id = :assistant_id WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

                $Update->bindparam("assistant_id", $assistant_id);
                $Update->bindparam("clinic_id", $clinic_id);
                $Update->bindparam("doctor_id", $doctor_id);

                if ($Update->execute()) {

                    if ($Update->rowCount() > 0) {

                        //Get From Clinic Table

                        $get_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");
                        $get_clinic->bindparam("clinic_id", $clinic_id);
                        $get_clinic->bindparam("doctor_id", $doctor_id);

                        if ($get_clinic->execute()) {

                            $get_clinic = $get_clinic->fetchObject();
                            $_SESSION['clinic'] = $get_clinic;

                            $Message = "تم التعديل بنجاح";
                            print_r(json_encode(Message(null, $Message, 201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل جلب البيانات";
                            print_r(json_encode(Message(null, $Message, 422)));
                        }
                    } else {
                        $Message = "فشل تعديل المساعد";
                        print_r(json_encode(Message(null, $Message, 422)));
                    }
                } else {
                    $Message = "فشل تعديل المساعد";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "رقم المساعد غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null , $message , 400)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
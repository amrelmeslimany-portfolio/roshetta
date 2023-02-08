<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        $clinic_id = $_SESSION['clinic']->id;
        $doctor_id = $_SESSION['doctor']->id;

        // Delete From Appointment Table

        $delete_assistant = $database->prepare("UPDATE clinic SET assistant_id = NULL  WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

        $delete_assistant->bindparam("clinic_id", $clinic_id);
        $delete_assistant->bindparam("doctor_id", $doctor_id);

        if ($delete_assistant->execute()) {

            if ($delete_assistant->rowCount() > 0) {

                //Get From Clinic Table

                $get_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

                $get_clinic->bindparam("clinic_id", $clinic_id);
                $get_clinic->bindparam("doctor_id", $doctor_id);

                if ($get_clinic->execute()) {

                    if ($get_clinic->rowCount() > 0 ) {

                    $get_clinic = $get_clinic->fetchObject();
                    $_SESSION['clinic'] = $get_clinic;

                    $Message = "تم الحذف بنجاح";
                    print_r(json_encode(Message(null, $Message, 200)));

                    } else {
                        $Message = "لم يتم العثور على عيادة";
                        print_r(json_encode(Message(null, $Message, 204)));
                    }
                } else {
                    $Message = "فشل جلب البيانات";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "فشل حذف المساعد";
                print_r(json_encode(Message(null, $Message, 422)));
            }
        } else {
            $Message = "فشل حذف المساعد";
            print_r(json_encode(Message(null, $Message, 422)));
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
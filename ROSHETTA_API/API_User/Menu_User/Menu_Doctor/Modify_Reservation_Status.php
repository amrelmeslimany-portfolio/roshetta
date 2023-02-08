<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        if (isset($_POST['appointment_id']) && !empty($_POST['appointment_id'])) {

            $appointment_id = filter_var($_POST['appointment_id'], FILTER_SANITIZE_NUMBER_INT);
            $clinic_id      = $_SESSION['clinic']->id;

            $check_appointment = $database->prepare("SELECT * FROM  appointment WHERE appointment.id = :appointment_id ");
            $check_appointment->bindparam("appointment_id", $appointment_id);
            $check_appointment->execute();

            if ($check_appointment->rowCount() > 0) {

                $update_appoint = $database->prepare("UPDATE appointment SET appoint_case = 2 WHERE  appointment.clinic_id = :clinic_id AND appointment.id = :appointment_id ");
                $update_appoint->bindparam("appointment_id", $appointment_id);
                $update_appoint->bindparam("clinic_id", $clinic_id);
                $update_appoint->execute();

                if ($update_appoint->rowCount() > 0) {

                    $Message = "تم الكشف بنجاح";
                    print_r(json_encode(Message(null, $Message, 201)));

                } else {
                    $Message = "فشل تعديل حالة الكشف";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "رقم الحجز غير صحيح";
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
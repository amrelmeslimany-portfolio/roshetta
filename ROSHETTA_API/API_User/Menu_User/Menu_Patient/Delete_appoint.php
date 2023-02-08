<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        if (isset($_POST['appointment_id']) && !empty($_POST['appointment_id'])) {

            $appointment_id = filter_var($_POST['appointment_id'], FILTER_SANITIZE_NUMBER_INT);
            $patient_id     = $_SESSION['patient']->id;

            // Delete From Appointment Table

            $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appointment.id = :appointment_id AND appointment.patient_id = :patient_id ");
            $delete_appoint->bindparam("appointment_id", $appointment_id);
            $delete_appoint->bindparam("patient_id", $patient_id);

            if ($delete_appoint->execute()) {

                if ($delete_appoint->rowCount() > 0) {

                    $Message = "تم الحذف بنجاح";
                    print_r(json_encode(Message(null, $Message, 200)));

                } else {
                    $Message = "فشل حذف الحجز";
                    print_r(json_encode(Message(null, $Message, 204)));
                }
            } else {
                $Message = "فشل حذف الحجز";
                print_r(json_encode(Message(null, $Message, 422)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
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
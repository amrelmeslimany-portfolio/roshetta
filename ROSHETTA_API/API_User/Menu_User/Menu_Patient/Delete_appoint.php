<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        if (isset($_POST['appointment_id']) && !empty($_POST['appointment_id'])) {

            require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

            $appointment_id = filter_var($_POST['appointment_id'], FILTER_SANITIZE_NUMBER_INT);
            $patient_id = $_SESSION['patient']->id;

            // Delete From Appointment Table

            $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appointment.id = :appointment_id AND appointment.patient_id = :patient_id ");

            $delete_appoint->bindparam("appointment_id", $appointment_id);
            $delete_appoint->bindparam("patient_id", $patient_id);

            if ($delete_appoint->execute()) {

                if ($delete_appoint->rowCount() > 0) {

                    print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "فشل حذف الحجز"]));
                }

            } else {
                print_r(json_encode(["Error" => "فشل حذف الحجز"]));
            }

        } else {
            print_r(json_encode(["Error" => "لم يتم العثورالحجز"]));
        }

    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
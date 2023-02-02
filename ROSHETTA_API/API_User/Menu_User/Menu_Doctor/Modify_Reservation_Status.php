<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

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

                    print_r(json_encode(["Message" => "تم الكشف بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "فشل تعديل حالة الكشف"]));
                }
            } else {
                print_r(json_encode(["Error" => "رقم الحجز غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على الحجز"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك بعرض تلك التفاصيل"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
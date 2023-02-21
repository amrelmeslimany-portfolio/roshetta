<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        $date = date('Y-m-d');

        $clinic_id = $_SESSION['clinic'];

        // Get From Patient And Appointment Table

        $get_reservation = $database->prepare("SELECT appointment.id as appointment_id , patient.id as patient_id , patient.name as patient_name , patient.phone_number  FROM  patient,appointment,clinic WHERE appoint_date = :appoint_date AND appointment.clinic_id = :clinic_id AND appointment.patient_id = patient.id AND appointment.appoint_case = 1 ORDER BY appointment.id ");
        $get_reservation->bindparam("clinic_id", $clinic_id);
        $get_reservation->bindparam("appoint_date", $date);

        if ($get_reservation->execute()) {

            if ($get_reservation->rowCount() > 0) {

                $data_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_reservation, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على حجوزات";
                print_r(json_encode(Message(null, $Message, 204)));
            }
        } else {
            $Message = "فشل جلب البيانات";
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
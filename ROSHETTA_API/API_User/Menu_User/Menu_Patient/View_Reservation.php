<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient']->id;

        // Get From Clinic And Appointment Table

        $get_reservation = $database->prepare("SELECT appointment.id as appointment_id,logo as clinic_logo,clinic_name,phone_number as clinic_phone_number,start_working,end_working,clinic_specialist,address as clinic_address,appoint_date  FROM  clinic,appointment  
                                                    WHERE clinic.id = appointment.clinic_id AND appointment.patient_id = :id  ORDER BY appointment.id DESC ");

        $get_reservation->bindparam("id", $id);
        $get_reservation->execute();

        if ($get_reservation->rowCount() > 0) {

            $get_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_reservation));

        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على حجز"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك القيام بالحجز"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
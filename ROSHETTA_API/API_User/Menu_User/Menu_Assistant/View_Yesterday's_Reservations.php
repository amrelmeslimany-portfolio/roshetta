<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if (isset($_SESSION['assistant']) && isset($_SESSION['clinic'])) {

    $time = time() - (1 * 24 * 60 * 60);
    $date = date('Y-m-d' , $time);

    $clinic_id = $_SESSION['clinic']->id;

    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $search = filter_var($_POST['search'], FILTER_SANITIZE_STRING);

        // Get From Patient And Appointment Table

        $get_reservation = $database->prepare("SELECT patient.id as patient_id , patient.patient_name , patient.phone_number FROM  patient,appointment,clinic WHERE   appoint_date = :appoint_date AND appointment.clinic_id = :clinic_id AND appointment.patient_id = patient.id  AND patient.patient_name LIKE :search ");

        $get_reservation->bindparam("clinic_id", $clinic_id);
        $get_reservation->bindparam("appoint_date", $date);
        $get_reservation->bindparam("search", $search);

        if ($get_reservation->execute()) {

            if($get_reservation->rowCount() > 0) {

                $get_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_reservation));

            } else {
                print_r(json_encode(["Error" => "لم يتم العثور على اي حجوزات"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }

    } else {

        // Get From Patient And Appointment Table

        $get_reservation = $database->prepare("SELECT appointment.id as appointment_id , patient.id as patient_id , patient.patient_name , patient.phone_number  FROM  patient,appointment,clinic WHERE   appoint_date = :appoint_date AND appointment.clinic_id = :clinic_id AND appointment.patient_id = patient.id AND appointment.appoint_case = 0 ORDER BY appointment.id ");

        $get_reservation->bindparam("clinic_id", $clinic_id);
        $get_reservation->bindparam("appoint_date", $date);

        if ($get_reservation->execute()) {

            if($get_reservation->rowCount() > 0) {

                $get_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_reservation));

            } else {
                print_r(json_encode(["Error" => "لم يتم العثور على اي حجوزات"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }
    }

} else {
    print_r(json_encode(["Error" => "غير مسموح لك عرض الحجز"]));
}
?>
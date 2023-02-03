<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['assistant']) && isset($_SESSION['clinic'])) {

        $time = time() - (1 * 24 * 60 * 60);
        $date = date('Y-m-d', $time);

        $clinic_id = $_SESSION['clinic']->id;

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];

            // Get From Patient And Appointment Table

            $get_reservation = $database->prepare("SELECT patient.id as patient_id , patient.patient_name , patient.phone_number FROM  patient,appointment,clinic WHERE   appoint_date = :appoint_date AND appointment.clinic_id = :clinic_id AND appointment.patient_id = patient.id  AND patient.patient_name = :search ");

            $get_reservation->bindparam("clinic_id", $clinic_id);
            $get_reservation->bindparam("appoint_date", $date);
            $get_reservation->bindparam("search", $search);

            if ($get_reservation->execute()) {

                if ($get_reservation->rowCount() > 0) {

                    $get_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);

                    print_r(json_encode($get_reservation));

                } else {
                    print_r(json_encode(["Error" => "لم يتم العثور على اي حجوزات"]));
                }
            } else {
                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك عرض الحجز"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
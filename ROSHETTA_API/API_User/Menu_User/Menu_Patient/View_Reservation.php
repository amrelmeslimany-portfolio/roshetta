<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['patient'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $id = $_SESSION['patient']->id;

    // Get From Clinic And Appointment Table

    $get_reservation = $database->prepare("SELECT appointment.id as appointment_id,logo as clinic_logo,clinic_name,phone_number as clinic_phone_number,start_working,end_working,clinic_specialist,address as clinic_address,appoint_date  FROM  clinic,appointment  
                                                    WHERE clinic.id = appointment.clinic_id AND appointment.patient_id = :id  ORDER BY appointment.id DESC ");

    $get_reservation->bindparam("id", $id);

    if ($get_reservation->execute()) {

        $get_reservation = $get_reservation->fetchAll(PDO::FETCH_ASSOC);

        print_r(json_encode($get_reservation));

    } else {
        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
    }

} else {
    print_r(json_encode(["Error" => "غير مسموح لك القيام بالحجز"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient'];

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);

            //Get From Clinic Table
            $get_clinic = $database->prepare("SELECT clinic.id as clinic_id,logo as clinic_logo,clinic.name as clinic_name,phone_number as clinic_phone_number,clinic_specialist,clinic_price,start_working,end_working,governorate,address as clinic_address FROM clinic,activation_place WHERE clinic.id = :clinic_id AND activation_place.isactive = 1 AND activation_place.clinic_id = clinic.id");
            $get_clinic->bindparam("clinic_id", $clinic_id);
            $get_clinic->execute();

            if ($get_clinic->rowCount() > 0) {

                $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

                $get_reservation = $database->prepare("SELECT * FROM appointment WHERE appointment.clinic_id = :clinic_id ");
                $get_reservation->bindparam("clinic_id", $clinic_id);
                $get_reservation->execute();

                if ($get_reservation->rowCount() >= 0) {
                    $get_reservation = $get_reservation->rowCount();
                } else {
                    //***** */
                }

                $get_reservation_patient = $database->prepare("SELECT * FROM appointment WHERE appointment.patient_id = :id AND appointment.clinic_id = :clinic_id");
                $get_reservation_patient->bindparam("id", $id);
                $get_reservation_patient->bindparam("clinic_id", $clinic_id);
                $get_reservation_patient->execute();

                if ($get_reservation_patient->rowCount() >= 0) {
                    $get_reservation_patient = $get_reservation_patient->rowCount();
                } else {
                    //**** */
                }

                $data_clinic = [
                    "clinic_details"                => $get_clinic,
                    "number_reservation_clinic"     => $get_reservation,
                    "number_reservation_patient"    => $get_reservation_patient
                ];

                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_clinic, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على بيانات";
                print_r(json_encode(Message(null, $Message, 204)));
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
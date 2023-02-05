<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient']->id;

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);

            //Get From Clinic Table
            $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,phone_number as clinic_phone_number,clinic_specialist,clinic_price,start_working,end_working,governorate,address as cilinic_address FROM clinic WHERE clinic.id = :clinic_id");
            $get_clinic->bindparam("clinic_id", $clinic_id);
            $get_clinic->execute();

            if ($get_clinic->rowCount() > 0) {

                $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

                $get_reservation = $database->prepare("SELECT * FROM appointment WHERE appointment.clinic_id = :clinic_id ");
                $get_reservation->bindparam("clinic_id", $clinic_id);
                $get_reservation->execute();

                if ($get_reservation->rowCount() > 0) {
                    $get_reservation = $get_reservation->rowCount();
                } else {
                    $get_reservation = 0;
                }

                $get_reservation_patient = $database->prepare("SELECT * FROM appointment WHERE appointment.patient_id = :id");
                $get_reservation_patient->bindparam("id", $id);
                $get_reservation_patient->execute();

                if ($get_reservation_patient->rowCount() > 0) {
                    $get_reservation_patient = $get_reservation_patient->rowCount();
                } else {
                    $get_reservation_patient = 0;
                }

                $data_clinic = array(
                    "clinic_details" => $get_clinic,
                    "number_reservation_clinic" => $get_reservation,
                    "number_reservation_patient" => $get_reservation_patient
                );

                print_r(json_encode($data_clinic));

            } else {
                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على معرف العيادة"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
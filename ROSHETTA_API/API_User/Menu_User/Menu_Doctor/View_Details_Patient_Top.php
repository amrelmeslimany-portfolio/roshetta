<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            $patient_id = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);

            $check_patient = $database->prepare("SELECT patient.id as patient_id , patient.profile_img , patient.patient_name FROM  patient WHERE patient.id = :patient_id ");
            $check_patient->bindparam("patient_id", $patient_id);
            $check_patient->execute();

            if ($check_patient->rowCount() > 0) {

                $patient_data = $check_patient->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($patient_data));

            } else {
                print_r(json_encode(["Error" => "رقم المريض غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على المريض"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك بعرض تلك التفاصيل"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
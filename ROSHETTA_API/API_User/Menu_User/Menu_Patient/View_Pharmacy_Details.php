<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient']->id;

        if (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            $pharmacy_id = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Pharmacy Table

            $get_pharmacy = $database->prepare("SELECT id as pharmacy_id,logo as pharmacy_logo,pharmacy_name,phone_number as pharmacy_phone_number,start_working,end_working,governorate,address as pharmacy_address FROM pharmacy WHERE pharmacy.id = :pharmacy_id");
            $get_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
            $get_pharmacy->execute();

            if ($get_pharmacy->rowCount() > 0) {

                $get_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

                $get_prescript = $database->prepare("SELECT * FROM pharmacy_prescript WHERE pharmacy_prescript.pharmacy_id = :pharmacy_id");
                $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
                $get_prescript->execute();

                if ($get_prescript->rowCount() > 0) {
                    $get_prescript = $get_prescript->rowCount();
                } else {
                    $get_prescript = 0;
                }

                $get_prescript_patient = $database->prepare("SELECT * FROM pharmacy_prescript,prescript WHERE pharmacy_prescript.pharmacy_id = :pharmacy_id AND pharmacy_prescript.prescript_id = prescript.id AND prescript.patient_id = :id");
                $get_prescript_patient->bindparam("pharmacy_id", $pharmacy_id);
                $get_prescript_patient->bindparam("id", $id);
                $get_prescript_patient->execute();

                if ($get_prescript_patient->rowCount() > 0) {
                    $get_prescript_patient = $get_prescript_patient->rowCount();
                } else {
                    $get_prescript_patient = 0;
                }

                $data_pharmacy = array(
                    "data_pharmacy" => $get_pharmacy,
                    "number_prescript_pharmacy" => $get_prescript,
                    "number_prescript_patient" => $get_prescript_patient
                );

                print_r(json_encode($data_pharmacy));

            } else {
                print_r(json_encode(["Error" => "لم يتم العثور على صيدلية"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على معرف الصيدلية"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
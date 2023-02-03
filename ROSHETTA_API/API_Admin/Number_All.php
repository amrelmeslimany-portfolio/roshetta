<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        //Get From Admin Table

        $count_admin = $database->prepare("SELECT * FROM admin");
        $count_admin->execute();
        if ($count_admin->rowCount() >= 0) {
            $number_admin = $count_admin->rowCount();

            //Get From Patient Table

            $count_patient = $database->prepare("SELECT * FROM patient");
            $count_patient->execute();
            if ($count_patient->rowCount() >= 0) {
                $number_patient = $count_patient->rowCount();
            } //** */

            //Get From Doctor Table

            $count_doctor = $database->prepare("SELECT * FROM doctor");
            $count_doctor->execute();
            if ($count_doctor->rowCount() >= 0) {
                $number_doctor = $count_doctor->rowCount();
            } //** */

            //Get From Pharmacist Table

            $count_pharmacist = $database->prepare("SELECT * FROM pharmacist");
            $count_pharmacist->execute();
            if ($count_pharmacist->rowCount() >= 0) {
                $number_pharmacist = $count_pharmacist->rowCount();
            } //** */

            //Get From Assistant Table

            $count_assistant = $database->prepare("SELECT * FROM assistant");
            $count_assistant->execute();
            if ($count_assistant->rowCount() >= 0) {
                $number_assistant = $count_assistant->rowCount();
            } //** */

            //Get From Clinic Table

            $count_clinic = $database->prepare("SELECT * FROM clinic");
            $count_clinic->execute();
            if ($count_clinic->rowCount() >= 0) {
                $number_clinic = $count_clinic->rowCount();
            } //** */

            //Get From Pharmacy Table

            $count_pharmacy = $database->prepare("SELECT * FROM pharmacy");
            $count_pharmacy->execute();
            if ($count_pharmacy->rowCount() >= 0) {
                $number_pharmacy = $count_pharmacy->rowCount();
            } //** */

            $number_all = array(
                // Array Of All

                "number_of_admin"       => $number_admin,
                "number_of_patient"     => $number_patient,
                "number_of_doctor"      => $number_doctor,
                "number_of_pharmacist"  => $number_pharmacist,
                "number_of_assistant"   => $number_assistant,
                "number_of_clinic"      => $number_clinic,
                "number_of_pharmacy"    => $number_pharmacy

            );

            print_r(json_encode($number_all));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية لعرض الاحصائيات"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (isset($_SESSION['patient'])) {

    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $search_data = $_POST['search'];

        //Get From Clinic Table
        $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,phone_number as clinic_phone_number,clinic_specialist,clinic_price,start_working,end_working,governorate,address as cilinic_address FROM clinic WHERE clinic_specialist = :clinic_specialist ");
        $get_clinic->bindparam("clinic_specialist", $search_data);
        $get_clinic->execute();

        if ($get_clinic->rowCount() > 0 ) {

            $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_clinic));

        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }

    } else {
        //Get From Clinic Table
        $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,phone_number as clinic_phone_number,clinic_specialist,clinic_price,start_working,end_working,governorate,address as cilinic_address FROM clinic ");
        $get_clinic->execute();

        if ($get_clinic->rowCount() > 0) {

            $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_clinic));

        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }
    }
} else {
    print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
}
?>
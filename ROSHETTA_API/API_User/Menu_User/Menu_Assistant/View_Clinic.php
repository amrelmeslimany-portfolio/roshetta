<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['assistant'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $assistant_id = $_SESSION['assistant']->id;

    //Get From Clinic Table
    $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,start_working,end_working FROM clinic WHERE assistant_id = :assistant_id ORDER BY start_working ");

    $get_clinic->bindparam("assistant_id", $assistant_id);

    if ($get_clinic->execute()) {

        if ($get_clinic->rowCount() > 0) {

            $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_clinic));
        } else {
            print_r(json_encode(["Error" => "ليس لديك اي عيادة"]));
        }

    } else {
        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
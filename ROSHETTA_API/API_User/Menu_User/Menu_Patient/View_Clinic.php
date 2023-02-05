<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        //Get From Clinic Table
        $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,clinic_specialist,governorate FROM clinic ");
        $get_clinic->execute();

        if ($get_clinic->rowCount() > 0) {

            $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_clinic));

        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
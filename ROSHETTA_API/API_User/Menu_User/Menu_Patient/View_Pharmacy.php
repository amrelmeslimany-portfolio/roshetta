<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        // Get From Pharmacy Table

        $get_pharmacy = $database->prepare("SELECT logo as pharmacy_logo,pharmacy_name,phone_number as pharmacy_phone_number,start_working,end_working,governorate,address as pharmacy_address FROM pharmacy ");
        $get_pharmacy->execute();

        if ($get_pharmacy->rowCount() > 0) {

            $get_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_pharmacy));

        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على صيدلية"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
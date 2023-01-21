<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['patient'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    // Get From Pharmacy Table

    $get_pharmacy = $database->prepare("SELECT logo as pharmacy_logo,pharmacy_name,phone_number as pharmacy_phone_number,start_working,end_working,governorate,address as pharmacy_address FROM pharmacy ");

    if ($get_pharmacy->execute()) {

        $get_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

        print_r(json_encode($get_pharmacy));

    } else {
        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
    }

} else {
    print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
}
?>
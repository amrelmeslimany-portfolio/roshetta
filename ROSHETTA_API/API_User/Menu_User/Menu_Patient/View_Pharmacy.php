<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        // Get From Pharmacy Table

        $get_pharmacy = $database->prepare("SELECT id as pharmacy_id,logo as pharmacy_logo,pharmacy_name,phone_number as pharmacy_phone_number,governorate FROM pharmacy ");
        $get_pharmacy->execute();

        if ($get_pharmacy->rowCount() > 0) {

            $data_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

            $Message = "تم جلب البيانات ";
            print_r(json_encode(Message($data_pharmacy, $Message, 200)));

        } else {
            $Message = "لم يتم العثور على صيدلية";
            print_r(json_encode(Message(null, $Message, 204)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
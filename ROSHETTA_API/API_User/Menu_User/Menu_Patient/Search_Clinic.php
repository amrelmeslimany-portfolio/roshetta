<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        if (isset($_GET['search']) && ! empty($_GET['search'])) {

            $search_data = $_GET['search'];

            //Get From Clinic Table
            $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic.name as clinic_name,phone_number as clinic_phone_number,clinic_specialist,clinic_price,start_working,end_working,governorate,address as clinic_address FROM clinic WHERE clinic_specialist = :clinic_specialist ");
            $get_clinic->bindparam("clinic_specialist", $search_data);
            $get_clinic->execute();

            if ($get_clinic->rowCount() > 0 ) {

                $data_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_clinic, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على بيانات";
                print_r(json_encode(Message(null, $Message, 204)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
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
<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];

            //Get From Clinic Table

            $get_clinic = $database->prepare("SELECT id,logo,name,isactive as status FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND (clinic.name = :search OR clinic.ser_id = :search)");
            $get_clinic->bindparam("search", $search);
            $get_clinic->execute();
            if ($get_clinic->rowCount() > 0 ) {

                $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

            } else {
                $get_clinic = null;
            }

            //Get From Pharmacy Table
            $get_pharmacy = $database->prepare("SELECT id,logo,name,isactive as status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND (pharmacy.name = :search OR pharmacy.ser_id = :search)");
            $get_pharmacy->bindparam("search", $search);
            $get_pharmacy->execute();
            if ($get_pharmacy->rowCount() > 0 ) {

                $get_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

            } else {
                $get_pharmacy = null;
            }
            
            $data_all = [

                "clinic"   => $get_clinic,
                "pharmacy" => $get_pharmacy
            ];

            $message = "تم جلب البيانات";
            print_r(json_encode(Message($data_all , $message , 200)));

        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
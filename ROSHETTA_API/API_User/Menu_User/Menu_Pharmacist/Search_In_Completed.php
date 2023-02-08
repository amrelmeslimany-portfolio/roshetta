<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        $pharmacy_id = $_SESSION['pharmacy']->id;

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];

            // Get From Prescript,Patient,Pharmacy_Prescript,Pharmacy  Table

            // If Input Search Name OR SSD OR Ser_id

            $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,prescript.ser_id as prescript_ser_id,date_pay,patient_name  FROM prescript,patient,pharmacy_prescript,pharmacy 
                                                        WHERE prescript.patient_id = patient.id AND pharmacy.id = :pharmacy_id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id AND (patient.patient_name = :search OR patient.ssd = :search OR prescript.ser_id = :search)  ORDER BY date_pay DESC ");

            $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
            $get_prescript->bindparam("search", $search);
            $get_prescript->execute();

            if ($get_prescript->rowCount() > 0) {

                $data_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_prescript, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على اي روشتة";
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
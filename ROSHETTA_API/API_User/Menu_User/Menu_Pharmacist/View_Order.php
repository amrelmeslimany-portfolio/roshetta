<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        $pharmacy_id    = $_SESSION['pharmacy']->id;
        $time           = date("h:i", (time() - 1 * 24 * 60 * 60));

        //Delete Order From Pharmacy_Order Table

        $delete_order = $database->prepare("DELETE FROM pharmacy_order WHERE pharmacy_order.time = :time");
        $delete_order->bindparam("time", $time);
        $delete_order->execute();

        if($delete_order->rowCount() > 0 ) {
            //*** */
        } else {
            //*** */
        }

        // Get From Pharmacy_Order  Table

        $get_order = $database->prepare("SELECT pharmacy_order.prescript_id , prescript.ser_id , patient.patient_name , patient.phone_number FROM pharmacy_order , patient , prescript , pharmacy WHERE pharmacy.id = :pharmacy_id AND pharmacy_order.pharmacy_id = pharmacy.id AND pharmacy_order.prescript_id = prescript.id AND prescript.patient_id = patient.id ORDER BY pharmacy_order.time DESC");

        $get_order->bindparam("pharmacy_id", $pharmacy_id);
        $get_order->execute();

        if ($get_order->rowCount() > 0) {

            $data_order = $get_order->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($data_order));

        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على اي طلب"]));
        }
        
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك عرض الطلبات"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['prescript_id'])   && !empty($_POST['prescript_id'])
            && isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])
        ) {
            $time           = date("h:i");
            $pharmacy_id    = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT); //Filter 'Int'
            $patient_id     = $_SESSION['patient']->id;
            $prescript_id   = $_POST['prescript_id'];

            $check_pharmacy = $database->prepare("SELECT * FROM pharmacy WHERE pharmacy.id = :pharmacy_id");
            $check_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
            $check_pharmacy->execute();

            if ($check_pharmacy->rowCount() > 0 ) {

                foreach ($prescript_id as $value) {

                    $check_prescript = $database->prepare("SELECT * FROM prescript WHERE prescript.id = :prescript_id");
                    $check_prescript->bindparam("prescript_id", $value['id']);
                    $check_prescript->execute();

                    if ($check_prescript->rowCount() > 0 ) {

                        //Add To Pharmacy_Order Table
    
                        $add_order = $database->prepare("INSERT INTO pharmacy_order(time,patient_id,prescript_id,pharmacy_id)
                        VALUES(:time,:patient_id,:prescript_id,:pharmacy_id)");

                        $add_order->bindparam("time", $time);
                        $add_order->bindparam("patient_id", $patient_id);
                        $add_order->bindparam("prescript_id", $value['id']);
                        $add_order->bindparam("pharmacy_id", $pharmacy_id);
                        $add_order->execute();

                        if ($add_order->rowCount() > 0) {
                            $message = "تم اضافة الطلب بنجاح";
                        } else {
                            $message = "فشل اضافة الطلب";
                        }

                        $error =  null;
                        
                    } else{
                        $error = "معرف الروشتة غير صحيح";
                    }
                }

                $Message = [
                    "Success" => $message,
                    "Error"   => $error 
                ];

                print_r(json_encode(Message(null, $Message, 201)));

            }else{
                $Message = "معرف الصيدلية غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor'])) { //If Doctor

        // Delete From Chat When Message has Old

        $delete_message = $database->prepare("DELETE FROM chat WHERE time = :time_delete");
        $delete_message->bindparam("time_delete", $time_delete);
        $delete_message->execute();

        if ($delete_message->rowCount() > 0) {

            //Get From Chat Table

            $get_message = $database->prepare("SELECT name,time,profile_img,message FROM chat");
            $get_message->execute();

            if ($get_message->rowCount() > 0) {

                $data_message = $get_message->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_message, $Message, 200)));
                
            } else {
                $Message = "كن أول من يرسل رسالة";
                print_r(json_encode(Message(null, $Message, 204)));
            }

        } else {

            //Get From Chat Table

            $get_message = $database->prepare("SELECT name,time,profile_img,message FROM chat");
            $get_message->execute();

            if ($get_message->rowCount() > 0) {

                $data_message = $get_message->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_message, $Message, 200)));

            } else {
                $Message = "كن أول من يرسل رسالة";
                print_r(json_encode(Message(null, $Message, 204)));
            }
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
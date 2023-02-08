<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        // Get From Message Table

        $get_message = $database->prepare("SELECT name,email,message,role FROM message WHERE m_case = 0  ORDER BY time DESC");
        $get_message->execute();

        if ($get_message->rowCount() > 0) {

            $data_message = $get_message->fetchAll(PDO::FETCH_ASSOC);
            $message = 'تم جلب البيانات بنجاح';
            print_r(json_encode(Message($data_message , $message , 200)));
            
        } else {
            $Message = "لا يوجد رسائل";
            print_r(json_encode(Message(null, $Message, 204)));
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
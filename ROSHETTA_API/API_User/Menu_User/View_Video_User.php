<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (  //If Found SESSION
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $type = 'patient';
        } elseif (isset($_SESSION['doctor'])) {
            $type = 'doctor';
        } elseif (isset($_SESSION['pharmacist'])) {
            $type = 'pharmacist';
        } elseif (isset($_SESSION['assistant'])) {
            $type = 'assistant';
        } else {
            $type = '';
        }

        //Get Video For User

        $get_video = $database->prepare("SELECT video FROM video WHERE type = :type");
        $get_video->bindparam("type", $type);
        $get_video->execute();

        if ($get_video->rowCount() > 0) {

            $data_video = $get_video->fetchAll(PDO::FETCH_ASSOC);
            $message = 'تم جلب البيانات بنجاح';
            print_r(json_encode(Message($data_video , $message , 200)));

        } else {
            $message = "لا يوجد فيديو";
            print_r(json_encode(Message(null , $message , 204)));
        }
    } else {
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
} 
?>
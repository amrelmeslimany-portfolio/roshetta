<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        // Get From Video Table

        $get_video = $database->prepare("SELECT video,type FROM video");
        $get_video->execute();

        if ($get_video->rowCount() > 0) {

            $data_video = $get_video->fetchAll(PDO::FETCH_ASSOC);

            $message = "تم جلب البيانات";
            print_r(json_encode(Message($data_video , $message , 200)));

        } else {
            $message = "لا يوجد فيديوهات";
            print_r(json_encode(Message(null , $message , 204)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //Allow Access Via 'GET' Method 

    if (isset($_SESSION['clinic'])) {

        unset($_SESSION['clinic']);

        $Message = "تم تسجيل الخروج";
        print_r(json_encode(Message(null, $Message, 200)));

    } elseif (isset($_SESSION['pharmacy'])) {

        unset($_SESSION['pharmacy']);

        $Message = "تم تسجيل الخروج";
        print_r(json_encode(Message(null, $Message, 200)));

    } else {
        //**** */
    }
} else {
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
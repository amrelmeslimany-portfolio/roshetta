<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //Allow Access Via 'GET' Method 

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
        || isset($_SESSION['admin'])
    ) {
        session_unset();
        session_destroy();

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
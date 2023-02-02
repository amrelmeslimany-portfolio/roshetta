<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

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

        print_r(json_encode(["Message" => "تم تسجيل الخروج"]));

    } else {
        print_r(json_encode(["Message" => "لا يوجد مستخدمين تسجيل الخروج"]));
    }
} else {
    //***** */
}
?>
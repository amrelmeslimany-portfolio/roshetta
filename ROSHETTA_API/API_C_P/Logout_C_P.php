<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //Allow Access Via 'GET' Method 

    if (isset($_SESSION['clinic'])) {

        unset($_SESSION['clinic']);

        print_r(json_encode(["Message" => "تم تسجيل الخروج"]));

    } elseif (isset($_SESSION['pharmacy'])) {

        unset($_SESSION['pharmacy']);

        print_r(json_encode(["Message" => "تم تسجيل الخروج"]));

    } else {
        print_r(json_encode(["Message" => "لا يوجد مستخدمين لتسجيل الخروج"]));
    }
} else {
    //***** */
}
?>
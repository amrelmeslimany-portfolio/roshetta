<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        //Print Admin Data From Session

        $admin_name     = $_SESSION['admin']->admin_name;
        $ssd            = $_SESSION['admin']->ssd;
        $email          = $_SESSION['admin']->email;
        $phone_number   = $_SESSION['admin']->phone_number;
        $gender         = $_SESSION['admin']->gender;
        $birth_date     = $_SESSION['admin']->birth_date;
        $profile_img    = $_SESSION['admin']->profile_img;

        $admin_data = array(

            "admin_name"    => $admin_name,
            "ssd"           => $ssd,
            "email"         => $email,
            "phone_number"  => $phone_number,
            "gender"        => $gender,
            "birth_date"    => $birth_date,
            "profile_img"   => $profile_img

        );

        print_r(json_encode($admin_data));
        
    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) { //If Patient

            $patient_name   = $_SESSION['patient']->patient_name;
            $ssd            = $_SESSION['patient']->ssd;
            $profile_img    = $_SESSION['patient']->profile_img;

            $top_menu_data = array(

                "patient_name"  => $patient_name,
                "ssd"           => $ssd,
                "profile_img"   => $profile_img

            );

            print_r(json_encode($top_menu_data));


        } elseif (isset($_SESSION['doctor'])) { //If Doctor

            $doctor_name    = $_SESSION['doctor']->doctor_name;
            $ssd            = $_SESSION['doctor']->ssd;
            $profile_img    = $_SESSION['doctor']->profile_img;

            $top_menu_data = array(

                "doctor_name"   => $doctor_name,
                "ssd"           => $ssd,
                "profile_img"   => $profile_img

            );

            print_r(json_encode($top_menu_data));

        } elseif (isset($_SESSION['pharmacist'])) { //If Pharmacist

            $pharmacist_name    = $_SESSION['pharmacist']->pharmacist_name;
            $ssd                = $_SESSION['pharmacist']->ssd;
            $profile_img        = $_SESSION['pharmacist']->profile_img;

            $top_menu_data = array(

                "pharmacist_name"   => $pharmacist_name,
                "ssd"               => $ssd,
                "profile_img"       => $profile_img

            );

            print_r(json_encode($top_menu_data));

        } elseif (isset($_SESSION['assistant'])) { //If Assistant

            $assistant_name = $_SESSION['assistant']->assistant_name;
            $ssd            = $_SESSION['assistant']->ssd;
            $profile_img    = $_SESSION['assistant']->profile_img;

            $top_menu_data = array(

                "assistant_name"    => $assistant_name,
                "ssd"               => $ssd,
                "profile_img"       => $profile_img

            );

            print_r(json_encode($top_menu_data));

        } else {
            print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
} 
?>
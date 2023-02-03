<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['pharmacy']) || isset($_SESSION['pharmacist'])) {

        if (isset($_SESSION['pharmacy'])) {

            //Print Pharmacy Data From Session

            $logo               = $_SESSION['pharmacy']->logo;
            $pharmacy_name      = $_SESSION['pharmacy']->pharmacy_name;
            $phone_number       = $_SESSION['pharmacy']->phone_number;
            $owner              = $_SESSION['pharmacy']->owner;
            $start_working      = $_SESSION['pharmacy']->start_working;
            $end_working        = $_SESSION['pharmacy']->end_working;
            $governorate        = $_SESSION['pharmacy']->governorate;
            $address            = $_SESSION['pharmacy']->address;


            $pharmacy_data = array(

                "logo"              => $logo,
                "pharmacy_name"     => $pharmacy_name,
                "phone_number"      => $phone_number,
                "owner"             => $owner,
                "start_working"     => $start_working,
                "end_working"       => $end_working,
                "governorate"       => $governorate,
                "address"           => $address

            );

            print_r(json_encode($pharmacy_data));

        } else {
            print_r(json_encode(["Error" => "فشل العثور على صيدلية"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
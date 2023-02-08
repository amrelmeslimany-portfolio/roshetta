<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) || isset($_SESSION['assistant'])) {

        if (isset($_SESSION['clinic'])) {

            //Print Clinic Data From Session

            $logo               = $_SESSION['clinic']->logo;
            $clinic_name        = $_SESSION['clinic']->clinic_name;
            $clinic_specialist  = $_SESSION['clinic']->clinic_specialist;
            $phone_number       = $_SESSION['clinic']->phone_number;
            $owner              = $_SESSION['clinic']->owner;
            $clinic_price       = $_SESSION['clinic']->clinic_price;
            $start_working      = $_SESSION['clinic']->start_working;
            $end_working        = $_SESSION['clinic']->end_working;
            $governorate        = $_SESSION['clinic']->governorate;
            $address            = $_SESSION['clinic']->address;


            $clinic_data = [

                "logo"              => $logo,
                "clinic_name"       => $clinic_name,
                "clinic_specialist" => $clinic_specialist,
                "phone_number"      => $phone_number,
                "owner"             => $owner,
                "clinic_price"      => $clinic_price,
                "start_working"     => $start_working,
                "end_working"       => $end_working,
                "governorate"       => $governorate,
                "address"           => $address

            ];

            $Message = "تم جلب البيانات";
            print_r(json_encode(Message($clinic_data,$Message,200)));

        } else {
            $Message = "فشل العثور على مستخدم";
            print_r(json_encode(Message(null,$Message,401)));
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
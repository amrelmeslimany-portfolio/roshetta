<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {

             //Print Patient Data From Session

             $patient_name   = $_SESSION['patient']->patient_name;
             $ssd            = $_SESSION['patient']->ssd;
             $email          = $_SESSION['patient']->email;
             $phone_number   = $_SESSION['patient']->phone_number;
             $gender         = $_SESSION['patient']->gender;
             $birth_date     = $_SESSION['patient']->birth_date;
             $weight         = $_SESSION['patient']->weight;
             $height         = $_SESSION['patient']->height;
             $governorate    = $_SESSION['patient']->governorate;
             $profile_img    = $_SESSION['patient']->profile_img;
 
             $patient_data = [
 
                 "name"          => $patient_name,
                 "ssd"           => $ssd,
                 "email"         => $email,
                 "phone_number"  => $phone_number,
                 "gender"        => $gender,
                 "birth_date"    => $birth_date,
                 "weight"        => $weight,
                 "height"        => $height,
                 "governorate"   => $governorate,
                 "profile_img"   => $profile_img
 
             ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($patient_data , $message , 200)));

        } elseif (isset($_SESSION['doctor'])) {

            //Print Doctor Data From Session

            $doctor_name    = $_SESSION['doctor']->doctor_name;
            $ssd            = $_SESSION['doctor']->ssd;
            $email          = $_SESSION['doctor']->email;
            $phone_number   = $_SESSION['doctor']->phone_number;
            $gender         = $_SESSION['doctor']->gender;
            $birth_date     = $_SESSION['doctor']->birth_date;
            $specialist     = $_SESSION['doctor']->specialist;
            $governorate    = $_SESSION['doctor']->governorate;
            $profile_img    = $_SESSION['doctor']->profile_img;

            $doctor_data = [

                "name"          => $doctor_name,
                "ssd"           => $ssd,
                "email"         => $email,
                "phone_number"  => $phone_number,
                "gender"        => $gender,
                "birth_date"    => $birth_date,
                "specialist"    => $specialist,
                "governorate"   => $governorate,
                "profile_img"   => $profile_img

            ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($doctor_data , $message , 200)));

        } elseif (isset($_SESSION['pharmacist'])) {

            //Print Pharmacist Data From Session

            $pharmacist_name    = $_SESSION['pharmacist']->pharmacist_name;
            $ssd                = $_SESSION['pharmacist']->ssd;
            $email              = $_SESSION['pharmacist']->email;
            $phone_number       = $_SESSION['pharmacist']->phone_number;
            $gender             = $_SESSION['pharmacist']->gender;
            $birth_date         = $_SESSION['pharmacist']->birth_date;
            $governorate        = $_SESSION['pharmacist']->governorate;
            $profile_img        = $_SESSION['pharmacist']->profile_img;

            $pharmacist_data = [

                "name"              => $pharmacist_name,
                "ssd"               => $ssd,
                "email"             => $email,
                "phone_number"      => $phone_number,
                "gender"            => $gender,
                "birth_date"        => $birth_date,
                "governorate"       => $governorate,
                "profile_img"       => $profile_img 

            ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($pharmacist_data , $message , 200)));

        } elseif (isset($_SESSION['assistant'])) {

            //Print Assistant Data From Session

            $assistant_name = $_SESSION['assistant']->assistant_name;
            $ssd            = $_SESSION['assistant']->ssd;
            $email          = $_SESSION['assistant']->email;
            $phone_number   = $_SESSION['assistant']->phone_number;
            $gender         = $_SESSION['assistant']->gender;
            $birth_date     = $_SESSION['assistant']->birth_date;
            $governorate    = $_SESSION['assistant']->governorate;
            $profile_img    = $_SESSION['assistant']->profile_img;

            $assistant_data = [

                "name"              => $assistant_name,
                "ssd"               => $ssd,
                "email"             => $email,
                "phone_number"      => $phone_number,
                "gender"            => $gender,
                "birth_date"        => $birth_date,
                "governorate"       => $governorate,
                "profile_img"       => $profile_img

            ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($assistant_data , $message , 200)));

        } else {
            $Message = "فشل العثور على مستخدم";
            print_r(json_encode(Message(null,$Message,401)));
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
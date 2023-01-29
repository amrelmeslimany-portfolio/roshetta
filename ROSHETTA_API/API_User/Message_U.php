<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (  // If Found SESSION
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $username   = $_SESSION['patient']->patient_name;
            $ssd        = $_SESSION['patient']->ssd;
            $email      = $_SESSION['patient']->email;
            $role       = $_SESSION['patient']->role;

        } elseif (isset($_SESSION['doctor'])) {
            $username   = $_SESSION['doctor']->doctor_name;
            $ssd        = $_SESSION['doctor']->ssd;
            $email      = $_SESSION['doctor']->email;
            $role       = $_SESSION['doctor']->role;

        } elseif (isset($_SESSION['pharmacist'])) {
            $username   = $_SESSION['pharmacist']->pharmacist_name;
            $ssd        = $_SESSION['pharmacist']->ssd;
            $email      = $_SESSION['pharmacist']->email;
            $role       = $_SESSION['pharmacist']->role;

        } elseif (isset($_SESSION['assistant'])) {
            $username   = $_SESSION['assistant']->assistant_name;
            $ssd        = $_SESSION['assistant']->ssd;
            $email      = $_SESSION['assistant']->email;
            $role       = $_SESSION['assistant']->role;

        } else {
            $username = '';
            $ssd = '';
            $email = '';
            $role = '';
        }

        //I Expect To Receive This Data

        if (isset($_POST['message']) && !empty($_POST['message'])) {

            //Filter Data String
            $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

            //Add To Message Table

            $addMessage = $database->prepare("INSERT INTO message(username,email,ssd,role,message)
                                                        VALUES(:username,:email,:ssd,:role,:message)");

            $addMessage->bindparam("username", $username);
            $addMessage->bindparam("email", $email);
            $addMessage->bindparam("ssd", $ssd);
            $addMessage->bindparam("role", $role);
            $addMessage->bindparam("message", $message);
            $addMessage->execute();

            if ($addMessage->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الارسال للمختص للمراجعة"]));

            } else {
                print_r(json_encode(["Error" => "فشل ارسال الرسالة"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
        }
    } else { //If Didn't Find The Name Of The Session Available
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
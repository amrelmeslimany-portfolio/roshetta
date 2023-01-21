<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Allow Access Via 'POST' Method Only

    //I Expect To Receive This Data

    if (
        isset($_POST['email']) && !empty($_POST['email'])
        && isset($_POST['message']) && !empty($_POST['message'])
    ) {

        session_start();
        session_regenerate_id();

        if (
            isset($_SESSION['patient'])
            || isset($_SESSION['doctor'])
            || isset($_SESSION['pharmacist'])
            || isset($_SESSION['assistant'])
        ) {

            //Filter Data Email && String

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

            if (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE) {

                if (isset($_SESSION['patient'])) { //If Patient

                    if ($_SESSION['patient']->role === "PATIENT") {

                        $username_p = $_SESSION['patient']->first_name;
                        $ssd_p      = $_SESSION['patient']->ssd;
                        $role_p     = $_SESSION['patient']->role;

                        require_once("../API_C_A/Connection.php"); //Connect To DataBases

                        //Add To Message Table

                        $addMessage = $database->prepare("INSERT INTO message(username,email,ssd,role,message)
                                                                            VALUES(:username,:email,:ssd,:role,:message)");

                        $addMessage->bindparam("username", $username_p);
                        $addMessage->bindparam("email", $email);
                        $addMessage->bindparam("ssd", $ssd_p);
                        $addMessage->bindparam("role", $role_p);
                        $addMessage->bindparam("message", $message);

                        if ($addMessage->execute()) {
                            print_r(json_encode(["Message" => "تم الارسال للمختص للمراجعة"]));
                        } else {
                            print_r(json_encode(["Error" => "فشل ارسال الرسالة"]));
                            die("");
                        }
                    } else { //If Didn't Find The Role
                        print_r(json_encode(["Error" => "فشل العثور على الدور"]));
                        die("");
                    }

                    //********************************************************************************//    

                } elseif (isset($_SESSION['doctor'])) { //If Doctor

                    if ($_SESSION['doctor']->role === "DOCTOR") {

                        $username_d = $_SESSION['doctor']->first_name;
                        $ssd_d      = $_SESSION['doctor']->ssd;
                        $role_d     = $_SESSION['doctor']->role;

                        require_once("../API_C_A/Connection.php"); //Connect To DataBases

                        //Add To Message Table

                        $addMessage = $database->prepare("INSERT INTO message(username,email,ssd,role,message)
                                                                    VALUES(:username,:email,:ssd,:role,:message)");

                        $addMessage->bindparam("username", $username_d);
                        $addMessage->bindparam("email", $email);
                        $addMessage->bindparam("ssd", $ssd_d);
                        $addMessage->bindparam("role", $role_d);
                        $addMessage->bindparam("message", $message);

                        if ($addMessage->execute()) {
                            print_r(json_encode(["Message" => "تم الارسال للمختص للمراجعة"]));
                        } else {
                            print_r(json_encode(["Error" => "فشل ارسال الرسالة"]));
                            die("");
                        }
                    } else { //If Didn't Find The Role
                        print_r(json_encode(["Error" => "فشل العثور على الدور"]));
                        die("");
                    }

                    //***********************************************************************//    

                } elseif (isset($_SESSION['pharmacist'])) { //If Pharmacist

                    if ($_SESSION['pharmacist']->role === "PHARMACIST") {

                        $username_ph = $_SESSION['pharmacist']->first_name;
                        $ssd_ph      = $_SESSION['pharmacist']->ssd;
                        $role_ph     = $_SESSION['pharmacist']->role;

                        require_once("../API_C_A/Connection.php"); //Connect To DataBases

                        //Add To Message Table

                        $addMessage = $database->prepare("INSERT INTO message(username,email,ssd,role,message)
                                                                    VALUES(:username,:email,:ssd,:role,:message)");

                        $addMessage->bindparam("username", $username_ph);
                        $addMessage->bindparam("email", $email);
                        $addMessage->bindparam("ssd", $ssd_ph);
                        $addMessage->bindparam("role", $role_ph);
                        $addMessage->bindparam("message", $message);

                        if ($addMessage->execute()) {
                            print_r(json_encode(["Message" => "تم الارسال للمختص للمراجعة"]));
                        } else {
                            print_r(json_encode(["Error" => "فشل ارسال الرسالة"]));
                            die("");
                        }
                    } else { //If Didn't Find The Role
                        print_r(json_encode(["Error" => "فشل العثور على الدور"]));
                        die("");
                    }

                    //*************************************************************************************//    

                } elseif (isset($_SESSION['assistant'])) { //If Assistant

                    if ($_SESSION['assistant']->role === "ASSISTANT") {

                        $username_a = $_SESSION['assistant']->first_name;
                        $ssd_a = $_SESSION['assistant']->ssd;
                        $role_a = $_SESSION['assistant']->role;

                        require_once("../API_C_A/Connection.php"); //Connect To DataBases

                        //Add To Message Table

                        $addMessage = $database->prepare("INSERT INTO message(username,email,ssd,role,message)
                                                                    VALUES(:username,:email,:ssd,:role,:message)");

                        $addMessage->bindparam("username", $username_a);
                        $addMessage->bindparam("email", $email);
                        $addMessage->bindparam("ssd", $ssd_a);
                        $addMessage->bindparam("role", $role_a);
                        $addMessage->bindparam("message", $message);

                        if ($addMessage->execute()) {
                            print_r(json_encode(["Message" => "تم الارسال للمختص للمراجعة"]));
                        } else {
                            print_r(json_encode(["Error" => "فشل ارسال الرسالة"]));
                            die("");
                        }
                    } else { //If Didn't Find The Role
                        print_r(json_encode(["Error" => "فشل العثور على الدور"]));
                        die("");
                    }

                    //******************************************************************************************//    

                } else {
                    print_r(json_encode(["Error" => "فشل تحديد الشيشن"]));
                    die("");
                }
            } else { //If InValid Email
                print_r(json_encode(["Error" => "ايميل غير صالح"]));
                die("");
            }
        } else { //If Didn't Find The Name Of The Session Available
            print_r(json_encode(["Error" => "فشل العثور على الشيشن"]));
            die("");
        }
    } else {
        print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
        die("");
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
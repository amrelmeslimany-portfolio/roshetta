<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            (isset($_POST['type'])   && !empty($_POST['type']))
            || (isset($_POST['id'])  && !empty($_POST['id']))

        ) {

            $type = $_POST['type'];
            $id   = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

            if ($type == 'PATIENT') {
                $table_name = 'patient';
            } elseif ($type == 'DOCTOR') {
                $table_name = 'doctor';
            } elseif ($type == 'PHARMACIST') {
                $table_name = 'pharmacist';
            } elseif ($type == 'ASSISTANT') {
                $table_name = 'assistant';
            } else {
                $table_name = '';
            }

            //I Expect To Receive This Data

            if (isset($_POST['email']) && !empty($_POST['email'])) {

                $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); //Filter Email

                if (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE) {  //Verify Email Is Valid 

                    $check_email = $database->prepare("SELECT * FROM $table_name WHERE  email = :email");
                    $check_email->bindParam("email", $email);
                    $check_email->execute();

                    if ($check_email->rowCount() > 0) {

                        $Message = "البريد الإلكترونى موجود من قبل";
                        print_r(json_encode(Message(null,$Message,400)));
                        die();

                    } else {

                        //UpDate Email Table

                        $Update = $database->prepare("UPDATE $table_name SET email = :email WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("email", $email);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            $Message = "تم تعديل البريد الإلكترونى بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    }
                } else {
                    $Message = "البريد الإلكترونى غير صالح للاستخدام";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
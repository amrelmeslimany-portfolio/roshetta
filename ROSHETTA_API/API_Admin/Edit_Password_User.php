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

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    
                    //UpDate Password Table

                    $Update = $database->prepare("UPDATE $table_name SET password = :password WHERE id = :id");
                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        $Message = "تم تعديل كلمة المرور بنجاح";
                        print_r(json_encode(Message(null,$Message,201)));
                        header("refresh:2;");
                            
                    } else {
                        $Message = "فشل تعديل كلمة المرور";
                        print_r(json_encode(Message(null,$Message,422)));
                    }
                } else {
                    $Message = "كلمة المرور غير متطابقة";
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
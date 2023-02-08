<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (  //If Found SESSION
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $table_name = 'patient';
            $id         = $_SESSION['patient']->id;
        } elseif (isset($_SESSION['doctor'])) {
            $table_name = 'doctor';
            $id         = $_SESSION['doctor']->id;
        } elseif (isset($_SESSION['pharmacist'])) {
            $table_name = 'pharmacist';
            $id         = $_SESSION['pharmacist']->id;
        } elseif (isset($_SESSION['assistant'])) {
            $table_name = 'assistant';
            $id         = $_SESSION['assistant']->id;
        } else {
            $table_name = '';
            $id = '';
        }

        //I Expect To Receive This Data

        if (
            isset($_POST['password'])               && !empty($_POST['password'])
            && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
        ) {

            if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash

                //UpDate User Table

                $Update = $database->prepare("UPDATE $table_name SET password = :password WHERE id = :id ");
                $Update->bindparam("id", $id);
                $Update->bindparam("password", $password_hash);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    //Get New Data From User Table

                    $get_data = $database->prepare("SELECT * FROM $table_name WHERE id = :id ");
                    $get_data->bindparam("id", $id);
                    $get_data->execute();

                    if ($get_data->rowCount() > 0) {

                        $data_user = $get_data->fetchObject();

                        if ($table_name == "patient") {
                            $_SESSION['patient'] = $data_user;
                        } elseif ($table_name == "doctor") {
                            $_SESSION['doctor'] = $data_user;
                        } elseif ($table_name == "pharmacist") {
                            $_SESSION['pharmacist'] = $data_user;
                        } elseif ($table_name == "assistant") {
                            $_SESSION['assistant'] = $data_user;
                        } else {
                            $_SESSION['null'] = '';
                        }

                        $Message = "تم تعديل كلمة المرور بنجاح";
                        print_r(json_encode(Message(null,$Message,201)));
                        header("refresh:2;");

                    } else {
                        $Message = "فشل جلب البيانات";
                        print_r(json_encode(Message(null,$Message,422)));
                    }
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
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
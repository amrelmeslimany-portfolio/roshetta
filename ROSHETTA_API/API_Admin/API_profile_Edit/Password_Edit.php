<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    require_once("../../API_C_A/Connection.php"); //Connect To DataBases

    if (isset($_SESSION['admin'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['password'])               && !empty($_POST['password'])
            && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
        ) {

            if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                $id             = $_SESSION['admin']->id;

                //UpDate Admin Table

                $Update = $database->prepare("UPDATE admin SET password = :password WHERE id = :id ");

                $Update->bindparam("id", $id);
                $Update->bindparam("password", $password_hash);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    //Get New Data From Admin Table

                    $get_data = $database->prepare("SELECT * FROM admin WHERE id = :id ");

                    $get_data->bindparam("id", $id);
                    $get_data->execute();

                    if ($get_data->rowCount() > 0) {

                        $admin_up = $get_data->fetchObject();
                        $_SESSION['admin'] = $admin_up; //UpDate SESSION Admin

                        print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                        header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                }
            } else {
                print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
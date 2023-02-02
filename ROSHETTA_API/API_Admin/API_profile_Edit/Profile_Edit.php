<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        //I Expect To Receive This Data

        if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {

            //Filter Data 'Number_Int' And 'String' And 'Email'

            $id             = $_SESSION['admin']->id;
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);

            if (strlen($phone_number) == 11 ) {

                //Verify That It Has Not Been Present Before

                $check_phone = $database->prepare("SELECT * FROM admin WHERE phone_number = :phone_number ");

                $check_phone->bindparam("phone_number", $phone_number);
                $check_phone->execute();

                if ($check_phone->rowCount() > 0) {

                    print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));

                } else {

                    //UpDate Admin Table

                    $Update = $database->prepare("UPDATE admin SET phone_number = :phone_number WHERE id = :id");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->execute();

                    if ($Update->rowCount() > 0) {

                        //Get New Data From Admin Table

                        $get_data = $database->prepare("SELECT * FROM admin WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0) {

                            $admin_up = $get_data->fetchObject();
                            $_SESSION['admin'] = $admin_up; //UpDate SESSION Admin

                            print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                            header("refresh:2;");


                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                    }
                }
            } else {
                print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
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
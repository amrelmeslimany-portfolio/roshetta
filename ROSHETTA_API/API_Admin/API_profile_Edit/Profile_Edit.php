<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

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

                    $Message = "رقم الهاتف موجود من قبل";
                    print_r(json_encode(Message(null,$Message,400)));

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

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل جلب البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    } else {
                        $Message = "فشل تعديل البيانات";
                        print_r(json_encode(Message(null,$Message,422)));
                    }
                }
            } else {
                $Message = "رقم الهاتف غير صالح";
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
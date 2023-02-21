<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number']) && !empty($_POST['phone_number'])
                && isset($_POST['weight']) && !empty($_POST['weight'])
                && isset($_POST['height']) && !empty($_POST['height'])
                && isset($_POST['governorate']) && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $id             = $_SESSION['patient'];
                $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $weight         = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_INT);
                $height         = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_INT);
                $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11 ) {

                    //UpDate Patient Table

                    $Update = $database->prepare("UPDATE patient SET phone_number = :phone_number , weight = :weight , height = :height , governorate = :governorate WHERE id = :id");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->bindparam("weight", $weight);
                    $Update->bindparam("height", $height);
                    $Update->bindparam("governorate", $governorate);
                    $Update->execute();

                    if ($Update->rowCount() > 0) {

                        $Message = "تم تعديل البيانات بنجاح";
                        print_r(json_encode(Message(null, $Message, 201)));
                        header("refresh:2;");

                    } else {
                        $Message = "فشل تعديل البيانات";
                        print_r(json_encode(Message(null, $Message, 422)));
                    }
                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null, $Message, 400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null, $Message, 400)));
            }

        } else {

            if (isset($_SESSION['doctor'])) {
                $id         = $_SESSION['doctor'];
                $table_name = 'doctor';
            } elseif (isset($_SESSION['pharmacist'])) {
                $id         = $_SESSION['pharmacist'];
                $table_name = 'pharmacist';
            } elseif (isset($_SESSION['assistant'])) {
                $id         = $_SESSION['assistant'];
                $table_name = 'assistant';
            } else {
                $id = '';
                $table_name = '';
            }

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number']) && !empty($_POST['phone_number'])
                && isset($_POST['governorate']) && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    //Verify That It Has Not Been Present Before

                    $check_phone = $database->prepare("SELECT * FROM $table_name WHERE phone_number = :phone_number");
                    $check_phone->bindparam("phone_number", $phone_number);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        $check_phone = $database->prepare("SELECT * FROM $table_name WHERE phone_number = :phone_number AND id = :id");
                        $check_phone->bindparam("phone_number", $phone_number);
                        $check_phone->bindparam("id", $id);
                        $check_phone->execute();

                        if ($check_phone->rowCount() > 0) {

                            //UpDate Doctor Table

                            $Update = $database->prepare("UPDATE $table_name SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");
                            $Update->bindparam("id", $id);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("governorate", $governorate);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                $Message = "تم تعديل البيانات بنجاح";
                                print_r(json_encode(Message(null, $Message, 201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل البيانات";
                                print_r(json_encode(Message(null, $Message, 422)));
                            }

                        } else {
                            $Message = "رقم الهاتف موجود من قبل";
                            print_r(json_encode(Message(null, $Message, 400)));
                        }

                    } else {

                        //UpDate Doctor Table

                        $Update = $database->prepare("UPDATE $table_name SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null, $Message, 201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null, $Message, 422)));
                        }
                    }
                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null, $Message, 400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        }
    } else {
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null, $Message, 401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
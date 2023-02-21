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

            if (isset($_POST['ssd']) && !empty($_POST['ssd'])) {

                $ssd  = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE  && strlen($ssd) == 14 ) {  //Verify SSD Is Valid 

                    $check_ssd = $database->prepare("SELECT * FROM $table_name WHERE  ssd = :ssd");
                    $check_ssd->bindParam("ssd", $ssd);
                    $check_ssd->execute();

                    if ($check_ssd->rowCount() > 0) {

                        $Message = "الرقم القومى موجود من قبل";
                        print_r(json_encode(Message(null,$Message,400)));
                        die();

                    } else {

                        //UpDate SSD Table

                        $Update = $database->prepare("UPDATE $table_name SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {
                            
                            $Message = "تم تعديل الرقم القومى بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    }
                } else {
                    $Message = "الرقم القومى غير صالح للاستخدام";
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
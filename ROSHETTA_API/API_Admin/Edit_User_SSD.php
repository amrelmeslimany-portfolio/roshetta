<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            (isset($_POST['patient_id'])        && !empty($_POST['patient_id']))
            || (isset($_POST['doctor_id'])      && !empty($_POST['doctor_id']))
            || (isset($_POST['pharmacist_id'])  && !empty($_POST['pharmacist_id']))
            || (isset($_POST['assistant_id'])   && !empty($_POST['assistant_id']))
        ) {

            if (isset($_POST['patient_id'])) {
                $id         = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);  //Filter Number INT
                $table_name = 'patient';
            } elseif (isset($_POST['doctor_id'])) {
                $id         = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT
                $table_name = 'doctor';
            } elseif (isset($_POST['pharmacist_id'])) {
                $id         = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT
                $table_name = 'pharmacist';
            } elseif (isset($_POST['assistant_id'])) {
                $id         = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT
                $table_name = 'assistant';
            } else {
                $id = '';
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

                        print_r(json_encode(["Error" => "الرقم القومى موجود من قبل"]));
                        die();

                    } else {

                        //UpDate SSD Table

                        $Update = $database->prepare("UPDATE $table_name SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل الرقم القومى بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل الرقم القومى"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى غير صالح للاستخدام"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل العثور على معرف المستخدم"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['patient'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['appoint_date']) && !empty($_POST['appoint_date'])
            && isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])
        ) {

            $appoint_date   = $_POST['appoint_date'];
            $clinic_id      = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT); //Filter 'Int'
            $patient_id     = $_SESSION['patient']->id;

            //Add To Appointment Table

            $add_appoint = $database->prepare("INSERT INTO appointment(appoint_date,patient_id,clinic_id,appoint_case)
                                                        VALUES(:appoint_date,:patient_id,:clinic_id,0)");

            $add_appoint->bindparam("appoint_date", $appoint_date);
            $add_appoint->bindparam("patient_id", $patient_id);
            $add_appoint->bindparam("clinic_id", $clinic_id);
            $add_appoint->execute();

            if ($add_appoint->rowCount() > 0 ) {

                $Message = "تم اضافة الحجز بنجاح";
                print_r(json_encode(Message(null, $Message, 201)));

            } else {
                $Message = "فشل اضافة الحجز";
                print_r(json_encode(Message(null, $Message, 422)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

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

                print_r(json_encode(["Message" => "تم اضافة الحجز بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل اضافة الحجز"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك القيام بالحجز"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
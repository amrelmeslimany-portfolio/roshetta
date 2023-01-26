<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        require_once("../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            $patient_id = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Patient Table

            $delete_patient = $database->prepare("DELETE FROM patient WHERE patient.id = :patient_id");
            $delete_patient->bindparam("patient_id", $patient_id);
            $delete_patient->execute();

            if ($delete_patient->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } elseif (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {

            $doctor_id = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Doctor Table

            $delete_doctor = $database->prepare("DELETE FROM doctor WHERE doctor.id = :doctor_id");
            $delete_doctor->bindparam("doctor_id", $doctor_id);
            $delete_doctor->execute();

            if ($delete_doctor->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } elseif (isset($_POST['pharmacist_id']) && !empty($_POST['pharmacist_id'])) {

            $pharmacist_id = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Pharmacist Table

            $delete_pharmacist = $database->prepare("DELETE FROM pharmacist WHERE pharmacist.id = :pharmacist_id");
            $delete_pharmacist->bindparam("pharmacist_id", $pharmacist_id);
            $delete_pharmacist->execute();

            if ($delete_pharmacist->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } elseif (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            $assistant_id = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Patient Table

            $delete_assistant = $database->prepare("DELETE FROM assistant WHERE assistant.id = :assistant_id");
            $delete_assistant->bindparam("assistant_id", $assistant_id);
            $delete_assistant->execute();

            if ($delete_assistant->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
        }

    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
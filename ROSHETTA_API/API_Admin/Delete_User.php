<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            (isset($_POST['patient_id'])        && !empty($_POST['patient_id']))
            || (isset($_POST['doctor_id'])      && !empty($_POST['doctor_id']))
            || (isset($_POST['pharmacist_id'])  && !empty($_POST['pharmacist_id']))
            || (isset($_POST['assistant_id'])   && !empty($_POST['assistant_id']))
        ) {

            if (isset($_POST['patient_id'])) {
                $id         = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
                $table_name = 'patient';
            } elseif (isset($_POST['doctor_id'])) {
                $id         = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT);
                $table_name = 'doctor';
            } elseif (isset($_POST['pharmacist_id'])) {
                $id         = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT);
                $table_name = 'pharmacist';
            } elseif (isset($_POST['assistant_id'])) {
                $id         = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);
                $table_name = 'assistant';
            } else {
                $id = '';
                $table_name = '';
            }

            //Delete User

            $delete_user = $database->prepare("DELETE FROM $table_name WHERE id = :id");
            $delete_user->bindparam("id", $id);
            $delete_user->execute();

            if ($delete_user->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } else {
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
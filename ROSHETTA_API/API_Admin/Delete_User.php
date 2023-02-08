<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

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

                $Message = "تم الحذف بنجاح";
                print_r(json_encode(Message(null,$Message,200)));

            } else {
                $Message = "فشل الحذف";
                print_r(json_encode(Message(null,$Message,422)));
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
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
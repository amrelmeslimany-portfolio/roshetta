<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {  //If Admin

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {
            $id         = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
            $table_name = 'clinic';
        } elseif (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {
            $id         = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);
            $table_name = 'pharmacy';
        } else {
            $id = '';
            $table_name = '';
        }

        // Delete From Place Table

        $delete_place = $database->prepare("DELETE FROM $table_name WHERE id = :id");
        $delete_place->bindparam("id", $id);
        $delete_place->execute();

        if ($delete_place->rowCount() > 0) {

            print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

        } else {
            print_r(json_encode(["Error" => "فشل الحذف"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
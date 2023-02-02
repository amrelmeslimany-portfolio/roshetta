<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor'])) {

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
            $doctor_id = $_SESSION['doctor']->id;

            // Delete From Clinic Table

            $delete_clinic = $database->prepare("DELETE FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

            $delete_clinic->bindparam("clinic_id", $clinic_id);
            $delete_clinic->bindparam("doctor_id", $doctor_id);

            if ($delete_clinic->execute()) {

                if ($delete_clinic->rowCount() > 0) {

                    print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "فشل حذف العيادة"]));
                }
            } else {
                print_r(json_encode(["Error" => "فشل حذف العيادة"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور العيادة"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
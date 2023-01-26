<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        require_once("../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Clinic Table

            $delete_clinic = $database->prepare("DELETE FROM clinic WHERE clinic.id = :clinic_id");
            $delete_clinic->bindparam("clinic_id", $clinic_id);
            $delete_clinic->execute();

            if ($delete_clinic->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } elseif (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            $pharmacy_id = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete From Pharmacy Table

            $delete_pharmacy = $database->prepare("DELETE FROM pharmacy WHERE pharmacy.id = :pharmacy_id");
            $delete_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
            $delete_pharmacy->execute();

            if ($delete_pharmacy->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل العثور على المكان"]));
        }

    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
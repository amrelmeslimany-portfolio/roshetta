<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist'])) {

        if (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

            $pharmacy_id = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);
            $pharmacist_id = $_SESSION['pharmacist']->id;

            // Delete From Pharmacy Table

            $delete_pharmacy = $database->prepare("DELETE FROM pharmacy WHERE pharmacy.id = :pharmacy_id AND pharmacy.pharmacist_id = :pharmacist_id ");

            $delete_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
            $delete_pharmacy->bindparam("pharmacist_id", $pharmacist_id);

            if ($delete_pharmacy->execute()) {

                if ($delete_pharmacy->rowCount() > 0) {

                    print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "فشل حذف الصيدلية"]));
                }

            } else {
                print_r(json_encode(["Error" => "فشل حذف الصيدلية"]));
            }

        } else {
            print_r(json_encode(["Error" => "لم يتم العثور الصيدلية"]));
        }

    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
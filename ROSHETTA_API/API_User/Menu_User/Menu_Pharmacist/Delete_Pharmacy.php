<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist'])) {

        if (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            $pharmacy_id    = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);
            $pharmacist_id  = $_SESSION['pharmacist'];

            // Delete From Pharmacy Table

            $delete_pharmacy = $database->prepare("DELETE FROM pharmacy WHERE pharmacy.id = :pharmacy_id AND pharmacy.pharmacist_id = :pharmacist_id ");
            $delete_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
            $delete_pharmacy->bindparam("pharmacist_id", $pharmacist_id);

            if ($delete_pharmacy->execute()) {

                if ($delete_pharmacy->rowCount() > 0 ) {

                    $Message = "تم الحذف بنجاح";
                    print_r(json_encode(Message(null, $Message, 200)));

                } else {
                    $Message = "فشل حذف الصيدلية";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "فشل حذف الصيدلية";
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
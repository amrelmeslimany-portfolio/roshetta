<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor'])) {

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
            $doctor_id = $_SESSION['doctor'];

            // Delete From Clinic Table

            $delete_clinic = $database->prepare("DELETE FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

            $delete_clinic->bindparam("clinic_id", $clinic_id);
            $delete_clinic->bindparam("doctor_id", $doctor_id);

            if ($delete_clinic->execute()) {

                if ($delete_clinic->rowCount() > 0) {

                    $Message = "تم الحذف بنجاح";
                    print_r(json_encode(Message(null, $Message, 200)));

                } else {
                    $Message = "فشل حذف العيادة";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "فشل حذف العيادة";
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
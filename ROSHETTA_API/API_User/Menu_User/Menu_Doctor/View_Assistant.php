<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        $doctor_id = $_SESSION['doctor']->id;
        $clinic_id = $_SESSION['clinic']->id;

        //Get From Assistant Table

        $get_assistant = $database->prepare("SELECT profile_img,assistant_name,assistant.phone_number FROM assistant,clinic WHERE assistant.id = clinic.assistant_id AND clinic.doctor_id = :doctor_id AND clinic.id = :clinic_id ");
        $get_assistant->bindparam("clinic_id", $clinic_id);
        $get_assistant->bindparam("doctor_id", $doctor_id);

        if ($get_assistant->execute()) {

            if ($get_assistant->rowCount() > 0) {

                $get_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_assistant));

            } else {
                print_r(json_encode(["Error" => "لم يتم العثور على مساعد"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
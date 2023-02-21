<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        $doctor_id = $_SESSION['doctor'];
        $clinic_id = $_SESSION['clinic'];

        //Get From Assistant Table

        $get_assistant = $database->prepare("SELECT profile_img,assistant.name as assistant_name,assistant.phone_number FROM assistant,clinic WHERE assistant.id = clinic.assistant_id AND clinic.doctor_id = :doctor_id AND clinic.id = :clinic_id ");
        $get_assistant->bindparam("clinic_id", $clinic_id);
        $get_assistant->bindparam("doctor_id", $doctor_id);

        if ($get_assistant->execute()) {

            if ($get_assistant->rowCount() > 0) {

                $data_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_assistant, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على مساعد";
                print_r(json_encode(Message(null, $Message, 204)));
            }
        } else {
            $Message = "فشل جلب البيانات";
            print_r(json_encode(Message(null, $Message, 422)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
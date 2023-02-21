<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases 
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['assistant'])) {

        $assistant_id = $_SESSION['assistant'];

        //Get From Clinic Table
        $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic.name as clinic_name,start_working,end_working FROM clinic WHERE assistant_id = :assistant_id ORDER BY start_working ");
        $get_clinic->bindparam("assistant_id", $assistant_id);

        if ($get_clinic->execute()) {

            if ($get_clinic->rowCount() > 0) {

                $data_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_clinic, $Message, 200)));

            } else {
                $Message = "ليس لديك اي عيادة";
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
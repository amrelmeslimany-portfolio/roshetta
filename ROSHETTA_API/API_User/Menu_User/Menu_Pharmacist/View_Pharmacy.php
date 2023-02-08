<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['pharmacist'])) {

        $pharmacist_id = $_SESSION['pharmacist']->id;

        $checkActivation = $database->prepare("SELECT * FROM activation_person,pharmacist  WHERE  activation_person.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
        $checkActivation->bindparam("id", $pharmacist_id);
        $checkActivation->execute();

        if ($checkActivation->rowCount() > 0) {

            $Activation = $checkActivation->fetchObject();

            if ($Activation->isactive == 1) {

                //Get From Pharmacy Table
                $get_pharmacy = $database->prepare("SELECT id as pharmacy_id,logo as pharmacy_logo,pharmacy_name,start_working,end_working FROM pharmacy WHERE pharmacist_id = :pharmacist_id ORDER BY start_working ");
                $get_pharmacy->bindparam("pharmacist_id", $pharmacist_id);
                $get_pharmacy->execute();

                if ($get_pharmacy->rowCount() > 0) {

                    $data_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

                    $Message = "تم جلب البيانات ";
                    print_r(json_encode(Message($data_pharmacy , $Message, 200)));

                } else {
                    $Message = "لم يتم العثور على صيدلية";
                    print_r(json_encode(Message(null, $Message, 204)));
                }
            } else {
                $Message = "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل المشرف";
                print_r(json_encode(Message(null, $Message, 202)));
            }
        } else {
            $Message = "يجب تفعيل الحساب";
            print_r(json_encode(Message(null, $Message, 202)));
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
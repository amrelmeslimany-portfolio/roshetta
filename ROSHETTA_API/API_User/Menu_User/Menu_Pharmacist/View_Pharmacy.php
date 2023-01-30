<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

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

                $get_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_pharmacy));

            } else {
                print_r(json_encode(["Error" => "ليس لديك صيدلية "]));
            }
        } else {
            print_r(json_encode(["Error" => "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل الادمن"]));
        }
    } else {
        print_r(json_encode(["Error" => "يجب تفعيل الحساب"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
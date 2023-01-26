<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['doctor'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $doctor_id = $_SESSION['doctor']->id;

    $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
    $checkActivation->bindparam("id", $doctor_id);
    $checkActivation->execute();

    if ($checkActivation->rowCount() > 0) {

        $Activation = $checkActivation->fetchObject();

        if ($Activation->isactive == 1) {

            //Get From Clinic Table
            $get_clinic = $database->prepare("SELECT id as clinic_id,logo as clinic_logo,clinic_name,start_working,end_working FROM clinic WHERE doctor_id = :doctor_id ORDER BY start_working ");

            $get_clinic->bindparam("doctor_id", $doctor_id);

            $get_clinic->execute();

            if ($get_clinic->rowCount() > 0 ) {

                $get_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_clinic));

            } else {
                print_r(json_encode(["Error" => "ليس لديك عيادة"]));
            }
        } else {
            print_r(json_encode(["Error" => "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل الادمن"]));
            die("");
        }
    } else {
        print_r(json_encode(["Error" => "يجب تفعيل الحساب"]));
        die("");
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
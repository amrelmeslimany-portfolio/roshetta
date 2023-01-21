<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $clinic_id = $_SESSION['clinic']->id;
    $doctor_id = $_SESSION['doctor']->id;

    // Delete From Appointment Table

    $delete_assistant = $database->prepare("UPDATE clinic SET assistant_id = NULL  WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

    $delete_assistant->bindparam("clinic_id", $clinic_id);
    $delete_assistant->bindparam("doctor_id", $doctor_id);

    if ($delete_assistant->execute()) {

        if ($delete_assistant->rowCount() > 0) {

            //Get From Clinic Table

            $get_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

            $get_clinic->bindparam("clinic_id", $clinic_id);
            $get_clinic->bindparam("doctor_id", $doctor_id);

            if ($get_clinic->execute()) {

                $get_clinic = $get_clinic->fetchObject();

                $_SESSION['clinic'] = $get_clinic;

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل حذف المساعد"]));
        }

    } else {
        print_r(json_encode(["Error" => "فشل حذف المساعد"]));
    }
} else {
    print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
}
?>
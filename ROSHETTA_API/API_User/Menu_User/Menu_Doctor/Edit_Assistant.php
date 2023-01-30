<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

    $doctor_id = $_SESSION['doctor']->id;
    $clinic_id = $_SESSION['clinic']->id;

    if (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

        //Filter Data 'Int'

        $assistant_id = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);

        //Check Assistant

        $check_assistant = $database->prepare("SELECT * FROM assistant WHERE assistant.id = :assistant_id ");
        $check_assistant->bindparam("assistant_id", $assistant_id);
        $check_assistant->execute();

        if ($check_assistant->rowCount() > 0) {

            // UpDate Clinic Table

            $Update = $database->prepare("UPDATE clinic SET assistant_id = :assistant_id WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

            $Update->bindparam("assistant_id", $assistant_id);
            $Update->bindparam("clinic_id", $clinic_id);
            $Update->bindparam("doctor_id", $doctor_id);

            if ($Update->execute()) {

                if ($Update->rowCount() > 0) {

                    //Get From Clinic Table

                    $get_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");
                    $get_clinic->bindparam("clinic_id", $clinic_id);
                    $get_clinic->bindparam("doctor_id", $doctor_id);

                    if ($get_clinic->execute()) {

                        $get_clinic = $get_clinic->fetchObject();

                        $_SESSION['clinic'] = $get_clinic;

                        print_r(json_encode(["Message" => "تم التعديل بنجاح"]));

                        header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل تعديل المساعد"]));
                }
            } else {
                print_r(json_encode(["Error" => "فشل تعديل المساعد"]));
            }
        } else {
            print_r(json_encode(["Error" => "رقم المساعد غير صحيح"]));
        }

    } else {

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
    }
} else {
    print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['doctor'])) {

    if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

        require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

        $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
        $doctor_id = $_SESSION['doctor']->id;

        // Delete From Appointment Table

        $delete_cilinic = $database->prepare("DELETE FROM clinic WHERE clinic.id = :clinic_id AND clinic.doctor_id = :doctor_id ");

        $delete_cilinic->bindparam("clinic_id", $clinic_id);
        $delete_cilinic->bindparam("doctor_id", $doctor_id);

        if ($delete_cilinic->execute()) {

            if ($delete_cilinic->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل حذف العيادة"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل حذف العيادة"]));
        }

    } else {
        print_r(json_encode(["Error" => "لم يتم العثور العيادة"]));
    }

} else {
    print_r(json_encode(["Error" => "لم يتم العثور على مستخدم"]));
}
?>
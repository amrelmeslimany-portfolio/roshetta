<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_GET['search'])      && !empty($_GET['search'])
            && isset($_GET['type'])     && !empty($_GET['type'])) {

            $search = $_GET['search'];
            $type   = $_GET['type'];

            if ($type == 'doctor') {

                // Get Data From Doctor Table

                $get_doctor = $database->prepare("SELECT activation_person.id as activation_id ,doctor.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND (ssd = :search OR name = :search OR email = :search)");
                $get_doctor->bindParam("search", $search);
                $get_doctor->execute();

                if ($get_doctor->rowCount() > 0) {
                    $data = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'pharmacist') {

                // Get Data From Pharmacist Table

                $get_pharmacist = $database->prepare("SELECT activation_person.id as activation_id , pharmacist.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND (ssd = :search OR name = :search OR email = :search)");
                $get_pharmacist->bindParam("search", $search);
                $get_pharmacist->execute();

                if ($get_pharmacist->rowCount() > 0) {
                    $data = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'clinic') {

                // Get Data From Clinic Table

                $get_clinic = $database->prepare("SELECT activation_place.id as activation_id , clinic.id as place_id,name,ser_id,logo,activation_place.isactive as status FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND (ser_id = :search OR name = :search OR owner = :search)");
                $get_clinic->bindParam("search", $search);
                $get_clinic->execute();

                if ($get_clinic->rowCount() > 0) {
                    $data = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'pharmacy') {

                // Get Data From Pharmacy Table

                $get_pharmacy = $database->prepare("SELECT activation_place.id as activation_id,pharmacy.id as place_id ,name,ser_id,logo,activation_place.isactive as status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND (ser_id = :search OR name = :search OR owner = :search)");
                $get_pharmacy->bindParam("search", $search);
                $get_pharmacy->execute();

                if ($get_pharmacy->rowCount() > 0) {
                    $data = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } else {
                $message = "النوع غير معروف";
                print_r(json_encode(Message(null, $message, 401)));
            }

            $message = "تم جلب البيانات";
            print_r(json_encode(Message($data, $message, 200)));

        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
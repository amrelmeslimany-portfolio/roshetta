<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            isset($_GET['type'])        && !empty($_GET['type'])
            && isset($_GET['status'])   && !empty($_GET['status'])
        ) {
            $type   = $_GET['type']; //Account Type [doctor,pharmacist,clinic,pharmacy]
            $status = $_GET['status']; //Account Status [0,1]

            if ($type == 'doctor') {
                $get_doctor = $database->prepare("SELECT activation_person.id as activation_id ,doctor.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = :status ");
                $get_doctor->bindParam("status", $status);
                $get_doctor->execute();

                if ($get_doctor->rowCount() > 0) {
                    $data = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'pharmacist') {
                $get_pharmacist = $database->prepare("SELECT activation_person.id as activation_id , pharmacist.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = :status ");
                $get_pharmacist->bindParam("status", $status);
                $get_pharmacist->execute();

                if ($get_pharmacist->rowCount() > 0) {
                    $data = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'clinic') {
                $get_clinic = $database->prepare("SELECT activation_place.id as activation_id , clinic.id as place_id,name,ser_id,logo,activation_place.isactive as status FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = :status ");
                $get_clinic->bindParam("status", $status);
                $get_clinic->execute();

                if ($get_clinic->rowCount() > 0) {
                    $data = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data = null;
                }

            } elseif ($type == 'pharmacy') {
                $get_pharmacy = $database->prepare("SELECT activation_place.id as activation_id,pharmacy.id as place_id ,name,ser_id,logo,activation_place.isactive as status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = :status ");
                $get_pharmacy->bindParam("status", $status);
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

            //Get From Doctor Table When Doctor Account Not Active

            $get_doctor = $database->prepare("SELECT activation_person.id as activation_id ,doctor.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = 0 ");
            $get_doctor->execute();
            if ($get_doctor->rowCount() > 0) {
                $data_doctor = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_doctor = null;
            }
            //Get From Pharmacist Table When Pharmacist Account Not Active

            $get_pharmacist = $database->prepare("SELECT activation_person.id as activation_id , pharmacist.id as user_id,name,ssd,profile_img,activation_person.isactive as status FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = 0 ");
            $get_pharmacist->execute();
            if ($get_pharmacist->rowCount() > 0) {
                $data_pharmacist = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_pharmacist = null;
            }
            //Get From Clinic Table When Clinic Account Not Active

            $get_clinic = $database->prepare("SELECT activation_place.id as activation_id , clinic.id as place_id,name,ser_id,logo,activation_place.isactive as status FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = 0 ");
            $get_clinic->execute();
            if ($get_clinic->rowCount() > 0) {
                $data_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_clinic = null;
            }

            //Get From Pharmacy Table When Pharmacy Account Not Active

            $get_pharmacy = $database->prepare("SELECT activation_place.id as activation_id,pharmacy.id as place_id ,name,ser_id,logo,activation_place.isactive as status FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = 0 ");
            $get_pharmacy->execute();
            if ($get_pharmacy->rowCount() > 0) {
                $data_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data_pharmacy = null;
            }

            $data_all = [

                // Array Of All

                "data_doctor"       => $data_doctor,
                "data_pharmacist"   => $data_pharmacist,
                "data_clinic"       => $data_clinic,
                "data_pharmacy"     => $data_pharmacy

            ];

            $message = "تم جلب البيانات";
            print_r(json_encode(Message($data_all, $message, 200)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $message, 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}

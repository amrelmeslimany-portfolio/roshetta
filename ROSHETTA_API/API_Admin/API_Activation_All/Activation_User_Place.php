<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (isset($_SESSION['admin'])) {

    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $search = $_POST['search'];

        //Get From Doctor Table When Doctor Account Active

        $get_active_doctor = $database->prepare("SELECT activation_person.id as activation_person_id , doctor.id as doctor_id , doctor_name , ssd as doctor_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = 1 AND (doctor.ssd = :search OR doctor_name = :search OR email = :search)");
        $get_active_doctor->bindParam("search", $search);
        $get_active_doctor->execute();
        if ($get_active_doctor->rowCount() > 0) {
            $data_doctor_active = $get_active_doctor->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_doctor_active = array(["Error" => ":("]);
        }
        //Get From Doctor Table When Doctor Account Not Active

        $get_not_active_doctor = $database->prepare("SELECT activation_person.id as activation_person_id , doctor.id as doctor_id , doctor_name , ssd as doctor_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = 0 AND (doctor.ssd = :search OR doctor_name = :search OR email = :search)");
        $get_not_active_doctor->bindParam("search", $search);
        $get_not_active_doctor->execute();
        if ($get_not_active_doctor->rowCount() > 0) {
            $data_doctor_not_active = $get_not_active_doctor->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_doctor_not_active = array(["Error" => ":("]);
        }


        //Get From Pharmacist Table When Pharmacist Account Active

        $get_active_pharmacist = $database->prepare("SELECT activation_person.id as activation_person_id , pharmacist.id as pharmacist_id , pharmacist_name , ssd as pharmacist_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = 1 AND (pharmacist.ssd = :search OR pharmacist_name = :search OR email = :search)");
        $get_active_pharmacist->bindParam("search", $search);
        $get_active_pharmacist->execute();
        if ($get_active_pharmacist->rowCount() > 0) {
            $data_pharmacist_active = $get_active_pharmacist->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacist_active = array(["Error" => ":("]);
        }
        //Get From Pharmacist Table When Pharmacist Account Not Active

        $get_not_active_pharmacist = $database->prepare("SELECT activation_person.id as activation_person_id , pharmacist.id as pharmacist_id , pharmacist_name , ssd as pharmacist_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = 0 AND (pharmacist.ssd = :search OR pharmacist_name = :search OR email = :search)");
        $get_not_active_pharmacist->bindParam("search", $search);
        $get_not_active_pharmacist->execute();
        if ($get_not_active_pharmacist->rowCount() > 0) {
            $data_pharmacist_not_active = $get_not_active_pharmacist->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacist_not_active = array(["Error" => ":("]);
        }


        //Get From Clinic Table When Clinic Account Active

        $get_active_clinic = $database->prepare("SELECT activation_place.id as activation_place_id , clinic.id as clinic_id , clinic_name , clinic.ser_id as clinic_ser_id , clinic.logo , license_img FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = 1 AND (clinic.ser_id = :search OR clinic_name = :search OR clinic.owner = :search)");
        $get_active_clinic->bindParam("search", $search);
        $get_active_clinic->execute();
        if ($get_active_clinic->rowCount() > 0) {
            $data_clinic_active = $get_active_clinic->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_clinic_active = array(["Error" => ":("]);
        }
        //Get From Clinic Table When Clinic Account Not Active

        $get_not_active_clinic = $database->prepare("SELECT activation_place.id as activation_place_id , clinic.id as clinic_id , clinic_name , clinic.ser_id as clinic_ser_id , clinic.logo , license_img FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = 0 AND (clinic.ser_id = :search OR clinic_name = :search OR clinic.owner = :search)");
        $get_not_active_clinic->bindParam("search", $search);
        $get_not_active_clinic->execute();
        if ($get_not_active_clinic->rowCount() > 0) {
            $data_clinic_not_active = $get_not_active_clinic->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_clinic_not_active = array(["Error" => ":("]);
        }

        //Get From Pharmacy Table When Pharmacy Account Active

        $get_active_pharmacy = $database->prepare("SELECT activation_place.id as activation_place_id , pharmacy.id as pharmacy_id , pharmacy_name , pharmacy.ser_id as pharmacy_ser_id , pharmacy.logo , license_img FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = 1 AND (pharmacy.ser_id = :search OR pharmacy_name = :search OR pharmacy.owner = :search)");
        $get_active_pharmacy->bindParam("search", $search);
        $get_active_pharmacy->execute();
        if ($get_active_pharmacy->rowCount() > 0) {
            $data_pharmacy_active = $get_active_pharmacy->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacy_active = array(["Error" => ":("]);
        }
        //Get From Pharmacy Table When Pharmacy Account Active

        $get_not_active_pharmacy = $database->prepare("SELECT activation_place.id as activation_place_id , pharmacy.id as pharmacy_id , pharmacy_name , pharmacy.ser_id as pharmacy_ser_id , pharmacy.logo , license_img FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = 0 AND (pharmacy.ser_id = :search OR pharmacy_name = :search OR pharmacy.owner = :search)");
        $get_not_active_pharmacy->bindParam("search", $search);
        $get_not_active_pharmacy->execute();
        if ($get_not_active_pharmacy->rowCount() > 0) {
            $data_pharmacy_not_active = $get_not_active_pharmacy->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacy_not_active = array(["Error" => ":("]);
        }


        $data_search = array(

            // Array Of All

            "data_doctor_active"            => $data_doctor_active,
            "data_doctor_not_active"        => $data_doctor_not_active,
            "data_pharmacist_active"        => $data_pharmacist_active,
            "data_pharmacist_not_active"    => $data_pharmacist_not_active,
            "data_clinic_active"            => $data_clinic_active,
            "data_clinic_not_active"        => $data_clinic_not_active,
            "data_pharmacy_active"          => $data_pharmacy_active,
            "data_pharmacy_not_active"      => $data_pharmacy_not_active

        );

        print_r(json_encode($data_search));

    } else {

        //Get From Doctor Table When Doctor Account Active

        $get_active_doctor = $database->prepare("SELECT activation_person.id as activation_person_id , doctor.id as doctor_id , doctor_name , ssd as doctor_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = 1");
        $get_active_doctor->execute();
        if ($get_active_doctor->rowCount() > 0) {
            $data_doctor_active = $get_active_doctor->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_doctor_active = array(["Error" => "لا يوجد دكتور"]);
        }
        //Get From Doctor Table When Doctor Account Not Active

        $get_not_active_doctor = $database->prepare("SELECT activation_person.id as activation_person_id , doctor.id as doctor_id , doctor_name , ssd as doctor_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM doctor,activation_person WHERE doctor.id = activation_person.doctor_id AND activation_person.isactive = 0 ");
        $get_not_active_doctor->execute();
        if ($get_not_active_doctor->rowCount() > 0) {
            $data_doctor_not_active = $get_not_active_doctor->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_doctor_not_active = array(["Error" => "لا يوجد دكتور"]);
        }


        //Get From Pharmacist Table When Pharmacist Account Active

        $get_active_pharmacist = $database->prepare("SELECT activation_person.id as activation_person_id , pharmacist.id as pharmacist_id , pharmacist_name , ssd as pharmacist_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = 1 ");
        $get_active_pharmacist->execute();
        if ($get_active_pharmacist->rowCount() > 0) {
            $data_pharmacist_active = $get_active_pharmacist->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacist_active = array(["Error" => "لا يوجد صيدلى"]);
        }
        //Get From Pharmacist Table When Pharmacist Account Not Active

        $get_not_active_pharmacist = $database->prepare("SELECT activation_person.id as activation_person_id , pharmacist.id as pharmacist_id , pharmacist_name , ssd as pharmacist_ssd , profile_img , front_nationtional_card , back_nationtional_card , graduation_cer , card_id_img FROM pharmacist,activation_person WHERE pharmacist.id = activation_person.pharmacist_id AND activation_person.isactive = 0 ");
        $get_not_active_pharmacist->execute();
        if ($get_not_active_pharmacist->rowCount() > 0) {
            $data_pharmacist_not_active = $get_not_active_pharmacist->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacist_not_active = array(["Error" => "لا يوجد صيدلى"]);
        }


        //Get From Clinic Table When Clinic Account Active

        $get_active_clinic = $database->prepare("SELECT activation_place.id as activation_place_id , clinic.id as clinic_id , clinic_name , clinic.ser_id as clinic_ser_id , clinic.logo , license_img FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = 1 ");
        $get_active_clinic->execute();
        if ($get_active_clinic->rowCount() > 0) {
            $data_clinic_active = $get_active_clinic->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_clinic_active = array(["Error" => "لا يوجد عيادة"]);
        }
        //Get From Clinic Table When Clinic Account Not Active

        $get_not_active_clinic = $database->prepare("SELECT activation_place.id as activation_place_id , clinic.id as clinic_id , clinic_name , clinic.ser_id as clinic_ser_id , clinic.logo , license_img FROM clinic,activation_place WHERE clinic.id = activation_place.clinic_id AND activation_place.isactive = 0 ");
        $get_not_active_clinic->execute();
        if ($get_not_active_clinic->rowCount() > 0) {
            $data_clinic_not_active = $get_not_active_clinic->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_clinic_not_active = array(["Error" => "لا يوجد عيادة"]);
        }

        //Get From Pharmacy Table When Pharmacy Account Active

        $get_active_pharmacy = $database->prepare("SELECT activation_place.id as activation_place_id , pharmacy.id as pharmacy_id , pharmacy_name , pharmacy.ser_id as pharmacy_ser_id , pharmacy.logo , license_img FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = 1 ");
        $get_active_pharmacy->execute();
        if ($get_active_pharmacy->rowCount() > 0) {
            $data_pharmacy_active = $get_active_pharmacy->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacy_active = array(["Error" => "لا يوجد صيدلية"]);
        }
        //Get From Pharmacy Table When Pharmacy Account Active

        $get_not_active_pharmacy = $database->prepare("SELECT activation_place.id as activation_place_id , pharmacy.id as pharmacy_id , pharmacy_name , pharmacy.ser_id as pharmacy_ser_id , pharmacy.logo , license_img FROM pharmacy,activation_place WHERE pharmacy.id = activation_place.pharmacy_id AND activation_place.isactive = 0 ");
        $get_not_active_pharmacy->execute();
        if ($get_not_active_pharmacy->rowCount() > 0) {
            $data_pharmacy_not_active = $get_not_active_pharmacy->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data_pharmacy_not_active = array(["Error" => "لا يوجد صيدلية"]);
        }


        $data_all = array(

            // Array Of All

            "data_doctor_active"            => $data_doctor_active,
            "data_doctor_not_active"        => $data_doctor_not_active,
            "data_pharmacist_active"        => $data_pharmacist_active,
            "data_pharmacist_not_active"    => $data_pharmacist_not_active,
            "data_clinic_active"            => $data_clinic_active,
            "data_clinic_not_active"        => $data_clinic_not_active,
            "data_pharmacy_active"          => $data_pharmacy_active,
            "data_pharmacy_not_active"      => $data_pharmacy_not_active

        );

        print_r(json_encode($data_all));
    }

} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية لعرض الاحصائيات"]));
}
?>
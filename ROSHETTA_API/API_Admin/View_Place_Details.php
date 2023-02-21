<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        //If Clinic Account

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            // Filter Data INT

            $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Clinic Table

            $get_clinic = $database->prepare("SELECT id,name,clinic_specialist,owner,phone_number,clinic_price,start_working,end_working,governorate,address,logo,ser_id FROM clinic WHERE clinic.id = :clinic_id");
            $get_clinic->bindParam("clinic_id", $clinic_id);
            $get_clinic->execute();

            if ($get_clinic->rowCount() > 0) {

                $data_clinic = $get_clinic->fetchAll(PDO::FETCH_ASSOC);

                //Get Doctor

                $get_doctor = $database->prepare("SELECT doctor.id,doctor.name,profile_img FROM doctor,clinic WHERE clinic.id = :clinic_id AND doctor.id = clinic.doctor_id");
                $get_doctor->bindParam("clinic_id", $clinic_id);
                $get_doctor->execute();

                if($get_doctor->rowCount() > 0 ){
                    $data_doctor = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data_doctor = null;
                }

                //Get Assistant

                $get_assistant = $database->prepare("SELECT  assistant.id,assistant.name,profile_img FROM assistant,clinic WHERE clinic.id = :clinic_id AND assistant.id = clinic.assistant_id");
                $get_assistant->bindParam("clinic_id", $clinic_id);
                $get_assistant->execute();

                if($get_assistant->rowCount() > 0 ){
                    $data_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data_assistant = null;
                }

                //Get Patient Number

                $get_patient = $database->prepare("SELECT patient.id FROM patient,clinic,appointment WHERE clinic.id = :clinic_id AND patient.id = appointment.patient_id AND clinic.id = appointment.clinic_id");
                $get_patient->bindParam("clinic_id", $clinic_id);
                $get_patient->execute();

                if($get_patient->rowCount() >= 0 ){
                    $data_patient = $get_patient->rowCount();
                } //*** */

                //Get Prescript Number

                $get_prescript = $database->prepare("SELECT prescript.id FROM prescript,clinic WHERE prescript.clinic_id = clinic.id AND clinic.id = :clinic_id");
                $get_prescript->bindParam("clinic_id", $clinic_id);
                $get_prescript->execute();

                if($get_prescript->rowCount() >= 0 ){
                    $data_prescript = $get_prescript->rowCount();
                } //**** */

                $data_all = [

                    "data_clinic"           => $data_clinic,
                    "data_doctor"           => $data_doctor,
                    "data_assistant"        => $data_assistant,
                    "Number_Of_Patient"     => $data_patient,
                    "Number_Of_Prescript"   => $data_prescript

                ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_all , $message , 200)));

            } else {
                $message = "معرف العيادة غير صحيح";
                print_r(json_encode(Message(null , $message , 200)));
            }

            //If Pharmacy Account

        } elseif (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            // Filter Data INT

            $pharmacy_id = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Pharmacy Table

            $get_pharmacy = $database->prepare("SELECT id,name,owner,phone_number,start_working,end_working,governorate,address,logo,ser_id FROM pharmacy WHERE pharmacy.id = :pharmacy_id");
            $get_pharmacy->bindParam("pharmacy_id", $pharmacy_id);
            $get_pharmacy->execute();

            if ($get_pharmacy->rowCount() > 0) {

                $data_pharmacy = $get_pharmacy->fetchAll(PDO::FETCH_ASSOC);

                //Get Pharmacist

                $get_pharmacist = $database->prepare("SELECT pharmacist.id,pharmacist.name,profile_img FROM pharmacist,pharmacy WHERE pharmacy.id = :pharmacy_id AND pharmacist.id = pharmacy.pharmacist_id");
                $get_pharmacist->bindParam("pharmacy_id", $pharmacy_id);
                $get_pharmacist->execute();

                if($get_pharmacist->rowCount() > 0 ){
                    $data_pharmacist = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $data_pharmacist = null;
                }

                //Get Prescript Number

                $get_prescript = $database->prepare("SELECT prescript.id FROM prescript,pharmacy,pharmacy_prescript WHERE prescript.id = pharmacy_prescript.prescript_id AND pharmacy.id = pharmacy_prescript.pharmacy_id AND pharmacy.id = :pharmacy_id");
                $get_prescript->bindParam("pharmacy_id", $pharmacy_id);
                $get_prescript->execute();

                if($get_prescript->rowCount() >= 0 ){
                    $data_prescript = $get_prescript->rowCount();
                } //*** */

                $data_all = [

                    "data_pharmacy"          => $data_pharmacy,
                    "data_pharmacist"        => $data_pharmacist,
                    "Number_Of_Prescript"    => $data_prescript

                ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_all , $message , 200)));

            } else {
                $message = "معرف الصيدلية غير صحيح";
                print_r(json_encode(Message(null , $message , 200)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
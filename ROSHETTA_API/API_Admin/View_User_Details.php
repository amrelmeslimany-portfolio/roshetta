<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        //If Patient Account

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            // Filter Data INT

            $patient_id = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Patient Table

            $get_patient = $database->prepare("SELECT id , patient_name , ssd , email , phone_number , gender , birth_date , weight , height , governorate , profile_img FROM patient WHERE id = :id");
            $get_patient->bindParam("id", $patient_id);
            $get_patient->execute();

            if ($get_patient->rowCount() > 0) {
                $data_patient = $get_patient->fetchAll(PDO::FETCH_ASSOC);
                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_patient , $message , 200)));

            } else {
                $Message = "معرف المستخدم غير صحيح";
                print_r(json_encode(Message(null,$Message,400)));
            }

            //If Doctor Account

        } elseif (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {

             // Filter Data INT

            $doctor_id = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Doctor Table

            $get_doctor = $database->prepare("SELECT id , doctor_name , ssd , email , phone_number , gender , birth_date , specialist , governorate , profile_img FROM doctor WHERE id = :id");
            $get_doctor->bindParam("id", $doctor_id);
            $get_doctor->execute();

            if ($get_doctor->rowCount() > 0) {
                $data_doctor = $get_doctor->fetchAll(PDO::FETCH_ASSOC);
                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_doctor , $message , 200)));
            } else {
                $Message = "معرف المستخدم غير صحيح";
                print_r(json_encode(Message(null,$Message,400)));
            }

            // If Pharmacist Account

        } elseif (isset($_POST['pharmacist_id']) && !empty($_POST['pharmacist_id'])) {

            // Filter Data INT

            $pharmacist_id = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Pharmacist Table

            $get_pharmacist = $database->prepare("SELECT id , pharmacist_name , ssd , email , phone_number , gender , birth_date , governorate , profile_img FROM pharmacist WHERE id = :id");
            $get_pharmacist->bindParam("id", $pharmacist_id);
            $get_pharmacist->execute();

            if ($get_pharmacist->rowCount() > 0) {
                $data_pharmacist = $get_pharmacist->fetchAll(PDO::FETCH_ASSOC);
                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_pharmacist , $message , 200)));
            } else {
                $Message = "معرف المستخدم غير صحيح";
                print_r(json_encode(Message(null,$Message,400)));
            }

            // If Assistant Account 

        } elseif (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            // Filter Data INT

            $assistant_id = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);

            // Get From Assistant Table

            $get_assistant = $database->prepare("SELECT id , assistant_name , ssd , email , phone_number , gender , birth_date , governorate , profile_img FROM assistant WHERE id = :id");
            $get_assistant->bindParam("id", $assistant_id);
            $get_assistant->execute();

            if ($get_assistant->rowCount() > 0) {
                $data_assistant = $get_assistant->fetchAll(PDO::FETCH_ASSOC);
                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_assistant , $message , 200)));
            } else {
                $Message = "معرف المستخدم غير صحيح";
                print_r(json_encode(Message(null,$Message,400)));
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
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        if (isset($_POST['prescript_id']) && !empty($_POST['prescript_id'])) {

            $prescript_id = filter_var($_POST['prescript_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT

            $check_prescript = $database->prepare("SELECT * FROM  prescript WHERE prescript.id = :prescript_id ");
            $check_prescript->bindparam("prescript_id", $prescript_id);
            $check_prescript->execute();

            if ($check_prescript->rowCount() > 0) {

                // Get From Disease , Prescript , Doctor , Clinic Table

                $get_prescript = $database->prepare("SELECT  prescript.ser_id as prescript_ser_id,creaded_date,patient.patient.name as patient_name,disease.name as disease_name,rediscovery_date,doctor.name as doctor_name ,doctor.specialist as doctor_specialist,logo as clinic_logo,clinic.name as clinic_name,clinic.phone_number as clinic_phone_number,address as clinic_address,start_working,end_working  
                                                                    FROM   disease,prescript,doctor,clinic,patient 
                                                                    WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id  AND prescript.id = :prescript_id ");

                $get_prescript->bindparam("prescript_id", $prescript_id);

                if ($get_prescript->execute()) {

                    if ($get_prescript->rowCount() > 0) {

                        $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                        // Get From Medicine 

                        $get_medicine = $database->prepare("SELECT medicine_data FROM medicine,patient,prescript WHERE medicine.prescript_id = prescript.id AND prescript.id = :prescript_id AND prescript.patient_id = patient.id ");

                        $get_medicine->bindparam("prescript_id", $prescript_id);
                        $get_medicine->execute();

                        if ($get_medicine->rowCount() > 0) {

                            $get_medicine = $get_medicine->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($get_medicine as $key => $value) { //Foreach Data As Key , Value

                                $array_value = $value["medicine_data"]; //Determine Medicine Data
                                $data_decode = unserialize(base64_decode($array_value)); // Decode Medicine Data
                                $medicine_data_array = array($data_decode); //Medicine Data In Array For Print

                            }

                            $data = [
                                // All Data In Array For Print 

                                "prescript_data"    => $get_prescript,
                                "medicine_data"     => $medicine_data_array
                            ];

                            $Message = "تم جلب البيانات ";
                            print_r(json_encode(Message($data , $Message, 200)));

                        } else {
                            $Message = "لم يتم العثور على بيانات";
                            print_r(json_encode(Message(null, $Message, 204)));
                        }
                    } else {
                        $Message = "لم يتم العثور على بيانات";
                        print_r(json_encode(Message(null, $Message, 204)));
                    }
                } else {
                    $Message = "فشل جلب البيانات";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "معرف الروشتة غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
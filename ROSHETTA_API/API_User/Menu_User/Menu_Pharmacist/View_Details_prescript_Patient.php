<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin


    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        if (isset($_POST['prescript_id']) && !empty($_POST['prescript_id'])) {

            require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

            $prescript_id = filter_var($_POST['prescript_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT

            $check_prescript = $database->prepare("SELECT * FROM  prescript WHERE prescript.id = :prescript_id ");

            $check_prescript->bindparam("prescript_id", $prescript_id);
            $check_prescript->execute();

            if ($check_prescript->rowCount() > 0) {

                // Get From Disease , Prescript , Doctor , Clinic Table

                $get_prescript = $database->prepare("SELECT  prescript.ser_id as prescript_ser_id,creaded_date,patient.patient_name,disease_name,rediscovery_date,doctor_name,doctor.specialist as doctor_specialist,logo as clinic_logo,clinic_name,clinic.phone_number as clinic_phone_number,address as clinic_address,start_working,end_working  
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

                            $data_message_value = array(
                                
                                // All Data In Array For Print 

                                "prescript_data" => $get_prescript,
                                "medicine_data" => $medicine_data_array
                            );

                            print_r(json_encode($data_message_value)); //Print Data

                        } else {
                            print_r(json_encode(["Error" => "لم يتم العثور على بيانات"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "لم يتم العثور على بيانات"]));
                    }

                } else {
                    print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                }

            } else {
                print_r(json_encode(["Error" => "معرف الروشتة غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على معرف الروشتة"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك القيام بالعرض"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
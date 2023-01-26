<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        if (isset($_POST['type']) && !empty($_POST['type'])) {

            if (isset($_POST['number']) && !empty($_POST['number'])) {

                if ($_POST['type'] === 'ssd') {

                    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

                    $number = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT);

                    // Get From Patient And Prescript Table

                    $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,ser_id as prescript_ser_id,creaded_date,patient_name  FROM prescript,patient  
                                                        WHERE prescript.patient_id = patient.id AND patient.ssd = :ssd  ORDER BY creaded_date DESC ");

                    $get_prescript->bindparam("ssd", $number);

                    if ($get_prescript->execute()) {

                        if ($get_prescript->rowCount() > 0) {

                            $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                            print_r(json_encode($get_prescript));

                        } else {
                            print_r(json_encode(["Error" => "لم يتم العثور على اي روشتة"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                    }

                } elseif ($_POST['type'] === 'ser_id') {

                    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

                    $number = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT);

                    $check_prescript = $database->prepare("SELECT * FROM  prescript WHERE prescript.ser_id = :ser_id ");

                    $check_prescript->bindparam("ser_id", $number);
                    $check_prescript->execute();

                    if ($check_prescript->rowCount() > 0) {

                        // Get From Disease , Prescript , Doctor , Clinic Table

                        $get_prescript = $database->prepare("SELECT  prescript.ser_id as prescript_ser_id,creaded_date,patient.patient_name,disease_name,rediscovery_date,doctor_name,doctor.specialist as doctor_specialist,logo as clinic_logo,clinic_name,clinic.phone_number as clinic_phone_number,address as clinic_address,start_working,end_working  
                                                                FROM   disease,prescript,doctor,clinic,patient 
                                                                WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id  AND prescript.ser_id = :ser_id ");

                        $get_prescript->bindparam("ser_id", $number);

                        if ($get_prescript->execute()) {

                            if ($get_prescript->rowCount() > 0) {

                                $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                                // Get From Medicine 

                                $get_medicine = $database->prepare("SELECT medicine_data FROM medicine,patient,prescript WHERE medicine.prescript_id = prescript.id AND prescript.ser_id = :ser_id AND prescript.patient_id = patient.id ");

                                $get_medicine->bindparam("ser_id", $number);
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
                    print_r(json_encode(["Error" => "فشل العثور على نوع البحث"]));
                }
            } else {
                print_r(json_encode(["Error" => "لم يتم ادخال الرقم القومى او معرف الروشتة"]));
            }

        } else {
            print_r(json_encode(["Error" => "لم يتم تحديد نوع البحث"]));
        }

    } else {
        print_r(json_encode(["Error" => "غير مسموح لك القيام بالعرض"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
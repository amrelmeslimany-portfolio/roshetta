<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Allow Access Via 'POST' Method Only

    session_start();
    session_regenerate_id();

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        if (isset($_POST['prescript_id']) && !empty($_POST['prescript_id'])) {

            require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

            $prescript_id = filter_var($_POST['prescript_id'], FILTER_SANITIZE_NUMBER_INT);

            $check_prescript = $database->prepare("SELECT * FROM  prescript WHERE prescript.id = :prescript_id ");

            $check_prescript->bindparam("prescript_id", $prescript_id);
            $check_prescript->execute();

            if ($check_prescript->rowCount() > 0) {

                // Get From Disease , Prescript , Medicine , Doctor , Clinic Table

                $get_prescript = $database->prepare("SELECT  prescript.ser_id as prescript_ser_id,creaded_date,patient.patient_name,disease_name,rediscovery_date,doctor_name,doctor.specialist as doctor_specialist,medicine_name,medicine_size,duration,description,logo as clinic_logo,clinic_name,clinic.phone_number as clinic_phone_number,address as clinic_address,start_working,end_working  
                                                                    FROM   disease,prescript,medicine,doctor,clinic,patient  
                                                                    WHERE  disease.id = prescript.disease_id AND prescript.patient_id = patient.id AND prescript.doctor_id = doctor.id AND prescript.clinic_id = clinic.id AND prescript.id = medicine.prescript_id AND prescript.id = :prescript_id ");

                $get_prescript->bindparam("prescript_id", $prescript_id);

                if ($get_prescript->execute()) {

                    if ($get_prescript->rowCount() > 0) {

                        $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                        print_r(json_encode($get_prescript));

                    } else {
                        print_r(json_encode(["Error" => "لم يتم العثور على اي بيانات"]));
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
        print_r(json_encode(["Error" => "غير مسرح لك عرض الروشتة"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
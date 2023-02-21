<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient'];

        // Get From Disease And Prescript Table

        $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,ser_id as prescript_ser_id,creaded_date,disease.name as disease_name  FROM  disease,prescript  
                                                    WHERE disease.id = prescript.disease_id AND prescript.patient_id = :id  ORDER BY creaded_date DESC ");

        $get_prescript->bindparam("id", $id);

        if ($get_prescript->execute()) {

            if ($get_prescript->rowCount() > 0) {

                $data_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);
                $Message = "تم جلب البيانات ";
                print_r(json_encode(Message($data_prescript, $Message, 200)));

            } else {
                $Message = "لم يتم العثور على اي روشتة";
                print_r(json_encode(Message(null, $Message, 204)));
            }
        } else {
            $Message = "فشل جلب البيانات";
            print_r(json_encode(Message(null, $Message, 422)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
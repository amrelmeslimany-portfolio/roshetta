<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        if (isset($_POST['disease_id']) && !empty($_POST['disease_id'])) {

            $disease_id = filter_var($_POST['disease_id'], FILTER_SANITIZE_NUMBER_INT);  // Filter 'INT'

            //Check Disease

            $check_disease = $database->prepare("SELECT * FROM  disease WHERE disease.id = :disease_id ");
            $check_disease->bindparam("disease_id", $disease_id);
            $check_disease->execute();

            if ($check_disease->rowCount() > 0) {

                $check_prescript = $database->prepare("SELECT prescript.id as prescript_id , prescript.ser_id as prescript_ser_id , disease.name as disease_name FROM prescript , disease WHERE prescript.disease_id = disease.id AND disease.id = :disease_id ");
                $check_prescript->bindparam("disease_id", $disease_id);
                $check_prescript->execute();

                if ($check_prescript->rowCount() > 0) {

                    $prescript_data = $check_prescript->fetchAll(PDO::FETCH_ASSOC);

                    $Message = "تم جلب البيانات ";
                    print_r(json_encode(Message($prescript_data, $Message, 200)));

                } else {
                    $Message = "لم يتم العثور على اي روشتة";
                    print_r(json_encode(Message(null, $Message, 204)));
                }
            } else {
                $Message = "رقم المرض غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null , $message , 400)));
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
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['patient'])) {

        $id = $_SESSION['patient']->id;

        // Get From Disease Table

        $get_disease = $database->prepare("SELECT disease_name,disease_place,disease_date  FROM  disease  WHERE  patient_id = :id  ORDER BY disease.disease_date DESC ");
        $get_disease->bindparam("id", $id);
        $get_disease->execute();

        if ($get_disease->rowCount() > 0) {

            $data_disease = $get_disease->fetchAll(PDO::FETCH_ASSOC);
            $Message = "تم جلب البيانات ";
            print_r(json_encode(Message($data_disease, $Message, 200)));

        } else {
            $Message = "لم يتم العثور على بيانات";
            print_r(json_encode(Message(null, $Message, 204)));
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
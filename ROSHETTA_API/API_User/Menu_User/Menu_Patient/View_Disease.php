<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

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

            $get_disease = $get_disease->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_disease));

        } else {
            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك القيام بالحجز"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
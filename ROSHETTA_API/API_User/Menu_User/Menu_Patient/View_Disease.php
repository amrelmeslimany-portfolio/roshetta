<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['patient'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $id = $_SESSION['patient']->id;

    // Get From Disease Table

    $get_disease = $database->prepare("SELECT disease_name,disease_place  FROM  disease  WHERE  patient_id = :id  ORDER BY disease.id DESC ");

    $get_disease->bindparam("id", $id);

    if ($get_disease->execute()) {

        $get_disease = $get_disease->fetchAll(PDO::FETCH_ASSOC);

        print_r(json_encode($get_disease));

    } else {
        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
    }

} else {
    print_r(json_encode(["Error" => "غير مسموح لك القيام بالحجز"]));
}
?>
<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['patient'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $id = $_SESSION['patient']->id;

    // Get From Disease And Prescript Table

    $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,ser_id as prescript_ser_id,creaded_date,disease_name  FROM  disease,prescript  
                                                    WHERE disease.id = prescript.disease_id AND prescript.patient_id = :id  ORDER BY creaded_date DESC ");

    $get_prescript->bindparam("id", $id);

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

} else {
    print_r(json_encode(["Error" => "غير مسموح لك القيام بالعرض"]));
}
?>
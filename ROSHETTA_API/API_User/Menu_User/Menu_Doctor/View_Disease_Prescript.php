<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

        if (isset($_POST['disease_id']) && !empty($_POST['disease_id'])) {

            $disease_id = filter_var($_POST['disease_id'], FILTER_SANITIZE_NUMBER_INT);

            $check_disease = $database->prepare("SELECT * FROM  disease WHERE disease.id = :disease_id ");

            $check_disease->bindparam("disease_id", $disease_id);
            $check_disease->execute();

            if ($check_disease->rowCount() > 0) {

                $check_prescript = $database->prepare("SELECT prescript.id as prescript_id , prescript.ser_id as prescript_ser_id , disease.disease_name FROM prescript , disease WHERE prescript.disease_id = disease.id AND disease.id = :disease_id ");

                $check_prescript->bindparam("disease_id", $disease_id);
                $check_prescript->execute();

                if ($check_prescript->rowCount() > 0) {

                    $prescript_data = $check_prescript->fetchAll(PDO::FETCH_ASSOC);

                    print_r(json_encode($prescript_data));


                } else {
                    print_r(json_encode(["Error" => "لم يتم العثور على اي روشتة"]));
                }

            } else {
                print_r(json_encode(["Error" => "رقم المرض غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على المرض"]));
        }
    } else {
        print_r(json_encode(["Error" => "غير مسموح لك بعرض تلك التفاصيل"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
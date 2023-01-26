<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

    require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

    $pharmacy_id = $_SESSION['pharmacy']->id;

    if (isset($_POST['search']) && !empty($_POST['search'])) {

        $search = filter_var($_POST['search'], FILTER_SANITIZE_STRING);

        // Get From Prescript,Patient,Pharmacy_Prescript,Pharmacy  Table

        // If Input Search Name 

        $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,prescript.ser_id as prescript_ser_id,date_pay,patient_name  FROM prescript,patient,pharmacy_prescript,pharmacy 
                                                        WHERE prescript.patient_id = patient.id AND pharmacy.id = :pharmacy_id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id AND patient.patient_name = :search  ORDER BY date_pay DESC ");

        $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
        $get_prescript->bindparam("search", $search);
        $get_prescript->execute();

        if ($get_prescript->rowCount() > 0) {

            $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_prescript));

            die();

        } else {   

            //If Input Search SSD

            $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,prescript.ser_id as prescript_ser_id,date_pay,patient_name  FROM prescript,patient,pharmacy_prescript,pharmacy 
                                                        WHERE prescript.patient_id = patient.id AND pharmacy.id = :pharmacy_id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id AND patient.ssd = :search  ORDER BY date_pay DESC ");

            $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
            $get_prescript->bindparam("search", $search);
            $get_prescript->execute();

            if ($get_prescript->rowCount() > 0) {

                $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                print_r(json_encode($get_prescript));

                die();

            } else {

                // If Input Search Prescript Ser_id

                $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,prescript.ser_id as prescript_ser_id,date_pay,patient_name  FROM prescript,patient,pharmacy_prescript,pharmacy 
                                                        WHERE prescript.patient_id = patient.id AND pharmacy.id = :pharmacy_id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id AND prescript.ser_id = :search  ORDER BY date_pay DESC ");

                $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
                $get_prescript->bindparam("search", $search);
                $get_prescript->execute();

                if ($get_prescript->rowCount() > 0) {

                    $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

                    print_r(json_encode($get_prescript));

                    die();

                } else {
                    print_r(json_encode(["Error" => "لم يتم العثور على اي روشتة"]));
                }
            }
        }

    } else {   // If There Is No Search

        // Get From Prescript,Patient,Pharmacy_Prescript,Pharmacy  Table

        $get_prescript = $database->prepare("SELECT prescript.id as prescript_id,prescript.ser_id as prescript_ser_id,date_pay,patient_name  FROM prescript,patient,pharmacy_prescript,pharmacy 
                                                        WHERE prescript.patient_id = patient.id AND pharmacy.id = :pharmacy_id AND pharmacy_prescript.pharmacy_id = pharmacy.id AND pharmacy_prescript.prescript_id = prescript.id  ORDER BY date_pay DESC ");

        $get_prescript->bindparam("pharmacy_id", $pharmacy_id);
        $get_prescript->execute();

        if ($get_prescript->rowCount() > 0) {

            $get_prescript = $get_prescript->fetchAll(PDO::FETCH_ASSOC);

            print_r(json_encode($get_prescript));

        } else {
            print_r(json_encode(["Error" => "لم يتم العثور على اي روشتة"]));
        }
    }

} else {
    print_r(json_encode(["Error" => "غير مسموح لك عرض الروشتات"]));
}
?>
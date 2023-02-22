<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            isset($_POST['activation_id']) && !empty($_POST['activation_id'])
            && isset($_POST['type'])        && !empty($_POST['type'])
        ) {

            $type   = $_POST['type'];
            $id     = filter_var($_POST['activation_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT

            if ($type == 'doctor' || $type == 'pharmacist') {

                // Get Image From Activation Person Table

                $get_img = $database->prepare("SELECT front_nationtional_card as front,back_nationtional_card as back,graduation_cer as graduation,card_id_img as card FROM activation_person WHERE id = :id");
                $get_img->bindparam("id", $id);
                $get_img->execute();

                if ($get_img->rowCount() > 0) {

                    $data_img = $get_img->fetchAll(PDO::FETCH_ASSOC);
                        
                } else {
                    $data_img = null;
                }
            } elseif ($type == 'clinic' || $type == 'pharmacy') {

                // Get Image From Activation Place Table

                $get_img = $database->prepare("SELECT license_img as license  FROM activation_place WHERE id = :id");
                $get_img->bindparam("id", $id);
                $get_img->execute();

                if ($get_img->rowCount() > 0) {

                    $data_img = $get_img->fetchAll(PDO::FETCH_ASSOC);
                        
                } else {
                    $data_img = null;
                }
            } else {
                $Message = "النوع غير معروف";
                print_r(json_encode(Message(null, $Message, 401)));
            }

                $message = "تم جلب البيانات بنجاح";
                print_r(json_encode(Message($data_img, $message, 200)));

        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}

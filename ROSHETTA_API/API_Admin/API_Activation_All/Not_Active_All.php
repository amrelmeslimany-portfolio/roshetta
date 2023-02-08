<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_POST['activation_person_id']) && !empty($_POST['activation_person_id'])) {

            //Filter Number INT

            $user_id = filter_var($_POST['activation_person_id'], FILTER_SANITIZE_NUMBER_INT);

            //Check Person

            $check_user = $database->prepare("SELECT * FROM activation_person WHERE id = :id ");
            $check_user->bindparam("id", $user_id);
            $check_user->execute();

            if ($check_user->rowCount() > 0) {

                //Update Activation_Person Table

                $Update = $database->prepare("UPDATE activation_person SET isactive = 0 WHERE id = :id ");
                $Update->bindparam("id", $user_id);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    $message = "تم الغاء التفعيل بنجاح";
                    print_r(json_encode(Message(null , $message , 200)));

                } else {
                    $message = "الحساب غير مفعل بالفعل";
                    print_r(json_encode(Message(null , $message , 202)));
                }
            } else {
                $message = "المعرف غير صحيح";
                print_r(json_encode(Message(null , $message , 400)));
            }

        } elseif (isset($_POST['activation_place_id']) && !empty($_POST['activation_place_id'])) {

            //Filter Number INT

            $place_id = filter_var($_POST['activation_place_id'], FILTER_SANITIZE_NUMBER_INT);

            //Check Place

            $check_place = $database->prepare("SELECT * FROM activation_place WHERE id = :id ");
            $check_place->bindparam("id", $place_id);
            $check_place->execute();

            if ($check_place->rowCount() > 0) {

                //Update Activation_Place Table

                $Update = $database->prepare("UPDATE activation_place SET isactive = 0 WHERE id = :id ");
                $Update->bindparam("id", $place_id);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    $message = "تم الغاء التفعيل بنجاح";
                    print_r(json_encode(Message(null , $message , 200)));

                } else {
                    $message = "الحساب غير مفعل بالفعل";
                    print_r(json_encode(Message(null , $message , 202)));
                }
            } else {
                $message = "المعرف غير صحيح";
                print_r(json_encode(Message(null , $message , 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        require_once("../../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {

            //Filter Number INT

            $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

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

                    print_r(json_encode(["Message" => "تم الغاء التفعيل بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "الحساب غير مفعل بالفعل"]));
                }

            } else {
                print_r(json_encode(["Error" => "المعرف غير صحيح"]));
            }

        } elseif (isset($_POST['place_id']) && !empty($_POST['place_id'])) {

            //Filter Number INT

            $place_id = filter_var($_POST['place_id'], FILTER_SANITIZE_NUMBER_INT);

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

                    print_r(json_encode(["Message" => "تم الغاء التفعيل بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "الحساب غير مفعل بالفعل"]));
                }

            } else {
                print_r(json_encode(["Error" => "المعرف غير صحيح"]));
            }

        } else {
            print_r(json_encode(["Error" => "يجب ارسال المعرف"]));
        }

    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
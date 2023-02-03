<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist']) && isset($_SESSION['pharmacy'])) {

        if (isset($_POST['prescript_id']) && !empty($_POST['prescript_id'])) {

            $pharmacy_id  = $_SESSION['pharmacy']->id;
            $prescript_id = filter_var($_POST['prescript_id'], FILTER_SANITIZE_NUMBER_INT);

            $check_pre = $database->prepare("SELECT * FROM prescript WHERE prescript.id = :prescript_id");
            $check_pre->bindParam("prescript_id", $prescript_id);
            $check_pre->execute();

            if ($check_pre->rowCount() > 0) {

                $add_pre_phar = $database->prepare("INSERT INTO pharmacy_prescript(prescript_id,pharmacy_id)
                                            VALUES(:prescript_id,:pharmacy_id)");

                $add_pre_phar->bindparam("prescript_id", $prescript_id);
                $add_pre_phar->bindparam("pharmacy_id", $pharmacy_id);
                $add_pre_phar->execute();

                if ($add_pre_phar->rowCount() > 0) {

                    print_r(json_encode(["Message" => "تم الصرف بنجاح"]));

                } else {
                    print_r(json_encode(["Error" => "فشل صرف الروشتة"]));
                }
            } else {
                print_r(json_encode(["Error" => "معرف الروشتة غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب ادخال البيانات بشكل صحيح"]));
        }
    } else {
        print_r(json_encode(["Error" => "لم يتم العثور على البيانات المطلوبة"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
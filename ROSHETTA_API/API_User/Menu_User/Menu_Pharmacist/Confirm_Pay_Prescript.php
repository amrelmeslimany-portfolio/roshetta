<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function

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

                    $Message = "تم الصرف بنجاح";
                    print_r(json_encode(Message(null, $Message, 201)));

                } else {
                    $Message = "فشل صرف الروشتة";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "معرف الروشتة غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
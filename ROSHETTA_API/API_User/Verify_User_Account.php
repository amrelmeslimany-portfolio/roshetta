<?php
require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 

if (isset($_GET['email']) && isset($_GET['code']) && isset($_GET['role'])) {

    $email          = $_GET['email'];
    $code           = $_GET['code'];
    $security_code  = md5(date('h:i:s Y-m-d'));

    if ($_GET['role'] == "PATIENT") {

        //Update Patient Table

        $update = $database->prepare("UPDATE patient SET email_isactive = 1 , security_code = :security_code WHERE email = :email AND security_code = :code");
        $update->bindparam("security_code", $security_code);
        $update->bindparam("email", $email);
        $update->bindparam("code", $code);
        $update->execute();

        if ($update->rowCount() > 0) {
            print_r(json_encode(["Message" => "تم تفعيل حسابك بنجاح"]));
        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } elseif ($_GET['role'] == "DOCTOR") {

        //Update Doctor Table

        $update = $database->prepare("UPDATE doctor SET email_isactive = 1 , security_code = :security_code WHERE email = :email AND security_code = :code");
        $update->bindparam("security_code", $security_code);
        $update->bindparam("email", $email);
        $update->bindparam("code", $code);
        $update->execute();

        if ($update->rowCount() > 0) {
            print_r(json_encode(["Message" => "تم تفعيل حسابك بنجاح"]));
        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } elseif ($_GET['role'] == "PHARMACIST") {

        //Update Pharmacist Table

        $update = $database->prepare("UPDATE pharmacist SET email_isactive = 1 , security_code = :security_code WHERE email = :email AND security_code = :code");
        $update->bindparam("security_code", $security_code);
        $update->bindparam("email", $email);
        $update->bindparam("code", $code);
        $update->execute();

        if ($update->rowCount() > 0) {
            print_r(json_encode(["Message" => "تم تفعيل حسابك بنجاح"]));
        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } elseif ($_GET['role'] == "ASSISTANT") {

        //Update Assistant Table

        $update = $database->prepare("UPDATE assistant SET email_isactive = 1 , security_code = :security_code WHERE email = :email AND security_code = :code");
        $update->bindparam("security_code", $security_code);
        $update->bindparam("email", $email);
        $update->bindparam("code", $code);
        $update->execute();

        if ($update->rowCount() > 0) {
            print_r(json_encode(["Message" => "تم تفعيل حسابك بنجاح"]));
        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } else {
        print_r(json_encode(["Error" => "فشل فى العثور على نوع الحساب"]));

    }
} else {
    print_r(json_encode(["Error" => "فشل فى العثور على البيانات"]));
}
?>
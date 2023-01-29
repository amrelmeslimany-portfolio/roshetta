<?php
require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 

if (isset($_GET['email']) && isset($_GET['code']) && isset($_GET['role'])) {

    $email          = $_GET['email'];
    $code           = $_GET['code'];
    $security_code  = md5(date('h:i:s Y-m-d'));
    $role           = $_GET['role'];

    if ($role == "PATIENT") {
        $table_name = 'patient';
    } elseif ($role == "DOCTOR") {
        $table_name = 'doctor';
    } elseif ($role == "PHARMACIST") {
        $table_name = 'pharmacist';
    } elseif ($role == "ASSISTANT") {
        $table_name = 'assistant';
    } else {
        $table_name = '';
    }

    //Activation User Account

    $update = $database->prepare("UPDATE $table_name SET email_isactive = 1 , security_code = :security_code WHERE email = :email AND security_code = :code");
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
    print_r(json_encode(["Error" => "فشل فى العثور على البيانات"]));
}
?>
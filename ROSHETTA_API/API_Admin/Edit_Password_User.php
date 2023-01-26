<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        require_once("../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = filter_var($_POST['patient_id'] , FILTER_SANITIZE_NUMBER_INT);

                    //UpDate Patient Table

                    $Update = $database->prepare("UPDATE patient SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");
                            
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = filter_var($_POST['doctor_id'] , FILTER_SANITIZE_NUMBER_INT);

                    //UpDate Doctor Table

                    $Update = $database->prepare("UPDATE doctor SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['pharmacist_id']) && !empty($_POST['pharmacist_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = filter_var($_POST['pharmacist_id'] , FILTER_SANITIZE_NUMBER_INT);

                    //UpDate Pharmacist Table

                    $Update = $database->prepare("UPDATE pharmacist SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = filter_var($_POST['assistant_id'] , FILTER_SANITIZE_NUMBER_INT);

                    //UpDate Assistant Table

                    $Update = $database->prepare("UPDATE assistant SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
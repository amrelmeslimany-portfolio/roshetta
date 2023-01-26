<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        require_once("../../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_SESSION['patient'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = $_SESSION['patient']->id;

                    //UpDate Patient Table

                    $Update = $database->prepare("UPDATE patient SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Patient Table

                        $get_data = $database->prepare("SELECT * FROM patient WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $patient_up = $get_data->fetchObject();
                            $_SESSION['patient'] = $patient_up; //UpDate SESSION Patient

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_SESSION['doctor'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = $_SESSION['doctor']->id;

                    //UpDate Doctor Table

                    $Update = $database->prepare("UPDATE doctor SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Doctor Table

                        $get_data = $database->prepare("SELECT * FROM doctor WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $doctor_up = $get_data->fetchObject();
                            $_SESSION['doctor'] = $doctor_up; //UpDate SESSION Doctor

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_SESSION['pharmacist'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = $_SESSION['pharmacist']->id;

                    //UpDate Pharmacist Table

                    $Update = $database->prepare("UPDATE pharmacist SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Pharmacist Table

                        $get_data = $database->prepare("SELECT * FROM pharmacist WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $pharmacist_up = $get_data->fetchObject();
                            $_SESSION['pharmacist'] = $pharmacist_up; //UpDate SESSION Pharmacist

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل كلمة المرور"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_SESSION['assistant'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) { //Verify password = confirm_password

                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash
                    $id            = $_SESSION['assistant']->id;

                    //UpDate Assistant Table

                    $Update = $database->prepare("UPDATE assistant SET password = :password WHERE id = :id ");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("password", $password_hash);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Assistant Table

                        $get_data = $database->prepare("SELECT * FROM assistant WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $assistant_up = $get_data->fetchObject();
                            $_SESSION['assistant'] = $assistant_up; //UpDate SESSION Assistant

                            print_r(json_encode(["Message" => "تم تعديل كلمة المرور بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
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
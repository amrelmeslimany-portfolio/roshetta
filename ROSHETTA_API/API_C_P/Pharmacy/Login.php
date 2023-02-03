<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist'])) {

        if ($_SESSION['pharmacist']->role === "PHARMACIST") {

            $ph_id = $_SESSION['pharmacist']->id;

            $checkActivation = $database->prepare("SELECT * FROM activation_person,pharmacist  WHERE  activation_person.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
            $checkActivation->bindparam("id", $ph_id);
            $checkActivation->execute();

            if ($checkActivation->rowCount() > 0) {

                $Activation = $checkActivation->fetchObject();

                if ($Activation->isactive == 1) {

                    //I Expect To Receive This Data

                    if (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

                        //Filter Data 'Int'

                        $pharmacy_id = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);

                        if (filter_var($pharmacy_id, FILTER_VALIDATE_INT) !== FALSE) {

                            //Check Clinic Table

                            $check_pharmacy = $database->prepare("SELECT * FROM pharmacy WHERE id = :pharmacy_id  AND pharmacist_id = :id ");
                            $check_pharmacy->bindparam("id", $ph_id);
                            $check_pharmacy->bindparam("pharmacy_id", $pharmacy_id);
                            $check_pharmacy->execute();

                            if ($check_pharmacy->rowCount() > 0) {

                                $pharmacy_login = $check_pharmacy->fetchObject();

                                print_r(json_encode(["Message" => "تم تسجيل الدخول الى الصيدلية"]));

                                $_SESSION['pharmacy'] = $pharmacy_login;

                            } else {
                                print_r(json_encode(["Error" => "فشل تسجيل الدخول"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "يجب ادخال رقم الصيدلية"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل الادمن"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب تفعيل الحساب"]));
            }
        } else {
            print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
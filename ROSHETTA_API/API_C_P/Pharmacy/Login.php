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
                                
                                $Message = "تم تسجيل الدخول الى الصيدلية";
                                print_r(json_encode(Message(null,$Message,200)));

                                $_SESSION['pharmacy'] = $pharmacy_login;

                            } else {
                                $Message = "فشل تسجيل الدخول";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {
                            $message = "المعرف الذى ادخلتة غير صالح";
                            print_r(json_encode(Message(null , $message , 400)));
                        }
                    } else {
                        $Message = "يجب اكمال البيانات";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $message = "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل المشرف";
                    print_r(json_encode(Message(null , $message , 202)));
                }
            } else {
                $message = "يجب تفعيل الحساب";
                print_r(json_encode(Message(null , $message , 202)));
            }
        } else {
            $message = "ليس لديك الصلاحية";
            print_r(json_encode(Message(null , $message , 403)));
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
<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['medicine']) && !empty($_POST['medicine'])) {

        if (isset($_SESSION['prescript']) && isset($_SESSION['doctor'])) {

            if ($_SESSION['doctor']->role === "DOCTOR") {

                $d_id = $_SESSION['doctor']->id;

                //Check Activation

                $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
                    $checkActivation->bindparam("id", $d_id);
                    $checkActivation->execute();

                if ($checkActivation->rowCount() > 0) {

                    $Activation = $checkActivation->fetchObject();

                    if ($Activation->isactive == 1) {

                        //Filter Data 'String'

                        $medicine_data = $_POST['medicine'];
                        $prescript_id = $_SESSION['prescript']->id;

                        // Hash Data With Base64

                        $data_hash = base64_encode(serialize($medicine_data));

                        //Add To Medicine Table

                        $addMedicine = $database->prepare("INSERT INTO medicine(medicine_data,prescript_id)VALUES(:medicine_data,:prescript_id)");

                        $addMedicine->bindparam("medicine_data", $data_hash);
                        $addMedicine->bindparam("prescript_id", $prescript_id);
                        $addMedicine->execute();

                        if ($addMedicine->rowCount() > 0 ) {

                            print_r(json_encode(["Message" => "تم اضافة الدواء بنجاح"]));

                            unset($_SESSION['disease']);
                            unset($_SESSION['prescript']);

                        } else {
                            print_r(json_encode(["Error" => "فشل اضافة الدواء"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرجاء الانتظار حتى يتم المراجعة من قبل الادمن"]));
                    }

                } else {
                    print_r(json_encode(["Error" => "يجب تفعيل الحساب"]));
                }
            } else {
                print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل العثور على الشيشن"]));
        }

    } else {
        print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
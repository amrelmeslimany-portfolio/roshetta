<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['medicine']) && !empty($_POST['medicine'])) {

        if (isset($_SESSION['prescript']) && isset($_SESSION['doctor'])) {

            $d_id = $_SESSION['doctor'];

            //Check Activation

            $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
            $checkActivation->bindparam("id", $d_id);
            $checkActivation->execute();

            if ($checkActivation->rowCount() > 0) {

                $Activation = $checkActivation->fetchObject();

                if ($Activation->isactive == 1) {

                    //Filter Data 'String'

                    $medicine_data  = $_POST['medicine'];
                    $prescript_id   = $_SESSION['prescript'];

                    // Hash Data With Base64

                    $data_hash = base64_encode(serialize($medicine_data));

                    //Add To Medicine Table

                    $addMedicine = $database->prepare("INSERT INTO medicine(medicine_data,prescript_id)VALUES(:medicine_data,:prescript_id)");
                    $addMedicine->bindparam("medicine_data", $data_hash);
                    $addMedicine->bindparam("prescript_id", $prescript_id);
                    $addMedicine->execute();

                    if ($addMedicine->rowCount() > 0) {

                        $Message = "تم اضافة الدواء بنجاح";
                        print_r(json_encode(Message(null, $Message, 201)));

                        unset($_SESSION['prescript']);

                    } else {
                        $Message = "فشل اضافة الدواء";
                        print_r(json_encode(Message(null, $Message, 422)));
                    }
                } else {
                    $Message = "الرجاء الانتظار حتى يتم تنشيط حسابك من قبل المشرف";
                    print_r(json_encode(Message(null, $Message, 202)));
                }
            } else {
                $Message = "يجب تفعيل الحساب";
                print_r(json_encode(Message(null, $Message, 202)));
            }
        } else {
            $Message = "فشل العثور على مستخدم";
            print_r(json_encode(Message(null, $Message, 401)));
        }
    } else {
        $Message = "يجب اكمال البيانات";
        print_r(json_encode(Message(null, $Message, 400)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
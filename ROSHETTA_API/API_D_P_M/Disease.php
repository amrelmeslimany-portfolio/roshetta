<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (
        isset($_POST['disease_name'])       && !empty($_POST['disease_name'])
        && isset($_POST['patient_id'])      && !empty($_POST['patient_id'])
        && isset($_POST['disease_place'])   && !empty($_POST['disease_place'])
    ) {

        if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

            $disease_name   = filter_var($_POST['disease_name'], FILTER_SANITIZE_STRING);
            $patient_id     = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
            $disease_place  = filter_var($_POST['disease_place'], FILTER_SANITIZE_STRING);
            $disease_date   = date('Y-m-d');
            $doctor_id      = $_SESSION['doctor'];
            $clinic_id      = $_SESSION['clinic'];

            //Check Activation

            $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
            $checkActivation->bindparam("id", $doctor_id);
            $checkActivation->execute();

            if ($checkActivation->rowCount() > 0) {

                $Activation = $checkActivation->fetchObject();

                if ($Activation->isactive == 1) {

                    //Filter Data 'String'

                    if (filter_var($patient_id, FILTER_VALIDATE_INT) !== FALSE) {

                        $checkpatient = $database->prepare("SELECT * FROM patient WHERE patient.id = :id ");
                        $checkpatient->bindparam("id", $patient_id);
                        $checkpatient->execute();

                        if ($checkpatient->rowCount() > 0) {

                            //Add TO Disease Table

                            $addDisease = $database->prepare("INSERT INTO disease(name,patient_id,disease_place,disease_date,doctor_id,clinic_id)
                                                                VALUES(:disease_name,:patient_id,:disease_place,:disease_date,:doctor_id,:clinic_id)");

                            $addDisease->bindparam("disease_name", $disease_name);
                            $addDisease->bindparam("patient_id", $patient_id);
                            $addDisease->bindparam("disease_place", $disease_place);
                            $addDisease->bindparam("disease_date", $disease_date);
                            $addDisease->bindparam("doctor_id", $doctor_id);
                            $addDisease->bindparam("clinic_id", $clinic_id);
                            $addDisease->execute();

                            if ($addDisease->rowCount() > 0) {

                                $get_disease = $database->prepare("SELECT id , patient_id FROM  disease WHERE  disease.patient_id = :pat_id AND disease.doctor_id = :doc_id AND disease.clinic_id = :cli_id AND disease.disease_date = :disease_date ");
                                $get_disease->bindparam("doc_id", $doctor_id);
                                $get_disease->bindparam("disease_date", $disease_date);
                                $get_disease->bindparam("pat_id", $patient_id);
                                $get_disease->bindparam("cli_id", $clinic_id);
                                $get_disease->execute();

                                if ($get_disease->rowCount() > 0) {

                                    $disease = $get_disease->fetchObject();

                                    $_SESSION['disease'] = $disease;

                                    $Message = "تم اضافة مرض";
                                    print_r(json_encode(Message(null, $Message, 201)));

                                } else {
                                    $Message = "فشل جلب المرض";
                                    print_r(json_encode(Message(null, $Message, 204)));
                                }
                            } else {
                                $Message = "فشل اضافة مرض";
                                print_r(json_encode(Message(null, $Message, 422)));
                            }
                        } else {
                            $Message = "رقم المريض غير صحيح";
                            print_r(json_encode(Message(null, $Message, 400)));
                        }
                    } else {
                        $Message = "يجب ادخال بيانات من نوع الارقام";
                        print_r(json_encode(Message(null, $Message, 400)));
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
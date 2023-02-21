<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (
        isset($_POST['rediscovery_date'])   && !empty($_POST['rediscovery_date'])
        && isset($_POST['patient_id'])      && !empty($_POST['patient_id'])
        && isset($_POST['disease_id'])      && !empty($_POST['disease_id'])
    ) {

        if (isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

            $doctor_id = $_SESSION['doctor'];

            //Check Activation

            $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
            $checkActivation->bindparam("id", $doctor_id);
            $checkActivation->execute();

            if ($checkActivation->rowCount() > 0) {

                $Activation = $checkActivation->fetchObject();

                if ($Activation->isactive == 1) {

                    $rediscovery_date   = $_POST['rediscovery_date'];
                    $patient_id         = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
                    $clinic_id          = $_SESSION['clinic'];
                    $disease_id         = filter_var($_POST['disease_id'], FILTER_SANITIZE_NUMBER_INT);
                    $ser_id             = rand(100000, 999999) . $patient_id;

                    if (filter_var($patient_id, FILTER_VALIDATE_INT) !== FALSE && filter_var($disease_id, FILTER_VALIDATE_INT) !== FALSE) {

                        $checkpatient = $database->prepare("SELECT * FROM patient WHERE patient.id = :id ");
                        $checkpatient->bindparam("id", $patient_id);
                        $checkpatient->execute();

                        if ($checkpatient->rowCount() > 0) {

                            $checkdisease = $database->prepare("SELECT * FROM disease WHERE disease.id = :id ");
                            $checkdisease->bindparam("id", $disease_id);
                            $checkdisease->execute();

                            if ($checkdisease->rowCount() > 0) {

                                //Add To Prescript Table

                                $addPrescript = $database->prepare("INSERT INTO prescript(rediscovery_date,patient_id,doctor_id,disease_id,clinic_id,ser_id)
                                                                        VALUES(:rediscovery_date,:patient_id,:doctor_id,:disease_id,:clinic_id,:ser_id)");

                                $addPrescript->bindparam("rediscovery_date", $rediscovery_date);
                                $addPrescript->bindparam("patient_id", $patient_id);
                                $addPrescript->bindparam("doctor_id", $doctor_id);
                                $addPrescript->bindparam("disease_id", $disease_id);
                                $addPrescript->bindparam("clinic_id", $clinic_id);
                                $addPrescript->bindparam("ser_id", $ser_id);
                                $addPrescript->execute();

                                if ($addPrescript->rowCount() > 0) {

                                    $get_Prescript = $database->prepare("SELECT id FROM  prescript WHERE prescript.doctor_id = :doc_id AND prescript.disease_id = :dis_id AND prescript.patient_id = :pat_id AND prescript.clinic_id = :cli_id ");
                                    $get_Prescript->bindparam("doc_id", $doctor_id);
                                    $get_Prescript->bindparam("dis_id", $disease_id);
                                    $get_Prescript->bindparam("pat_id", $patient_id);
                                    $get_Prescript->bindparam("cli_id", $clinic_id);
                                    $get_Prescript->execute();

                                    if ($get_Prescript->rowCount() > 0) {

                                        $prescript = $get_Prescript->fetchObject();

                                        $_SESSION['prescript'] = $prescript;

                                        $Message = "تم وضع الروشتة بنجاح جارى التجهيز لاضافة الادوية";
                                        print_r(json_encode(Message(null, $Message, 201)));

                                    } else {
                                        $Message = "فشل جلب الروشتة";
                                        print_r(json_encode(Message(null, $Message, 204)));
                                    }
                                } else {
                                    $Message = "فشل وضع الروشتة";
                                    print_r(json_encode(Message(null, $Message, 422)));
                                }
                            } else {
                                $Message = "رقم المرض غير صحيح";
                                print_r(json_encode(Message(null, $Message, 400)));
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
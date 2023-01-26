<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['rediscovery_date']) && ! empty($_POST['rediscovery_date'])) {

        if (isset($_SESSION['disease']) && isset($_SESSION['doctor']) && isset($_SESSION['clinic'])) {

            if ($_SESSION['doctor']->role === "DOCTOR") {

                $d_id = $_SESSION['doctor']->id;

                //Check Activation

                $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
                $checkActivation->bindparam("id", $d_id);
                $checkActivation->execute();

                if ($checkActivation->rowCount() > 0) {

                    $Activation = $checkActivation->fetchObject();

                    if ($Activation->isactive == 1) {

                        $rediscovery_date    = $_POST['rediscovery_date'];
                        $patient_id          = $_SESSION['disease']->patient_id;
                        $doctor_id           = $_SESSION['doctor']->id;
                        $disease_id          = $_SESSION['disease']->id;
                        $clinic_id           = $_SESSION['clinic']->id;
                        $ser_id              = rand(0, 1000000) . $patient_id;

                        if (filter_var($patient_id, FILTER_VALIDATE_INT) !== FALSE) {

                            //Add To Prescript Table

                            $addPrescript = $database->prepare("INSERT INTO prescript(rediscovery_date,patient_id,doctor_id,disease_id,clinic_id,ser_id)
                                                                                    VALUES(:rediscovery_date,:patient_id,:doctor_id,:disease_id,:clinic_id,:ser_id)");

                            $addPrescript->bindparam("rediscovery_date", $rediscovery_date);
                            $addPrescript->bindparam("patient_id", $patient_id);
                            $addPrescript->bindparam("doctor_id", $doctor_id);
                            $addPrescript->bindparam("disease_id", $disease_id);
                            $addPrescript->bindparam("clinic_id", $clinic_id);
                            $addPrescript->bindparam("ser_id", $ser_id);

                            if ($addPrescript->execute()) {

                                if ($addPrescript->rowCount() > 0) {

                                    $get_Prescript = $database->prepare("SELECT * FROM  prescript WHERE prescript.doctor_id = :doc_id AND prescript.disease_id = :dis_id AND prescript.patient_id = :pat_id AND prescript.clinic_id = :cli_id ");
                                    $get_Prescript->bindparam("doc_id", $doctor_id);
                                    $get_Prescript->bindparam("dis_id", $disease_id);
                                    $get_Prescript->bindparam("pat_id", $patient_id);
                                    $get_Prescript->bindparam("cli_id", $clinic_id);

                                    if ($get_Prescript->execute()) {

                                        if ($get_Prescript->rowCount() > 0) {

                                            $prescript = $get_Prescript->fetchObject();

                                            $_SESSION['prescript'] = $prescript;
                                            
                                            print_r(json_encode(["Message" => "تم وضع الروشتة بنجاح جارى التجهيز لاضافة الادوية"]));

                                        } else {
                                            print_r(json_encode(["Error" => "فشل جلب الروشتة"]));
                                        }
                                    } else {
                                        print_r(json_encode(["Error" => "فشل جلب الروشتة"]));
                                    }
                                } else {
                                    print_r(json_encode(["Error" => "فشل وضع الروشتة"]));
                                }   
                            } else {
                                print_r(json_encode(["Error" => "فشل وضع الروشتة"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
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
            print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
        }
    } else {
        print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
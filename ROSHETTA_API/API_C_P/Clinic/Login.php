<?php

date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    $time = time() - (2 * 24 * 60 * 60);
    $date = date('Y-m-d' , $time);

    if (isset($_SESSION['doctor'])) {

        if ($_SESSION['doctor']->role === "DOCTOR") {

            $d_id = $_SESSION['doctor']->id;

            $checkActivation = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
            $checkActivation->bindparam("id", $d_id);
            $checkActivation->execute();

            if ($checkActivation->rowCount() > 0) {

                $Activation = $checkActivation->fetchObject();

                if ($Activation->isactive == 1) {

                    //I Expect To Receive This Data

                    if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

                        //Filter Data 'Int'

                        $clinic_id = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);


                        if (filter_var($clinic_id, FILTER_VALIDATE_INT) !== FALSE) {

                            //Check Clinic Table

                            $check_clinic = $database->prepare("SELECT * FROM clinic WHERE id = :clinic_id   AND doctor_id = :id ");
                            $check_clinic->bindparam("id", $d_id);
                            $check_clinic->bindparam("clinic_id", $clinic_id);
                            $check_clinic->execute();

                            if ($check_clinic->rowCount() > 0) {

                                $clinic_login = $check_clinic->fetchObject();

                                print_r(json_encode(["Message" => "تم تسجيل الدخول الى العيادة"]));

                                $_SESSION['clinic'] = $clinic_login;

                                //Delete Old Appointments 

                                $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appoint_date = :appoint_date AND clinic_id = :clinic_id  AND appoint_case = 0");
                                $delete_appoint->bindparam("appoint_date", $date);
                                $delete_appoint->bindparam("clinic_id", $clinic_id);
                                $delete_appoint->execute();

                            } else {
                                print_r(json_encode(["Error" => "فشل تسجيل الدخول"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "يجب ادخال رقم العيادة"]));
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

    } elseif (isset($_SESSION['assistant'])) {

        if ($_SESSION['assistant']->role === "ASSISTANT") {

            //I Expect To Receive This Data

            if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

                $clinic_id  = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
                $a_id       = $_SESSION['assistant']->id;

                //Filter Data 'Int'

                if (filter_var($clinic_id, FILTER_VALIDATE_INT) !== FALSE) {

                    //Check Clinic Table

                    $check_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id   AND assistant_id = :id ");
                    $check_clinic->bindparam("id", $a_id);
                    $check_clinic->bindparam("clinic_id", $clinic_id);
                    $check_clinic->execute();

                    if ($check_clinic->rowCount() > 0) {

                        $clinic_login = $check_clinic->fetchObject();
                        print_r(json_encode(["Message" => "تم تسجيل الدخول الى العيادة"]));

                        $_SESSION['clinic'] = $clinic_login;

                        //Delete Old Appointments

                        $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appoint_date = :appoint_date AND clinic_id = :clinic_id  AND appoint_case = 0");
                        $delete_appoint->bindparam("appoint_date", $date);
                        $delete_appoint->bindparam("clinic_id", $clinic_id);
                        $delete_appoint->execute();

                    } else {
                        print_r(json_encode(["Error" => "فشل تسجيل الدخول"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب ادخال رقم العيادة"]));
            }
        } else {
            print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على السيشن"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
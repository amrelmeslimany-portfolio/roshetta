<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    $time = time() - (2 * 24 * 60 * 60);
    $date = date('Y-m-d', $time);

    if (isset($_SESSION['doctor'])) {

        $d_id = $_SESSION['doctor'];

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

                        $check_clinic = $database->prepare("SELECT * FROM clinic WHERE id = :clinic_id AND doctor_id = :id ");
                        $check_clinic->bindparam("id", $d_id);
                        $check_clinic->bindparam("clinic_id", $clinic_id);
                        $check_clinic->execute();

                        if ($check_clinic->rowCount() > 0) {

                            $clinic_login = $check_clinic->fetchObject();

                            $Data = [
                                "id"            => $clinic_login->id,
                                "name"          => $clinic_login->name,
                                "ser_id"        => $clinic_login->ser_id,
                                "type"          => "CLINIC",
                                "image"         => $clinic_login->logo
                            ];

                            $Message = "تم تسجيل الدخول الى العيادة";
                            print_r(json_encode(Message($Data, $Message, 200)));

                            $_SESSION['clinic'] = $clinic_login->id;

                            //Delete Old Appointments 

                            $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appoint_date = :appoint_date AND clinic_id = :clinic_id  AND appoint_case = 0");
                            $delete_appoint->bindparam("appoint_date", $date);
                            $delete_appoint->bindparam("clinic_id", $clinic_id);
                            $delete_appoint->execute();

                            if ($delete_appoint->rowCount() > 0) {
                                # code...
                            } else {
                                # code...
                            }

                        } else {
                            $Message = "فشل تسجيل الدخول";
                            print_r(json_encode(Message(null, $Message, 422)));
                        }
                    } else {
                        $message = "المعرف الذى ادخلتة غير صالح";
                        print_r(json_encode(Message(null, $message, 400)));
                    }
                } else {
                    $Message = "يجب اكمال البيانات";
                    print_r(json_encode(Message(null, $Message, 400)));
                }
            } else {
                $message = "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل المشرف";
                print_r(json_encode(Message(null, $message, 202)));
            }
        } else {
            $message = "يجب تفعيل الحساب";
            print_r(json_encode(Message(null, $message, 202)));
        }
    } elseif (isset($_SESSION['assistant'])) {

        //I Expect To Receive This Data

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            $clinic_id  = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
            $a_id       = $_SESSION['assistant'];

            //Filter Data 'Int'

            if (filter_var($clinic_id, FILTER_VALIDATE_INT) !== FALSE) {

                //Check Clinic Table

                $check_clinic = $database->prepare("SELECT * FROM clinic WHERE clinic.id = :clinic_id   AND assistant_id = :id ");
                $check_clinic->bindparam("id", $a_id);
                $check_clinic->bindparam("clinic_id", $clinic_id);
                $check_clinic->execute();

                if ($check_clinic->rowCount() > 0) {

                    $clinic_login = $check_clinic->fetchObject();

                    $Message = "تم تسجيل الدخول الى العيادة";
                    print_r(json_encode(Message(null, $Message, 200)));

                    $_SESSION['clinic'] = $clinic_login->id;

                    //Delete Old Appointments

                    $delete_appoint = $database->prepare("DELETE FROM appointment WHERE appoint_date = :appoint_date AND clinic_id = :clinic_id  AND appoint_case = 0");
                    $delete_appoint->bindparam("appoint_date", $date);
                    $delete_appoint->bindparam("clinic_id", $clinic_id);
                    $delete_appoint->execute();

                    if ($delete_appoint->rowCount() > 0) {
                        # code...
                    } else {
                        # code...
                    }

                } else {
                    $Message = "فشل تسجيل الدخول";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $message = "المعرف الذى ادخلتة غير صالح";
                print_r(json_encode(Message(null, $message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
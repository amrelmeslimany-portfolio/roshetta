<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function
require_once("../../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            isset($_POST['activation_id']) && !empty($_POST['activation_id'])
            && isset($_POST['type']) && !empty($_POST['type'])
        ) {

            $type = $_POST['type'];
            $id   = filter_var($_POST['activation_id'], FILTER_SANITIZE_NUMBER_INT); //Filter Number INT

            if ($type == 'doctor' || $type == 'pharmacist') {

                //Check Person

                $check_user = $database->prepare("SELECT * FROM activation_person WHERE activation_person.id = :id");
                $check_user->bindparam("id", $id);
                $check_user->execute();

                if ($check_user->rowCount() > 0) {

                    //Update Activation_Person Table

                    $Update = $database->prepare("UPDATE activation_person SET isactive = 1 WHERE activation_person.id = :id");
                    $Update->bindparam("id", $id);
                    $Update->execute();

                    if ($Update->rowCount() > 0) {

                        $get_doctor = $database->prepare("SELECT name,email FROM doctor,activation_person WHERE activation_person.id = :id AND activation_person.doctor_id = doctor.id AND isactive = 1");
                        $get_doctor->bindparam("id", $id);
                        $get_doctor->execute();

                        if ($get_doctor->rowCount() > 0) {

                            $data_user = $get_doctor->fetchObject();

                            $name   = $data_user->name;
                            $email  = $data_user->email;
                        } else {
                            $get_pharmacist = $database->prepare("SELECT name,email FROM pharmacist,activation_person WHERE activation_person.id = :id AND activation_person.pharmacist_id = pharmacist.id AND isactive = 1");
                            $get_pharmacist->bindparam("id", $id);
                            $get_pharmacist->execute();

                            if ($get_pharmacist->rowCount() > 0) {

                                $data_user = $get_pharmacist->fetchObject();

                                $name   = $data_user->name;
                                $email  = $data_user->email;
                            } else {
                                $data_user = '';
                            }
                        }

                        $message = "تم التفعيل بنجاح";
                        print_r(json_encode(Message(null, $message, 200)));

                        //Send  Message To Login

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Activation');
                        $mail->addAddress($email);
                        $mail->Subject = 'تهنئة لتفعيل حسابك';
                        $mail->Body = EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لقد تم تنشيط حسابك يمكنك الأن العمل والإستمتاع بكافة المميزات </p></br>         
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                        ');

                        $mail->send();
                    } else {
                        $message = "الحساب مفعل بالفعل";
                        print_r(json_encode(Message(null, $message, 202)));
                    }
                } else {
                    $message = "المعرف غير صحيح";
                    print_r(json_encode(Message(null, $message, 400)));
                }
            } elseif ($type == 'clinic' || $type == 'pharmacy') {

                //Check Place

                $check_place = $database->prepare("SELECT * FROM activation_place WHERE id = :id ");
                $check_place->bindparam("id", $id);
                $check_place->execute();

                if ($check_place->rowCount() > 0) {

                    //Update Activation_Place Table

                    $Update = $database->prepare("UPDATE activation_place SET isactive = 1 WHERE id = :id ");
                    $Update->bindparam("id", $id);
                    $Update->execute();

                    if ($Update->rowCount() > 0) {

                        $get_doctor = $database->prepare("SELECT doctor.name,email FROM doctor,activation_place,clinic WHERE activation_place.id = :id AND activation_place.clinic_id = clinic.id AND clinic.doctor_id = doctor.id AND isactive = 1");
                        $get_doctor->bindparam("id", $id);
                        $get_doctor->execute();

                        if ($get_doctor->rowCount() > 0) {

                            $data_user = $get_doctor->fetchObject();
                            $name   = $data_user->name;
                            $email  = $data_user->email;
                        } else {

                            $get_pharmacist = $database->prepare("SELECT pharmacist.name,email FROM pharmacist,activation_place,pharmacy WHERE activation_place.id = :id AND activation_place.pharmacy_id = pharmacy.id AND pharmacy.pharmacist_id = pharmacist.id AND isactive = 1");
                            $get_pharmacist->bindparam("id", $id);
                            $get_pharmacist->execute();

                            if ($get_pharmacist->rowCount() > 0) {

                                $data_user = $get_pharmacist->fetchObject();

                                $name   = $data_user->name;
                                $email  = $data_user->email;
                            } else {
                                $data_user = '';
                            }
                        }

                        $message = "تم التفعيل بنجاح";
                        print_r(json_encode(Message(null, $message, 200)));

                        //Send  Message To Login

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Activation');
                        $mail->addAddress($email);
                        $mail->Subject = 'تهنئة لتفعيل حسابك';
                        $mail->Body = EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لقد تم تنشيط العيادة او الصيدلية الخاصة بك يمكنك الأن العمل والإستمتاع بكافة المميزات </p></br>         
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                        ');

                        $mail->send();

                    } else {
                        $message = "الحساب مفعل بالفعل";
                        print_r(json_encode(Message(null, $message, 202)));
                    }
                } else {
                    $message = "المعرف غير صحيح";
                    print_r(json_encode(Message(null, $message, 400)));
                }
            } else {
                $Message = "النوع غير معروف";
                print_r(json_encode(Message(null, $Message, 401)));
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

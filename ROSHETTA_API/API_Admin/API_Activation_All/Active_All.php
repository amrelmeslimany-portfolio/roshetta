<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_POST['activation_person_id']) && !empty($_POST['activation_person_id'])) {

            //Filter Number INT

            $user_id = filter_var($_POST['activation_person_id'], FILTER_SANITIZE_NUMBER_INT);

            //Check Person

            $check_user = $database->prepare("SELECT * FROM activation_person WHERE activation_person.id = :id");
            $check_user->bindparam("id", $user_id);
            $check_user->execute();

            if ($check_user->rowCount() > 0) {

                //Update Activation_Person Table

                $Update = $database->prepare("UPDATE activation_person SET isactive = 1 WHERE activation_person.id = :id");
                $Update->bindparam("id", $user_id);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    $get_doctor = $database->prepare("SELECT doctor_name,email FROM doctor,activation_person WHERE activation_person.id = :id AND activation_person.doctor_id = doctor.id AND isactive = 1");
                    $get_doctor->bindparam("id", $user_id);
                    $get_doctor->execute();

                    if ($get_doctor->rowCount() > 0 ) {

                        $data_user = $get_doctor->fetchObject();

                        $name   = $data_user->doctor_name;
                        $email  = $data_user->email;

                    } else {
                        $get_pharmacist = $database->prepare("SELECT pharmacist_name,email FROM pharmacist,activation_person WHERE activation_person.id = :id AND activation_person.pharmacist_id = pharmacist.id AND isactive = 1");
                        $get_pharmacist->bindparam("id", $user_id);
                        $get_pharmacist->execute();

                        if ($get_pharmacist->rowCount() > 0) {

                            $data_user = $get_pharmacist->fetchObject();

                            $name   = $data_user->pharmacist_name;
                            $email  = $data_user->email;

                        } else {
                            $data_user = '';
                        }
                    }

                    print_r(json_encode(["Message" => "تم التفعيل بنجاح"]));
                    
                    //Send  Message To Login

                    $mail->setFrom('roshettateam@gmail.com', 'Roshetta Activation');
                    $mail->addAddress($email);
                    $mail->Subject = 'تهنئة لتفعيل حسابك';
                    $mail->Body = '<div style="padding: 10px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                    <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin:  auto ;padding: 0px; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لقد تم تنشيط حسابك يمكنك الأن العمل والإستمتاع بكافة المميزات </p></br>         
                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                    <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                    <div style="text-align: center;margin: auto">
                    <small style="color: #3e3e3e; font-weight: 500;font-family: cursive;">مع تحيات فريق روشتة</small><br>
                    <div style="margin-top: 10px;">
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/facebook-new.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/instagram-new.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/linkedin.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/youtube--v1.png" />
                        </a>
                    </div>              
                    </div></div>';
                    $mail->send();

                } else {
                    print_r(json_encode(["Error" => "الحساب مفعل بالفعل"]));
                }
            } else {
                print_r(json_encode(["Error" => "المعرف غير صحيح"]));
            }

        } elseif (isset($_POST['activation_place_id']) && !empty($_POST['activation_place_id'])) {

            //Filter Number INT

            $place_id = filter_var($_POST['activation_place_id'], FILTER_SANITIZE_NUMBER_INT);

            //Check Place

            $check_place = $database->prepare("SELECT * FROM activation_place WHERE id = :id ");
            $check_place->bindparam("id", $place_id);
            $check_place->execute();

            if ($check_place->rowCount() > 0) {

                //Update Activation_Place Table

                $Update = $database->prepare("UPDATE activation_place SET isactive = 1 WHERE id = :id ");
                $Update->bindparam("id", $place_id);
                $Update->execute();

                if ($Update->rowCount() > 0) {

                    $get_doctor = $database->prepare("SELECT doctor_name,email FROM doctor,activation_place,clinic WHERE activation_place.id = :id AND activation_place.clinic_id = clinic.id AND clinic.doctor_id = doctor.id AND isactive = 1");
                    $get_doctor->bindparam("id", $place_id);
                    $get_doctor->execute();

                    if ($get_doctor->rowCount() > 0 ) {

                        $data_user = $get_doctor->fetchObject();
                        $name = $data_user->doctor_name;
                        $email = $data_user->email;

                    } else {

                        $get_pharmacist = $database->prepare("SELECT pharmacist_name,email FROM pharmacist,activation_place,pharmacy WHERE activation_place.id = :id AND activation_place.pharmacy_id = pharmacy.id AND pharmacy.pharmacist_id = pharmacist.id AND isactive = 1");
                        $get_pharmacist->bindparam("id", $place_id);
                        $get_pharmacist->execute();

                        if ($get_pharmacist->rowCount() > 0) {

                            $data_user = $get_pharmacist->fetchObject();

                            $name   = $data_user->pharmacist_name;
                            $email  = $data_user->email;

                        } else {
                            $data_user = '';
                        }
                    }

                    print_r(json_encode(["Message" => "تم التفعيل بنجاح"]));

                    //Send  Message To Login

                    $mail->setFrom('roshettateam@gmail.com', 'Roshetta Activation');
                    $mail->addAddress($email);
                    $mail->Subject = 'تهنئة لتفعيل حسابك';
                    $mail->Body = '<div style="padding: 10px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                    <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin:  auto ;padding: 0px; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لقد تم تنشيط العيادة او الصيدلية الخاصة بك يمكنك الأن العمل والإستمتاع بكافة المميزات </p></br>         
                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                    <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                    <div style="text-align: center;margin: auto">
                    <small style="color: #3e3e3e; font-weight: 500;font-family: cursive;">مع تحيات فريق روشتة</small><br>
                    <div style="margin-top: 10px;">
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/facebook-new.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/instagram-new.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/linkedin.png" />
                        </a>
                        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                            <img src="https://img.icons8.com/ios-glyphs/30/null/youtube--v1.png" />
                        </a>
                    </div>                 
                    </div></div>';
                    $mail->send();

                } else {
                    print_r(json_encode(["Error" => "الحساب مفعل بالفعل"]));
                }
            } else {
                print_r(json_encode(["Error" => "المعرف غير صحيح"]));
            }
        } else {
            print_r(json_encode(["Error" => "يجب ارسال المعرف"]));
        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
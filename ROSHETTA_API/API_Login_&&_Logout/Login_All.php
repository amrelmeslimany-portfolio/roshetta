<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Mail/Mail.php"); //To Send Email
require_once("Get_Ip_User.php"); //To Get The User IP Address
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    $ip = get_user_ip();  // Function To Get The User IP Address
    $date_time = date('h:i:s Y-m-d');
    $URL_Verify = 'http://localhost:3000/ROSHETTA_API/API_Forget_Password/Edit_Password_With_Email.php';

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) {

        if (
            isset($_POST['user_id'])            && !empty($_POST['user_id'])
            && isset($_POST['password'])        && !empty($_POST['password'])
        ) {

            $user_id          = $_POST['user_id'];
            $password_user = $_POST['password'];

            if (filter_var($user_id, FILTER_VALIDATE_INT) !== FALSE  || filter_var($user_id, FILTER_VALIDATE_EMAIL) !== FALSE) {

                if ($_POST['role'] === "patient") {

                    //Verify Patients Table

                    $LoginPatient = $database->prepare("SELECT * FROM patient WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $LoginPatient->bindparam("ssd", $user_id);
                    $LoginPatient->bindparam("email", $user_id);
                    $LoginPatient->execute();

                    if ($LoginPatient->rowCount() > 0) {

                        $patient          = $LoginPatient->fetchObject();
                        $password_patient = $patient->password;

                        if (password_verify($password_user, $password_patient)) {

                            $data_message = array(

                                "Message"       => $patient->patient_name . " : مرحبا بك ",
                                "Account_Type"  => $patient->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['patient'] = $patient;

                            $email          = $patient->email;
                            $security_code  = $patient->security_code;
                            $role           = $patient->role;
                            $name           = $patient->patient_name;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                            <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                            <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                            <img src="https://img.icons8.com/material-rounded/200/22C3E6/break.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                            <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك </h2>
                            <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;">'. $ip .' :(ip) عنوان</p>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;"> ' . $date_time .' : (بتوقيت القاهرة) التوقيت</p>
                            <h5 style="text-align: center;font-family: cursive; margin: -20px ;">هل ليس أنت ؟ برجاء إعادة تعيين كلمة المرور على الفور</h5>
                            <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحضة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                            <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                            <div style="text-align: center;">
                            <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                            </div></div>';
                            $mail->send();

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "doctor") {

                    //Verify Doctors Table

                    $LoginDoctor = $database->prepare("SELECT * FROM doctor WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $LoginDoctor->bindparam("ssd", $user_id);
                    $LoginDoctor->bindparam("email", $user_id);
                    $LoginDoctor->execute();

                    if ($LoginDoctor->rowCount() > 0) {

                        $doctor          = $LoginDoctor->fetchObject();
                        $password_doctor = $doctor->password;

                        if (password_verify($password_user, $password_doctor)) {

                            $data_message = array(

                                "Message"       => $doctor->doctor_name . " : مرحبا بك ",
                                "Account_Type"  => $doctor->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['doctor'] = $doctor;

                            $email          = $doctor->email;
                            $security_code  = $doctor->security_code;
                            $role           = $doctor->role;
                            $name           = $doctor->doctor_name;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                            <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                            <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                            <img src="https://img.icons8.com/material-rounded/200/22C3E6/break.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                            <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك دكتور </h2>
                            <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;">'. $ip .' :(ip) عنوان</p>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;"> ' . $date_time .' : (بتوقيت القاهرة) التوقيت</p>
                            <h5 style="text-align: center;font-family: cursive; margin: -20px ;">هل ليس أنت ؟ برجاء إعادة تعيين كلمة المرور على الفور</h5>
                            <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحضة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                            <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                            <div style="text-align: center;">
                            <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                            </div></div>';
                            $mail->send();

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "pharmacist") {

                    //Verify Pharmacists Table

                    $LoginPharmacist = $database->prepare("SELECT * FROM pharmacist WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $LoginPharmacist->bindparam("ssd", $user_id);
                    $LoginPharmacist->bindparam("email", $user_id);
                    $LoginPharmacist->execute();

                    if ($LoginPharmacist->rowCount() > 0) {

                        $pharmacist          = $LoginPharmacist->fetchObject();
                        $password_pharmacist = $pharmacist->password;

                        if (password_verify($password_user, $password_pharmacist)) {

                            $data_message = array(

                                "Message"       => $pharmacist->pharmacist_name . " : مرحبا بك ",
                                "Account_Type"  => $pharmacist->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['pharmacist'] = $pharmacist;

                            $email          = $pharmacist->email;
                            $security_code  = $pharmacist->security_code;
                            $role           = $pharmacist->role;
                            $name           = $pharmacist->pharmacist_name;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                            <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                            <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                            <img src="https://img.icons8.com/material-rounded/200/22C3E6/break.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                            <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك دكتور </h2>
                            <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;">'. $ip .' :(ip) عنوان</p>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;"> ' . $date_time .' : (بتوقيت القاهرة) التوقيت</p>
                            <h5 style="text-align: center;font-family: cursive; margin: -20px ;">هل ليس أنت ؟ برجاء إعادة تعيين كلمة المرور على الفور</h5>
                            <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحضة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                            <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                            <div style="text-align: center;">
                            <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                            </div></div>';
                            $mail->send();

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "assistant") {

                    //Verify Assistants Table

                    $LoginAssistant = $database->prepare("SELECT * FROM assistant WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $LoginAssistant->bindparam("ssd", $user_id);
                    $LoginAssistant->bindparam("email", $user_id);
                    $LoginAssistant->execute();

                    if ($LoginAssistant->rowCount() > 0) {

                        $assistant          = $LoginAssistant->fetchObject();
                        $password_assistant = $assistant->password;

                        if (password_verify($password_user, $assistant->password)) {

                            $data_message = array(

                                "Message"       => $assistant->assistant_name . " : مرحبا بك ",
                                "Account_Type"  => $assistant->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['assistant'] = $assistant;

                            $email          = $assistant->email;
                            $security_code  = $assistant->security_code;
                            $role           = $assistant->role;
                            $name           = $assistant->assistant_name;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                            <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                            <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                            <img src="https://img.icons8.com/material-rounded/200/22C3E6/break.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                            <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك </h2>
                            <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;">'. $ip .' :(ip) عنوان</p>
                            <p style="text-align: center;font-family: cursive; margin: -20px ;"> ' . $date_time .' : (بتوقيت القاهرة) التوقيت</p>
                            <h5 style="text-align: center;font-family: cursive; margin: -20px ;">هل ليس أنت ؟ برجاء إعادة تعيين كلمة المرور على الفور</h5>
                            <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحضة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                            <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                            <div style="text-align: center;">
                            <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                            </div></div>';
                            $mail->send();

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "admin") {

                    //Verify Admins Table

                    $LoginAdmin = $database->prepare("SELECT * FROM admin WHERE ssd = :ssd OR email = :email");
                    $LoginAdmin->bindparam("ssd", $user_id);
                    $LoginAdmin->bindparam("email", $user_id);
                    $LoginAdmin->execute();

                    if ($LoginAdmin->rowCount() > 0) {

                        $admin          = $LoginAdmin->fetchObject();
                        $password_admin = $admin->password;

                        if (password_verify($password_user, $password_admin)) {

                            $data_message = array(

                                "Message"       => $admin->admin_name . " : مرحبا بك ",
                                "Account_Type"  => $admin->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['admin'] = $admin;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل فى التعرف على نوع الحساب"]));
                }
            } else {
                print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
            }
        } else { //If Didn't Find SSD Or PASSWORD
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else { //If Didn't Find The Role
        print_r(json_encode(["Error" => "يجب تحديد نوع الحساب"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
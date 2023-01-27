<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) {

        if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {

            $user_id    = $_POST['user_id'];
            $URL_Verify = 'http://localhost:3000/ROSHETTA_API/API_Forget_Password/Edit_Password_With_Email.php';

            if (filter_var($user_id, FILTER_VALIDATE_INT) !== FALSE || filter_var($user_id, FILTER_VALIDATE_EMAIL) !== FALSE) {

                if ($_POST['role'] === "patient") {

                    //Verify Patients Table

                    $check_patient = $database->prepare("SELECT * FROM patient WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $check_patient->bindparam("ssd", $user_id);
                    $check_patient->bindparam("email", $user_id);
                    $check_patient->execute();

                    if ($check_patient->rowCount() > 0) {

                        $patient = $check_patient->fetchObject();

                        $email = $patient->email;
                        $security_code = $patient->security_code;
                        $role = $patient->role;
                        $name = $patient->patient_name;

                        //Send  Message To Verify Email

                        $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                        $mail->addAddress($email);
                        $mail->Subject = 'إعادة تعيين كلمة المرور';
                        $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                        <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                        <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                        <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك </h2>
                        <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name. '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لإعادة تعيين كلمة المرور الخاصة بحسابك الرجاء إتباع الأتى</p></br>
                        <p style="margin-top: 6px;font-family: cursive;">الضغط على الزر بلاسفل</p>                                    
                        <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                        <p style="font-family: cursive;color: #2d2d2d;"> <b>أو عن طريق الرابط التالي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                        <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                        <div style="text-align: center;">
                        <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                        </div></div>';

                        if ($mail->send()) {

                            print_r(json_encode(["Message" => "تم إرسال رسالة تأكيد عبر البريد الالكترونى المرتبط بحسابك"]));

                        } else {

                            print_r(json_encode(["Error" => "فشل ارسال رسالة التأكيد"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صحيح"]));
                    }

                } elseif ($_POST['role'] === "doctor") {

                    //Verify Doctor Table

                    $check_doctor = $database->prepare("SELECT * FROM doctor WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $check_doctor->bindparam("ssd", $user_id);
                    $check_doctor->bindparam("email", $user_id);
                    $check_doctor->execute();

                    if ($check_doctor->rowCount() > 0) {

                        $doctor = $check_doctor->fetchObject();

                        $email = $doctor->email;
                        $security_code = $doctor->security_code;
                        $role = $doctor->role;
                        $name = $doctor->doctor_name;

                        //Send  Message To Verify Email

                        $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                        $mail->addAddress($email);
                        $mail->Subject = 'إعادة تعيين كلمة المرور';
                        $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                        <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                        <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                        <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك دكتور </h2>
                        <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لإعادة تعيين كلمة المرور الخاصة بحسابك الرجاء إتباع الأتى</p></br>
                        <p style="margin-top: 6px;font-family: cursive;">الضغط على الزر بلاسفل</p>                                    
                        <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                        <p style="font-family: cursive;color: #2d2d2d;"> <b>أو عن طريق الرابط التالي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                        <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                        <div style="text-align: center;">
                        <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                        </div></div>';

                        if ($mail->send()) {

                            print_r(json_encode(["Message" => "تم إرسال رسالة تأكيد عبر البريد الالكترونى المرتبط بحسابك"]));

                        } else {

                            print_r(json_encode(["Error" => "فشل ارسال رسالة التأكيد"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صحيح"]));
                    }

                } elseif ($_POST['role'] === "pharmacist") {

                    //Verify Pharmacist Table

                    $check_pharmacist = $database->prepare("SELECT * FROM pharmacist WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $check_pharmacist->bindparam("ssd", $user_id);
                    $check_pharmacist->bindparam("email", $user_id);
                    $check_pharmacist->execute();

                    if ($check_pharmacist->rowCount() > 0) {

                        $pharmacist = $check_pharmacist->fetchObject();

                        $email = $pharmacist->email;
                        $security_code = $pharmacist->security_code;
                        $role = $pharmacist->role;
                        $name = $pharmacist->pharmacist_name;

                        //Send  Message To Verify Email

                        $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                        $mail->addAddress($email);
                        $mail->Subject = 'إعادة تعيين كلمة المرور';
                        $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                        <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                        <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                        <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك دكتور </h2>
                        <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لإعادة تعيين كلمة المرور الخاصة بحسابك الرجاء إتباع الأتى</p></br>
                        <p style="margin-top: 6px;font-family: cursive;">الضغط على الزر بلاسفل</p>                                    
                        <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                        <p style="font-family: cursive;color: #2d2d2d;"> <b>أو عن طريق الرابط التالي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                        <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                        <div style="text-align: center;">
                        <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                        </div></div>';

                        if ($mail->send()) {

                            print_r(json_encode(["Message" => "تم إرسال رسالة تأكيد عبر البريد الالكترونى المرتبط بحسابك"]));

                        } else {

                            print_r(json_encode(["Error" => "فشل ارسال رسالة التأكيد"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صحيح"]));
                    }

                } elseif ($_POST['role'] === "assistant") {

                    //Verify Assistant Table

                    $check_assistant = $database->prepare("SELECT * FROM assistant WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $check_assistant->bindparam("ssd", $user_id);
                    $check_assistant->bindparam("email", $user_id);
                    $check_assistant->execute();

                    if ($check_assistant->rowCount() > 0) {

                        $assistant = $check_assistant->fetchObject();

                        $email = $assistant->email;
                        $security_code = $assistant->security_code;
                        $role = $assistant->role;
                        $name = $assistant->assistant_name;

                        //Send  Message To Verify Email

                        $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                        $mail->addAddress($email);
                        $mail->Subject = 'إعادة تعيين كلمة المرور';
                        $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                         <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                         <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                         <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 150px ; heigh: 150px;" alt="تأكيد الاميل">
                         <h2 style="text-align: center;font-family: cursive;margin: -20px ;"> مرحبا بك </h2>
                         <h3 style="text-align: center;font-family: cursive; margin: -20px ;">' . $name . '</h3>
                         <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لإعادة تعيين كلمة المرور الخاصة بحسابك الرجاء إتباع الأتى</p></br>
                         <p style="margin-top: 6px;font-family: cursive;">الضغط على الزر بلاسفل</p>                                    
                         <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 10px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 15px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                         <p style="font-family: cursive;color: #2d2d2d;"> <b>أو عن طريق الرابط التالي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                         <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                         <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                         <div style="text-align: center;">
                         <small style="color: #3e3e3e; font-weight: 600;font-family: cursive;">مع تحيات فريق روشتة</small>
                         </div></div>';

                        if ($mail->send()) {

                            print_r(json_encode(["Message" => "تم إرسال رسالة تأكيد عبر البريد الالكترونى المرتبط بحسابك"]));

                        } else {

                            print_r(json_encode(["Error" => "فشل ارسال رسالة التأكيد"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صحيح"]));
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
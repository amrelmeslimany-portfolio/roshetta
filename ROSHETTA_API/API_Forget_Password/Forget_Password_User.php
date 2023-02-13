<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) { //Type Account

        $role = $_POST['role'];

        if ($role == "patient") {
            $table_name = 'patient';
        } elseif ($role == "doctor") {
            $table_name = 'doctor';
        } elseif ($role == "pharmacist") {
            $table_name = 'pharmacist';
        } elseif ($role == "assistant") {
            $table_name = 'assistant';
        } else {
            $table_name = '';
        }

        if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {  //SSD or Email

            $user_id    = $_POST['user_id'];
            $URL_Verify = 'http://localhost:3000/ROSHETTA_API/API_Forget_Password/Edit_Password_With_Email.php';

            if (filter_var($user_id, FILTER_VALIDATE_INT) !== FALSE || filter_var($user_id, FILTER_VALIDATE_EMAIL) !== FALSE) {

                    //Verify User Table

                    $check_user = $database->prepare("SELECT * FROM $table_name WHERE (ssd = :ssd OR email = :email) AND email_isactive = 1");
                    $check_user->bindparam("ssd", $user_id);
                    $check_user->bindparam("email", $user_id);
                    $check_user->execute();

                    if ($check_user->rowCount() > 0) {

                        $data_user = $check_user->fetchObject();

                        if ($table_name == "patient") {
                            $name = $data_user->patient_name;
                            $Hi   = 'مـــــرحبــــــا بــــك';
                        } elseif ($table_name == "doctor") {
                            $name = $data_user->doctor_name;
                            $Hi   = 'مـــــرحبـــــا بــــك دكتــــور';
                        } elseif ($table_name == "pharmacist") {
                            $name = $data_user->pharmacist_name;
                            $Hi   = 'مـــــرحبـــــا بــــك دكتــــور';
                        } elseif ($table_name == "assistant") {
                            $name = $data_user->assistant_name;
                            $Hi   = 'مـــــرحبــــــا بــــك';
                        } else {
                            $name = '';
                            $Hi = '';
                        }

                        $email          = $data_user->email;
                        $security_code  = $data_user->security_code;
                        $role           = $data_user->role;

                        //Send  Message To Verify Email

                        $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                        $mail->addAddress($email);
                        $mail->Subject = 'إعادة تعيين كلمة المرور';
                        $mail->Body = EmailBody("https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" , '
                        <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;"> ' .$Hi. ' </h3>
                        <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name. '</h3>
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">لإعادة تعيين كلمة المرور الخاصة بحسابك الرجاء إتباع الأتـــــى</p></br>
                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                        <a href="' . $message_url . '" style="background: red;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 14px;display: block;margin: 15px auto ;">إعادة تعيين كلمة المرور</a>
                        <p style="font-family: cursive;color: #2d2d2d;font-weight: 400;"> <b>أو عن طريق الرابط التالـــي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                        ');
                     
                        if ($mail->send()) {
                            $Message = "تم إرسال رسالة تأكيد عبر البريد الالكترونى المرتبط بحسابك";
                            print_r(json_encode(Message(null,$Message,200)));
                        } else {
                            $Message = "فشل ارسال رسالة التأكيد";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    } else {
                        $Message = "الرقم القومى او البريد الالكترونى غير صحيح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
            } else {
                $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } else { //If Didn't Find SSD Or Email
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else { //If Didn't Find The Role
        $Message = "يجب تحديد نوع الحساب";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموع بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}

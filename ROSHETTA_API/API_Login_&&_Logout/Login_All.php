<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Mail/Mail.php"); //To Send Email
require_once("Get_Ip_User.php"); //To Get The User IP Address
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    $ip             = get_user_ip(); // Function To Get The User IP Address
    $device_data    = $_SERVER['HTTP_USER_AGENT'];
    $date_time      = date('h:i:s Y-m-d');
    $URL_Verify     = 'http://localhost:3000/ROSHETTA_API/API_Forget_Password/Edit_Password_With_Email.php';

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) {

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

        if (
            isset($_POST['user_id'])        && !empty($_POST['user_id'])
            && isset($_POST['password'])    && !empty($_POST['password'])
        ) {

            $user_id = $_POST['user_id'];
            $password_user = $_POST['password'];

            if (filter_var($user_id, FILTER_VALIDATE_INT) !== FALSE || filter_var($user_id, FILTER_VALIDATE_EMAIL) !== FALSE) {

                //Verify User Table

                $Login = $database->prepare("SELECT * FROM $table_name WHERE ssd = :ssd OR email = :email");
                $Login->bindparam("ssd", $user_id);
                $Login->bindparam("email", $user_id);
                $Login->execute();

                if ($Login->rowCount() > 0) {

                    $data_user = $Login->fetchObject();

                    $password = $data_user->password;

                    if (password_verify($password_user, $password)) {

                        if ($data_user->email_isactive == 1 ) {

                            if ($table_name == "patient") {
                                $_SESSION['patient'] = $data_user;
                                $name   = $data_user->patient_name;
                                $Hi     = 'مـــــرحبــــــا بــــك';
                            } elseif ($table_name == "doctor") {
                                $_SESSION['doctor'] = $data_user;
                                $name   = $data_user->doctor_name;
                                $Hi     = 'مـــــرحبـــــا بــــك دكتــــور';
                            } elseif ($table_name == "pharmacist") {
                                $_SESSION['pharmacist'] = $data_user;
                                $name   = $data_user->pharmacist_name;
                                $Hi     = 'مـــــرحبـــــا بــــك دكتــــور';
                            } elseif ($table_name == "assistant") {
                                $_SESSION['assistant'] = $data_user;
                                $name   = $data_user->assistant_name;
                                $Hi     = 'مـــــرحبــــــا بــــك';
                            } else {
                                $name = '';
                                $Hi = '';
                            }

                            $data_message = array(
                                "Message"       => $name . " : مرحبا بك ",
                                "Account_Type"  => $data_user->role
                            );

                            print_r(json_encode($data_message));

                            $email          = $data_user->email;
                            $security_code  = $data_user->security_code;
                            $role           = $data_user->role;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = '<div style="padding: 10px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                                <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                                <img src="https://img.icons8.com/material-rounded/200/22C3E6/break.png" style="display: block;margin:  auto ;padding: 0px; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                                <h3 style="text-align: center;font-family: cursive;padding: 0px ;font-style: italic;">' . $Hi . '</h3>
                                <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                                <p style="text-align: center;font-family: cursive;">' . ($device_data) . '</p>
                                <p style="text-align: center;font-family: cursive;">' . $ip . ' :(ip) عنوان</p>
                                <p style="text-align: center;font-family: cursive;"> ' . $date_time . ' : (بتوقيت القاهرة) التوقيت</p>
                                <h5 style="text-align: center;font-family: cursive;">هل ليس أنت ؟ <a href="' . $message_url . '">إعادة تعيين كلمة المرور</a></h5>
                                <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                                <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
                                <div style="text-align: center;margin: auto">
                                <small style="color: #3e3e3e; font-weight: 500;font-family: cursive;">مع تحيات فريق روشتة</small><br>
                                <div style="margin-top: 10px">
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
                            print_r(json_encode(["Error" => "يجب تفعيل الحساب"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى او البريد الإلكترونى غير صحيح"]));
                }
            } else {
                print_r(json_encode(["Error" => "الرقم القومى او البريد الإلكترونى غير صالح"]));
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
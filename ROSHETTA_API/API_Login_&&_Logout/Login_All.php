<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Mail/Mail.php"); //To Send Email
require_once("../API_Function/All_Function.php"); //All Function
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
        } elseif ($role == "admin") {
            $table_name = 'admin';
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
                            } elseif ($table_name == "admin") {
                                $_SESSION['admin'] = $data_user;
                                $name   = $data_user->admin_name;
                                $Hi     = 'مـــــرحبـــــا بــــك مـــديـــر';
                            } else {
                                $name = '';
                                $Hi = '';
                            }


                            $Data = [
                                "user_id"       => $data_user->id,
                                "name"          => $name,
                                "id_national"   => $data_user->ssd,
                                "role"          => $data_user->role,
                                "image"         => $data_user->profile_img
                            ];

                            $Message    = "تم تسجيل الدخول بنجاح";
                            
                            print_r(json_encode(Message($Data,$Message,200)));

                            $email          = $data_user->email;
                            $security_code  = $data_user->security_code;
                            $role           = $data_user->role;

                            $message_url = $URL_Verify . "?email=" . $email . "&role=" . $role . "&code=" . $security_code;

                            //Send  Message To Login

                            $mail->setFrom('roshettateam@gmail.com', 'Roshetta Login');
                            $mail->addAddress($email);
                            $mail->Subject = 'تنبية تسجيل دخول إلى حساب روشتة';
                            $mail->Body = EmailBody("https://img.icons8.com/material-rounded/200/22C3E6/break.png" , '
                            <h3 style="text-align: center;font-family: cursive;padding: 0px ;font-style: italic;">' . $Hi . '</h3>
                            <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
                            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
                            <p style="text-align: center;font-family: cursive;">' . ($device_data) . '</p>
                            <p style="text-align: center;font-family: cursive;">' . $ip . ' :(ip) عنوان</p>
                            <p style="text-align: center;font-family: cursive;"> ' . $date_time . ' : (بتوقيت القاهرة) التوقيت</p>
                            <h5 style="text-align: center;font-family: cursive;">هل ليس أنت ؟ <a href="' . $message_url . '">إعادة تعيين كلمة المرور</a></h5>
                            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                            ');
                            
                            $mail->send();

                        } else {
                            $Message = "يجب تفعيل الحساب";
                            print_r(json_encode(Message(null,$Message,202)));
                        }
                    } else {
                        $Message = "الرقم القومى او كلمة المرور غير صحيح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $Message = "الرقم القومى او البريد الإلكترونى غير صحيح";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "الرقم القومى او البريد الإلكترونى غير صالح";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } else { //If Didn't Find SSD Or PASSWORD
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else { //If Didn't Find The Role
        $Message = "يجب تحديد نوع الحساب";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}

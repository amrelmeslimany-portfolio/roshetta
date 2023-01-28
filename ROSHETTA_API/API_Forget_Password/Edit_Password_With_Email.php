<?php
require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 
require_once("../API_Mail/Mail.php"); //To Send Email

if (isset($_GET['email']) && isset($_GET['code']) && isset($_GET['role'])) {

    $email          = $_GET['email'];
    $code           = $_GET['code'];
    $security_code  = md5(date('h:i:s Y-m-d'));
    $password       = rand(100000, 999999);
    $password_hash  = password_hash($password, PASSWORD_DEFAULT);

    if ($_GET['role'] == "PATIENT") {

        //Verify Patients Table

        $check_patient = $database->prepare("SELECT * FROM patient WHERE security_code = :security_code AND email = :email");
        $check_patient->bindparam("security_code", $code);
        $check_patient->bindparam("email", $email);
        $check_patient->execute();

        if ($check_patient->rowCount() > 0) {

            $patient = $check_patient->fetchObject();
            $name = $patient->patient_name;

            //Update Patient Table

            $update = $database->prepare("UPDATE patient SET password = :password , security_code = :security_code WHERE email = :email AND security_code = :code");
            $update->bindparam("security_code", $security_code);
            $update->bindparam("email", $email);
            $update->bindparam("password", $password_hash);
            $update->bindparam("code", $code);
            $update->execute();

            if ($update->rowCount() > 0) {

                //Send  Message To Verify Password

                $password_user = $password;

                $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                $mail->addAddress($email);
                $mail->Subject = 'كلمة مرور حسابك فى روشتة';
                $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name . '</h3>
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">قم بأستعمال كلمة المرور التالية للدخول إلى حسابك</p></br>         
                <h2 style="font-family: cursive;color: red;">' . $password_user . '</h2>
                <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">هام / </b>ننصح بإعادة تعيين كلمة المرور من جديد بعد تسجيل الدخول</p>
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

                if ($mail->send()) {

                    print_r(json_encode(["Message" => "تم إرسال كلمة مرور مؤقتة إلى بريدك الالكترونى"]));

                } else {

                    print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
                }

            } else {

                print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
            }

        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } elseif ($_GET['role'] == "DOCTOR") {

        //Verify Doctor Table

        $check_Doctor = $database->prepare("SELECT * FROM doctor WHERE security_code = :security_code AND email = :email");
        $check_Doctor->bindparam("security_code", $code);
        $check_Doctor->bindparam("email", $email);
        $check_Doctor->execute();

        if ($check_Doctor->rowCount() > 0) {

            $doctor = $check_Doctor->fetchObject();
            $name = $doctor->doctor_name;

            //Update Doctor Table

            $update = $database->prepare("UPDATE doctor SET password = :password , security_code = :security_code WHERE email = :email AND security_code = :code");
            $update->bindparam("security_code", $security_code);
            $update->bindparam("email", $email);
            $update->bindparam("password", $password_hash);
            $update->bindparam("code", $code);
            $update->execute();

            if ($update->rowCount() > 0) {

                //Send  Message To Verify Password

                $password_user = $password;

                $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                $mail->addAddress($email);
                $mail->Subject = 'كلمة مرور حسابك فى روشتة';
                $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
                <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name . '</h3>
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">قم بأستعمال كلمة المرور التالية للدخول إلى حسابك</p></br>         
                <h2 style="font-family: cursive;color: red;">' . $password_user . '</h2>
                <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">هام / </b>ننصح بإعادة تعيين كلمة المرور من جديد بعد تسجيل الدخول</p>
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

                if ($mail->send()) {

                    print_r(json_encode(["Message" => "تم إرسال كلمة مرور مؤقتة إلى بريدك الالكترونى"]));

                } else {

                    print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
                }

            } else {

                print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
            }

        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }
    } elseif ($_GET['role'] == "PHARMACIST") {

        //Verify Pharmacist Table

        $check_pharmacist = $database->prepare("SELECT * FROM pharmacist WHERE security_code = :security_code AND email = :email");
        $check_pharmacist->bindparam("security_code", $code);
        $check_pharmacist->bindparam("email", $email);
        $check_pharmacist->execute();

        if ($check_pharmacist->rowCount() > 0) {

            $pharmacist = $check_pharmacist->fetchObject();
            $name = $pharmacist->pharmacist_name;

            //Update Pharmacist Table

            $update = $database->prepare("UPDATE pharmacist SET password = :password , security_code = :security_code WHERE email = :email AND security_code = :code");
            $update->bindparam("security_code", $security_code);
            $update->bindparam("email", $email);
            $update->bindparam("password", $password_hash);
            $update->bindparam("code", $code);
            $update->execute();

            if ($update->rowCount() > 0) {

                //Send  Message To Verify Password

                $password_user = $password;

                $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                $mail->addAddress($email);
                $mail->Subject = 'كلمة مرور حسابك فى روشتة';
                $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;"> مـــــرحبـــــا بــــك دكتـــور </h3>
                <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name . '</h3>
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">قم بأستعمال كلمة المرور التالية للدخول إلى حسابك</p></br>         
                <h2 style="font-family: cursive;color: red;">' . $password_user . '</h2>
                <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">هام / </b>ننصح بإعادة تعيين كلمة المرور من جديد بعد تسجيل الدخول</p>
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

                if ($mail->send()) {

                    print_r(json_encode(["Message" => "تم إرسال كلمة مرور مؤقتة إلى بريدك الالكترونى"]));

                } else {

                    print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
                }

            } else {

                print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
            }

        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } elseif ($_GET['role'] == "ASSISTANT") {

        //Verify Assistant Table

        $check_assistant = $database->prepare("SELECT * FROM assistant WHERE security_code = :security_code AND email = :email");
        $check_assistant->bindparam("security_code", $code);
        $check_assistant->bindparam("email", $email);
        $check_assistant->execute();

        if ($check_assistant->rowCount() > 0) {

            $assistant = $check_assistant->fetchObject();
            $name = $assistant->assistant_name;

            //Update Assistant Table

            $update = $database->prepare("UPDATE assistant SET password = :password , security_code = :security_code WHERE email = :email AND security_code = :code");
            $update->bindparam("security_code", $security_code);
            $update->bindparam("email", $email);
            $update->bindparam("password", $password_hash);
            $update->bindparam("code", $code);
            $update->execute();

            if ($update->rowCount() > 0) {

                //Send  Message To Verify Password

                $password_user = $password;

                $mail->setFrom('roshettateam@gmail.com', 'Roshetta Security');
                $mail->addAddress($email);
                $mail->Subject = 'كلمة مرور حسابك فى روشتة';
                $mail->Body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                <img src="https://iili.io/H0zAibe.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                <img src="https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" style="display: block;margin:  auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name . '</h3>
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">قم بأستعمال كلمة المرور التالية للدخول إلى حسابك</p></br>         
                <h2 style="font-family: cursive;color: red;">' . $password_user . '</h2>
                <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">هام / </b>ننصح بإعادة تعيين كلمة المرور من جديد بعد تسجيل الدخول</p>
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
                if ($mail->send()) {

                    print_r(json_encode(["Message" => "تم إرسال كلمة مرور مؤقتة إلى بريدك الالكترونى"]));

                } else {

                    print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
                }

            } else {

                print_r(json_encode(["Error" => "فشل إعادة تعيين كلمة المرور"]));
            }

        } else {
            print_r(json_encode(["Error" => "هذا الرابط لم يعد صالح"]));
        }

    } else {
        print_r(json_encode(["Error" => "فشل فى العثور على نوع الحساب"]));
    }
} else {
    print_r(json_encode(["Error" => "فشل فى العثور على البيانات"]));
}
?>
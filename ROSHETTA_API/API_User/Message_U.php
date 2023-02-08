<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (  // If Found SESSION
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $name       = $_SESSION['patient']->patient_name;
            $ssd        = $_SESSION['patient']->ssd;
            $email      = $_SESSION['patient']->email;
            $role       = $_SESSION['patient']->role;

        } elseif (isset($_SESSION['doctor'])) {
            $name       = $_SESSION['doctor']->doctor_name;
            $ssd        = $_SESSION['doctor']->ssd;
            $email      = $_SESSION['doctor']->email;
            $role       = $_SESSION['doctor']->role;

        } elseif (isset($_SESSION['pharmacist'])) {
            $name       = $_SESSION['pharmacist']->pharmacist_name;
            $ssd        = $_SESSION['pharmacist']->ssd;
            $email      = $_SESSION['pharmacist']->email;
            $role       = $_SESSION['pharmacist']->role;

        } elseif (isset($_SESSION['assistant'])) {
            $name       = $_SESSION['assistant']->assistant_name;
            $ssd        = $_SESSION['assistant']->ssd;
            $email      = $_SESSION['assistant']->email;
            $role       = $_SESSION['assistant']->role;

        } else {
            $name = '';
            $ssd = '';
            $email = '';
            $role = '';
        }

        //I Expect To Receive This Data

        if (isset($_POST['message']) && !empty($_POST['message'])) {

            //Filter Data String
            $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

            //Add To Message Table

            $addMessage = $database->prepare("INSERT INTO message(name,email,ssd,role,message)
                                                        VALUES(:name,:email,:ssd,:role,:message)");

            $addMessage->bindparam("name", $name);
            $addMessage->bindparam("email", $email);
            $addMessage->bindparam("ssd", $ssd);
            $addMessage->bindparam("role", $role);
            $addMessage->bindparam("message", $message);
            $addMessage->execute();

            if ($addMessage->rowCount() > 0) {

                $Message = "تم الإرسال للمختص للمراجعة";
                print_r(json_encode(Message(null,$Message,201)));

                if ($role == 'PATIENT' || $role == 'ASSISTANT') {
                    $Hi = 'مـــــرحبـــــا بــــك';
                } elseif ($role == 'DOCTOR' || $role == 'PHARMACIST') {
                    $Hi = 'مـــــرحبـــــا بــــك دكتــــور';
                } else {
                    $Hi = '';
                }

                $mail->setFrom('roshettateam@gmail.com', 'Roshetta Support');
                $mail->addAddress($email);
                $mail->Subject = 'فريق الدعم';
                $mail->Body = '<div style="padding: 10px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                <img src="https://img.icons8.com/fluency/200/null/envelope-number.png" style="display: block;margin:  auto ;padding: 0px; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'.$Hi.'</h3>
                <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'. $name .'</h3>       
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سوف يتم الرد على استفسارك فى غضون 48 ساعة الرجاء عدم تكرار الرسائل</p></br>  
                <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">نشكرك على التواصل معنا</p></br>                  
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
                $Message = "فشل ارسال الرسالة";
                print_r(json_encode(Message(null,$Message,422)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else { //If Didn't Find The Name Of The Session Available
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
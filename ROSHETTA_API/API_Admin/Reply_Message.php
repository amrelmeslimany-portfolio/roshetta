<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {  // If Admin

        if (
            isset($_POST['id'])         && !empty($_POST['id'])
            && isset($_POST['message']) && !empty($_POST['message'])
        ) {

            $message    = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
            $id         = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

            if (filter_var($id, FILTER_VALIDATE_INT) !== FALSE) {

                // Get From Message Table

                $get_message = $database->prepare("SELECT name,email,role FROM message WHERE id = :id");
                $get_message->bindparam("id", $id);
                $get_message->execute();

                if ($get_message->rowCount() > 0) {

                    $data_message = $get_message->fetchObject();

                    $name   = $data_message->name;
                    $email  = $data_message->email;
                    $role   = $data_message->role;

                    if ($role == 'PATIENT' || $role == 'ASSISTANT') {
                        $Hi = 'مـــــرحبـــــا بــــك';
                    } elseif ($role == 'DOCTOR' || $role == 'PHARMACIST') {
                        $Hi = 'مـــــرحبـــــا بــــك دكتــــور';
                    } else {
                        $Hi = '';
                    }

                    //Send Email For User

                    $mail->setFrom('roshettateam@gmail.com', 'Roshetta Support');
                    $mail->addAddress($email);
                    $mail->Subject = 'رد على استفسارك من قبل فريق الدعم';
                    $mail->Body = '<div style="padding: 10px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                    <img src="https://img.icons8.com/fluency/200/null/envelope-number.png" style="display: block;margin:  auto ;padding: 0px; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'.$Hi.'</h3>
                    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'. $name .'</h3>
                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">'.$message.'</p></br>         
                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>إذا كان لديك أى استفسار أخر برجاء التواصل معنا</p>
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

                        //Update Case Message 

                        $update_message = $database->prepare("UPDATE message SET m_case = 1 WHERE id = :id");
                        $update_message->bindparam("id", $id);
                        $update_message->execute();

                        if ($update_message->rowCount() > 0 ) {

                            $Message = "تم إرسال الرد بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));

                        } else {
                            $Message = "فشل تعديل الحالة";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    } else{
                        $Message = "فشل إرسال الرد";
                        print_r(json_encode(Message(null,$Message,422)));
                    }
                } else {
                    $Message = "لا يوجد رسائل";
                    print_r(json_encode(Message(null,$Message,204)));
                }
            } else {
                $Message = "المعرف غير صالح";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php"); //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (
        // If Found SESSION
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $table_name     = 'patient';
            $id             = $_SESSION['patient'];
        } elseif (isset($_SESSION['doctor'])) {
            $table_name     = 'doctor';
            $id             = $_SESSION['doctor'];
        } elseif (isset($_SESSION['pharmacist'])) {
            $table_name     = 'pharmacist';
            $id             = $_SESSION['pharmacist'];
        } elseif (isset($_SESSION['assistant'])) {
            $table_name     = 'assistant';
            $id             = $_SESSION['assistant'];
        } else {
            $table_name = '';
            $id = '';
        }

        //I Expect To Receive This Data

        if (isset($_POST['message']) && !empty($_POST['message'])) {
            
            //Filter Data String
            $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

            $get_data = $database->prepare("SELECT name,email,ssd,role FROM $table_name WHERE id = :id");
            $get_data->bindparam("id", $id);
            $get_data->execute();

            if ($get_data->rowCount() > 0) {

                $data_user = $get_data->fetchObject();

                $name   = $data_user->name;
                $email  = $data_user->email;
                $ssd    = $data_user->ssd;
                $role   = $data_user->role;

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
                    print_r(json_encode(Message(null, $Message, 201)));

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
                    $mail->Body = EmailBody("https://img.icons8.com/fluency/200/null/envelope-number.png", '
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $Hi . '</h3>
                        <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $name . '</h3>       
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سوف يتم الرد على استفسارك فى غضون 48 ساعة الرجاء عدم تكرار الرسائل</p></br>  
                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">نشكرك على التواصل معنا</p></br>                  
                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
                        ');

                    $mail->send();

                } else {
                    $Message = "فشل ارسال الرسالة";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "لم يتم العثور على بيانات";
                print_r(json_encode(Message(null, $Message, 204)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
        }
    } else { //If Didn't Find The Name Of The Session Available
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null, $Message, 401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
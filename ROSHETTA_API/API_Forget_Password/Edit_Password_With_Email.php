<?php
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function 
require_once("../API_Mail/Mail.php"); //To Send Email

if (isset($_GET['email']) && isset($_GET['code']) && isset($_GET['role'])) {

    $email          = $_GET['email'];
    $code           = $_GET['code'];
    $security_code  = md5(date('h:i:s Y-m-d'));
    $password       = rand(100000, 999999);
    $password_hash  = password_hash($password, PASSWORD_DEFAULT);
    $role           = $_GET['role'];

    if ($role == "PATIENT") {
        $table_name = 'patient';
    } elseif ($role == "DOCTOR") {
        $table_name = 'doctor';
    } elseif ($role == "PHARMACIST") {
        $table_name = 'pharmacist';
    } elseif ($role == "ASSISTANT") {
        $table_name = 'assistant';
    } else {
        $table_name = '';
    }

    //Verify User Table

    $check_user = $database->prepare("SELECT * FROM $table_name WHERE security_code = :security_code AND email = :email");
    $check_user->bindparam("security_code", $code);
    $check_user->bindparam("email", $email);
    $check_user->execute();

    if ($check_user->rowCount() > 0) {

        $data_user = $check_user->fetchObject();

        if ($table_name == "patient") {
            $name   = $data_user->patient_name;
            $Hi     = 'مـــــرحبــــــا بــــك';
        } elseif ($table_name == "doctor") {
            $name   = $data_user->doctor_name;
            $Hi     = 'مـــــرحبـــــا بــــك دكتــــور';
        } elseif ($table_name == "pharmacist") {
            $name   = $data_user->pharmacist_name;
            $Hi     = 'مـــــرحبـــــا بــــك دكتــــور';
        } elseif ($table_name == "assistant") {
            $name   = $data_user->assistant_name;
            $Hi     = 'مـــــرحبــــــا بــــك';
        } else {
            $name = '';
            $Hi = '';
        }

        //Update User Table

        $update = $database->prepare("UPDATE $table_name SET password = :password , security_code = :security_code WHERE email = :email AND security_code = :code");
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
            $mail->Body = EmailBody("https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png" , '
            <h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;">' . $Hi . '</h3>
            <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $name . '</h3>
            <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">قم بأستعمال كلمة المرور التالية للدخول إلى حسابك</p></br>         
            <h2 style="font-family: cursive;color: red;">' . $password_user . '</h2>
            <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">هام / </b>ننصح بإعادة تعيين كلمة المرور من جديد بعد تسجيل الدخول</p>
            ');
          
            if ($mail->send()) {
                    $Message = "تم إرسال كلمة مرور مؤقتة إلى بريدك الالكترونى";
                    $data = Message(null,$Message,200);
            } else {
                $Message = "فشل إرسال كلمة المرور الجديدة";
                $data = Message(null,$Message,202);
            }
        } else {
            $Message = "فشل إعادة تعيين كلمة المرور";
            $data = Message(null,$Message,422);
        }
    } else {
        $Message = "هذا الرابط لم يعد صالح";
        $data = Message(null,$Message,410);
    }
} else {
    $Message = "فشل فى العثور على البيانات";
    $data = Message(null,$Message,404);
}

   // Message For User When Open This Page
   echo MessageHtml($data);
?>
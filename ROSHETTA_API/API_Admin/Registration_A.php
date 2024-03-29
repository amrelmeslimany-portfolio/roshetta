<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php");  //To Send Email

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        $URL_Verify = 'http://localhost:3000/ROSHETTA_API/API_User/Verify_User_Account.php';

        //I Expect To Receive This Data

        if (
            isset($_POST['first_name'])         && !empty($_POST['first_name'])
            && isset($_POST['last_name'])       && !empty($_POST['last_name'])
            && isset($_POST['email'])           && !empty($_POST['email'])
            && isset($_POST['gender'])          && !empty($_POST['gender'])
            && isset($_POST['ssd'])             && !empty($_POST['ssd'])
            && isset($_POST['phone_number'])    && !empty($_POST['phone_number'])
            && isset($_POST['birth_date'])      && !empty($_POST['birth_date'])
            && isset($_POST['password'])        && !empty($_POST['password'])
            && isset($_POST['confirm_password'])&& !empty($_POST['confirm_password'])
        ) {

            if ($_POST['password'] == $_POST['confirm_password']) {

                $ssd            = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
                $email          = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); //Filter Data 'email'
                $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'Int'

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE && strlen($ssd) == 14 && filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE) {

                    if (strlen($phone_number) == 11) {

                        //Verify That It Has Not Been Present Before

                        $checkssd = $database->prepare("SELECT * FROM admin WHERE ssd =:ssd OR email = :email");
                        $checkssd->bindparam("ssd", $ssd);
                        $checkssd->bindparam("email", $email);
                        $checkssd->execute();

                        if ($checkssd->rowCount() > 0) {

                            $Message = "الرقم القومى او البريد الالكترونى موجود من قبل";
                            print_r(json_encode(Message(null, $Message, 400)));
                            die();

                        } else {

                            $check_phone = $database->prepare("SELECT * FROM admin WHERE phone_number = :phone_number");
                            $check_phone->bindparam("phone_number", $phone_number);
                            $check_phone->execute();

                            if ($check_phone->rowCount() > 0) {

                                $Message = "رقم الهاتف موجود من قبل";
                                print_r(json_encode(Message(null, $Message, 400)));

                            } else {

                                //Filter Data 'STRING' && Hash Password

                                $first_name     = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                                $last_name      = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                                $admin_name     = $first_name . ' ' . $last_name;
                                $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                                $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $birth_date     = $_POST['birth_date'];
                                $security_code  = md5(date('h:i:s Y-m-d'));

                                //Add To Admin Table

                                $addData = $database->prepare("INSERT INTO admin(name,gender,ssd,email,phone_number,birth_date,password,security_code,email_isactive,role)
                                                                            VALUES(:admin_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:security_code,0,'ADMIN')");

                                $addData->bindparam("admin_name", $admin_name);
                                $addData->bindparam("gender", $gender);
                                $addData->bindparam("ssd", $ssd);
                                $addData->bindparam("email", $email);
                                $addData->bindparam("phone_number", $phone_number);
                                $addData->bindparam("birth_date", $birth_date);
                                $addData->bindparam("password", $password_hash);
                                $addData->bindparam("security_code", $security_code);
                                $addData->execute();

                                if ($addData->rowCount() > 0) {

                                    //Send  Message To Verify Email

                                    $message_url = $URL_Verify . "?email=" . $email . "&role=ADMIN" . "&code=" . $security_code;

                                    $mail->setFrom('roshettateam@gmail.com', 'Roshetta Team');
                                    $mail->addAddress($email);
                                    $mail->Subject = 'تفعيل حسابك فى روشتة';
                                    $mail->Body = EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبـــــا بــــك مـــديـــر </h3>
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;">' . $admin_name . '</h3>
                                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">من اجل العمل بشكل طبيعى والتمتع بكافة الصلاحيات لمساعدة العملاء يجب تفعيل حسابك عن طريق</p></br>
                                    <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                    <a href="' . $message_url . '" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                    <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="' . $message_url . '" style="display: block;margin-top: 10px;">' . $message_url . '</a> </p>
                                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                                    ') ;

                                    if ($mail->send()) {

                                        $Message = "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى لتفعيل الحساب";
                                        print_r(json_encode(Message(null, $Message, 201)));

                                    } else {
                                        $Message = "فشل ارسال رسالة التفعيل";
                                        print_r(json_encode(Message(null, $Message, 202)));
                                    }
                                } else {
                                    $Message = "فشل التسجيل";
                                    print_r(json_encode(Message(null, $Message, 422)));
                                }
                            }
                        }
                    } else {
                        $Message = "رقم الهاتف غير صالح";
                        print_r(json_encode(Message(null, $Message, 400)));
                    }
                } else {
                    $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                    print_r(json_encode(Message(null, $Message, 400)));
                }
            } else {
                $Message = "كلمة المرور غير متطابقة";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
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
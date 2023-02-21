<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 
require_once("../API_Function/All_Function.php"); //All Function
require_once("../API_Mail/Mail.php");  //To Send Email

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_POST['role']) && !empty($_POST['role'])) {

        $URL_Verify = 'http://localhost:3000/ROSHETTA_API/API_User/Verify_User_Account.php';

        //******************************  Start Patients Table  ****************************//

        if ($_POST['role'] === "patient") { //If Patient

            //I Expect To Receive This Data

            if (
                isset($_POST['first_name'])          && !empty($_POST['first_name'])
                && isset($_POST['last_name'])        && !empty($_POST['last_name'])
                && isset($_POST['email'])            && !empty($_POST['email'])
                && isset($_POST['governorate'])      && !empty($_POST['governorate'])
                && isset($_POST['gender'])           && !empty($_POST['gender'])
                && isset($_POST['ssd'])              && !empty($_POST['ssd'])
                && isset($_POST['phone_number'])     && !empty($_POST['phone_number'])
                && isset($_POST['birth_date'])       && !empty($_POST['birth_date'])
                && isset($_POST['weight'])           && !empty($_POST['weight'])
                && isset($_POST['height'])           && !empty($_POST['height'])
                && isset($_POST['password'])         && !empty($_POST['password'])
                && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) {

                    $ssd            = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
                    $email          = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL); //Filter Data 'email'
                    $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'

                    if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE && strlen($ssd) == 14 &&  filter_var($email , FILTER_VALIDATE_EMAIL) !== FALSE) {

                        if (strlen($phone_number) == 11) {

                            //Verify That It Has Not Been Present Before

                            $checkssd = $database->prepare("SELECT * FROM patient WHERE ssd =:ssd OR email = :email");
                            $checkssd->bindparam("ssd", $ssd);
                            $checkssd->bindparam("email", $email);
                            $checkssd->execute();

                            if ($checkssd->rowCount() > 0) {

                                $Message = "الرقم القومى او البريد الالكترونى موجود من قبل";
                                print_r(json_encode(Message(null,$Message,400)));

                            } else {

                                //Filter Data 'INT' && 'STRING' && Hash Password

                                $first_name     = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                                $last_name      = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                                $patient_name   = $first_name . ' ' . $last_name;
                                $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                                $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                                $weight         = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_INT);
                                $height         = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_INT);
                                $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $birth_date     = $_POST['birth_date'];
                                $security_code  = md5(date('h:i:s Y-m-d'));

                                //Add To Table Patients

                                $addData = $database->prepare("INSERT INTO patient(name,ssd,email,phone_number,gender,birth_date,weight,height,governorate,password,security_code,email_isactive,role)
                                                                            VALUES(:patient_name,:ssd,:email,:phone_number,:gender,:birth_date,:weight,:height,:governorate,:password,:security_code,0,'PATIENT')");

                                $addData->bindparam("patient_name", $patient_name);
                                $addData->bindparam("ssd", $ssd);
                                $addData->bindparam("email", $email);
                                $addData->bindparam("phone_number", $phone_number);
                                $addData->bindparam("gender", $gender);
                                $addData->bindparam("birth_date", $birth_date);
                                $addData->bindparam("weight", $weight);
                                $addData->bindparam("height", $height);
                                $addData->bindparam("governorate", $governorate);
                                $addData->bindparam("password", $password_hash);
                                $addData->bindparam("security_code", $security_code);
                                $addData->execute();

                                if ($addData->rowCount() > 0 ) {

                                    //Send  Message To Verify Email

                                    $message_url = $URL_Verify."?email=".$email."&role=PATIENT"."&code=".$security_code;

                                    $mail->setFrom('roshettateam@gmail.com', 'Roshetta Team');
                                    $mail->addAddress($email);
                                    $mail->Subject = 'تفعيل حسابك فى روشتة';
                                    $mail->Body    = EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$patient_name.'</h3>
                                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                    <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                    <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                    <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                                    ') ;

                                    if ($mail->send()) {

                                        $Message = "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى لتفعيل الحساب";
                                        print_r(json_encode(Message(null,$Message,201)));

                                    } else {
                                        $Message = "فشل ارسال رسالة التفعيل";
                                        print_r(json_encode(Message(null,$Message,202)));
                                    }
                                    
                                } else {
                                    $Message = "فشل التسجيل";
                                    print_r(json_encode(Message(null,$Message,422)));
                                }
                            }
                        } else {
                            $Message = "رقم الهاتف غير صالح";
                            print_r(json_encode(Message(null,$Message,400)));
                        }
                    } else {
                        $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $Message = "كلمة المرور غير متطابقة";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

            //****************************** End Patients Table  ****************************//  

            //****************************** Start doctors table ***************************//

        } elseif ($_POST['role'] === "doctor") { //If Doctor

            //I Expect To Receive This Data

            if (
                isset($_POST['first_name'])             && !empty($_POST['first_name'])
                && isset($_POST['last_name'])           && !empty($_POST['last_name'])
                && isset($_POST['email'])               && !empty($_POST['email'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
                && isset($_POST['gender'])              && !empty($_POST['gender'])
                && isset($_POST['ssd'])                 && !empty($_POST['ssd'])
                && isset($_POST['phone_number'])        && !empty($_POST['phone_number'])
                && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
                && isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
                && isset($_POST['specialist'])          && !empty($_POST['specialist'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) {

                    $ssd            = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
                    $email          = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL); //Filter Data 'email'
                    $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'

                    if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE && strlen($ssd) == 14 &&  filter_var($email , FILTER_VALIDATE_EMAIL) !== FALSE) {

                        if (strlen($phone_number) == 11) {

                            //Verify That It Has Not Been Present Before

                            $checkssd = $database->prepare("SELECT * FROM doctor WHERE ssd =:ssd OR email = :email");
                            $checkssd->bindparam("ssd", $ssd);
                            $checkssd->bindparam("email", $email);
                            $checkssd->execute();

                            if ($checkssd->rowCount() > 0) {

                                $Message = "الرقم القومى او البريد الالكترونى موجود من قبل";
                                print_r(json_encode(Message(null,$Message,400)));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    $Message = "رقم الهاتف موجود من قبل";
                                    print_r(json_encode(Message(null,$Message,400)));

                                } else {

                                    //Filter Data 'STRING' && Hash Password

                                    $first_name     = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                                    $last_name      = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                                    $doctor_name    = $first_name . ' ' . $last_name;
                                    $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                                    $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                                    $specialist     = filter_var($_POST['specialist'], FILTER_SANITIZE_STRING);
                                    $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $birth_date     = $_POST['birth_date'];
                                    $security_code  = md5(date('h:i:s Y-m-d'));

                                    //Add To Table Doctors

                                    $addData = $database->prepare("INSERT INTO doctor(name,gender,ssd,email,phone_number,birth_date,password,security_code,specialist,governorate,email_isactive,role)
                                                                                    VALUES(:doctor_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:security_code,:specialist,:governorate,0,'DOCTOR')");

                                    $addData->bindparam("doctor_name", $doctor_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("specialist", $specialist);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->bindparam("security_code", $security_code);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        //Send  Message To Verify Email

                                        $message_url = $URL_Verify."?email=".$email."&role=DOCTOR"."&code=".$security_code;

                                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Team');
                                        $mail->addAddress($email);
                                        $mail->Subject = 'تفعيل حسابك فى روشتة';
                                        $mail->Body    = EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبـــــا بــــك دكتـــــور </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$doctor_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                                        ') ;

                                        
                                  

                                        if ($mail->send()) {

                                            $Message = "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى لتفعيل الحساب";
                                            print_r(json_encode(Message(null,$Message,201)));
                                            
                                        } else {
                                            $Message = "فشل ارسال رسالة التفعيل";
                                            print_r(json_encode(Message(null,$Message,202)));
                                        }

                                    } else {
                                        $Message = "فشل التسجيل";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                }
                            }
                        } else {
                            $Message = "رقم الهاتف غير صالح";
                            print_r(json_encode(Message(null,$Message,400)));
                        }
                    } else {
                        $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $Message = "كلمة المرور غير متطابقة";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

            //****************************** End Doctors Table ****************************//  

            //****************************** Start Pharmacists Table ***************************//

        } elseif ($_POST['role'] === "pharmacist") { //If Pharmacist 

            //I Expect To Receive This Data

            if (
                isset($_POST['first_name'])             && !empty($_POST['first_name'])
                && isset($_POST['last_name'])           && !empty($_POST['last_name'])
                && isset($_POST['email'])               && !empty($_POST['email'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
                && isset($_POST['gender'])              && !empty($_POST['gender'])
                && isset($_POST['ssd'])                 && !empty($_POST['ssd'])
                && isset($_POST['phone_number'])        && !empty($_POST['phone_number'])
                && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
                && isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) {

                    $ssd            = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
                    $email          = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL); //Filter Data 'email'
                    $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'

                    if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE && strlen($ssd) == 14 &&  filter_var($email , FILTER_VALIDATE_EMAIL) !== FALSE) {

                        if (strlen($phone_number) == 11) {

                            //Verify That It Has Not Been Present Before

                            $checkssd = $database->prepare("SELECT * FROM pharmacist WHERE ssd =:ssd OR email = :email");
                            $checkssd->bindparam("ssd", $ssd);
                            $checkssd->bindparam("email", $email);
                            $checkssd->execute();

                            if ($checkssd->rowCount() > 0) {

                                $Message = "الرقم القومى او البريد الالكترونى موجود من قبل";
                                print_r(json_encode(Message(null,$Message,400)));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM pharmacist WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    $Message = "رقم الهاتف موجود من قبل";
                                    print_r(json_encode(Message(null,$Message,400)));

                                } else {

                                    //Filter Data 'STRING' && Hash Password

                                    $first_name         = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                                    $last_name          = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                                    $pharmacist_name    = $first_name . ' ' . $last_name;
                                    $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                                    $gender             = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                                    $password_hash      = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $birth_date         = $_POST['birth_date'];
                                    $security_code      = md5(date('h:i:s Y-m-d'));

                                    //Add To Pharmacists Table

                                    $addData = $database->prepare("INSERT INTO pharmacist(name,gender,ssd,email,phone_number,birth_date,password,security_code,governorate,email_isactive,role)
                                                                                VALUES(:pharmacist_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:security_code,:governorate,0,'PHARMACIST')");

                                    $addData->bindparam("pharmacist_name", $pharmacist_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->bindparam("security_code", $security_code);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        //Send  Message To Verify Email

                                        $message_url = $URL_Verify."?email=".$email."&role=PHARMACIST"."&code=".$security_code;

                                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Team');
                                        $mail->addAddress($email);
                                        $mail->Subject = 'تفعيل حسابك فى روشتة';
                                        $mail->Body    =  EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبـــــا بــــك دكتـــــور </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$pharmacist_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                                        ');
                                        

    
                                        if ($mail->send()) {

                                            $Message = "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى لتفعيل الحساب";
                                            print_r(json_encode(Message(null,$Message,201)));

                                        } else {
                                            $Message = "فشل ارسال رسالة التفعيل";
                                            print_r(json_encode(Message(null,$Message,202)));
                                        }

                                    } else {
                                        $Message = "فشل التسجيل";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                }
                            }
                        } else {
                            $Message = "رقم الهاتف غير صالح";
                            print_r(json_encode(Message(null,$Message,400)));
                        }
                    } else {
                        $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $Message = "كلمة المرور غير متطابقة";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

            //****************************** End Pharmacists Table ****************************//

            //****************************** Start Assistants Table  ***************************//

        } elseif ($_POST['role'] === "assistant") { //If Assistant 

            //I Expect To Receive This Data

            if (
                isset($_POST['first_name'])             && !empty($_POST['first_name'])
                && isset($_POST['last_name'])           && !empty($_POST['last_name'])
                && isset($_POST['email'])               && !empty($_POST['email'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
                && isset($_POST['gender'])              && !empty($_POST['gender'])
                && isset($_POST['ssd'])                 && !empty($_POST['ssd'])
                && isset($_POST['phone_number'])        && !empty($_POST['phone_number'])
                && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
                && isset($_POST['password'])            && !empty($_POST['password'])
                && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
            ) {

                if ($_POST['password'] == $_POST['confirm_password']) {

                    $ssd            = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
                    $email          = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL); //Filter Data 'email'
                    $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'

                    if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE && strlen($ssd) == 14 &&  filter_var($email , FILTER_VALIDATE_EMAIL) !== FALSE) {

                        if (strlen($phone_number) == 11) {

                            //Verify That It Has Not Been Present Before

                            $checkssd = $database->prepare("SELECT * FROM assistant WHERE ssd =:ssd OR email = :email");
                            $checkssd->bindparam("ssd", $ssd);
                            $checkssd->bindparam("email", $email);
                            $checkssd->execute();

                            if ($checkssd->rowCount() > 0) {

                                $Message = "الرقم القومى او البريد الالكترونى موجود من قبل";
                                print_r(json_encode(Message(null,$Message,400)));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM assistant WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    $Message = "رقم الهاتف موجود من قبل";
                                    print_r(json_encode(Message(null,$Message,400)));

                                } else {
                                    //Filter Data 'STRING' && Hash Password

                                    $first_name     = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                                    $last_name      = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                                    $assistant_name = $first_name . ' ' . $last_name;
                                    $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                                    $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                                    $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $birth_date     = $_POST['birth_date'];
                                    $security_code  = md5(date('h:i:s Y-m-d'));

                                    //Add To Assistants Table

                                    $addData = $database->prepare("INSERT INTO assistant(name,gender,ssd,email,phone_number,birth_date,password,security_code,governorate,email_isactive,role)
                                                                                    VALUES(:assistant_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:security_code,:governorate,0,'ASSISTANT')");

                                    $addData->bindparam("assistant_name", $assistant_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->bindparam("security_code", $security_code);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        //Send  Message To Verify Email 

                                        $message_url = $URL_Verify."?email=".$email."&role=ASSISTANT"."&code=".$security_code;

                                        $mail->setFrom('roshettateam@gmail.com', 'Roshetta Team');
                                        $mail->addAddress($email);
                                        $mail->Subject = 'تفعيل حسابك فى روشتة';
                                        $mail->Body    = 
                                        EmailBody("https://img.icons8.com/fluency/300/null/reading-confirmation.png", '
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$assistant_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
                                        ');
   

                                        if ($mail->send()) {

                                            $Message = "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى لتفعيل الحساب";
                                            print_r(json_encode(Message(null,$Message,201)));

                                        } else {
                                            $Message = "فشل ارسال رسالة التفعيل";
                                            print_r(json_encode(Message(null,$Message,202)));
                                        }
                                    } else {
                                        $Message = "فشل التسجيل";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                }
                            }
                        } else {
                            $Message = "رقم الهاتف غير صالح";
                            print_r(json_encode(Message(null,$Message,400)));
                        }
                    } else {
                        $Message = "الرقم القومى او البريد الالكترونى غير صالح";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $Message = "كلمة المرور غير متطابقة";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

            //****************************** End Assistants Table  ****************************//

        } else { //If Didn't Find The Name Of The Role Available
            $Message = "نوع الحساب غير متاح";
            print_r(json_encode(Message(null,$Message,401)));
        }
    } else { //If Didn't Find The Role
        $Message = "يجب تحديد نوع الحساب";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
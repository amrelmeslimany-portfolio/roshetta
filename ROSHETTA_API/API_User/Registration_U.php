<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 
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

                                print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى موجود من قبل"]));

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

                                $addData = $database->prepare("INSERT INTO patient(patient_name,ssd,email,phone_number,gender,birth_date,weight,height,governorate,password,security_code,email_isactive,role)
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
                                    $mail->Body    = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                                    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                                    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                                    <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                                    <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$patient_name.'</h3>
                                    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                    <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                    <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                    <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
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

                                    if ($mail->send()) {

                                        print_r(json_encode(["Message" => "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى والضغط على الرابط لتفعيل الحساب"]));

                                    } else {

                                        print_r(json_encode(["Error" => "فشل ارسال رسالة التفعيل"]));
                                    }
                                    
                                } else {
                                    print_r(json_encode(["Error" => "فشل تسجيل المريض"]));
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صالح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
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

                                print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى موجود من قبل"]));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));

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

                                    $addData = $database->prepare("INSERT INTO doctor(doctor_name,gender,ssd,email,phone_number,birth_date,password,security_code,specialist,governorate,email_isactive,role)
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
                                        $mail->Body    = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                                        <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                                        <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبـــــا بــــك دكتـــــور </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$doctor_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
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

                                        if ($mail->send()) {

                                            print_r(json_encode(["Message" => "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى والضغط على الرابط لتفعيل الحساب"]));

                                        } else {

                                            print_r(json_encode(["Error" => "فشل ارسال رسالة التفعيل"]));
                                        }

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل الدكتور"]));
                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صالح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
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

                                print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى موجود من قبل"]));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM pharmacist WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));

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

                                    $addData = $database->prepare("INSERT INTO pharmacist(pharmacist_name,gender,ssd,email,phone_number,birth_date,password,security_code,governorate,email_isactive,role)
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
                                        $mail->Body    = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                                        <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                                        <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبـــــا بــــك دكتـــــور </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$pharmacist_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
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
    
                                        if ($mail->send()) {

                                            print_r(json_encode(["Message" => "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى والضغط على الرابط لتفعيل الحساب"]));

                                        } else {

                                            print_r(json_encode(["Error" => "فشل ارسال رسالة التفعيل"]));
                                        }

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل الصيدلى"]));

                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صالح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));

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

                                print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى موجود من قبل"]));

                            } else {

                                $check_phone = $database->prepare("SELECT * FROM assistant WHERE phone_number = :phone_number");
                                $check_phone->bindparam("phone_number", $phone_number);
                                $check_phone->execute();

                                if ($check_phone->rowCount() > 0) {

                                    print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));

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

                                    $addData = $database->prepare("INSERT INTO assistant(assistant_name,gender,ssd,email,phone_number,birth_date,password,security_code,governorate,email_isactive,role)
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
                                        $mail->Body    = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
                                        <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
                                        <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
                                        <img src="https://img.icons8.com/fluency/300/null/reading-confirmation.png" style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;"> مـــــرحبــــــا بــــك </h3>
                                        <h3 style="text-align: center;font-family: cursive;font-style: italic;">'.$assistant_name.'</h3>
                                        <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لروشتة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل الحساب الخاص بك من خلال</p></br>
                                        <p style="margin-top: 6px;font-family: cursive;font-weight: 600;">الضغط على الزر بلاسفل</p>                                    
                                        <a href="'.$message_url.'" style="background: #49ce91;color: white;text-decoration: none;padding: 5px 5px;width: fit-content;font-weight: 600;font-family: cursive;border-radius: 5px;font-size: 18px;display: block;margin: 15px auto ;">تفعيل الحساب</a>
                                        <p style="font-family: cursive;color: #2d2d2d;"> <b style="font-weight: 600;"> : أو أكد عن طريق الرابط التالـــي</b> <a href="'.$message_url.'" style="display: block;margin-top: 10px;">'.$message_url.'</a> </p>
                                        <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الرابط متاح للاستخدام مرة واحدة فقط</p>
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

                                        if ($mail->send()) {

                                            print_r(json_encode(["Message" => "تم التسجيل بنجاح الرجاء التوجه الى البريد الالكترونى والضغط على الرابط لتفعيل الحساب"]));

                                        } else {

                                            print_r(json_encode(["Error" => "فشل ارسال رسالة التفعيل"]));
                                        }

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل المساعد"]));
                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او البريد الالكترونى غير صالح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));

            }

            //****************************** End Assistants Table  ****************************//

        } else { //If Didn't Find The Name Of The Role Available
            print_r(json_encode(["Error" => "لا يوجد قاعدة بيانات"]));
        }
    } else { //If Didn't Find The Role
        print_r(json_encode(["Error" => "لا يوجد دور"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
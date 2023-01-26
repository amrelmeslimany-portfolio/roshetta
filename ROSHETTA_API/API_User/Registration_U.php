<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers  

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin


    if (isset($_POST['role']) && !empty($_POST['role'])) {

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

                    require_once("../API_C_A/Connection.php"); //Connect To DataBases 

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

                                print_r(json_encode(["Error" => "الرقم القومى او الايميل موجود من قبل"]));

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

                                //Add To Table Patients

                                $addData = $database->prepare("INSERT INTO patient(patient_name,ssd,email,phone_number,gender,birth_date,weight,height,governorate,password,role)
                                                                            VALUES(:patient_name,:ssd,:email,:phone_number,:gender,:birth_date,:weight,:height,:governorate,:password,'PATIENT')");

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
                                $addData->execute();

                                if ($addData->rowCount() > 0 ) {

                                    print_r(json_encode(["Message" => "تم تسجيل مريض بنجاح"]));

                                } else {
                                    print_r(json_encode(["Error" => "فشل تسجيل المريض"]));
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
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

                    require_once("../API_C_A/Connection.php"); //Connect To DataBases

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

                                print_r(json_encode(["Error" => "الرقم القومى او الايميل موجود من قبل"]));

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

                                    //Add To Table Doctors

                                    $addData = $database->prepare("INSERT INTO doctor(doctor_name,gender,ssd,email,phone_number,birth_date,password,specialist,governorate,role)
                                                                                    VALUES(:doctor_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:specialist,:governorate,'DOCTOR')");

                                    $addData->bindparam("doctor_name", $doctor_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("specialist", $specialist);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        print_r(json_encode(["Message" => "تم تسجيل دكتور بنجاح"]));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل المريض"]));
                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
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

                    require_once("../API_C_A/Connection.php"); //Connect To DataBases

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

                                print_r(json_encode(["Error" => "الرقم القومى او الايميل موجود من قبل"]));

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

                                    //Add To Pharmacists Table

                                    $addData = $database->prepare("INSERT INTO pharmacist(pharmacist_name,gender,ssd,email,phone_number,birth_date,password,governorate,role)
                                                                                VALUES(:pharmacist_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:governorate,'PHARMACIST')");

                                    $addData->bindparam("pharmacist_name", $pharmacist_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        print_r(json_encode(["Message" => "تم تسجيل صيدلى بنجاح"]));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل الصيدلى"]));

                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
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

                    require_once("../API_C_A/Connection.php"); //Connect To DataBases

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

                                print_r(json_encode(["Error" => "الرقم القومى او الايميل موجود من قبل"]));

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

                                    //Add To Assistants Table

                                    $addData = $database->prepare("INSERT INTO assistant(assistant_name,gender,ssd,email,phone_number,birth_date,password,governorate,role)
                                                                                    VALUES(:assistant_name,:gender,:ssd,:email,:phone_number,:birth_date,:password,:governorate,'ASSISTANT')");

                                    $addData->bindparam("assistant_name", $assistant_name);
                                    $addData->bindparam("governorate", $governorate);
                                    $addData->bindparam("gender", $gender);
                                    $addData->bindparam("ssd", $ssd);
                                    $addData->bindparam("email", $email);
                                    $addData->bindparam("phone_number", $phone_number);
                                    $addData->bindparam("birth_date", $birth_date);
                                    $addData->bindparam("password", $password_hash);
                                    $addData->execute();

                                    if ($addData->rowCount() > 0 ) {

                                        print_r(json_encode(["Message" => "تم تسجيل مساعد بنجاح"]));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل تسجيل المساعد"]));
                                    }
                                }
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
                }
            } else {
                print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));

            }

            //****************************** End Assistants Table  ****************************//

        } else { //If Didn't Find The Name Of The Role Available
            print_r(json_encode(["Error" => "لا يوجد داتا بيز"]));
        }
    } else { //If Didn't Find The Role
        print_r(json_encode(["Error" => "لا يوجد دور"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
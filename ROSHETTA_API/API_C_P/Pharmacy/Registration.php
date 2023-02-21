<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist'])) {

        $ph_id = $_SESSION['pharmacist'];

        //Check Activation 

        $checkActivation = $database->prepare("SELECT * FROM activation_person,pharmacist  WHERE  activation_person.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
        $checkActivation->bindparam("id", $ph_id);
        $checkActivation->execute();

        if ($checkActivation->rowCount() > 0) {

            $Activation = $checkActivation->fetchObject();

            if ($Activation->isactive == 1) {

                //Check Number Of Pharmacy

                $check_number_p = $database->prepare("SELECT * FROM pharmacy,pharmacist WHERE  pharmacy.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
                $check_number_p->bindparam("id", $ph_id);
                $check_number_p->execute();

                if ($check_number_p->rowCount() < 2) {

                    $data_user  = $check_number_p->fetchObject();
                    $owner      = $data_user->name;

                    //I Expect To Receive This Data

                    if (
                        isset($_POST['pharmacy_name'])      && !empty($_POST['pharmacy_name'])
                        && isset($_POST['phone_number'])    && !empty($_POST['phone_number'])
                        && isset($_POST['start_working'])   && !empty($_POST['start_working'])
                        && isset($_POST['end_working'])     && !empty($_POST['end_working'])
                        && isset($_POST['governorate'])     && !empty($_POST['governorate'])
                        && isset($_POST['address'])         && !empty($_POST['address'])
                    ) {

                        //Filter Data 'String'

                        $pharmacy_name  = filter_var($_POST['pharmacy_name'], FILTER_SANITIZE_STRING);
                        $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
                        $address        = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                        $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                        $start_working  = $_POST['start_working'];
                        $end_working    = $_POST['end_working'];
                        $ser_id         = rand(1000, 9999) . $phar_id . rand(100000, 999999);

                        //Check Phone Number

                        if (strlen($phone_number) == 11 ) {

                            $check_number_phone = $database->prepare("SELECT * FROM pharmacy WHERE  phone_number = :phone_number");
                            $check_number_phone->bindparam("phone_number", $phone_number);
                            $check_number_phone->execute();

                            if ($check_number_phone->rowCount() > 0) {

                                $Message = "رقم الهاتف موجود من قبل";
                                print_r(json_encode(Message(null,$Message,400)));
                                die("");

                            } else {

                                //Add To Pharmacy Table

                                $addData = $database->prepare("INSERT INTO pharmacy(name,owner,phone_number,start_working,end_working,address,governorate,pharmacist_id,ser_id)
                                                                                VALUES(:pharmacy_name,:owner,:phone_number,:start_working,:end_working,:address,:governorate,:pharmacist_id,:ser_id)");

                                $addData->bindparam("pharmacy_name", $pharmacy_name);
                                $addData->bindparam("phone_number", $phone_number);
                                $addData->bindparam("address", $address);
                                $addData->bindparam("governorate", $governorate);
                                $addData->bindparam("start_working", $start_working);
                                $addData->bindparam("end_working", $end_working);
                                $addData->bindparam("pharmacist_id", $ph_id);
                                $addData->bindparam("owner", $owner);
                                $addData->bindparam("ser_id", $ser_id);

                                if ($addData->execute()) {

                                    if($addData->rowCount() > 0 ) {

                                        $Message = "تم تسجيل الصيدلية بنجاح";
                                        print_r(json_encode(Message(null,$Message,201)));
                                        header("refresh:2;");
                                        
                                    } else {
                                        $Message = "فشل تسجيل الصيدلية";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                } else {
                                    $Message = "فشل تسجيل الصيدلية";
                                    print_r(json_encode(Message(null,$Message,422)));
                                }
                            }
                        } else {
                            $Message = "رقم الهاتف غير صالح";
                            print_r(json_encode(Message(null,$Message,400)));
                        }
                    } else {
                        $Message = "يجب اكمال البيانات";
                        print_r(json_encode(Message(null,$Message,400)));
                    }
                } else {
                    $message = "لايمكنك تسجيل اكثر من '2' صيدلية";
                    print_r(json_encode(Message(null , $message , 202)));
                }
            } else {
                $message = "الرجاء الانتظار حتى يتم تنشيط خسابك من قبل المشرف";
                print_r(json_encode(Message(null , $message , 202)));
            }
        } else {
            $message = "يجب تفعيل الحساب";
            print_r(json_encode(Message(null , $message , 202)));
        }
    } else {
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
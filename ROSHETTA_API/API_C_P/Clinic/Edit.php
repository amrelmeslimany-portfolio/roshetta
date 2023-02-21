<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor']) || isset($_SESSION['assistant'])) {

        if (isset($_SESSION['clinic'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])       && !empty($_POST['phone_number'])
                && isset($_POST['governorate'])     && !empty($_POST['governorate'])
                && isset($_POST['address'])         && !empty($_POST['address'])
                && isset($_POST['clinic_price'])    && !empty($_POST['clinic_price'])
                && isset($_POST['start_working'])   && !empty($_POST['start_working'])
                && isset($_POST['end_working'])     && !empty($_POST['end_working'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $id             = $_SESSION['clinic'];
                $start_working  = $_POST['start_working'];
                $end_working    = $_POST['end_working'];
                $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $address        = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                $clinic_price   = filter_var($_POST['clinic_price'], FILTER_SANITIZE_NUMBER_INT);
                $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11 ) {

                    $check_number_phone = $database->prepare("SELECT * FROM clinic WHERE  phone_number = :phone_number");
                    $check_number_phone->bindparam("phone_number", $phone_number);
                    $check_number_phone->execute();

                    if ($check_number_phone->rowCount() > 0) {

                        $check_number_phone_id = $database->prepare("SELECT * FROM clinic WHERE  phone_number = :phone_number AND id = :id");
                        $check_number_phone_id->bindparam("phone_number", $phone_number);
                        $check_number_phone_id->bindparam("id", $id);
                        $check_number_phone_id->execute();

                        if ($check_number_phone_id->rowCount() > 0) {

                            //UpDate Clinic Table

                            $Update = $database->prepare("UPDATE clinic SET phone_number = :phone_number , address = :address , clinic_price = :clinic_price , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                            $Update->bindparam("id", $id);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("address", $address);
                            $Update->bindparam("clinic_price", $clinic_price);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("start_working", $start_working);
                            $Update->bindparam("end_working", $end_working);
                            $Update->execute();

                            if ($Update->rowCount() > 0 ) {

                                $Message = "تم تعديل البيانات بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل البيانات";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {
                            $Message = "رقم الهاتف موجود من قبل";
                            print_r(json_encode(Message(null,$Message,400)));
                            die("");
                        }

                    } else {

                        //UpDate Clinic Table

                        $Update = $database->prepare("UPDATE clinic SET phone_number = :phone_number , address = :address , clinic_price = :clinic_price , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("address", $address);
                        $Update->bindparam("clinic_price", $clinic_price);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("start_working", $start_working);
                        $Update->bindparam("end_working", $end_working);
                        $Update->execute();

                        if ($Update->rowCount() > 0 ) {

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
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
            $Message = "فشل العثور على مستخدم";
            print_r(json_encode(Message(null,$Message,401)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
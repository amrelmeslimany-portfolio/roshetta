<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin


    if (isset($_SESSION['admin'])) {

        require_once("../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['clinic_id']) && !empty($_POST['clinic_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])           && !empty($_POST['phone_number'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
                && isset($_POST['address'])             && !empty($_POST['address'])
                && isset($_POST['clinic_price'])        && !empty($_POST['clinic_price'])
                && isset($_POST['start_working'])       && !empty($_POST['start_working'])
                && isset($_POST['end_working'])         && !empty($_POST['end_working'])
                && isset($_POST['clinic_name'])         && !empty($_POST['clinic_name'])
                && isset($_POST['owner'])               && !empty($_POST['owner'])
                && isset($_POST['clinic_specialist'])   && !empty($_POST['clinic_specialist'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $id                 = filter_var($_POST['clinic_id'], FILTER_SANITIZE_NUMBER_INT);
                $start_working      = $_POST['start_working'];
                $end_working        = $_POST['end_working'];
                $phone_number       = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $address            = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                $clinic_price       = filter_var($_POST['clinic_price'], FILTER_SANITIZE_NUMBER_INT);
                $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                $clinic_name        = filter_var($_POST['clinic_name'], FILTER_SANITIZE_STRING);
                $owner              = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);
                $clinic_specialist  = filter_var($_POST['clinic_specialist'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

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

                            $Update = $database->prepare("UPDATE clinic SET clinic_name = :clinic_name , owner = :owner , clinic_specialist = :clinic_specialist , phone_number = :phone_number , address = :address , clinic_price = :clinic_price , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                            $Update->bindparam("id", $id);
                            $Update->bindparam("clinic_name", $clinic_name);
                            $Update->bindparam("owner", $owner);
                            $Update->bindparam("clinic_specialist", $clinic_specialist);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("address", $address);
                            $Update->bindparam("clinic_price", $clinic_price);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("start_working", $start_working);
                            $Update->bindparam("end_working", $end_working);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                            }

                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف مرتبط بعيادة اخرى"]));
                            die("");
                        }

                    } else {

                        //UpDate Clinic Table

                        $Update = $database->prepare("UPDATE clinic SET clinic_name = :clinic_name , owner = :owner , clinic_specialist = :clinic_specialist , phone_number = :phone_number , address = :address , clinic_price = :clinic_price , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("clinic_name", $clinic_name);
                        $Update->bindparam("owner", $owner);
                        $Update->bindparam("clinic_specialist", $clinic_specialist);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("address", $address);
                        $Update->bindparam("clinic_price", $clinic_price);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("start_working", $start_working);
                        $Update->bindparam("end_working", $end_working);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }
                    }

                } else {
                    print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال جميع البيانات"]));
            }

        } elseif (isset($_POST['pharmacy_id']) && !empty($_POST['pharmacy_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])       && !empty($_POST['phone_number'])
                && isset($_POST['governorate'])     && !empty($_POST['governorate'])
                && isset($_POST['address'])         && !empty($_POST['address'])
                && isset($_POST['start_working'])   && !empty($_POST['start_working'])
                && isset($_POST['end_working'])     && !empty($_POST['end_working'])
                && isset($_POST['pharmacy_name'])   && !empty($_POST['pharmacy_name'])
                && isset($_POST['owner'])           && !empty($_POST['owner'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $id                 = filter_var($_POST['pharmacy_id'], FILTER_SANITIZE_NUMBER_INT);
                $start_working      = $_POST['start_working'];
                $end_working        = $_POST['end_working'];
                $phone_number       = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $address            = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
                $clinic_name        = filter_var($_POST['pharmacy_name'], FILTER_SANITIZE_STRING);
                $owner              = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    $check_number_phone = $database->prepare("SELECT * FROM pharmacy WHERE  phone_number = :phone_number");
                    $check_number_phone->bindparam("phone_number", $phone_number);
                    $check_number_phone->execute();

                    if ($check_number_phone->rowCount() > 0) {

                        $check_number_phone_id = $database->prepare("SELECT * FROM pharmacy WHERE  phone_number = :phone_number AND id = :id");
                        $check_number_phone_id->bindparam("phone_number", $phone_number);
                        $check_number_phone_id->bindparam("id", $id);
                        $check_number_phone_id->execute();

                        if ($check_number_phone_id->rowCount() > 0) {

                            //UpDate Pharmacy Table

                            $Update = $database->prepare("UPDATE pharmacy SET pharmacy_name = :pharmacy_name , owner = :owner , phone_number = :phone_number , address = :address , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                            $Update->bindparam("id", $id);
                            $Update->bindparam("pharmacy_name", $pharmacy_name);
                            $Update->bindparam("owner", $owner);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("address", $address);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("start_working", $start_working);
                            $Update->bindparam("end_working", $end_working);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "رقم الهاتف مرتبط بصيدلية اخرى"]));
                            die("");
                        }

                    } else {

                        //UpDate Pharmacy Table

                        $Update = $database->prepare("UPDATE pharmacy SET pharmacy_name = :pharmacy_name , owner = :owner , phone_number = :phone_number , address = :address , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("pharmacy_name", $pharmacy_name);
                        $Update->bindparam("owner", $owner);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("address", $address);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("start_working", $start_working);
                        $Update->bindparam("end_working", $end_working);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }
                    }

                } else {
                    print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال جميع البيانات"]));
            }

        }
    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
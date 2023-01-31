<?php

session_start();
session_regenerate_id();

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

            $id             = $_SESSION['clinic']->id;
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

                            //Get New Data From Clinic Table

                            $get_data = $database->prepare("SELECT * FROM clinic WHERE id = :id ");

                            $get_data->bindparam("id", $id);
                            $get_data->execute();

                            if ($get_data->rowCount() > 0 ) {

                                $clinic_up = $get_data->fetchObject();

                                $_SESSION['clinic'] = $clinic_up; //UpDate SESSION Clinic

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "رقم الهاتف مرتبط بعيادة اخرى"]));
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

                        //Get New Data From Clinic Table

                        $get_data = $database->prepare("SELECT * FROM clinic WHERE id = :id ");
                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $clinic_up = $get_data->fetchObject();

                            $_SESSION['clinic'] = $clinic_up; //UpDate SESSION Clinic

                            print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                    }
                }
            } else {
                print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
            }
        } else {

            //Print Clinic Data From Session

            $logo               = $_SESSION['clinic']->logo;
            $clinic_name        = $_SESSION['clinic']->clinic_name;
            $clinic_specialist  = $_SESSION['clinic']->clinic_specialist;
            $phone_number       = $_SESSION['clinic']->phone_number;
            $owner              = $_SESSION['clinic']->owner;
            $clinic_price       = $_SESSION['clinic']->clinic_price;
            $start_working      = $_SESSION['clinic']->start_working;
            $end_working        = $_SESSION['clinic']->end_working;
            $governorate        = $_SESSION['clinic']->governorate;
            $address            = $_SESSION['clinic']->address;


            $clinic_data = array(

                "logo"              => $logo,
                "clinic_name"       => $clinic_name,
                "clinic_specialist" => $clinic_specialist,
                "phone_number"      => $phone_number,
                "owner"             => $owner,
                "clinic_price"      => $clinic_price,
                "start_working"     => $start_working,
                "end_working"       => $end_working,
                "governorate"       => $governorate,
                "address"           => $address

            );

            print_r(json_encode($clinic_data));
        }

    } else {
        print_r(json_encode(["Error" => "فشل العثور على عيادة"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
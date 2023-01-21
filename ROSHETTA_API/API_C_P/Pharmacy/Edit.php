<?php

session_start();
session_regenerate_id();

if (isset($_SESSION['pharmacy']) || isset($_SESSION['pharmacist'])) {

    if (isset($_SESSION['pharmacy'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['phone_number'])       && !empty($_POST['phone_number'])
            && isset($_POST['governorate'])     && !empty($_POST['governorate'])
            && isset($_POST['address'])         && !empty($_POST['address'])
            && isset($_POST['start_working'])   && !empty($_POST['start_working'])
            && isset($_POST['end_working'])     && !empty($_POST['end_working'])
        ) {

            //Filter Data 'Number_Int' And 'String'

            $id             = $_SESSION['pharmacy']->id;
            $start_working  = $_POST['start_working'];
            $end_working    = $_POST['end_working'];
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
            $address        = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
            $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);


            //UpDate Pharmacy Table

            $Update = $database->prepare("UPDATE pharmacy SET phone_number = :phone_number , address = :address , governorate = :governorate , start_working = :start_working , end_working = :end_working  WHERE id = :id");

            $Update->bindparam("id", $id);
            $Update->bindparam("phone_number", $phone_number);
            $Update->bindparam("address", $address);
            $Update->bindparam("governorate", $governorate);
            $Update->bindparam("start_working", $start_working);
            $Update->bindparam("end_working", $end_working);

            if ($Update->execute()) {

                //Get New Data From Pharmacy Table

                $get_data = $database->prepare("SELECT * FROM pharmacy WHERE id = :id ");

                $get_data->bindparam("id", $id);

                if ($get_data->execute()) {

                    $pharmacy_up = $get_data->fetchObject();

                    $_SESSION['pharmacy'] = $pharmacy_up; //UpDate SESSION Pharmacy

                    print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                    header("refresh:2;");


                } else {
                    print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                }
            } else {
                print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
            }

        } else {

            //Print Pharmacy Data From Session

            $logo               = $_SESSION['pharmacy']->logo;
            $pharmacy_name      = $_SESSION['pharmacy']->pharmacy_name;
            $phone_number       = $_SESSION['pharmacy']->phone_number;
            $owner              = $_SESSION['pharmacy']->owner;
            $start_working      = $_SESSION['pharmacy']->start_working;
            $end_working        = $_SESSION['pharmacy']->end_working;
            $governorate        = $_SESSION['pharmacy']->governorate;
            $address            = $_SESSION['pharmacy']->address;


            $pharmacy_data = array(

                "logo"              => $logo,
                "pharmacy_name"     => $pharmacy_name,
                "phone_number"      => $phone_number,
                "owner"             => $owner,
                "start_working"     => $start_working,
                "end_working"       => $end_working,
                "governorate"       => $governorate,
                "address"           => $address

            );

            print_r(json_encode($pharmacy_data));
        }


    } else {
        print_r(json_encode(["Error" => "فشل العثور على صيدلية"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
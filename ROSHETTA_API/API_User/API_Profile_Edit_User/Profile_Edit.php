<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (
    isset($_SESSION['patient'])
    || isset($_SESSION['doctor'])
    || isset($_SESSION['pharmacist'])
    || isset($_SESSION['assistant'])
) {

    if (isset($_SESSION['patient'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['phone_number'])   && !empty($_POST['phone_number'])
            && isset($_POST['weight'])      && !empty($_POST['weight'])
            && isset($_POST['height'])      && !empty($_POST['height'])
            && isset($_POST['governorate']) && !empty($_POST['governorate'])
        ) {

            //Filter Data 'Number_Int' And 'String'

            $id             = $_SESSION['patient']->id;
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
            $weight         = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_INT);
            $height         = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_INT);
            $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

            if (strlen($phone_number) == 11 ) {

                //UpDate Patient Table

                $Update = $database->prepare("UPDATE patient SET phone_number = :phone_number , weight = :weight , height = :height , governorate = :governorate WHERE id = :id");

                $Update->bindparam("id", $id);
                $Update->bindparam("phone_number", $phone_number);
                $Update->bindparam("weight", $weight);
                $Update->bindparam("height", $height);
                $Update->bindparam("governorate", $governorate);
                $Update->execute();

                if ($Update->rowCount() > 0 ) {

                    //Get New Data From Patient Table

                    $get_data = $database->prepare("SELECT * FROM patient WHERE id = :id ");

                    $get_data->bindparam("id", $id);
                    $get_data->execute();

                    if ($get_data->rowCount() > 0 ) {

                        $patient_up = $get_data->fetchObject();
                        $_SESSION['patient'] = $patient_up; //UpDate SESSION Patient

                        print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                        header("refresh:2;");

                    } else {
                        print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                }
            } else {
                print_r(json_encode(["Error" => "رقم الهاتف غير صالح"]));
            }

        } else {

            //Print Patient Data From Session

            $patient_name   = $_SESSION['patient']->patient_name;
            $ssd            = $_SESSION['patient']->ssd;
            $email          = $_SESSION['patient']->email;
            $phone_number   = $_SESSION['patient']->phone_number;
            $gender         = $_SESSION['patient']->gender;
            $birth_date     = $_SESSION['patient']->birth_date;
            $weight         = $_SESSION['patient']->weight;
            $height         = $_SESSION['patient']->height;
            $governorate    = $_SESSION['patient']->governorate;
            $profile_img    = $_SESSION['patient']->profile_img;

            $patient_data = array(

                "patient_name"  => $patient_name,
                "ssd"           => $ssd,
                "email"         => $email,
                "phone_number"  => $phone_number,
                "gender"        => $gender,
                "birth_date"    => $birth_date,
                "weight"        => $weight,
                "height"        => $height,
                "governorate"   => $governorate,
                "profile_img"   => $profile_img

            );

            print_r(json_encode($patient_data));


        }
    } elseif (isset($_SESSION['doctor'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['phone_number'])   && !empty($_POST['phone_number'])
            && isset($_POST['governorate']) && !empty($_POST['governorate'])
        ) {

            //Filter Data 'Number_Int' And 'String'

            $id             = $_SESSION['doctor']->id;
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
            $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

            if (strlen($phone_number) == 11 ) {

                //Verify That It Has Not Been Present Before

                $check_phone = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number");

                $check_phone->bindparam("phone_number", $phone_number);
                $check_phone->execute();

                if ($check_phone->rowCount() > 0) {

                    $check_phone = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number AND id = :id");

                    $check_phone->bindparam("phone_number", $phone_number);
                    $check_phone->bindparam("id", $id);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        //UpDate Doctor Table

                        $Update = $database->prepare("UPDATE doctor SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->execute();

                        if ($Update->rowCount() > 0 ) {

                            //Get New Data From Doctor Table

                            $get_data = $database->prepare("SELECT * FROM doctor WHERE id = :id ");

                            $get_data->bindparam("id", $id);
                            $get_data->execute();

                            if ($get_data->rowCount() > 0 ) {

                                $doctor_up = $get_data->fetchObject();
                                $_SESSION['doctor'] = $doctor_up; //UpDate SESSION Doctor

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));
                    }

                } else {

                    //UpDate Doctor Table

                    $Update = $database->prepare("UPDATE doctor SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->bindparam("governorate", $governorate);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Doctor Table

                        $get_data = $database->prepare("SELECT * FROM doctor WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $doctor_up = $get_data->fetchObject();
                            $_SESSION['doctor'] = $doctor_up; //UpDate SESSION Doctor

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

            //Print Doctor Data From Session

            $doctor_name    = $_SESSION['doctor']->doctor_name;
            $ssd            = $_SESSION['doctor']->ssd;
            $email          = $_SESSION['doctor']->email;
            $phone_number   = $_SESSION['doctor']->phone_number;
            $gender         = $_SESSION['doctor']->gender;
            $birth_date     = $_SESSION['doctor']->birth_date;
            $specialist     = $_SESSION['doctor']->specialist;
            $governorate    = $_SESSION['doctor']->governorate;
            $profile_img    = $_SESSION['doctor']->profile_img;

            $doctor_data = array(

                "doctor_name"   => $doctor_name,
                "ssd"           => $ssd,
                "email"         => $email,
                "phone_number"  => $phone_number,
                "gender"        => $gender,
                "birth_date"    => $birth_date,
                "specialist"    => $specialist,
                "governorate"   => $governorate,
                "profile_img"   => $profile_img

            );

            print_r(json_encode($doctor_data));

        }

    } elseif (isset($_SESSION['pharmacist'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['phone_number']) && !empty($_POST['phone_number'])
            && isset($_POST['governorate']) && !empty($_POST['governorate'])
        ) {

            //Filter Data 'Number_Int' And 'String'

            $id             = $_SESSION['pharmacist']->id;
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
            $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

            if (strlen($phone_number) == 11 ) {

                //Verify That It Has Not Been Present Before

                $check_phone = $database->prepare("SELECT * FROM pharmacist WHERE phone_number = :phone_number");

                $check_phone->bindparam("phone_number", $phone_number);
                $check_phone->execute();

                if ($check_phone->rowCount() > 0) {

                    $check_phone = $database->prepare("SELECT * FROM pharmacist WHERE phone_number = :phone_number AND id = :id");

                    $check_phone->bindparam("phone_number", $phone_number);
                    $check_phone->bindparam("id", $id);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        //UpDate Pharmacist Table

                        $Update = $database->prepare("UPDATE pharmacist SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->execute();

                        if ($Update->rowCount() > 0 ) {

                            //Get New Data From Pharmacist Table

                            $get_data = $database->prepare("SELECT * FROM pharmacist WHERE id = :id ");

                            $get_data->bindparam("id", $id);
                            $get_data->execute();

                            if ($get_data->rowCount() > 0 ) {

                                $pharmacist_up = $get_data->fetchObject();
                                $_SESSION['pharmacist'] = $pharmacist_up; //UpDate SESSION Pharmacist

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));
                    }

                } else {

                    //UpDate Pharmacist Table

                    $Update = $database->prepare("UPDATE pharmacist SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->bindparam("governorate", $governorate);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Pharmacist Table

                        $get_data = $database->prepare("SELECT * FROM pharmacist WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $pharmacist_up = $get_data->fetchObject();
                            $_SESSION['pharmacist'] = $pharmacist_up; //UpDate SESSION Pharmacist

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

            //Print Pharmacist Data From Session

            $pharmacist_name    = $_SESSION['pharmacist']->pharmacist_name;
            $ssd                = $_SESSION['pharmacist']->ssd;
            $email              = $_SESSION['pharmacist']->email;
            $phone_number       = $_SESSION['pharmacist']->phone_number;
            $gender             = $_SESSION['pharmacist']->gender;
            $birth_date         = $_SESSION['pharmacist']->birth_date;
            $governorate        = $_SESSION['pharmacist']->governorate;
            $profile_img        = $_SESSION['pharmacist']->profile_img;

            $pharmacist_data = array(

                "pharmacist_name"   => $pharmacist_name,
                "ssd"               => $ssd,
                "email"             => $email,
                "phone_number"      => $phone_number,
                "gender"            => $gender,
                "birth_date"        => $birth_date,
                "governorate"       => $governorate,
                "profile_img"       => $profile_img

            );

            print_r(json_encode($pharmacist_data));

        }

    } elseif (isset($_SESSION['assistant'])) {

        //I Expect To Receive This Data

        if (
            isset($_POST['phone_number'])   && !empty($_POST['phone_number'])
            && isset($_POST['governorate']) && !empty($_POST['governorate'])
        ) {

            //Filter Data 'Number_Int' And 'String'

            $id             = $_SESSION['assistant']->id;
            $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
            $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

            if (strlen($phone_number) == 11 ) {

                //Verify That It Has Not Been Present Before

                $check_phone = $database->prepare("SELECT * FROM assistant WHERE phone_number = :phone_number");

                $check_phone->bindparam("phone_number", $phone_number);
                $check_phone->execute();

                if ($check_phone->rowCount() > 0 ) {

                    $check_phone = $database->prepare("SELECT * FROM assistant WHERE phone_number = :phone_number AND id = :id ");

                    $check_phone->bindparam("phone_number", $phone_number);
                    $check_phone->bindparam("id", $id);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0 ) {

                        //UpDate Assistant Table

                        $Update = $database->prepare("UPDATE assistant SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id ");

                        $Update->bindparam("id", $id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->execute();

                        if ($Update->rowCount() > 0 ) {

                            //Get New Data From Assistant Table

                            $get_data = $database->prepare("SELECT * FROM assistant WHERE id = :id ");

                            $get_data->bindparam("id", $id);
                            $get_data->execute();

                            if ($get_data->rowCount() > 0 ) {

                                $assistant_up = $get_data->fetchObject();
                                $_SESSION['assistant'] = $assistant_up; //UpDate SESSION Assistant

                                print_r(json_encode(["Message" => "تم تعديل البيانات بنجاح"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب البيانات"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل البيانات"]));
                        }

                    } else {
                        print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));
                    }

                } else {

                    //UpDate Assistant Table

                    $Update = $database->prepare("UPDATE assistant SET phone_number = :phone_number , governorate = :governorate  WHERE id = :id");

                    $Update->bindparam("id", $id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->bindparam("governorate", $governorate);
                    $Update->execute();

                    if ($Update->rowCount() > 0 ) {

                        //Get New Data From Assistant Table

                        $get_data = $database->prepare("SELECT * FROM assistant WHERE id = :id ");

                        $get_data->bindparam("id", $id);
                        $get_data->execute();

                        if ($get_data->rowCount() > 0 ) {

                            $assistant_up = $get_data->fetchObject();
                            $_SESSION['assistant'] = $assistant_up; //UpDate SESSION Assistant

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

            //Print Assistant Data From Session

            $assistant_name = $_SESSION['assistant']->assistant_name;
            $ssd            = $_SESSION['assistant']->ssd;
            $email          = $_SESSION['assistant']->email;
            $phone_number   = $_SESSION['assistant']->phone_number;
            $gender         = $_SESSION['assistant']->gender;
            $birth_date     = $_SESSION['assistant']->birth_date;
            $governorate    = $_SESSION['assistant']->governorate;
            $profile_img    = $_SESSION['assistant']->profile_img;

            $assistant_data = array(

                "assistant_name"    => $assistant_name,
                "ssd"               => $ssd,
                "email"             => $email,
                "phone_number"      => $phone_number,
                "gender"            => $gender,
                "birth_date"        => $birth_date,
                "governorate"       => $governorate,
                "profile_img"       => $profile_img

            );

            print_r(json_encode($assistant_data));

        }

    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else {
    print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
}
?>
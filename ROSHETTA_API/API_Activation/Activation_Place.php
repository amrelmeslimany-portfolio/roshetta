<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Allow Access Via 'POST' Method Only

    session_start();
    session_regenerate_id();

    if (isset($_SESSION['clinic']) || isset($_SESSION['pharmacy'])) {

        //I Expect To Receive This Data

        $img_name   = $_FILES["license_img"]["name"];
        $img_size   = $_FILES["license_img"]["size"];
        $img_tmp    = $_FILES["license_img"]["tmp_name"];

        $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

        //To Get The Image Formul

        $check_formul   = explode(".", $img_name);
        $formul         = end($check_formul);

        if (in_array($formul, $allowed_formulas)) {

            if ($img_size > 1000000) { //To Specify The Image Size < 1M

                print_r(json_encode(["Error" => "الحجم كبير"]));

                header("refresh:2;");

            } else {

                if (isset($_SESSION['clinic'])) {

                    $folder_name    = $_SESSION['clinic']->ser_id;
                    $img_new_name   = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link           = 'IMG/place_Img/Clinic/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['clinic']->id;

                                $check_clinic = $database->prepare("SELECT * FROM activation_place,clinic  WHERE  activation_place.clinic_id = clinic.id  AND clinic.id = :id ");
                                $check_clinic->bindparam("id", $id);
                                $check_clinic->execute();

                                if ($check_clinic->rowCount() > 0) {

                                    //UpDate activation_place Table

                                    $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.clinic_id = :id ");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("id", $id);

                                    if ($uploadImg->execute()) {

                                        print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                        header("refresh:2;");

                                    } else {
                                        print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                    }
                                } else {

                                    //Add To activation_place Table

                                    $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,clinic_id,isactive) VALUES(:license_img,:clinic_id,0)");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("clinic_id", $id);

                                    if ($uploadImg->execute()) {

                                        print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['clinic']->id;

                        $check_clinic = $database->prepare("SELECT * FROM activation_place,clinic  WHERE  activation_place.clinic_id = clinic.id  AND clinic.id = :id ");
                        $check_clinic->bindparam("id", $id);
                        $check_clinic->execute();

                        if ($check_clinic->rowCount() > 0) {

                            //UpDate activation_place Table

                            $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.clinic_id = :id ");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("id", $id);

                            if ($uploadImg->execute()) {

                                print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل رفع الملف"]));
                            }
                        } else {

                            //Add To activation_place Table

                            $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,clinic_id,isactive) VALUES(:license_img,:clinic_id,0)");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("clinic_id", $id);

                            if ($uploadImg->execute()) {

                                print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                            } else {
                                print_r(json_encode(["Error" => "فشل رفع الملف"]));
                            }

                        }
                    }

                } elseif (isset($_SESSION['pharmacy'])) {


                    $folder_name    = $_SESSION['pharmacy']->ser_id;
                    $img_new_name   = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link           = 'IMG/place_Img/Pharmacy/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['pharmacy']->id;

                                $check_clinic = $database->prepare("SELECT * FROM activation_place,pharmacy  WHERE  activation_place.pharmacy_id = pharmacy.id  AND pharmacy.id = :id ");
                                $check_clinic->bindparam("id", $id);
                                $check_clinic->execute();

                                if ($check_clinic->rowCount() > 0) {

                                    //UpDate activation_place Table

                                    $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.pharmacy_id = :id ");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("id", $id);

                                    if ($uploadImg->execute()) {

                                        print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                        header("refresh:2;");

                                    } else {
                                        print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                    }
                                } else {

                                    //Add To activation_place Table

                                    $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,pharmacy_id,isactive) VALUES(:license_img,:pharmacy_id,0)");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("pharmacy_id", $id);

                                    if ($uploadImg->execute()) {

                                        print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['pharmacy']->id;

                        $check_clinic = $database->prepare("SELECT * FROM activation_place,pharmacy  WHERE  activation_place.pharmacy_id = pharmacy.id  AND pharmacy.id = :id ");
                        $check_clinic->bindparam("id", $id);
                        $check_clinic->execute();

                        if ($check_clinic->rowCount() > 0) {

                            //UpDate activation_place Table

                            $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.pharmacy_id = :id ");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("id", $id);

                            if ($uploadImg->execute()) {

                                print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                                header("refresh:2;");

                            } else {
                                print_r(json_encode(["Error" => "فشل رفع الملف"]));
                            }
                        } else {

                            //Add To activation_place Table

                            $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,pharmacy_id,isactive) VALUES(:license_img,:pharmacy_id,0)");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("pharmacy_id", $id);

                            if ($uploadImg->execute()) {

                                print_r(json_encode(["Message" => "تم التقديم للمراجعة"]));

                            } else {
                                print_r(json_encode(["Error" => "فشل رفع الملف"]));
                            }

                        }
                    }

                } else {
                    print_r(json_encode(["Error" => "فشل تحديد الشيشن"]));
                }
            }
        } else {
            print_r(json_encode(["Error" => "صيغة الملف غير مدعومة"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على الشيشن"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
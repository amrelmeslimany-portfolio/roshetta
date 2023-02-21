<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBase
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['clinic']) || isset($_SESSION['pharmacy'])) {

        if (isset($_SESSION['clinic'])) {
            $table_name = 'clinic';
            $id         = $_SESSION['clinic'];
        } else {
            $table_name = 'pharmacy';
            $id         = $_SESSION['pharmacy'];
        }

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

                $Message = "(1M)يجب أن يكون حجم الصورة أقل من";
                print_r(json_encode(Message(null,$Message,400)));
                header("refresh:2;");

            } else {

                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]

                $get_data = $database->prepare("SELECT * FROM $table_name WHERE id = :id");
                $get_data->bindparam("id", $id);
                $get_data->execute();

                if ($get_data->rowCount() > 0 ) {
                    $data_place      = $get_data->fetchObject();
                    $folder_place    = $data_place->ser_id;
                } else {
                    $folder_place = 'UNKNOWN';
                }
                
                $folder_name    = $folder_place;
                $img_new_name   = bin2hex(random_bytes(10)) . '.' . $formul; //To Input A Random Name For The Image 

                if (isset($_SESSION['clinic'])) {

                    $link  = 'IMG/place_Img/Clinic/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                                $check_clinic = $database->prepare("SELECT * FROM activation_place,clinic  WHERE  activation_place.clinic_id = clinic.id  AND clinic.id = :id ");
                                $check_clinic->bindparam("id", $id);
                                $check_clinic->execute();

                                if ($check_clinic->rowCount() > 0) {

                                    //UpDate activation_place Table

                                    $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.clinic_id = :id ");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("id", $id);

                                    if ($uploadImg->execute()) {

                                        $Message = "تم التقديم للمراجعة";
                                        print_r(json_encode(Message(null,$Message,201)));
                                        header("refresh:2;");

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                } else {

                                    //Add To activation_place Table

                                    $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,clinic_id,isactive) VALUES(:license_img,:clinic_id,0)");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("clinic_id", $id);

                                    if ($uploadImg->execute()) {

                                        $Message = "تم التقديم للمراجعة";
                                        print_r(json_encode(Message(null,$Message,201)));

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                        $check_clinic = $database->prepare("SELECT * FROM activation_place,clinic  WHERE  activation_place.clinic_id = clinic.id  AND clinic.id = :id ");
                        $check_clinic->bindparam("id", $id);
                        $check_clinic->execute();

                        if ($check_clinic->rowCount() > 0) {

                            //UpDate activation_place Table

                            $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.clinic_id = :id ");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("id", $id);

                            if ($uploadImg->execute()) {

                                $Message = "تم التقديم للمراجعة";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {

                            //Add To activation_place Table

                            $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,clinic_id,isactive) VALUES(:license_img,:clinic_id,0)");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("clinic_id", $id);

                            if ($uploadImg->execute()) {

                                $Message = "تم التقديم للمراجعة";
                                print_r(json_encode(Message(null,$Message,201)));

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        }
                    }

                } elseif (isset($_SESSION['pharmacy'])) {

                    $link = 'IMG/place_Img/Pharmacy/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                                $check_clinic = $database->prepare("SELECT * FROM activation_place,pharmacy  WHERE  activation_place.pharmacy_id = pharmacy.id  AND pharmacy.id = :id ");
                                $check_clinic->bindparam("id", $id);
                                $check_clinic->execute();

                                if ($check_clinic->rowCount() > 0) {

                                    //UpDate activation_place Table

                                    $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.pharmacy_id = :id ");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("id", $id);

                                    if ($uploadImg->execute()) {

                                        $Message = "تم التقديم للمراجعة";
                                        print_r(json_encode(Message(null,$Message,201)));
                                        header("refresh:2;");

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                } else {

                                    //Add To activation_place Table

                                    $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,pharmacy_id,isactive) VALUES(:license_img,:pharmacy_id,0)");

                                    $uploadImg->bindparam("license_img", $license_img);
                                    $uploadImg->bindparam("pharmacy_id", $id);

                                    if ($uploadImg->execute()) {

                                        $Message = "تم التقديم للمراجعة";
                                        print_r(json_encode(Message(null,$Message,201)));

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $license_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $link . $img_new_name; //The Path WithIn The DataBase

                        $check_clinic = $database->prepare("SELECT * FROM activation_place,pharmacy  WHERE  activation_place.pharmacy_id = pharmacy.id  AND pharmacy.id = :id ");
                        $check_clinic->bindparam("id", $id);
                        $check_clinic->execute();

                        if ($check_clinic->rowCount() > 0) {

                            //UpDate activation_place Table

                            $uploadImg = $database->prepare("UPDATE activation_place SET license_img = :license_img , isactive = 0 WHERE activation_place.pharmacy_id = :id ");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("id", $id);

                            if ($uploadImg->execute()) {

                                $Message = "تم التقديم للمراجعة";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {

                            //Add To activation_place Table

                            $uploadImg = $database->prepare("INSERT INTO activation_place(license_img,pharmacy_id,isactive) VALUES(:license_img,:pharmacy_id,0)");

                            $uploadImg->bindparam("license_img", $license_img);
                            $uploadImg->bindparam("pharmacy_id", $id);

                            if ($uploadImg->execute()) {

                                $Message = "تم التقديم للمراجعة";
                                print_r(json_encode(Message(null,$Message,201)));

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        }
                    }

                } else {
                    $Message = "فشل العثور على مستخدم";
                    print_r(json_encode(Message(null,$Message,401)));
                }
            }
        } else {
            $Message = "صيغة الملف غير مدعومة";
            print_r(json_encode(Message(null,$Message,415)));
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
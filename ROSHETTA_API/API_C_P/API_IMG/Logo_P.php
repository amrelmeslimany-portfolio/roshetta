<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin


    if (isset($_SESSION['pharmacy'])) { //If Find Pharmacy Session

        //I Expect To Receive This Data

        $img_name   = $_FILES["logo"]["name"];
        $img_size   = $_FILES["logo"]["size"];
        $img_tmp    = $_FILES["logo"]["tmp_name"];

        $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

        //To Get The Image Formul

        $check_formul   = explode(".", $img_name);
        $formul         = end($check_formul);

        if (in_array($formul, $allowed_formulas)) {

            if ($img_size > 1000000) { //To Specify The Image Size < 1M

                $Message = "(1M)يجب أن يكون حجم الصورة أقل من";
                print_r(json_encode(Message(null,$Message,400)));

            } else {

                $folder_name    = $_SESSION['pharmacy']->ser_id;
                $img_new_name   = bin2hex(random_bytes(10)) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                $link           = 'Logo_Img/Pharmacy/' . $folder_name . '/' . ''; //File Link

                if (is_dir($link)) { //If The File Exists

                    $scandir = scandir($link); //To Displays File Data In Array
                    foreach ($scandir as $folder_content) {

                        if (is_file($link . $folder_content)) {

                            unlink($link . $folder_content); //To Delete File Data
                            move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                            $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                            $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                            $logo_img       = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_C_P/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                            $id = $_SESSION['pharmacy']->id;

                            //UpDate pharmacy Table

                            $uploadImg = $database->prepare("UPDATE pharmacy SET logo = :logo WHERE id = :id ");

                            $uploadImg->bindparam("logo", $logo_img);
                            $uploadImg->bindparam("id", $id);
                            $uploadImg->execute();

                            if ($uploadImg->rowCount() > 0 ) {

                                //Get Logo From pharmacy

                                $getImg = $database->prepare("SELECT * FROM pharmacy WHERE id = :id ");

                                $getImg->bindparam("id", $id);
                                $getImg->execute();

                                if ($getImg->rowCount() > 0 ) {

                                    $getImg = $getImg->fetchObject();
                                    $_SESSION['pharmacy'] = $getImg;

                                    $data = [
                                        "URL"  => $getImg->logo
                                    ];

                                    $Message = "تم تعديل الشعار بنجاح";
                                    print_r(json_encode(Message($data,$Message,201)));

                                } else {
                                    $Message = "فشل جلب الشعار";
                                    print_r(json_encode(Message(null,$Message,422)));
                                }
                            } else {
                                $Message = "فشل تعديل الشعار";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        }
                    }
                } else { //If The File Does Not Exists

                    mkdir($link); //To Create A New File

                    move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                    $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                    $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                    $logo_img       = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_C_P/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                    $id = $_SESSION['pharmacy']->id;

                    //UpDate pharmacy Table

                    $uploadImg = $database->prepare("UPDATE pharmacy SET logo = :logo WHERE id = :id ");

                    $uploadImg->bindparam("logo", $logo_img);
                    $uploadImg->bindparam("id", $id);
                    $uploadImg->execute();

                    if ($uploadImg->rowCount() > 0 ) {

                        //Get Logo From pharmacy

                        $getImg = $database->prepare("SELECT * FROM pharmacy WHERE id = :id ");

                        $getImg->bindparam("id", $id);
                        $getImg->execute();

                        if ($getImg->rowCount() > 0 ) {

                            $getImg = $getImg->fetchObject();
                            $_SESSION['pharmacy'] = $getImg;

                            $data = [
                                "URL"  => $getImg->logo
                            ];

                            $Message = "تم تعديل الشعار بنجاح";
                            print_r(json_encode(Message($data,$Message,201)));

                        } else {
                            $Message = "فشل جلب الشعار";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    } else {
                        $Message = "فشل تعديل الشعار";
                        print_r(json_encode(Message(null,$Message,422)));
                    }
                }
            }
        } else {
            $Message = "صيغة الملف غير مدعومة";
            print_r(json_encode(Message(null,$Message,415)));
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
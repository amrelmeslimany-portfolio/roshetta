<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin


    if (isset($_SESSION['clinic'])) { //If Find clinic Session

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

                print_r(json_encode(["Error" => "الحجم كبير"]));

            } else {

                $folder_name    = $_SESSION['clinic']->ser_id;
                $img_new_name   = bin2hex(random_bytes(10)) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                $link           = 'Logo_Img/Clinic/' . $folder_name . '/' . ''; //File Link

                if (is_dir($link)) { //If The File Exists

                    $scandir = scandir($link); //To Displays File Data In Array
                    foreach ($scandir as $folder_content) {

                        if (is_file($link . $folder_content)) {

                            unlink($link . $folder_content); //To Delete File Data
                            move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                            $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                            $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                            $logo_img       = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_C_P/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                            require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                            $id = $_SESSION['clinic']->id;

                            //UpDate Clinic Table

                            $uploadImg = $database->prepare("UPDATE clinic SET logo = :logo WHERE id = :id ");

                            $uploadImg->bindparam("logo", $logo_img);
                            $uploadImg->bindparam("id", $id);
                            $uploadImg->execute();

                            if ($uploadImg->rowCount() > 0 ) {

                                //Get Logo From Clinic

                                $getImg = $database->prepare("SELECT * FROM clinic WHERE id = :id ");

                                $getImg->bindparam("id", $id);
                                $getImg->execute();

                                if ($getImg->rowCount() > 0 ) {

                                    $getImg = $getImg->fetchObject();
                                    $_SESSION['clinic'] = $getImg;
                                    $data_message = array(

                                        "Message" => "تم تعديل الشعار بنجاح",
                                        "URL"     => $getImg->logo
                                    );
                                    
                                    print_r(json_encode($data_message));

                                } else {
                                    print_r(json_encode("فشل جلب الملف"));
                                }
                            } else {
                                print_r(json_encode("فشل رفع الملف"));
                            }
                        }
                    }
                } else { //If The File Does Not Exists

                    mkdir($link); //To Create A New File

                    move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                    $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                    $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                    $logo_img       = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_C_P/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                    require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                    $id = $_SESSION['clinic']->id;

                    //UpDate Clinic Table

                    $uploadImg = $database->prepare("UPDATE clinic SET logo = :logo WHERE id = :id ");

                    $uploadImg->bindparam("logo", $logo_img);
                    $uploadImg->bindparam("id", $id);
                    $uploadImg->execute();

                    if ($uploadImg->rowCount() > 0 ) {

                        //Get Logo From Clinic

                        $getImg = $database->prepare("SELECT * FROM clinic WHERE id = :id ");

                        $getImg->bindparam("id", $id);
                        $getImg->execute();

                        if ($getImg->rowCount() > 0 ) {

                            $getImg = $getImg->fetchObject();
                            $_SESSION['clinic'] = $getImg;
                            $data_message = array(

                                "Message" => "تم تعديل الشعار بنجاح",
                                "URL"     => $getImg->logo
                            );

                            print_r(json_encode($data_message));

                        } else {
                            print_r(json_encode(["Error" => "فشل جلب الملف"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "فشل رفع الملف"]));
                    }
                }
            }
        } else {
            print_r(json_encode(["Error" => "صيغة الملف غير مدعومة"]));
        }
    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
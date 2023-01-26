<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) { //If Find Pharmacist Session

        //I Expect To Receive This Data

        $img_name = $_FILES["profile_img"]["name"];
        $img_size = $_FILES["profile_img"]["size"];
        $img_tmp  = $_FILES["profile_img"]["tmp_name"];

        $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

        //To Get The Image Formul

        $check_formul = explode(".", $img_name);
        $formul       = end($check_formul);

        if (in_array($formul, $allowed_formulas)) {

            if ($img_size > 1000000) { //To Specify The Image Size  > 1M

                print_r(json_encode(["Error" => "الحجم كبير"]));

            } else {

                if (isset($_SESSION['patient'])) {

                    $folder_name   = $_SESSION['patient']->ssd;
                    $img_new_name  = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link          = 'Profile_Img/Profile_patient_img/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['patient']->id;

                                //UpDate Patient Table

                                $uploadImg = $database->prepare("UPDATE patient SET profile_img = :profile_img WHERE id = :id ");

                                $uploadImg->bindparam("profile_img", $profile_img);
                                $uploadImg->bindparam("id", $id);
                                $uploadImg->execute();

                                if ($uploadImg->rowCount() > 0 ) {

                                    //Get Pro_Img From Patient

                                    $getImg = $database->prepare("SELECT * FROM patient WHERE id = :id ");

                                    $getImg->bindparam("id", $id);
                                    $getImg->execute();

                                    if ($getImg->rowCount() > 0 ) {

                                        $new_session = $getImg->fetchObject();
                                        $_SESSION['patient'] = $new_session;
                                        $data_img = array(

                                            "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                            "URL"     => $new_session->profile_img
                                        );

                                        print_r(json_encode($data_img));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل جلب الملف"]));
                                    }
                                } else {
                                    print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['patient']->id;

                        //UpDate Patient Table

                        $uploadImg = $database->prepare("UPDATE patient SET profile_img = :profile_img WHERE id = :id ");

                        $uploadImg->bindparam("profile_img", $profile_img);
                        $uploadImg->bindparam("id", $id);
                        $uploadImg->execute();

                        if ($uploadImg->rowCount() > 0 ) {

                            //Get Pro_Img From Patient

                            $getImg = $database->prepare("SELECT * FROM patient WHERE id = :id ");

                            $getImg->bindparam("id", $id);
                            $getImg->execute();

                            if ($getImg->rowCount() > 0 ) {

                                $new_session = $getImg->fetchObject();
                                $_SESSION['patient'] = $new_session;
                                $data_img = array(

                                    "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                    "URL"     => $new_session->profile_img
                                );

                                print_r(json_encode($data_img));

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب الملف"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل رفع الملف"]));
                        }
                    }
                } elseif (isset($_SESSION['doctor'])) {

                    $folder_name  = $_SESSION['doctor']->ssd;
                    $img_new_name = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link         = 'Profile_Img/Profile_doctor_img/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['doctor']->id;

                                //UpDate Doctor Table

                                $uploadImg = $database->prepare("UPDATE doctor SET profile_img = :profile_img WHERE id = :id ");

                                $uploadImg->bindparam("profile_img", $profile_img);
                                $uploadImg->bindparam("id", $id);
                                $uploadImg->execute();

                                if ($uploadImg->rowCount() > 0 ) {

                                    //Get Pro_Img From Doctor

                                    $getImg = $database->prepare("SELECT * FROM doctor WHERE id = :id ");

                                    $getImg->bindparam("id", $id);
                                    $getImg->execute();

                                    if ($getImg->rowCount() > 0 ) {

                                        $new_session = $getImg->fetchObject();
                                        $_SESSION['doctor'] = $new_session;
                                        $data_img = array(

                                            "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                            "URL"     => $new_session->profile_img
                                        );

                                        print_r(json_encode($data_img));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل جلب الملف"]));
                                    }
                                } else {
                                    print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['doctor']->id;

                        //UpDate Doctor Table

                        $uploadImg = $database->prepare("UPDATE doctor SET profile_img = :profile_img WHERE id = :id ");

                        $uploadImg->bindparam("profile_img", $profile_img);
                        $uploadImg->bindparam("id", $id);
                        $uploadImg->execute();

                        if ($uploadImg->rowCount() > 0 ) {

                            //Get Pro_Img From Doctor

                            $getImg = $database->prepare("SELECT * FROM doctor WHERE id = :id ");

                            $getImg->bindparam("id", $id);
                            $getImg->execute();

                            if ($getImg->rowCount() > 0 ) {

                                $new_session = $getImg->fetchObject();
                                $_SESSION['doctor'] = $new_session;
                                $data_img = array(

                                    "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                    "URL"     => $new_session->profile_img
                                );

                                print_r(json_encode($data_img));

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب الملف"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل رفع الملف"]));
                        }
                    }
                } elseif (isset($_SESSION['pharmacist'])) {

                    $folder_name  = $_SESSION['pharmacist']->ssd;
                    $img_new_name = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link         = 'Profile_Img/Profile_pharmacist_img/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['pharmacist']->id;

                                //UpDate Pharmacist Table

                                $uploadImg = $database->prepare("UPDATE pharmacist SET profile_img = :profile_img WHERE id = :id ");

                                $uploadImg->bindparam("profile_img", $profile_img);
                                $uploadImg->bindparam("id", $id);
                                $uploadImg->execute();

                                if ($uploadImg->rowCount() > 0) {

                                    //Get Pro_Img From Pharmacist

                                    $getImg = $database->prepare("SELECT * FROM pharmacist WHERE id = :id ");

                                    $getImg->bindparam("id", $id);
                                    $getImg->execute();

                                    if ($getImg->rowCount() > 0 ) {

                                        $new_session = $getImg->fetchObject();
                                        $_SESSION['pharmacist'] = $new_session;
                                        $data_img = array(

                                            "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                            "URL"     => $new_session->profile_img
                                        );

                                        print_r(json_encode($data_img));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل جلب الملف"]));
                                    }
                                } else {
                                    print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['pharmacist']->id;

                        //UpDate Pharmacist Table

                        $uploadImg = $database->prepare("UPDATE pharmacist SET profile_img = :profile_img WHERE id = :id ");

                        $uploadImg->bindparam("profile_img", $profile_img);
                        $uploadImg->bindparam("id", $id);
                        $uploadImg->execute();

                        if ($uploadImg->rowCount() > 0 ) {

                            //Get Pro_Img From Pharmacist

                            $getImg = $database->prepare("SELECT * FROM pharmacist WHERE id = :id ");

                            $getImg->bindparam("id", $id);
                            $getImg->execute();

                            if ($getImg->rowCount() > 0 ) {

                                $new_session = $getImg->fetchObject();
                                $_SESSION['pharmacist'] = $new_session;
                                $data_img = array(

                                    "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                    "URL"     => $new_session->profile_img
                                );

                                print_r(json_encode($data_img));

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب الملف"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل رفع الملف"]));
                        }
                    }
                } elseif (isset($_SESSION['assistant'])) {

                    $folder_name  = $_SESSION['assistant']->ssd;
                    $img_new_name = rand(0, 1000000) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                    $link         = 'Profile_Img/Profile_assistant_img/' . $folder_name . '/' . ''; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                                require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                                $id = $_SESSION['assistant']->id;

                                //UpDate Assistant Table

                                $uploadImg = $database->prepare("UPDATE assistant SET profile_img = :profile_img WHERE id = :id ");

                                $uploadImg->bindparam("profile_img", $profile_img);
                                $uploadImg->bindparam("id", $id);
                                $uploadImg->execute();

                                if ($uploadImg->rowCount() > 0 ) {

                                    //Get Pro_Img From Assistant

                                    $getImg = $database->prepare("SELECT * FROM assistant WHERE id = :id ");

                                    $getImg->bindparam("id", $id);
                                    $getImg->execute();

                                    if ($getImg->rowCount() > 0 ) {

                                        $new_session = $getImg->fetchObject();
                                        $_SESSION['assistant'] = $new_session;
                                        $data_img = array(

                                            "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                            "URL"     => $new_session->profile_img
                                        );

                                        print_r(json_encode($data_img));

                                    } else {
                                        print_r(json_encode(["Error" => "فشل جلب الملف"]));
                                    }
                                } else {
                                    print_r(json_encode(["Error" => "فشل رفع الملف"]));
                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                        require_once("../../API_C_A/Connection.php"); //Connect To DataBase

                        $id = $_SESSION['assistant']->id;

                        //UpDate Assistant Table

                        $uploadImg = $database->prepare("UPDATE assistant SET profile_img = :profile_img WHERE id = :id ");

                        $uploadImg->bindparam("profile_img", $profile_img);
                        $uploadImg->bindparam("id", $id);
                        $uploadImg->execute();

                        if ($uploadImg->rowCount() > 0 ) {

                            //Get Pro_Img From Assistant

                            $getImg = $database->prepare("SELECT * FROM assistant WHERE id = :id ");

                            $getImg->bindparam("id", $id);
                            $getImg->execute();

                            if ($getImg->rowCount() > 0 ) {

                                $new_session = $getImg->fetchObject();
                                $_SESSION['assistant'] = $new_session;
                                $data_img = array(

                                    "Message" => "تم تعديل صورة الملف الشخصى بنجاح",
                                    "URL"     => $new_session->profile_img
                                );

                                print_r(json_encode($data_img));

                            } else {
                                print_r(json_encode(["Error" => "فشل جلب الملف"]));
                            }
                        } else {
                            print_r(json_encode(["Error" => "فشل رفع الملف"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
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
<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) { //If Find Session

        //Get User Data

        if (isset($_SESSION['patient'])) {
            $type           = 'Profile_patient_img/';
            $table_name     = 'patient';
            $id             = $_SESSION['patient'];
        } elseif (isset($_SESSION['doctor'])) {
            $type           = 'Profile_doctor_img/';
            $table_name     = 'doctor';
            $id             = $_SESSION['doctor'];
        } elseif (isset($_SESSION['pharmacist'])) {
            $type           = 'Profile_pharmacist_img/';
            $table_name     = 'pharmacist';
            $id             = $_SESSION['pharmacist'];
        } elseif (isset($_SESSION['assistant'])) {
            $type           = 'Profile_assistant_img/';
            $table_name     = 'assistant';
            $id             = $_SESSION['assistant'];
        } else {
            $type = '';
            $table_name = '';
            $id = '';
        }

        $get_data = $database->prepare("SELECT * FROM $table_name WHERE id = :id");
        $get_data->bindparam("id", $id);
        $get_data->execute();

        if ($get_data->rowCount() > 0 ) {
            $data_user      = $get_data->fetchObject();
            $folder_user    = $data_user->ssd;
        } else {
            $folder_user = 'UNKNOWN';
        }

        $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]

        //I Expect To Receive This Data

        $img_name   = $_FILES["profile_img"]["name"];
        $img_size   = $_FILES["profile_img"]["size"];
        $img_tmp    = $_FILES["profile_img"]["tmp_name"];

        $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

        //To Get The Image Formul

        $check_formul   = explode(".", $img_name);
        $formul         = end($check_formul);

        if (in_array($formul, $allowed_formulas)) { 

            if ($img_size > 1000000) { //To Specify The Image Size  < 1M
                $Message = "(1M)يجب أن يكون حجم الصورة أقل من";
                print_r(json_encode(Message(null,$Message,400)));
            } else {

                $folder_name    = $folder_user;
                $img_new_name   = bin2hex(random_bytes(10)) . $folder_name . '.' . $formul; //To Input A Random Name For The Image 
                $link           = 'Profile_Img/' . $type . $folder_name . '/' . ''; //File Link

                if (is_dir($link)) { //If The File Exists

                    $scandir = scandir($link); //To Displays File Data In Array
                    foreach ($scandir as $folder_content) {

                        if (is_file($link . $folder_content)) {

                            unlink($link . $folder_content); //To Delete File Data
                            move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File

                            $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                            //UpDate Table

                            $uploadImg = $database->prepare("UPDATE $table_name SET profile_img = :profile_img WHERE id = :id ");
                            $uploadImg->bindparam("profile_img", $profile_img);
                            $uploadImg->bindparam("id", $id);
                            $uploadImg->execute();

                            if ($uploadImg->rowCount() > 0) {

                                $Message = "تم تعديل صورة الملف الشخصى بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل الصورة";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        }
                    }
                } else { //If The File Does Not Exists

                    mkdir($link); //To Create A New File

                    move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File
                    
                    $profile_img    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_User/API_IMG/" . $link . $img_new_name; //The Path WithIn The DataBase

                    //UpDate Table

                    $uploadImg = $database->prepare("UPDATE $table_name SET profile_img = :profile_img WHERE id = :id ");
                    $uploadImg->bindparam("profile_img", $profile_img);
                    $uploadImg->bindparam("id", $id);
                    $uploadImg->execute();

                    if ($uploadImg->rowCount() > 0) {

                        $Message = "تم تعديل صورة الملف الشخصى بنجاح";
                        print_r(json_encode(Message(null,$Message,201)));
                        header("refresh:2;");
                        
                    } else {
                        $Message = "فشل تعديل الصورة";
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
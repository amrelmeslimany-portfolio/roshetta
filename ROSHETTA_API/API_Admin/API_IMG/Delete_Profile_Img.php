<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) { //If Find Admin Session

        $id          = $_SESSION['admin']->id;
        $folder_name = $_SESSION['admin']->ssd;

        // Delete From Video Folder

        $link = 'Profile_Img_Admin/' . $folder_name . '/'; //File Link

        if (is_dir($link)) { //If The File Exists
            $scandir = scandir($link); //To Displays File Data In Array
            foreach ($scandir as $folder_content) {
                if (is_file($link . $folder_content)) {
                    unlink($link . $folder_content); //To Delete File Data
                    rmdir($link);
                }
            }
        }

        $delete_img = $database->prepare("UPDATE admin SET profile_img = NULL WHERE id = :id");
        $delete_img->bindparam("id" , $id);
        $delete_img->execute();

        if ($delete_img->rowCount() > 0 ){
            print_r(json_encode(["Message" => "تم الحذف بنجاح"]));
        }else{
            print_r(json_encode(["Error" => "فشل الحذف"]));
        }

    } else {
        print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
    }
} else { //If The Entry Method Is Not 'GET'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
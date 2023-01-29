<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_POST['type']) && !empty($_POST['type'])) {

            $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);

            // Delete From Video Folder

            $link = 'Video/' . $type . '/'; //File Link

            if (is_dir($link)) { //If The File Exists
                $scandir = scandir($link); //To Displays File Data In Array
                foreach ($scandir as $folder_content) {
                    if (is_file($link . $folder_content)) {
                        unlink($link . $folder_content); //To Delete File Data
                        rmdir($link);
                    }
                }
            }
            // Delete From Video Table

            $delete_Video = $database->prepare("DELETE FROM video WHERE type = :type");
            $delete_Video->bindparam("type", $type);
            $delete_Video->execute();

            if ($delete_Video->rowCount() > 0) {

                print_r(json_encode(["Message" => "تم الحذف بنجاح"]));

            } else {
                print_r(json_encode(["Error" => "فشل الحذف"]));
            }

        } else {
            print_r(json_encode(["Error" => "لم يتم تحديد نوع الحساب"]));
        }

    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
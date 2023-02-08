<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

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

                $Message = "تم الحذف بنجاح";
                print_r(json_encode(Message(null,$Message,200)));

            } else {
                $Message = "فشل الحذف";
                print_r(json_encode(Message(null,$Message,422)));
            }
        } else {
            $Message = "لم يتم تحديد نوع الحساب";
            print_r(json_encode(Message(null,$Message,401)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) { //If Find Session

        if (isset($_SESSION['patient'])) {
            $type           = 'Profile_patient_img/';
            $folder_user    = $_SESSION['patient']->ssd;
            $table_name     = 'patient';
            $id             = $_SESSION['patient']->id;
        } elseif (isset($_SESSION['doctor'])) {
            $type           = 'Profile_doctor_img/';
            $folder_user    = $_SESSION['doctor']->ssd;
            $table_name     = 'doctor';
            $id             = $_SESSION['doctor']->id;
        } elseif (isset($_SESSION['pharmacist'])) {
            $type           = 'Profile_pharmacist_img/';
            $folder_user    = $_SESSION['pharmacist']->ssd;
            $table_name     = 'pharmacist';
            $id             = $_SESSION['pharmacist']->id;
        } elseif (isset($_SESSION['assistant'])) {
            $type           = 'Profile_assistant_img/';
            $folder_user    = $_SESSION['assistant']->ssd;
            $table_name     = 'assistant';
            $id             = $_SESSION['assistant']->id;
        } else {
            $type = '';
            $folder_user = '';
            $table_name = '';
            $id = '';
        }

        // Delete Folder

        $link = 'Profile_Img/' . $type . $folder_user . '/' . ''; //File Link

        if (is_dir($link)) { //If The File Exists
            $scandir = scandir($link); //To Displays File Data In Array
            foreach ($scandir as $folder_content) {
                if (is_file($link . $folder_content)) {
                    unlink($link . $folder_content); //To Delete File Data
                    rmdir($link);
                }
            }
        }

        $delete_img = $database->prepare("UPDATE $table_name SET profile_img = NULL WHERE id = :id");
        $delete_img->bindparam("id", $id);
        $delete_img->execute();

        if ($delete_img->rowCount() > 0) {
            $Message = "تم الحذف بنجاح";
            print_r(json_encode(Message(null,$Message,200)));
        } else {
            $Message = "فشل الحذف";
            print_r(json_encode(Message(null,$Message,422)));
        }
    } else {
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}    
?>
<?php
require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) { //If Find Admin Session

        if (isset($_POST['type']) && !empty($_POST['type'])) {

            $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);

            //I Expect To Receive This Data

            $video_name = $_FILES["video"]["name"];
            $video_size = $_FILES["video"]["size"];
            $video_tmp  = $_FILES["video"]["tmp_name"];

            $allowed_formulas = array("mp4"); //Allowed Formulas For The Video

            //To Get The Video Formul

            $check_formul = explode(".", $video_name);
            $formul       = end($check_formul);

            if (in_array($formul, $allowed_formulas)) {

                if ($video_size > 2000000) { //To Specify The Video Size  > 2M

                    print_r(json_encode(["Error" => "الحجم كبير"]));

                } else {

                    $video_new_name = rand(10000000, 99999999) . '.' . $formul; //To Input A Random Name For The Video 

                    $link = 'Video/' . $type . '/'; //File Link

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($video_tmp, $link . $video_new_name); //To Transfer The New Video To The File

                                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                                $video          = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Admin/" . $link . $video_new_name; //The Path WithIn The DataBase

                                $check_video = $database->prepare("SELECT * FROM video WHERE type = :type");
                                $check_video->bindparam("type", $type);
                                $check_video->execute();

                                if ($check_video->rowCount() > 0) {

                                    //UpDate Video Table

                                    $upload_video = $database->prepare("UPDATE video SET video = :video WHERE type = :type ");
                                    $upload_video->bindparam("video", $video);
                                    $upload_video->bindparam("type", $type);
                                    $upload_video->execute();

                                    if ($upload_video->rowCount() > 0) {
                                        print_r(json_encode(["Message" => "تم التعديل بنجاح"]));
                                        header("refresh:2;");
                                    } else {
                                        print_r(json_encode(["Error" => "فشل التعديل"]));
                                    }

                                } else {

                                    // Add Into Video Table

                                    $upload_video = $database->prepare("INSERT INTO video(video,type) VALUES(:video,:type)");
                                    $upload_video->bindparam("video", $video);
                                    $upload_video->bindparam("type", $type);
                                    $upload_video->execute();

                                    if ($upload_video->rowCount() > 0) {
                                        print_r(json_encode(["Message" => "تم الإضافة بنجاح"]));
                                    } else {
                                        print_r(json_encode(["Error" => "فشل الإضافة"]));
                                    }
                                }
                            }
                        } // foreach

                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($video_tmp, $link . $video_new_name); //To Transfer The New Video To The File

                        $HTTP_HOST = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                        $video = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Admin/" . $link . $video_new_name; //The Path WithIn The DataBase

                        $check_video = $database->prepare("SELECT * FROM video WHERE type = :type");
                        $check_video->bindparam("type", $type);
                        $check_video->execute();

                        if ($check_video->rowCount() > 0) {

                            //UpDate Video Table

                            $upload_video = $database->prepare("UPDATE video SET video = :video WHERE type = :type ");
                            $upload_video->bindparam("video", $video);
                            $upload_video->bindparam("type", $type);
                            $upload_video->execute();

                            if ($upload_video->rowCount() > 0) {
                                print_r(json_encode(["Message" => "تم التعديل بنجاح"]));
                                header("refresh:2;");
                            } else {
                                print_r(json_encode(["Error" => "فشل التعديل"]));
                            }

                        } else {

                            // Add Into Video Table

                            $upload_video = $database->prepare("INSERT INTO video(video,type) VALUES(:video,:type)");
                            $upload_video->bindparam("video", $video);
                            $upload_video->bindparam("type", $type);
                            $upload_video->execute();

                            if ($upload_video->rowCount() > 0) {
                                print_r(json_encode(["Message" => "تم الإضافة بنجاح"]));
                            } else {
                                print_r(json_encode(["Error" => "فشل الإضافة"]));
                            }
                        }
                    }
                }
            } else {
                print_r(json_encode(["Error" => "صيغة الملف غير مدعومة"]));
            }
        } else {
            print_r(json_encode(["Error" => "فشل العثور على نوع الصفحة"]));
        }

    } else {
        print_r(json_encode([ "Error" => "ليس لديك الصلاحية"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
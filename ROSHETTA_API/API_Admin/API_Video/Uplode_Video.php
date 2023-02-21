<?php
require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBase
require_once("../../API_Function/All_Function.php"); //All Function

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

                    $Message = "(2M)يجب أن يكون حجم الفيديو أقل من";
                    print_r(json_encode(Message(null,$Message,400)));

                } else {

                    $video_new_name = bin2hex(random_bytes(10)) . '.' . $formul; //To Input A Random Name For The Video 
                    $link = 'Video/' . $type . '/'; //File Link

                    $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                    $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]
                    $video          = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Admin/" . $link . $video_new_name; //The Path WithIn The DataBase

                    if (is_dir($link)) { //If The File Exists

                        $scandir = scandir($link); //To Displays File Data In Array
                        foreach ($scandir as $folder_content) {

                            if (is_file($link . $folder_content)) {

                                unlink($link . $folder_content); //To Delete File Data
                                move_uploaded_file($video_tmp, $link . $video_new_name); //To Transfer The New Video To The File

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
                                        $Message = "تم التعديل بنجاح";
                                        print_r(json_encode(Message(null,$Message,201)));
                                        header("refresh:2;");
                                    } else {
                                        $Message = "فشل التعديل";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }

                                } else {

                                    // Add Into Video Table

                                    $upload_video = $database->prepare("INSERT INTO video(video,type) VALUES(:video,:type)");
                                    $upload_video->bindparam("video", $video);
                                    $upload_video->bindparam("type", $type);
                                    $upload_video->execute();

                                    if ($upload_video->rowCount() > 0) {
                                        $Message = "تم الإضافة بنجاح";
                                        print_r(json_encode(Message(null,$Message,201)));
                                    } else {
                                        $Message = "فشل الإضافة";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                }
                            }
                        } // foreach

                    } else { //If The File Does Not Exists

                        mkdir($link); //To Create A New File

                        move_uploaded_file($video_tmp, $link . $video_new_name); //To Transfer The New Video To The File

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
                                $Message = "تم التعديل بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");
                            } else {
                                $Message = "فشل التعديل";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        } else {

                            // Add Into Video Table

                            $upload_video = $database->prepare("INSERT INTO video(video,type) VALUES(:video,:type)");
                            $upload_video->bindparam("video", $video);
                            $upload_video->bindparam("type", $type);
                            $upload_video->execute();

                            if ($upload_video->rowCount() > 0) {
                                $Message = "تم الإضافة بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                            } else {
                                $Message = "فشل الإضافة";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        }
                    }
                }
            } else {
                $Message = "صيغة الملف غير مدعومة";
                print_r(json_encode(Message(null,$Message,415)));
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
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
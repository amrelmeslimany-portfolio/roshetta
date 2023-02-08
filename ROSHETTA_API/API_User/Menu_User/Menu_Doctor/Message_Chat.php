<?php

require_once("../../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../../API_Function/All_Function.php"); //All Function
date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['doctor'])) {  //If Doctor

        $name           = $_SESSION['doctor']->doctor_name;     // Name Doctor
        $profile_img    = $_SESSION['doctor']->profile_img;     // Profile Image
        $time           = date("h:i");                          //Time Chat
        $time_delete    = date("h:i", (time() - (1 * 5 * 60 * 60 )));   //Time Delete Message  (5) Hours

        if (isset($_POST['message']) && !empty($_POST['message'])) {

            $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);   //Filter Message String

            // Add To Chat Table

            $add_message = $database->prepare("INSERT INTO chat(name,time,profile_img,message) VALUES(:name,:time,:profile_img,:message)");
            $add_message->bindparam("name", $name);
            $add_message->bindparam("time", $time);
            $add_message->bindparam("profile_img", $profile_img);
            $add_message->bindparam("message", $message);
            $add_message->execute();

            if ($add_message->rowCount() > 0) {

                //Get From Chat Table

                $get_message = $database->prepare("SELECT name,time,profile_img,message FROM chat");
                $get_message->execute();

                if ($get_message->rowCount() > 0) {

                    $data_message = $get_message->fetchAll(PDO::FETCH_ASSOC);

                    $Message = "تم جلب البيانات ";
                    print_r(json_encode(Message($data_message, $Message, 200)));

                } else {
                    $Message = "فشل جلب البيانات";
                    print_r(json_encode(Message(null, $Message, 422)));
                }
            } else {
                $Message = "فشل إرسال الرسالة";
                print_r(json_encode(Message(null, $Message, 422)));
            }

        } else {
            //******* */
        }
    } else {
        $Message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null, $Message, 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
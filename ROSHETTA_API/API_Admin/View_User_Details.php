<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (
            isset($_POST['type']) && !empty($_POST['type'])
            && isset($_POST['id']) && !empty($_POST['id'])
        ) {

            $type = $_POST['type'];
            $id   = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

            if ($type == 'PATIENT') {
                $table_name = 'patient';
            } elseif ($type == 'DOCTOR') {
                $table_name = 'doctor';
            } elseif ($type == 'PHARMACIST') {
                $table_name = 'pharmacist';
            } elseif ($type == 'ASSISTANT') {
                $table_name = 'assistant';
            } else {
                $table_name = '';
            }

            $get_user = $database->prepare("SELECT * FROM $table_name WHERE id = :id");
            $get_user->bindParam("id", $id);
            $get_user->execute();

            if ($get_user->rowCount() > 0) {
                $data_user = $get_user->fetchObject();

                $role = $data_user->role;

                if ($role == 'PATIENT') {
                    $weight     = $data_user->weight;
                    $height     = $data_user->height;
                    $specialist = null;
                } elseif ($role == 'DOCTOR') {
                    $weight     = null;
                    $height     = null;
                    $specialist = $data_user->specialist;
                } else {
                    $weight     = null;
                    $height     = null;
                    $specialist = null;
                }

                $data_message = [

                    "id"            => $data_user->id,
                    "name"          => $data_user->name,
                    "ssd"           => $data_user->ssd,
                    "email"         => $data_user->email,
                    "phone_number"  => $data_user->phone_number,
                    "gender"        => $data_user->gender,
                    "birth_date"    => $data_user->birth_date,
                    "governorate"   => $data_user->governorate,
                    "weight"        => $weight,
                    "height"        => $height,
                    "specialist"    => $specialist,
                    "profile_img"   => $data_user->profile_img,
                    "role"          => $role
                ];

                $message = 'تم جلب البيانات بنجاح';
                print_r(json_encode(Message($data_message, $message, 200)));

            } else {
                $Message = "معرف المستخدم غير صحيح";
                print_r(json_encode(Message(null, $Message, 400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null, $Message, 400)));
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
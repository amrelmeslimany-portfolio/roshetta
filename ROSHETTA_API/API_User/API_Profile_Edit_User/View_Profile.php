<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (
        isset($_SESSION['patient'])
        || isset($_SESSION['doctor'])
        || isset($_SESSION['pharmacist'])
        || isset($_SESSION['assistant'])
    ) {

        if (isset($_SESSION['patient'])) {
            $table_name = 'patient';
            $id         = $_SESSION['patient'];
        } elseif (isset($_SESSION['doctor'])) {
            $table_name = 'doctor';
            $id         = $_SESSION['doctor'];
        } elseif (isset($_SESSION['pharmacist'])) {
            $table_name = 'pharmacist';
            $id         = $_SESSION['pharmacist'];
        } elseif (isset($_SESSION['assistant'])) {
            $table_name = 'assistant';
            $id         = $_SESSION['assistant'];
        } else {
            $table_name = '';
            $id = '';
        }

        $get_data = $database->prepare("SELECT * FROM $table_name WHERE id = :id");
        $get_data->bindparam("id", $id);
        $get_data->execute();

        if ($get_data->rowCount() > 0 ) {

            $data_user  = $get_data->fetchObject();
            $role       = $data_user->role;
            @$age       = date("Y-m-d") - $data_user->birth_date;

            if ($role === 'PATIENT') {
                $weight     = $data_user->weight;
                $height     = $data_user->height;
                $specialist = null;
            } elseif ($role === 'DOCTOR') {
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
                "age"           => $age,
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

            $Message = "تم جلب البيانات";
            print_r(json_encode(Message($data_message,$Message,200)));

        } else {
            $Message = "لم يتم العثور على بيانات";
            print_r(json_encode(Message(null,$Message,204)));
        }
    } else {
        $Message = "فشل العثور على مستخدم";
        print_r(json_encode(Message(null,$Message,401)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
} 
?>
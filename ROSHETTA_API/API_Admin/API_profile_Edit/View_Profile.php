<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases
require_once("../../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['admin'])) { 

        $id = $_SESSION['admin'];

        $get_data = $database->prepare("SELECT * FROM admin WHERE id = :id");
        $get_data->bindparam("id", $id);
        $get_data->execute();

        if ($get_data->rowCount() > 0) {

            $data_user = $get_data->fetchObject();
            @$age      = date("Y-m-d") - $data_user->birth_date;

            $admin_data = [
                
                "id"            => $data_user->id,
                "name"          => $data_user->name,
                "age"           => $age,
                "ssd"           => $data_user->ssd,
                "email"         => $data_user->email,
                "phone_number"  => $data_user->phone_number,
                "gender"        => $data_user->gender,
                "birth_date"    => $data_user->birth_date,
                "profile_img"   => $data_user->profile_img,
                "role"          => $data_user->role

            ];

            $message = 'تم جلب البيانات بنجاح';
            print_r(json_encode(Message($admin_data , $message , 200)));

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
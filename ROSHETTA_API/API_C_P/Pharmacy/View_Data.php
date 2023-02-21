<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['pharmacy']) || isset($_SESSION['pharmacist'])) {

        if (isset($_SESSION['pharmacy'])) {

            $id = $_SESSION['pharmacy'];

            $get_data = $database->prepare("SELECT * FROM pharmacy WHERE id = :id");
            $get_data->bindparam("id", $id);
            $get_data->execute();

            if ($get_data->rowCount() > 0) {

                $data_place = $get_data->fetchObject();

                //Get Prescript Number

                $get_prescript = $database->prepare("SELECT prescript.id FROM prescript,pharmacy,pharmacy_prescript WHERE prescript.id = pharmacy_prescript.prescript_id AND pharmacy.id = pharmacy_prescript.pharmacy_id AND pharmacy.id = :pharmacy_id");
                $get_prescript->bindParam("pharmacy_id", $id);
                $get_prescript->execute();

                if($get_prescript->rowCount() >= 0 ){
                    $data_prescript = $get_prescript->rowCount();
                } //*** */


                $pharmacy_data = [

                    "id"                    => $data_place->id,
                    "logo"                  => $data_place->logo,
                    "name"                  => $data_place->name,
                    "phone_number"          => $data_place->phone_number,
                    "owner"                 => $data_place->owner,
                    "start_working"         => $data_place->start_working,
                    "end_working"           => $data_place->end_working,
                    "governorate"           => $data_place->governorate,
                    "address"               => $data_place->address,
                    "Number_Of_Prescript"   => $data_prescript

                ];

                $Message = "تم جلب البيانات";
                print_r(json_encode(Message($pharmacy_data,$Message,200)));

            } else {
                $Message = "لم يتم العثور عللى بيانات";
                print_r(json_encode(Message(null,$Message,204)));
            } 
        } else {
            $Message = "فشل العثور على مستخدم";
            print_r(json_encode(Message(null,$Message,401)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
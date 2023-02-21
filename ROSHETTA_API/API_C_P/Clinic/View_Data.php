<?php

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['admin'])) { //Allow Access Via 'GET' Method Or Admin

    if (isset($_SESSION['doctor']) || isset($_SESSION['assistant'])) {

        if (isset($_SESSION['clinic'])) {

            $id = $_SESSION['clinic'];

            $get_data = $database->prepare("SELECT * FROM clinic WHERE id = :id");
            $get_data->bindparam("id", $id);
            $get_data->execute();

            if ($get_data->rowCount() > 0) {

                $data_place = $get_data->fetchObject();

                //Get Patient Number

                $get_patient = $database->prepare("SELECT patient.id FROM patient,clinic,appointment WHERE clinic.id = :clinic_id AND patient.id = appointment.patient_id AND clinic.id = appointment.clinic_id");
                $get_patient->bindParam("clinic_id", $id);
                $get_patient->execute();

                if($get_patient->rowCount() >= 0 ){
                    $data_patient = $get_patient->rowCount();
                } //*** */

                //Get Prescript Number

                $get_prescript = $database->prepare("SELECT prescript.id FROM prescript,clinic WHERE prescript.clinic_id = clinic.id AND clinic.id = :clinic_id");
                $get_prescript->bindParam("clinic_id", $id);
                $get_prescript->execute();

                if($get_prescript->rowCount() >= 0 ){
                    $data_prescript = $get_prescript->rowCount();
                } //**** */

                $clinic_data = [

                    "id"                    => $data_place->id,
                    "logo"                  => $data_place->logo,
                    "name"                  => $data_place->name,
                    "specialist"            => $data_place->clinic_specialist,
                    "phone_number"          => $data_place->phone_number,
                    "owner"                 => $data_place->owner,
                    "price"                 => $data_place->clinic_price,
                    "start_working"         => $data_place->start_working,
                    "end_working"           => $data_place->end_working,
                    "governorate"           => $data_place->governorate,
                    "address"               => $data_place->address,
                    "number_of_patient"     => $data_patient,
                    "number_of_prescript"   => $data_prescript,

                ];
    
                $Message = "تم جلب البيانات";
                print_r(json_encode(Message($clinic_data,$Message,200)));

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
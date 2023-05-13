<?php

session_start();
session_regenerate_id();

header("Access-Control-Allow-Origin:*"); //Allow All WebSites To Access.
header("Content-Type:application/json; charset=UTF-8"); //To Support Json And Other Languages.
header("Access-Control-Allow-Methods:*"); //Determine The access Method (GET,POST,...).
header("Access-Control-Max-Age:3600"); //The time Period For Data Recovery.
header("Access-Control-Allow-Headers:*"); //Give Permissions To The browser To Exchange Data.

date_default_timezone_set('Africa/Cairo'); //Set To Cairo TimeZone

//****************************************************** Function Design All Message ********************************************************//
function userMessage($Status = null, $Message = null, $data = null)
{
    $array = [
        "Status"    => $Status,
        "Message"   => $Message,
        "Data"      => $data,
    ];
    return print_r(json_encode($array));
}

//************************************************************* Function User Age ********************************************************//
function userAge($age)
{
    $explode    = explode("-", $age);
    $year       = $explode[0];
    $age_year   = date("Y") - $year;
    $month      = $explode[1];
    $age_month  = date("m") - $month;
    $day        = $explode[2];
    $age_day    = date("d") - $day;

    if (($age_month >= 0) && ($age_day >= 0) && ($age_year > 0)) {
        $age = $age_year . ' year';
    } elseif (($age_month > 0) && ($age_year == 0)) {
        $age = $age_month . ' month';
    } elseif (($age_month == 0) && ($age_year == 0)) {
        $age = $age_day . ' day';
    } elseif ((($age_month < 0) || ($age_day < 0)) && ($age_year > 0)) {
        $age = $age_year - 1;
        if ($age == 0) {
            $age = 1 . ' year';
        } else {
            $age = $age . ' year';
        }
    } else {
        $age = null;
    }

    return $age;
}

//************************************************************* Function Get Url Image ********************************************************//
function getImage($mo, $url)
{
    $url_image = URL_LOCAL . $url . $mo . '\\';
    if (is_dir($url_image)) { //If The File Exists
        $scandir = scandir($url_image); //To Displays File Data In Array
        foreach ($scandir as $folder_content) {
            if (is_file($url_image . $folder_content)) {
                return URL_PUBLIC . $url . $mo . "/" . $folder_content;
            }
        }
    }
    return null;
}

function getImageActive($mo, $url)
{
    $url_image = URL_LOCAL . $url . $mo . '\\';
    if (is_dir($url_image)) { //If The File Exists
        $scandir = array_diff(scandir($url_image), ['.', '..']); //To Displays File Data In Array
        if (count($scandir) == 4) {
            $url_public = URL_PUBLIC . $url . $mo . "/";
            $data       = [$url_public . $scandir[2], $url_public . $scandir[3], $url_public . $scandir[4], $url_public . $scandir[5]];
            return $data;
        }
    }
    return null;
}

//************************************************************* Function Get Url Video ********************************************************//
function getVideo($data = [])
{
    $video = URL_LOCAL . $data['url'] . $data['name'];
    if (is_file($video)) {
        return URL_PUBLIC . $data['url'] . $data['name'];
    }
    return null;
}

//************************************************************* Function Add Video ********************************************************//
function addVideo($data = [])
{

    $video_name   = $data["name"];
    $video_tmp    = $data["tmp"];

    $allowed_formulas = ['mp4']; //Allowed Formulas For The Video

    //To Get The Video Formula

    $check_formula   = explode(".", $video_name);
    $formula         = end($check_formula);

    if (in_array($formula, $allowed_formulas)) {
        $video_new_name     = str_split($data['type'], 2)[0] . '-' . bin2hex(random_bytes(10)) . '.' . $formula; //To Input A Random Name For The Image 
        $link               = URL_LOCAL . "videos\\"; //File Link

        if (is_file($link . $data['db_name'])) {
            if (explode(".", $data['db_name'])[0] != 'df_video') {
                unlink($link . $data['db_name']); //To Delete File Data
            }
        }

        move_uploaded_file($video_tmp, $link . $video_new_name); //To Transfer The New Image To The File
        $video = $video_new_name; //The Path WithIn The DataBase

        return $video;
    } else {
        return false;
    }
}

//************************************************************* Function Delete Video ********************************************************//
function removeVideo($name)
{
    $link    = URL_LOCAL . "videos\\"; //File Link
    if (is_file($link . $name)) {
        if (explode(".", $name)[0] != 'df_video') {
            unlink($link  . $name); //To Delete File Data;
            return true;
        }
    }
    return false;
}
//************************************************************* Function Design Message View Profile ********************************************************//
function messageProfile($data_user, $url, $number = null)
{
    $age    = userAge($data_user->birth_date);
    $image  = getImage($data_user->profile_img, $url);

    $data_message = [
        "name"          => $data_user->name,
        "age"           => $age,
        "ssd"           => $data_user->ssd,
        "email"         => $data_user->email,
        "phone_number"  => $data_user->phone_number,
        "gender"        => $data_user->gender,
        "birth_date"    => $data_user->birth_date,
        "governorate"   => $data_user->governorate,
        "image"         => $image,
        "type"          => $data_user->role
    ];
    switch ($data_user->role) {
        case 'patient':
            $data_message['weight']             = $data_user->weight;
            $data_message['height']             = $data_user->height;
            $data_message['number_prescript']   = $number['pre'];
            $data_message['number_disease']     = $number['dis'];
            $data_message['number_appoint']     = $number['app'];
            break;
        case 'doctor':
            $data_message['specialist']         = $data_user->specialist;
            $data_message['number_clinic']      = $number['clinic'];
            $data_message['number_prescript']   = $number['prescript'];
            $data_message['number_appoint']     = $number['appointment'];
            break;
        case 'pharmacist':
            $data_message['number_pharmacy']      = $number['pharmacy'];
            $data_message['number_prescript']     = $number['prescript'];
            $data_message['number_order']         = $number['order'];
            break;
        case 'assistant':
            $data_message['number_clinic']              = $number['clinic'];
            $data_message['number_today_appoint']       = $number['today_appointment'];
            $data_message['number_all_appointment']     = $number['all_appointment'];
            break;
        default:
            $data_message;
    }
    return $data_message;
}

//************************************************************* Function Add Image Profile ********************************************************//
function addImageProfile($data = [])
{

    $img_name   = $data["name"];
    $img_tmp    = $data["tmp"];

    $allowed_formulas = ["jpg", "jpeg", "png"]; //Allowed Formulas For The Image

    //To Get The Image Formula

    $check_formula   = explode(".", $img_name);
    $formula         = end($check_formula);

    if (in_array($formula, $allowed_formulas)) {
        $folder_name    = str_split($data['type'], 2)[0] . '-' . $data['ssd'];
        $img_new_name   = bin2hex(random_bytes(10)) . '.' . $formula; //To Input A Random Name For The Image 
        $link           = URL_LOCAL . $data['url'] . $folder_name . '\\'; //File Link

        if (is_dir($link)) { //If The File Exists
            $scandir = scandir($link); //To Displays File Data In Array
            foreach ($scandir as $folder_content) {
                if (is_file($link . $folder_content)) {
                    unlink($link . $folder_content); //To Delete File Data
                }
            }
        } else {
            mkdir($link); //To Create A New File
        }

        move_uploaded_file($img_tmp, $link . $img_new_name); //To Transfer The New Image To The File
        $image = $folder_name; //The Path WithIn The DataBase

        return $image;
    } else {
        return false;
    }
}

//************************************************************* Function Delete Image Profile ********************************************************//
function removeImage($data = [])
{
    $folder_name    = str_split($data['type'], 2)[0] . '-' . $data['ssd'];
    $link           = URL_LOCAL . $data['url'] . $folder_name . '\\'; //File Link

    if (is_dir($link)) { //If The File Exists
        $scandir = scandir($link); //To Displays File Data In Array
        foreach ($scandir as $folder_content) {
            if (is_file($link . $folder_content)) {
                unlink($link . $folder_content); //To Delete File Data;
                rmdir($link);
                return true;
            }
        }
    } else {
        return false;
    }
}

//************************************************************* Function Add Activation Person Image ********************************************************//
function addImageActivePerson($data = [])
{
    $front_name  = $data["front_name"];
    $back_name   = $data["back_name"];
    $grad_name   = $data["grad_name"];
    $card_name   = $data["card_name"];

    $front_tmp   = $data["front_tmp"];
    $back_tmp    = $data["back_tmp"];
    $grad_tmp    = $data["grad_tmp"];
    $card_tmp    = $data["card_tmp"];

    $allowed_formulas = ["jpg", "jpeg", "png"]; //Allowed Formulas For The Image

    //To Get The Image Formula

    $get_front_formula      = explode(".", $front_name);
    $get_front_formula      = end($get_front_formula);

    $get_back_formula       = explode(".", $back_name);
    $get_back_formula       = end($get_back_formula);

    $get_grad_formula       = explode(".", $grad_name);
    $get_grad_formula       = end($get_grad_formula);

    $get_card_formula       = explode(".", $card_name);
    $get_card_formula       = end($get_card_formula);

    if (
        in_array($get_front_formula, $allowed_formulas)
        && in_array($get_back_formula, $allowed_formulas)
        && in_array($get_grad_formula, $allowed_formulas)
        && in_array($get_card_formula, $allowed_formulas)
    ) {
        $front_new_name  = bin2hex(random_bytes(10)) . '.' . $get_front_formula; //To Input A Random Name For The Image
        $back_new_name   = bin2hex(random_bytes(10)) . '.' . $get_back_formula; //To Input A Random Name For The Image
        $grad_new_name   = bin2hex(random_bytes(10)) . '.' . $get_grad_formula; //To Input A Random Name For The Image
        $card_new_name   = bin2hex(random_bytes(10)) . '.' . $get_card_formula; //To Input A Random Name For The Image

        $folder_name    = str_split($data['type'], 2)[0] . '-' . $data['ssd'];
        $link           = URL_LOCAL . $data['url'] . $folder_name . '\\'; //File Link

        if (is_dir($link)) { //If The File Exists
            $scandir = scandir($link); //To Displays File Data In Array
            foreach ($scandir as $folder_content) {
                if (is_file($link . $folder_content)) {
                    unlink($link . $folder_content); //To Delete File Data
                }
            }
        } else {
            mkdir($link); //To Create A New File
        }

        move_uploaded_file($front_tmp, $link . $front_new_name); //To Transfer The New Image To The File
        move_uploaded_file($back_tmp, $link . $back_new_name); //To Transfer The New Image To The File
        move_uploaded_file($grad_tmp, $link . $grad_new_name); //To Transfer The New Image To The File
        move_uploaded_file($card_tmp, $link . $card_new_name); //To Transfer The New Image To The File

        $image = $folder_name; //The Path WithIn The DataBase

        return $image;
    } else {
        return false;
    }
}

//************************************************************* Function Decode Hash Medicine ********************************************************//
function decodeMedicine($data)
{
    foreach ($data as $value) { //Foreach Data As Key , Value

        $array_value = $value["medicine_data"]; //Determine Medicine Data
        $data_decode = unserialize(base64_decode($array_value)); // Decode Medicine Data
        //$medicine_data_array = [$data_decode]; //Medicine Data In Array For Print
    }
    return $data_decode;
}

//***************************************************** View Clinic Details **************************************************//
function viewClinic($data, $num, $url)
{
    $stuff = [];
    if (!empty($num['data_assistant'])) {
        array_push($stuff, [
            "name"  => $num['data_assistant']->name,
            "age"   => userAge($num['data_assistant']->birth_date),
            "image" => getImage($num['data_assistant']->profile_img, $url['person']),
            "type"  => $num['data_assistant']->role,
        ]);
    }
    array_push($stuff, [
        "name"  => $num['data_doctor']->name,
        "age"   => userAge($num['data_doctor']->birth_date),
        "image" => getImage($num['data_doctor']->profile_img, $url['person']),
        "type"  => $num['data_doctor']->role,
    ]);

    $clinic_data = [
        "id"                    => $data->id,
        "ser_id"                => $data->ser_id,
        "type"                  => "clinic",
        "logo"                  => getImage($data->logo, $url['place']),
        "name"                  => $data->name,
        "specialist"            => $data->specialist,
        "phone_number"          => $data->phone_number,
        "owner"                 => $data->owner,
        "price"                 => $data->price,
        "start_working"         => $data->start_working,
        "end_working"           => $data->end_working,
        "governorate"           => $data->governorate,
        "address"               => $data->address,
        "status"                => $data->status,
        "appoint_all"           => $num['num_appoint'],
        "appoint_day"           => $num['num_ap_day'],
        "number_of_prescript"   => $num['num_pres'],
        "stuff"                 => $stuff
    ];
    return $clinic_data;
}

//***************************************************************** Clinic Design Message ***********************************************************//
function clinicMessageDetails($data, $url)
{
    $appoint_case = 1;
    $appoint_date = null;
    if (!empty($data['data_appoint'])) {
        foreach ($data['data_appoint'] as $app) {
            if ($data['data_clinic']->id == $app['clinic_id']) {
                if ($app['appoint_case'] == 0) {
                    $appoint_case = 0;
                    $appoint_date = $app['appoint_date'];
                }
            }
        }
    }

    $data_clinic = [
        "clinic_id"                 => $data['data_clinic']->id,
        "logo"                      => getImage($data['data_clinic']->logo, $url),
        "name"                      => $data['data_clinic']->name,
        "specialist"                => $data['data_clinic']->specialist,
        "governorate"               => $data['data_clinic']->governorate,
        "phone_number"              => $data['data_clinic']->phone_number,
        "price"                     => $data['data_clinic']->price,
        "start_working"             => $data['data_clinic']->start_working,
        "end_working"               => $data['data_clinic']->end_working,
        "address"                   => $data['data_clinic']->address,
        "isOpen"                    => $data['data_clinic']->status,
        "number_appoint_clinic"     => $data['number_appoint_clinic'],
        "number_appoint_patient"    => $data['number_appoint_patient'],
        "appoint_case"              => $appoint_case,
        "appoint_date"              => $appoint_date,
    ];
    return $data_clinic;
}

//***************************************************************** Pharmacy Design Message ***********************************************************//
function pharmacyMessageDetails($data, $url)
{
    $data_pharmacy = [
        "pharmacy_id"                   => $data['data_pharmacy']->id,
        "logo"                          => getImage($data['data_pharmacy']->logo, $url),
        "name"                          => $data['data_pharmacy']->name,
        "governorate"                   => $data['data_pharmacy']->governorate,
        "phone_number"                  => $data['data_pharmacy']->phone_number,
        "start_working"                 => $data['data_pharmacy']->start_working,
        "end_working"                   => $data['data_pharmacy']->end_working,
        "address"                       => $data['data_pharmacy']->address,
        "status"                        => $data['data_pharmacy']->status,
        "number_prescript_pharmacy"     => $data['number_prescript_pharmacy'],
        "number_prescript_patient"      => $data['number_prescript_patient']
    ];
    return $data_pharmacy;
}

//***************************************************** View Pharmacy Details **************************************************//
function viewPharmacy($data, $num, $url)
{
    $pharmacy_data = [
        "id"                    => $data->id,
        "ser_id"                => $data->ser_id,
        "type"                  => "pharmacy",
        "logo"                  => getImage($data->logo, $url['place']),
        "name"                  => $data->name,
        "phone_number"          => $data->phone_number,
        "owner"                 => $data->owner,
        "start_working"         => $data->start_working,
        "end_working"           => $data->end_working,
        "governorate"           => $data->governorate,
        "address"               => $data->address,
        "status"                => $data->status,
        "number_of_prescript"   => $num['num_pres'],
        'number_of_orders'      => $num['data_order'],
        "stuff"                 => [
            "name"           => $num['data_pharmacist']->name,
            "age"            => userAge($num['data_pharmacist']->birth_date),
            "image"          => getImage($num['data_pharmacist']->profile_img, $url['person']),
        ]
    ];
    return $pharmacy_data;
}

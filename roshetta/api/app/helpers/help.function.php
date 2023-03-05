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
    $url_image = $url . $mo . '\\';
    if (is_dir($url_image)) { //If The File Exists
        $scandir = scandir($url_image); //To Displays File Data In Array
        foreach ($scandir as $folder_content) {
            if (is_file($url_image . $folder_content)) {
                return $url_image . $folder_content;
            }
        }
    }
    return null;
}

//************************************************************* Function Get Url Video ********************************************************//
function getVideo($data = [])
{
    $video = $data['url'] . $data['name'];
    if (is_file($video)) {
        return $video;
    }
    return null;
}

//************************************************************* Function Design Message View Profile ********************************************************//
function messageProfile($data_user, $url)
{
    $age = userAge($data_user->birth_date);
    $image = getImage($data_user->profile_img, $url);
    switch ($data_user->role) {
        case 'patient':
            $data_message = [
                "name"          => $data_user->name,
                "age"           => $age,
                "ssd"           => $data_user->ssd,
                "email"         => $data_user->email,
                "phone_number"  => $data_user->phone_number,
                "gender"        => $data_user->gender,
                "birth_date"    => $data_user->birth_date,
                "governorate"   => $data_user->governorate,
                "weight"        => $data_user->weight,
                "height"        => $data_user->height,
                "image"         => $image,
                "type"          => $data_user->role
            ];
            break;
        case 'doctor':
            $data_message = [
                "name"          => $data_user->name,
                "age"           => $age,
                "ssd"           => $data_user->ssd,
                "email"         => $data_user->email,
                "phone_number"  => $data_user->phone_number,
                "gender"        => $data_user->gender,
                "birth_date"    => $data_user->birth_date,
                "governorate"   => $data_user->governorate,
                "specialist"    => $data_user->specialist,
                "image"         => $image,
                "type"          => $data_user->role
            ];
            break;
        default:
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
    }

    return $data_message;
}

//************************************************************* Function Add Image Profile ********************************************************//
function addImageProfile($data = [])
{

    $img_name   = $data["name"];
    $img_tmp    = $data["tmp"];

    $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

    //To Get The Image Formula

    $check_formula   = explode(".", $img_name);
    $formula         = end($check_formula);

    if (in_array($formula, $allowed_formulas)) {

        $folder_name    = str_split($data['type'], 2)[0] . '-' . $data['ssd'];
        $img_new_name   = bin2hex(random_bytes(10)) . '.' . $formula; //To Input A Random Name For The Image 
        $link           = $data['url'] . $folder_name . '\\'; //File Link

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
    $link           = $data['url'] . $folder_name . '\\'; //File Link

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

    $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

    //To Get The Image Formula

    $get_front_formula   = explode(".", $front_name);
    $get_front_formula   = end($get_front_formula);

    $get_back_formula   = explode(".", $back_name);
    $get_back_formula   = end($get_back_formula);

    $get_grad_formula   = explode(".", $grad_name);
    $get_grad_formula   = end($get_grad_formula);

    $get_card_formula   = explode(".", $card_name);
    $get_card_formula   = end($get_card_formula);

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
        $link           = $data['url'] . $folder_name . '\\'; //File Link

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
    foreach ($data as $key => $value) { //Foreach Data As Key , Value

        $array_value = $value["medicine_data"]; //Determine Medicine Data
        $data_decode = unserialize(base64_decode($array_value)); // Decode Medicine Data
        $medicine_data_array = array($data_decode); //Medicine Data In Array For Print
    }

    return $medicine_data_array;
}

//***************************************************** View Clinic **************************************************//
function viewClinic($data, $num, $url)
{

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
        "appoint_all"           => $num['num_appoint'],
        "appoint_day"           => $num['num_ap_day'],
        "number_of_prescript"   => $num['num_pres'],
        "stuff"                 => [
            "doctor_name"           => $num['data_doctor']->name,
            "doctor_age"            => userAge($num['data_doctor']->birth_date),
            "doctor_image"          => getImage($num['data_doctor']->profile_img, $url['person']),
            "assistant_name"        => $num['data_assistant']->name,
            "assistant_age"         => userAge($num['data_assistant']->birth_date),
            "assistant_image"       => getImage($num['data_assistant']->profile_img, $url['person'])
        ]

    ];
    return $clinic_data;
}

//***************************************************************** Clinic Design Message ***********************************************************//
function clinicMessage($data, $url)
{
    $data_clinic = [
        "clinic_id"     => $data->id,
        "logo"          => getImage($data->logo,$url),
        "name"          => $data->name,
        "specialist"    => $data->specialist,
        "governorate"   => $data->governorate
    ];
    return $data_clinic;
}
function clinicMessageDetails($data, $url)
{
    $data_clinic = [
        "clinic_id"     => $data['data_clinic']->id,
        "logo"          => getImage($data['data_clinic']->logo,$url),
        "name"          => $data['data_clinic']->name,
        "specialist"    => $data['data_clinic']->specialist,
        "governorate"   => $data['data_clinic']->governorate,
        "phone_number"  => $data['data_clinic']->phone_number,
        "price"         => $data['data_clinic']->price,
        "start_working" => $data['data_clinic']->start_working,
        "end_working"   => $data['data_clinic']->phone_number,
        "address"       => $data['data_clinic']->address,
        "number_appoint_clinic"   => $data['number_appoint_clinic'],
        "number_appoint_patient"  => $data['number_appoint_patient']
    ];
    return $data_clinic;
}

//***************************************************************** Pharmacy Design Message ***********************************************************//
function pharmacyMessage($data, $url)
{
    $data_clinic = [
        "pharmacy_id"   => $data->id,
        "logo"          => getImage($data->logo,$url),
        "name"          => $data->name,
        "phone_number"  => $data->phone_number,
        "governorate"   => $data->governorate
    ];
    return $data_clinic;
}
function pharmacyMessageDetails($data, $url)
{
    $data_pharmacy = [
        "pharmacy_id"   => $data['data_pharmacy']->id,
        "logo"          => getImage($data['data_pharmacy']->logo,$url),
        "name"          => $data['data_pharmacy']->name,
        "governorate"   => $data['data_pharmacy']->governorate,
        "phone_number"  => $data['data_pharmacy']->phone_number,
        "start_working" => $data['data_pharmacy']->start_working,
        "end_working"   => $data['data_pharmacy']->phone_number,
        "address"       => $data['data_pharmacy']->address,
        "number_prescript_pharmacy"   => $data['number_prescript_pharmacy'],
        "number_prescript_patient"    => $data['number_prescript_patient']
    ];
    return $data_pharmacy;
}
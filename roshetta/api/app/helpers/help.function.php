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

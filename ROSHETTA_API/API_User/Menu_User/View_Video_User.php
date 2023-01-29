<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (  //If Found SESSION
    isset($_SESSION['patient'])
    || isset($_SESSION['doctor'])
    || isset($_SESSION['pharmacist'])
    || isset($_SESSION['assistant'])
) {

    if (isset($_SESSION['patient'])) {
        $type = 'patient';
    } elseif (isset($_SESSION['doctor'])) {
        $type = 'doctor';
    } elseif (isset($_SESSION['pharmacist'])) {
        $type = 'pharmacist';
    } elseif (isset($_SESSION['assistant'])) {
        $type = 'assistant';
    } else {
        $type = '';
    }

    //Get Video For User

    $get_video = $database->prepare("SELECT video FROM video WHERE type = :type");
    $get_video->bindparam("type", $type);
    $get_video->execute();

    if ($get_video->rowCount() > 0) {

        $data_video = $get_video->fetchAll(PDO::FETCH_ASSOC);
        print_r(json_encode($data_video));

    } else {
        print_r(json_encode(["Error" => "لا يوجد فيديو"]));
    }
} else {
    print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
}
?>
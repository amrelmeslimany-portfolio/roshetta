<?php

require_once("../../API_C_A/Allow.php"); //Allow All Headers
require_once("../../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (isset($_SESSION['admin'])) {

    // Get From Video Table

    $get_video = $database->prepare("SELECT video,type FROM video");
    $get_video->execute();

    if ($get_video->rowCount() > 0) {

        $data_video = $get_video->fetchAll(PDO::FETCH_ASSOC);
        print_r(json_encode($data_video));
        
    } else {
        print_r(json_encode(["Error" => "لا يوجد فيديوهات"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
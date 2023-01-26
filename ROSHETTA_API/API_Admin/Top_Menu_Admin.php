<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if (isset($_SESSION['admin'])) {

    require_once("../API_C_A/Connection.php"); //Connect To DataBases

        $admin_name     = $_SESSION['admin']->admin_name;
        $ssd            = $_SESSION['admin']->ssd;
        $profile_img    = $_SESSION['admin']->profile_img;

        $top_menu_data = array(

            "admin_name"    => $admin_name,
            "ssd"           => $ssd,
            "profile_img"   => $profile_img

        );

        print_r(json_encode($top_menu_data));

} else{
    print_r(json_encode(["Error" => "فشل العثور على مستخدم"]));
}
?>
<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases

session_start();
session_regenerate_id();

if (isset($_SESSION['admin'])) {

    // Get From Message Table

    $get_message = $database->prepare("SELECT name,email,message,role FROM message WHERE m_case = 1 ORDER BY time DESC");
    $get_message->execute();

    if ($get_message->rowCount() > 0) {

        $data_message = $get_message->fetchAll(PDO::FETCH_ASSOC);
        print_r(json_encode($data_message));
        
    } else {
        print_r(json_encode(["Error" => "لا يوجد رسائل"]));
    }
} else {
    print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
}
?>
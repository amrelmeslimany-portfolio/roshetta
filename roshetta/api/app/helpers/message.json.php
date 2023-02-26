<?php

session_start();
session_regenerate_id();

header("Access-Control-Allow-Origin:*"); //Allow All WebSites To Access.
header("Content-Type:application/json; charset=UTF-8"); //To Support Json And Other Languages.
header("Access-Control-Allow-Methods:*"); //Determine The access Method (GET,POST,...).
header("Access-Control-Max-Age:3600"); //The time Period For Data Recovery.
header("Access-Control-Allow-Headers:*"); //Give Permissions To The browser To Exchange Data.

function userMessage($Status = null, $Message = null, $data = null) // Design All Message
{    
    $array = [
        "Status"    => $Status,
        "Message"   => $Message,
        "Data"      => $data,
    ];
    return print_r(json_encode($array));
}

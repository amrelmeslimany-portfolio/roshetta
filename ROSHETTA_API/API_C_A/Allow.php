<?php

//Allow All WebSites To Access.
header("Access-Control-Allow-Origin:*");

//To Support Json And Other Languages.
header("Content-Type:application/json; charset=UTF-8");

//Determine The access Method (GET,POST,...).
header("Access-Control-Allow-Methods:*");

//The time Period For Data Recovery.
header("Access-Control-Max-Age:3600");

//Give Permissions To The browser To Exchange Data.
header("Access-Control-Allow-Headers:*");

?>
<?php

// Create Database Info

define("DB_HOST", 'localhost');
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'roshetta');

define('URL_LOCAL',dirname(__DIR__,3) . "\uploads\\");

//Url Images And Videos Public

define("URL_PUBLIC" , $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/roshetta/uploads/"); //To Find Out The Server Name And Port //To Find The Type Of Connection [HTTP , HTTPS]

//Url Images And Videos Local

define("URL_PLACE",'images/place_image/');
define("URL_PERSON",'images/profile_image/');
define("URL_ACTIVATION_PERSON",'images/activation_image_person/');
define("URL_ACTIVATION_PLACE",'images/activation_image_place/');
define("URL_VIDEO",'videos/');

//Url Default Images And Videos Local

define("DF_VIDEO_DO",'df_video.mp4');
define("DF_VIDEO_PA",'df_video.mp4');
define("DF_VIDEO_PH",'df_video.mp4');
define("DF_VIDEO_AS",'df_video.mp4');
define("DF_IMAGE_PERSON_MALE",'df_male');
define("DF_IMAGE_PERSON_FEMALE",'df_female');
define("DF_IMAGE_CLINIC",'df_clinic');
define("DF_IMAGE_PHARMACY",'df_pharmacy');



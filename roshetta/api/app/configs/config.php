<?php

// Create Database Info

define("DB_HOST", 'localhost');
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'roshetta');

//Url Image Public

define("URL_PUBLIC" , $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/roshetta/uploads/"); //To Find Out The Server Name And Port //To Find The Type Of Connection [HTTP , HTTPS]
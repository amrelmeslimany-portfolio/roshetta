<?php

// Function to get the client IP address
function get_user_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Function Message

function Message($data = null,$message = null , $status = null){
    $array = [
        "Data"    => $data,
        "Message" => $message ,
        "Status"  => $status
    ];
    return $array;
}

function EmailBody($icon_src  , $html_body)
{
    return '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
    <img src='.$icon_src.' style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
    '.$html_body.'
    <hr style="margin: 10px 0;border: 1px solid #d7d7d7">
    <div style="text-align: center;margin: auto">
    <small style="color: #3e3e3e; font-weight: 500;font-family: cursive;">مع تحيات فريق روشتة</small><br>
    <div style="margin-top: 10px">
        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
            <img src="https://img.icons8.com/ios-glyphs/30/null/facebook-new.png" />
        </a>
        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
            <img src="https://img.icons8.com/ios-glyphs/30/null/instagram-new.png" />
        </a>
        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
            <img src="https://img.icons8.com/ios-glyphs/30/null/linkedin.png" />
        </a>
        <a href="http://google.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
            <img src="https://img.icons8.com/ios-glyphs/30/null/youtube--v1.png" />
        </a>
    </div>
    </div></div>';
}

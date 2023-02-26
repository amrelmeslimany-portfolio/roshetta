<?php
function emailBody($icon_src, $html_body)
{
    $body = '<div style="padding: 20px; max-width: 500px; margin: auto;border: #d7d7d7 2px solid;border-radius: 10px;background-color: rgba(241, 241, 241 , 0.5) !important;text-align: center;">
    <img src="https://i.ibb.co/hVcMYnQ/lg-text.png" style="display: block;width: 110px;margin: auto;" alt="roshetta , روشته">
    <hr style="margin: 20px 0;border: 1px solid #d7d7d7">
    <img src=' . $icon_src . ' style="display: block;margin: 0 auto ; width: 100px ; heigh: 100px;" alt="تأكيد الاميل">
    ' . $html_body . '
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

    return $body;
}

function registerEmailBody($data_email = [])
{

    $name = 'Roshetta Register';
    $email = $data_email['email'];
    $subject = 'تفعيل بريدك الإلكترونى فى روشتة';
    $icon_src = "https://img.icons8.com/fluency/300/null/reading-confirmation.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;font-style: italic;">' . $data_email['hi'] . '</h3>
    <h3 style="text-align: center;font-family: cursive;font-style: italic;">' . $data_email['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لـــروشتــــة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل البريد الإلكترونى الخاص بك</p></br>
    <h3 style="margin-top: 6px;font-family: cursive;">كود تفعيل البريد الإلكترونى</h3> 
    <h2 style="font-family: cursive;color: red;">' . $data_email['number'] . '</h2>
    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذا الكود متاح للاستخدام مرة واحدة فقط</p>
    ';

    $array = [
        "name" => $name,
        "email" => $email,
        "subject" => $subject,
        "icon" => $icon_src,
        "body" => $html_body
    ];

    return $array;
}

function loginEmailBody($data_email)
{
    $name = 'Roshetta Login';
    $email = $data_email['email'];
    $subject = 'تنبية تسجيل دخول إلى حساب روشتة';
    $icon_src = "https://img.icons8.com/material-rounded/200/22C3E6/break.png";
    $device_data  = $_SERVER['HTTP_USER_AGENT'];
    $date_time  = date('h:i:s Y-m-d');
    $ip = getIp();
    $html_body = '<h3 style="text-align: center;font-family: cursive;padding: 0px ;font-style: italic;">' . $data_email['hi'] . '</h3>
    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' .  $data_email['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">جديد(ip)لاحظنا أن حسابك تم الوصول إلية من عنوان </p></br>
    <p style="text-align: center;font-family: cursive;">' . ($device_data) . '</p>
    <p style="text-align: center;font-family: cursive;">' . $ip . ' :(ip) عنوان</p>
    <p style="text-align: center;font-family: cursive;"> ' . $date_time . ' : (بتوقيت القاهرة) التوقيت</p>
    <h5 style="text-align: center;font-family: cursive;">هل ليس أنت ؟ <a href="' . $data_email['password_edit'] . '">إعادة تعيين كلمة المرور</a></h5>
    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>هذة الرسالة ألية برجاء عدم الرد</p>
    ';

    $array = [
        "name" => $name,
        "email" => $email,
        "subject" => $subject,
        "icon" => $icon_src,
        "body" => $html_body
    ];

    return $array;
}

// Function to get the client IP address
function getIp()
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

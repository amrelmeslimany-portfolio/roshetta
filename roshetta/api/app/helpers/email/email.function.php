<?php

//************************************************************* Function Hello Users ********************************************************//
function hi($type)
{
    switch ($type) {
        case 'doctor':
            $hi = 'مـــــرحبـــــا بــــك دكتـــــور';
            break;
        case 'pharmacist':
            $hi = 'مـــــرحبـــــا بــــك دكتـــــور';
            break;
        case 'admin':
            $hi = 'مـــــرحبـــــا بــــك مـــــدير';
            break;
        default:
            $hi = 'مـــــرحبــــــا بــــك';
    }
    return $hi;
}

//************************************************************* Function Parent Email Body ********************************************************//
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

//************************************************************* Function Active Email Body ********************************************************//
function registerEmailBody($data_email = [])
{
    $hi = hi($data_email['type']);
    $name = 'Roshetta Register';
    $email = $data_email['email'];
    $subject = 'تفعيل بريدك الإلكترونى فى روشتة';
    $icon_src = "https://img.icons8.com/fluency/300/null/reading-confirmation.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;font-style: italic;">' . $hi . '</h3>
    <h3 style="text-align: center;font-family: cursive;font-style: italic;">' . $data_email['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سعداء لانضمامك لـــروشتــــة سنفعل ما بوسعنا لتقديم للعملاء افضل الخدمات للاستمتاع بافضل المميزات والخدمات الرجاء تفعيل البريد الإلكترونى الخاص بك</p></br>
    <p style="margin-top: 6px;font-family: cursive;">كود تفعيل البريد الإلكترونى</p> 
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

//************************************************************* Function Alert Login Email Body ********************************************************//
function loginEmailBody($data_email)
{
    function getIp()   // Function Get The Client IP Address
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

    // Create an instance of MobileDetect class 
    $mdetect = new MobileDetect();                  // Function Get The Client Type Device And Operating System

    if ($mdetect->isMobile()) {
        // Detect mobile/tablet  
        if ($mdetect->isTablet()) {
            $type = 'Tablet';
        } else {
            $type = 'Mobile';
        }
        // Detect platform 
        if ($mdetect->isiOS()) {
            $operating_system = 'IOS';
        } elseif ($mdetect->isAndroidOS()) {
            $operating_system = 'Android';
        }
    } else {
        $type = 'Desktop';
        $operating_system = 'Windows';
    }


    $device_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);   // Function Get The Client Name Device

    $hi = hi($data_email['type']);
    $name = 'Roshetta Login';
    $email = $data_email['email'];
    $subject = 'تنبية تسجيل دخول إلى حساب روشتة';
    $icon_src = "https://img.icons8.com/material-rounded/200/22C3E6/break.png";
    $date_time  = date('h:i:s Y-m-d');
    $ip = getIp();

    $html_body = '<h3 style="text-align: center;font-family: cursive;padding: 0px ;font-style: italic;">' . $hi . '</h3>
    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' .  $data_email['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">هل قمت بتسجيل الدخول من جهاز جديد أو موقع جديد ؟</p></br>         
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">( ' . $type . ' ) : لأحظنا أن حسابك تم الوصول إلية من جهاز </p></br>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">( ' . $device_name . ' ) : إسم الجهاز </p></br>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">( ' . $operating_system . ' ) : يعمل بنظام </p></br>
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

//************************************************************* Function Support Email Body ********************************************************//
function supportEmailBody($data)
{
    $hi = hi($data['type']);
    $name = 'Roshetta Support';
    $email = $data['email'];
    $subject = 'فريق الدعم';
    $icon_src = "https://img.icons8.com/fluency/200/null/envelope-number.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $hi . '</h3>
    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $data['user_name'] . '</h3>       
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">سوف يتم الرد على استفسارك فى غضون 48 ساعة الرجاء عدم تكرار الرسائل</p></br>  
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">نشكرك على التواصل معنا</p></br>                  
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

//************************************************************* Function Reset Password Email Body ********************************************************//
function resetPasswordEmail($data)
{
    $hi = hi($data['type']);
    $name = 'Roshetta Security';
    $email = $data['email'];
    $subject = 'إعادة تعيين كلمة مرور حسابك فى روشتة';
    $icon_src = "https://img.icons8.com/ios-filled/200/22C3E6/keyhole-shield.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;margin: -20px ;font-style: italic;">' . $hi . '</h3>
    <h3 style="text-align: center;font-family: cursive; margin: -20px ;font-style: italic;">' . $data['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">كود إعادة تعيين كلمة المرور</p></br>         
    <h2 style="font-family: cursive;color: red;">' . $data['code'] . '</h2>
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

//************************************************************* Function Activation Account ********************************************************//

function activeEmailBody($data)
{
    if ($data['type'] == 'doctor' || $data['type'] == 'pharmacist') {
        $message = 'لقد تم تنشيط حسابك يمكنك الأن العمل والإستمتاع بكافة المميزات';
    } elseif ($data['type'] == 'clinic') {
        $message = 'لقد تم تنشيط العيادة الخاصة بك يمكنك الأن العمل والإستمتاع بكافة المميزات';
    } else {
        $message = 'لقد تم تنشيط الصيدلية الخاصة بك يمكنك الأن العمل والإستمتاع بكافة المميزات';
    }

    $name = 'Roshetta Activation';
    $email = $data['email'];
    $subject = 'تهنئة لتفعيل حسابك';
    $icon_src = "https://img.icons8.com/fluency/300/null/reading-confirmation.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;"> مـــــرحبـــــا بــــك دكتــــور </h3>
    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">' . $data['user_name'] . '</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">' . $message . '</p></br>         
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

//************************************************************* Function Reply Message ********************************************************//
function replyMessage($data)
{
    $hi = hi($data['type']);
    $name = 'Roshetta Support';
    $email = $data['email'];
    $subject = 'رد على استفسارك من قبل فريق الدعم';
    $icon_src = "https://img.icons8.com/fluency/200/null/envelope-number.png";
    $html_body = '<h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'.$hi.'</h3>
    <h3 style="text-align: center;font-family: cursive;padding: 0px;font-style: italic;">'. $data['user_name'] .'</h3>
    <p style="margin-top: 6px;font-family: cursive;color: #2d2d2d;">'.$data['message'].'</p></br>         
    <p style="margin-top: 10px;font-family: cursive;color: #2d2d2d;"><b style="color: red;">ملاحظة / </b>إذا كان لديك أى استفسار أخر برجاء التواصل معنا</p>
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

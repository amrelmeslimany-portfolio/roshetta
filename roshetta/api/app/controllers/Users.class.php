<?php

class Users extends Controller
{
    private $userModel, $doctorModel, $pharmacistModel;
    public function __construct()
    {
        $this->userModel        = $this->model('User');
        $this->doctorModel      = $this->model('Doctor');
        $this->pharmacistModel  = $this->model('Pharmacist');
    }

    public function document()
    {
        $Message    = '(API_Users)برجاء الإطلاع على شرح';
        $Status     = 400;
        $url        = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#2f22abfd-6fd8-4413-a924-9ef15a1227b0';
        userMessage($Status, $Message, $url);
        die();
    }

    //*************************************************** Token Verify **************************************************************//
    private function tokenVerify()
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization']) || isset($headers['Authorization'])) {
            @$Auth      = explode(" ", $headers['authorization'] ? $headers['authorization'] : $headers['Authorization'])[1]; // Get Token From Auth
            @$token_out = TokenDecode($Auth);
            if (!$token_out) {
                return false;
            }
            @$token_in = $this->userModel->getToken($token_out);
            if (!$token_in) {
                return false;
            }
            if ($token_in->token != $Auth) {
                return false;
            } else {
                return $token_out;
            }
        } else {
            return false;
        }
    }

    //********************************************* Register ************************************************************//
    public function register() //Function Register All User
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$email          = filter_var($_POST['email'], 517); //FILTER_SANITIZE_EMAIL
            @$ssd            = filter_var($_POST['ssd'], 519);  //FILTER_SANITIZE_INT
            @$phone_number   = filter_var($_POST['phone_number'], 519);  //FILTER_SANITIZE_INT

            $data = [  //Array Data
                "type"              => @$_POST['role'],
                "name"              => @$_POST['first_name'] . ' ' . @$_POST['last_name'],
                "email"             => @strtolower($email),
                "ssd"               => @$ssd,
                "phone_number"      => @$phone_number,
                "birth_date"        => @$_POST['birth_date'],
                "gender"            => @$_POST['gender'],
                "governorate"       => @$_POST['governorate'],
                "password"          => @$_POST['password'],
                "confirm_password"  => @$_POST['confirm_password'],
            ];
            $data_err = [ //Array Error Data
                "type_err"              => '',
                "name_err"              => '',
                "email_err"             => '',
                "ssd_err"               => '',
                "phone_number_err"      => '',
                "birth_date_err"        => '',
                "gender_err"            => '',
                "governorate_err"       => '',
                "weight_err"            => '',
                "height_err"            => '',
                "specialist_err"        => '',
                "password_err"          => '',
                "confirm_password_err"  => ''
            ];

            if (empty($data['type'])) {  // Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount)) {
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
                    $Message    = $data_err;
                    $Status     = 400;
                    userMessage($Status, $Message);
                    die();
                } else {
                    if (empty($_POST['first_name'])) $data_err['name_err'] = 'برجاء إدخال الإسم';  //Check Name

                    if (empty($data['email'])) {
                        $data_err['email_err'] = 'برجاء إدخال البريد الإلكترونى';
                    } else {
                        if (!filter_var($data['email'], 274)) {
                            $data_err['email_err'] = 'البريد الإلكترونى غير صالح';  // FILTER_VALIDATE_EMAIL
                        } else {
                            if ($this->userModel->getUserEmail($data['email'], $data['type'])) $data_err['email_err'] = 'البريد الإلكترونى موجود من قبل';
                        }
                    }

                    if (empty($data['ssd'])) { // Check SSD
                        $data_err['ssd_err'] = 'برجاء إدخال الرقم القومى';
                    } else {
                        if (!filter_var($data['ssd'], 257) || strlen($data['ssd']) != 14) {
                            $data_err['ssd_err'] = 'الرقم القومى غير صالح';  // FILTER_VALIDATE_INT
                        } else {
                            if ($this->userModel->getUserSSD($data['ssd'], $data['type'])) $data_err['ssd_err'] = 'الرقم القومى موجود من قبل';
                        }
                    }

                    if (empty($data['phone_number'])) {  // Check Phone
                        $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                    } else {
                        if (strlen($data['phone_number']) != 11) {
                            $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                        } else {
                            if ($this->userModel->getUserPhone($data['phone_number'], $data['type'])) $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                        }
                    }

                    if (empty($data['birth_date'])) $data_err['birth_date_err'] = 'برجاء إدخال تاريخ الميلاد';  // Check Birth Date
                    if (empty($data['gender'])) $data_err['gender_err'] = 'برجاء إدخال الجنس';  // Check User Gender 
                    if (empty($data['governorate'])) $data_err['governorate_err'] = 'برجاء إدخال المحافظة'; // Check User Governorate

                    if (empty($data['password'])) {
                        $data_err['password_err'] = 'برجاء إدخال كلمة المرور'; // Check User Password
                    } else {
                        if (strlen($data['password']) < 6) $data_err['password_err'] = 'كلمة المرور يجب الأ تقل عن 6 عناصر'; // Check Length Password
                    }
                    if (empty($data['confirm_password'])) {
                        $data_err['confirm_password_err'] = 'برجاء تأكيد كلمة المرور'; // Check Confirm Password
                    } else {
                        if ($data['password'] != $data['confirm_password']) $data_err['confirm_password_err'] = 'كلمة المرور غير متطابقة'; //Check Validate Password
                    }

                    switch ($data['type']) {
                        case 'patient':
                            $data_other = [
                                "weight"        => $_POST['weight'],
                                "height"        => $_POST['height']
                            ];
                            if (empty($data_other['weight'])) $data_err['weight_err'] = 'برجاء إدخال الوزن'; //Check Weight
                            if (empty($data_other['height'])) $data_err['height_err'] = 'برجاء إدخال الطول'; //Check Hight
                            break;
                        case 'doctor':
                            $data_other = [
                                "specialist" => $_POST['specialist']
                            ];
                            if (empty($data_other['specialist'])) $data_err['specialist_err'] = 'برجاء إدخال التخصص'; // Check Specialist
                            break;
                        default:
                            $data_err['weight_err'] = "";
                            $data_err['height_err'] = "";
                            $data_err['specialist_err'] = "";
                    }

                    if (
                        empty($data_err['type_err'])
                        && empty($data_err['name_err'])
                        && empty($data_err['email_err'])
                        && empty($data_err['ssd_err'])
                        && empty($data_err['phone_number_err'])
                        && empty($data_err['birth_date_err'])
                        && empty($data_err['gender_err'])
                        && empty($data_err['governorate_err'])
                        && empty($data_err['password_err'])
                        && empty($data_err['confirm_password_err'])
                        && empty($data_err['weight_err'])
                        && empty($data_err['height_err'])
                        && empty($data_err['specialist_err'])
                    ) {
                        @$password      = password_hash($data['password'], "2y"); //PASSWORD_DEFAULT Hash
                        @$security_code = random_int(100000, 999999);  // Create Random Number

                        if ($data['gender'] == 'ذكر' || $data['gender'] == 'male') {
                            $image = DF_IMAGE_PERSON_MALE;
                        } else {
                            $image = DF_IMAGE_PERSON_FEMALE;
                        }

                        switch ($data['type']) {
                            case 'patient':
                                $data = [
                                    "type"          => $data['type'],
                                    "name"          => $data['name'],
                                    "email"         => $data['email'],
                                    "ssd"           => $data['ssd'],
                                    "phone_number"  => $data['phone_number'],
                                    "birth_date"    => $data['birth_date'],
                                    "gender"        => $data['gender'],
                                    "governorate"   => $data['governorate'],
                                    "weight"        => $data_other['weight'],
                                    "height"        => $data_other['height'],
                                    "password"      => $password,
                                    "security_code" => $security_code,
                                    "image"         => $image
                                ];
                                if (@$this->userModel->registerPatient($data)) {
                                    $Message    = 'تم التسجيل بنجاح';
                                    $Status     = 201;
                                } else {
                                    $Message    = 'فشل التسجيل';
                                    $Status     = 422;
                                    userMessage($Status, $Message);
                                    die();
                                }
                                break;
                            case 'doctor':
                                $data = [
                                    "type"          => $data['type'],
                                    "name"          => $data['name'],
                                    "email"         => $data['email'],
                                    "ssd"           => $data['ssd'],
                                    "phone_number"  => $data['phone_number'],
                                    "birth_date"    => $data['birth_date'],
                                    "gender"        => $data['gender'],
                                    "governorate"   => $data['governorate'],
                                    "specialist"    => $data_other['specialist'],
                                    "password"      => $password,
                                    "security_code" => $security_code,
                                    "image"         => $image
                                ];
                                if (@$this->userModel->registerDoctor($data)) {
                                    $Message    = 'تم التسجيل بنجاح';
                                    $Status     = 201;
                                } else {
                                    $Message    = 'فشل التسجيل';
                                    $Status     = 422;
                                    userMessage($Status, $Message);
                                    die();
                                }
                                break;
                            default:
                                $data = [
                                    "type"          => $data['type'],
                                    "name"          => $data['name'],
                                    "email"         => $data['email'],
                                    "ssd"           => $data['ssd'],
                                    "phone_number"  => $data['phone_number'],
                                    "birth_date"    => $data['birth_date'],
                                    "gender"        => $data['gender'],
                                    "governorate"   => $data['governorate'],
                                    "password"      => $password,
                                    "security_code" => $security_code,
                                    "image"         => $image
                                ];
                                if ($data['type'] == 'admin') {
                                    @$check_token = $this->tokenVerify();
                                    if (!$check_token) {
                                        $Message    = 'الرجاء تسجيل الدخول';
                                        $Status     = 401;
                                        userMessage($Status, $Message);
                                        die();
                                    }
                                    if ($check_token['type'] == 'admin') {
                                        if (@$this->userModel->registerOther($data)) {
                                            $Message    = 'تم التسجيل بنجاح';
                                            $Status     = 201;
                                        } else {
                                            $Message    = 'فشل التسجيل';
                                            $Status     = 422;
                                            userMessage($Status, $Message);
                                            die();
                                        }
                                    } else {
                                        $Message    = 'ليس لديك الصلاحية';
                                        $Status     = 403;
                                        userMessage($Status, $Message);
                                        die();
                                    }
                                } else {
                                    if (@$this->userModel->registerOther($data)) {
                                        $Message    = 'تم التسجيل بنجاح';
                                        $Status     = 201;
                                    } else {
                                        $Message    = 'فشل التسجيل';
                                        $Status     = 422;
                                        userMessage($Status, $Message);
                                        die();
                                    }
                                }
                        }

                        //************************************* Send Email Verify ***********************************//
                        $data_email = [
                            "type"      => $data['type'],
                            "user_name" => $data['name'],
                            "email"     => $data['email'],
                            "number"    => $security_code
                        ];
                        $mail_data = registerEmailBody($data_email);  //Function To Get Email Data
                        @require_once('../app/helpers/email/mail.php');
                        $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                        $mail->addAddress($mail_data['email']);
                        $mail->Subject = $mail_data['subject'];
                        $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                        if ($mail->send()) {
                            userMessage($Status, $Message);
                            die();
                        } else {
                            $Message    = 'فشل إرسال كود التفعيل';
                            $Status     = 422;
                            userMessage($Status, $Message);
                            die();
                        }
                        //************************************* End Send Email Verify ***********************************//

                    } else {
                        $Message    = $data_err;
                        $Status     = 400;
                        userMessage($Status, $Message);
                        die();
                    }
                }
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************** LogIn **********************************************************//
    public function login() // Function Login All Users
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST  //FILTER_SANITIZE_STRING

            $data = [ //Array Data
                "type"      => @$_POST['role'],
                "user_id"   => @strtolower($_POST['user_id']),
                "password"  => @$_POST['password']
            ];
            $data_err = [  //Array Error Data 
                "type_err"      => '',
                "user_id_err"   => '',
                "password_err"  => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount))
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
            }
            if (empty($data['user_id'])) {  //Check SSD OR Email
                $data_err['user_id_err'] = 'برجاء إدخال الرقم القومى أو البريد الإلكترونى';
            } else {
                if (!filter_var($data['user_id'], 274)) {
                    if (!(filter_var($data['user_id'], 257) && strlen($data['user_id']) == 14)) $data_err['user_id_err'] =  'الرقم القومى أو البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_INT OR // FILTER_VALIDATE_EMAIL
                }
            }
            if (empty($data['password'])) $data_err['password_err'] = 'برجاء إدخال كلمة المرور'; //Check Password

            if (
                empty($data_err['type_err'])
                && empty($data_err['user_id_err'])
                && empty($data_err['password_err'])
            ) {

                $data_login = [
                    "type"      => $data['type'],
                    "user_id"   => $data['user_id']
                ];

                @$result = $this->userModel->login($data_login);
                if ($result) {
                    if (password_verify($data['password'], $result->password)) {
                        if ($result->email_isActive == 1) {
                            $name = $result->name;
                            @$token = TokenEncode($result);

                            $data_token = [ // Data For Add Token In User Table
                                "id"    => $result->id,
                                "token" => $token['token'],
                                "type"  => $result->role
                            ];

                            if (@$this->userModel->editToken($data_token)) {

                                $url = URL_PERSON;
                                $data_message = [
                                    "token"         => $token['token'],
                                    "expiredToken"  => $token['exp'],
                                    "name"          => $name,
                                    "ssd"           => $result->ssd,
                                    "type"          => $result->role,
                                    "isActive"      => $result->email_isActive,
                                    "image"         => getImage($result->profile_img, $url)
                                ];

                                if ($result->role == 'doctor' || $result->role == 'pharmacist') {
                                    @$isVerify = $this->userModel->getActivation($result->id, $result->role);
                                    if ($isVerify) {
                                        if ($isVerify->isActive == 0) {
                                            $status_active = 'waiting';
                                        } elseif ($isVerify->isActive == 1) {
                                            $status_active = 'success';
                                        } else {
                                            $status_active = 'error';
                                        }
                                        array_push($data_message, $data_message['isVerify'] = $status_active);
                                        unset($data_message['0']);
                                    } else {
                                        array_push($data_message, $data_message['isVerify'] = 'none');
                                        unset($data_message['0']);
                                    }
                                }

                                //************************************* Send Email Alert ***********************************//
                                $data_mail = [  // Data Email
                                    "type"          => $result->role,
                                    "user_name"     => $name,
                                    "email"         => $result->email,
                                    "password_edit" => @$_POST['password_edit']
                                ];
                                $mail_data = loginEmailBody($data_mail);  //Function To Get Email Data
                                @require_once('../app/helpers/email/mail.php');
                                $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                                $mail->addAddress($mail_data['email']);
                                $mail->Subject = $mail_data['subject'];
                                $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);
                                @($mail->send());

                                //************************************* End Send Email Alert ***********************************//

                                $Message    = 'تم تسجيل الدخول بنجاح';
                                $Status     = 200;
                                userMessage($Status, $Message, $data_message);
                                die();
                            } else {
                                $Message    = 'فشل تحديث الرمز';
                                $Status     = 422;
                                userMessage($Status, $Message);
                                die();
                            }
                        } else {
                            @$new_code = random_int(100000, 999999);
                            $data_active = [
                                "type"  => $data['type'],
                                "id"    => $result->id,
                                "code"  => $new_code
                            ];
                            if (!$this->userModel->resetCode($data_active)) {
                                $Message    = 'الرجاء المحاولة فى وقت لأحق';
                                $Status     = 422;
                                userMessage($Status, $Message);
                                die();
                            }
                            $data_email = [
                                "type"      => $result->role,
                                "user_name" => $result->name,
                                "email"     => $result->email,
                                "number"    => $new_code
                            ];
                            $mail_data = registerEmailBody($data_email);  //Function To Get Email Data
                            @require_once('../app/helpers/email/mail.php');
                            $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                            $mail->addAddress($mail_data['email']);
                            $mail->Subject = $mail_data['subject'];
                            $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                            if ($mail->send()) {
                                $Message    = 'يجب تفعيل البريد الإلكترونى';
                                $Status     = 400;
                                userMessage($Status, $Message, ["isActive" => $result->email_isActive]);
                                die();
                            } else {
                                $Message    = 'فشل إرسال كود التفعيل';
                                $Status     = 422;
                                userMessage($Status, $Message);
                                die();
                            }
                        }
                    } else {
                        $data_err['password_err'] = 'كلمة المرور غير صحيحة';
                        $Message    = $data_err;
                        $Status     = 400;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {
                    $data_err['user_id_err'] = 'الرقم القومى أو البريد الإلكترونى غير صحيح';
                    $Message    = $data_err;
                    $Status     = 400;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************** LogOut ***********************************************//
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id"    => $check_token['id'],
                "type"  => $check_token['type'],
                "token" => null
            ];
            if (@$this->userModel->editToken($data)) {
                session_unset();
                session_destroy();
                $Message    = 'تم تسجيل الخروج';
                $Status     = 200;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //**************************************** Active Email **********************************************//
    public function active_email()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING
            $data = [
                "type"  => @$_POST['role'],
                "email" => @strtolower($_POST['email']),
                "code"  => @$_POST['code']
            ];
            $data_err = [
                "type_err"  => '',
                "email_err" => '',
                "code_err"  => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount)) {
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
                    $Message    = $data_err;
                    $Status     = 400;
                    die(userMessage($Status, $Message));  //Send Message
                } else {
                    if (empty($data['email'])) {
                        $data_err['email_err'] = 'برجاء إدخال البريد الإلكترونى';
                    } else {
                        if (!filter_var($data['email'], 274)) {
                            $data_err['email_err'] = 'البريد الإلكترونى غير صالح';  // FILTER_VALIDATE_EMAIL
                        } else {
                            if ($this->userModel->getUserEmail($data['email'], $data['type'])) {
                                if (empty($data['code'])) { // Check Code
                                    $data_err['code_err'] = 'برجاء إدخال الكود';
                                } else {
                                    if (!filter_var($data['code'], 257) || strlen($data['code']) != 6) $data_err['code_err'] = 'الكود غير صالح';  // FILTER_VALIDATE_INT
                                }
                            } else {
                                $data_err['email_err'] = 'البريد الإلكترونى غير صحيح';
                            }
                        }
                    }
                }
            }

            if (
                empty($data_err['type_err'])
                && empty($data_err['email_err'])
                && empty($data_err['code_err'])
            ) {
                $data_code = [
                    "type"      => $data['type'],
                    "user_id"   => $data['email']
                ];
                @$result = $this->userModel->login($data_code);
                if ($result) {
                    $code_user = $result->security_code;
                    if ($code_user != $data['code']) {
                        $Message    = 'الكود غير صحيح';
                        $Status     = 400;
                        userMessage($Status, $Message);
                        die();
                    } else {
                        @$new_code = random_int(100000, 999999);
                        $data_active = [
                            "type"  => $data['type'],
                            "email" => $data['email'],
                            "code"  => $new_code
                        ];

                        if (@$this->userModel->activeEmail($data_active)) {
                            $Message    = 'تم تفعيل البريد الإلكترونى';
                            $Status     = 201;
                            userMessage($Status, $Message);
                            die();
                        } else {
                            $Message    = 'الرجاء المحاولة فى وقت لأحق';
                            $Status     = 422;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*********************************************** View Profile ****************************************************************//

    public function profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id"    => $check_token['id'],
                "type"  => $check_token['type']
            ];

            @$profile = $this->userModel->viewProfile($data);

            if ($data['type'] == 'patient') {
                @$number = $this->userModel->numberPatient($data['id']);
            } elseif ($data['type'] == 'doctor') {
                @$number = $this->userModel->numberDoctor($data['id']);
            } elseif ($data['type'] == 'pharmacist') {
                @$number = $this->userModel->numberPharmacist($data['id']);
            } else {
                @$number = $this->userModel->numberAssistant($data['id']);
            }

            if ($profile) {
                $url = URL_PERSON;
                $data_new = messageProfile($profile, $url, $number); // Determind Data User
                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_new);
                die();
            } else {
                $Message = 'لا يوجد البيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //******************************************************* Edit Password **********************************************************//
    public function edit_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [
                "id"                => $check_token['id'],
                "type"              => $check_token['type'],
                "password"          => @$_POST['password'],
                "confirm_password"  => @$_POST['confirm_password']
            ];

            $data_err = [
                "password_err"          => '',
                "confirm_password_err"  => ''
            ];

            if (empty($data['password'])) {
                $data_err['password_err'] = 'برجاء إدخال كلمة المرور الجديدة';
            } else {
                if (strlen($data['password']) < 6) $data_err['password_err'] = 'كلمة المرور يجب الأ تقل عن 6 عناصر';
            }
            if (empty($data['confirm_password'])) {
                $data_err['confirm_password_err'] = 'برجاء تأكيد كلمة المرور الجديدة';
            } else {
                if ($data['password'] != $data['confirm_password']) $data_err['confirm_password_err'] = 'كلمة المرور غير متطابقة';
            }

            if (empty($data_err['password_err']) && empty($data_err['confirm_password_err'])) {

                $password_hash = password_hash($data['password'], "2y");

                $data_password = [
                    "id"        => $check_token['id'],
                    "type"      => $check_token['type'],
                    "password"  => $password_hash
                ];

                if (@$this->userModel->editPassword($data_password)) {

                    $Message    = 'تم تعديل كلمة المرور بنجاح';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************ Edit Profile ***************************************************************//
    public function edit_profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [
                "id"            => $check_token['id'],
                "type"          => $check_token['type'],
                "phone_number"  => @$_POST['phone_number'],
                "governorate"   => @$_POST['governorate'],
                "weight"        => @$_POST['weight'],
                "height"        => @$_POST['height']
            ];

            $data_err = [
                "phone_number_err"  => '',
                "governorate_err"   => '',
                "weight_err"        => '',
                "height_err"        => ''
            ];

            if (empty($data['phone_number'])) {
                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
            } else {
                if (!filter_var($data['phone_number'], 519) || strlen($data['phone_number']) != 11) {
                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                } else {
                    $result = $this->userModel->getUserPhone($data['phone_number'], $data['type']);
                    if ($result) {
                        if ($data['id'] != $result->id) $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                    }
                }
            }

            if (empty($data['governorate'])) $data_err['governorate_err'] = 'برجاء إختيار المحافظة';

            if ($data['type'] == 'patient') {
                if (empty($data['weight'])) $data_err['weight_err'] = 'برجاء إدخال الوزن';
                if (empty($data['height'])) $data_err['height_err'] = 'برجاء إدخال الطول';
            }

            if (
                empty($data_err['phone_number_err'])
                && empty($data_err['governorate_err'])
                && empty($data_err['weight_err'])
                && empty($data_err['height_err'])
            ) {
                switch ($data['type']) {
                    case 'patient':
                        $data_patient = [
                            "id"            => $data['id'],
                            "phone_number"  => $data['phone_number'],
                            "governorate"   => $data['governorate'],
                            "weight"        => $data['weight'],
                            "height"        => $data['height']
                        ];
                        break;
                    default:
                        $data_other = [
                            "id"            => $data['id'],
                            "type"          => $data['type'],
                            "phone_number"  => $data['phone_number'],
                            "governorate"   => $data['governorate']
                        ];
                }

                if ($data['type'] == 'patient') {
                    if ($this->userModel->editPatient($data_patient)) {
                        $Message    = 'تم التعديل بنجاح';
                        $Status     = 201;
                        userMessage($Status, $Message);
                        die();
                    } else {
                        $Message    = 'الرجاء المحاولة فى وقت لأحق';
                        $Status     = 400;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {
                    if ($this->userModel->editOther($data_other)) {
                        $Message    = 'تم التعديل بنجاح';
                        $Status     = 201;
                        userMessage($Status, $Message);
                        die();
                    } else {
                        $Message    = 'الرجاء المحاولة فى وقت لأحق';
                        $Status     = 400;
                        userMessage($Status, $Message);
                        die();
                    }
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************************* Add Image *****************************************************//
    public function add_image()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id"            => $check_token['id'],
                "type"          => $check_token['type'],
                "image_name"    => $_FILES['image']["name"],
                "image_size"    => $_FILES['image']["size"],
                "tmp_name"      => $_FILES['image']["tmp_name"],
            ];
            $data_err = [
                "image_err" => '',
            ];

            if (empty($data['image_name'])) {
                $data_err['image_err'] = 'برجاء تحميل صورة';
            } else {
                if ($data['image_size'] > 4000000) $data_err['image_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';  //To Specify The Image Size  < 4M
            }
            if (empty($data_err['image_err'])) {

                @$result = $this->userModel->getSSD($data['type'], $data['id']);

                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                $data_image = [
                    "type"  => $check_token['type'],
                    "ssd"   => $result->ssd,
                    "name"  => $data['image_name'],
                    "tmp"   => $data['tmp_name'],
                    "url"   => URL_PERSON
                ];

                @$url_img = addImageProfile($data_image);

                if (!$url_img) {
                    $Message    = 'صيغة الملف غير مدعوم';
                    $Status     = 415;
                    userMessage($Status, $Message);
                    die();
                }
                $data_url = [
                    "id"    => $check_token['id'],
                    "type"  => $check_token['type'],
                    "image" => $url_img
                ];
                if ($this->userModel->editImage($data_url)) {
                    @$new_image = $this->userModel->getPlace($data['type'], $data['id']);
                    if ($new_image) {
                        $url_img = getImage($new_image->profile_img, $data_image['url']);
                    } else {
                        $url_img = null;
                    }
                    $Message    = 'تم تحديث صورة الملف الشخصى';
                    $Status     = 201;
                    userMessage($Status, $Message, $url_img);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Delete Image ***********************************************************//
    public function remove_image()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id"    => $check_token['id'],
                "type"  => $check_token['type'],
            ];

            @$result    = $this->userModel->getSSD($data['type'], $data['id']);
            @$user      = $this->userModel->getPlace($data['type'], $data['id']);

            if (!($result || $user)) {
                $Message    = 'الرجاء المحاولة فى وق لأحق';
                $Status     = 422;
                userMessage($Status, $Message);
                die();
            }
            $data_image = [
                "type"  => $check_token['type'],
                "ssd"   => $result->ssd,
                "url"   => URL_PERSON
            ];

            if ($user->gender == 'ذكر' || $user->gender == 'male') {
                $image = DF_IMAGE_PERSON_MALE;
            } else {
                $image = DF_IMAGE_PERSON_FEMALE;
            }

            if ($user->profile_img != $image) {
                @$url_img = removeImage($data_image);
                if (!$url_img) {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            }

            $data_url = [
                "id"        => $check_token['id'],
                "type"      => $check_token['type'],
                "image"     => $image
            ];

            if ($this->userModel->editImage($data_url)) {
                @$new_image = $this->userModel->getPlace($data['type'], $data['id']);
                if ($new_image) {
                    $url_img = getImage($new_image->profile_img, $data_image['url']);
                } else {
                    $url_img = null;
                }
                $Message    = 'تم حذف صورة الملف الشخصى';
                $Status     = 201;
                userMessage($Status, $Message, $url_img);
                die();
            } else {
                $Message    = 'الرجاء المحاولة فى وق لأحق';
                $Status     = 422;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************************* Send Message ***********************************************************//
    public function message()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [
                "id"        => $check_token['id'],
                "type"      => $check_token['type'],
                "message"   => @$_POST['message'],
            ];
            $data_err = [
                "message_err" => '',
            ];

            if (empty($data['message'])) {
                $data_err['message_err'] = 'برجاء إدخال رسالتك';
            } else {
                @$get_data = $this->userModel->viewProfile($data);
                if (!$get_data) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $data_message = [
                        "name"      => $get_data->name,
                        "ssd"       => $get_data->ssd,
                        "email"     => $get_data->email,
                        "role"      => $check_token['type'],
                        "message"   => $data['message'],
                    ];
                }
            }

            if (empty($data_err['message_err'])) {
                if ($this->userModel->addMessageUser($data_message)) {

                    $Message = 'تم الإرسال للمختص للمراجعة';
                    $Status = 201;
                    userMessage($Status, $Message);  //Send Message

                    //************************************* Send Email Alert ***********************************//
                    $data_mail = [  // Data Email
                        "type"      => $check_token['type'],
                        "user_name" => $get_data->name,
                        "email"     => $get_data->email,
                    ];
                    $mail_data = supportEmailBody($data_mail);  //Function To Get Email Data
                    @require_once('../app/helpers/email/mail.php');
                    $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                    $mail->addAddress($mail_data['email']);
                    $mail->Subject = $mail_data['subject'];
                    $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);
                    @($mail->send());
                    die();
                    //************************************* End Send Email Alert ***********************************//
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //********************************************************** Forget Password *********************************************************//
    public function forget_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [ //Array Data
                "type"      => @$_POST['role'],
                "user_id"   => @strtolower($_POST['user_id']),
            ];
            $data_err = [  //Array Error Data 
                "type_err"      => '',
                "user_id_err"   => '',
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount))
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
            }
            if (empty($data['user_id'])) {  //Check SSD OR Email
                $data_err['user_id_err'] = 'برجاء إدخال الرقم القومى أو البريد الإلكترونى';
            } else {
                if (!filter_var($data['user_id'], 274)) {
                    if (!(filter_var($data['user_id'], 257) && strlen($data['user_id']) == 14)) $data_err['user_id_err'] =  'الرقم القومى أو البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_INT OR // FILTER_VALIDATE_EMAIL
                }
            }
            if (
                empty($data_err['type_err'])
                && empty($data_err['user_id_err'])
            ) {

                $data_code = [
                    "type"      => $data['type'],
                    "user_id"   => $data['user_id']
                ];

                @$result = $this->userModel->login($data_code);
                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                if ($result->email_isActive == 1) {
                    //************************************* Send Email Alert ***********************************//
                    $data_mail = [
                        "type"      => $data['type'],
                        "user_name" => $result->name,
                        "email"     => $result->email,
                        "code"      => $result->security_code
                    ];
                    @$mail_data = resetPasswordEmail($data_mail);  //Function To Get Email Data
                    @require_once('../app/helpers/email/mail.php');
                    $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                    $mail->addAddress($mail_data['email']);
                    $mail->Subject = $mail_data['subject'];
                    $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                    if ($mail->send()) {
                        $Message    = 'تم إرسال كود إعادة التعيين عبر البريد الإلكترونى المرتبط بالحساب';
                        $Status     = 200;
                        userMessage($Status, $Message);
                        die();
                    } else {
                        $Message    = 'الرجاء المحاولة فى وقت لأحق';
                        $Status     = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                    //*********************************************** End Send Email ****************************************************//
                } else {
                    $Message    = 'البريد الإلكترونى غير مفعل';
                    $Status     = 400;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************************* Code Password *********************************************************//
    public function code_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [ //Array Data
                "type"      => @$_POST['role'],
                "user_id"   => @strtolower($_POST['user_id']),
                "code"      => @$_POST['code']
            ];
            $data_err = [  //Array Error Data 
                "type_err"      => '',
                "user_id_err"   => '',
                "code_err"      => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount))
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
            }
            if (empty($data['user_id'])) {  //Check SSD OR Email
                $data_err['user_id_err'] = 'برجاء إدخال الرقم القومى أو البريد الإلكترونى';
            } else {
                if (!filter_var($data['user_id'], 274)) {
                    if (!(filter_var($data['user_id'], 257) && strlen($data['user_id']) == 14)) $data_err['user_id_err'] =  'الرقم القومى أو البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_INT OR // FILTER_VALIDATE_EMAIL
                }
            }

            if (empty($data['code'])) {
                $data_err['code_err'] = 'برجاء إدخال الكود';
            } else {
                if (!(filter_var($data['code'], 257) && strlen($data['code']) == 6)) $data_err['code_err'] = 'الكود غير صالح'; // FILTER_VALIDATE_INT 
            }

            if (empty($data_err['type_err']) && empty($data_err['user_id_err']) && empty($data_err['code_err'])) {
                $get_code = [
                    "type"      => $data['type'],
                    "user_id"   => $data['user_id'],
                ];
                @$result = $this->userModel->login($get_code);
                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                if ($result->security_code != $data['code']) {
                    $Message    = 'الكود غير صحيح';
                    $Status     = 400;
                    userMessage($Status, $Message);
                    die();
                }

                $new_code = random_int(100000, 999999);
                $data_reset = [
                    "type"  => $data['type'],
                    "id"    => $result->id,
                    "code"  => $new_code
                ];

                if (@$this->userModel->resetCode($data_reset)) {
                    $Message    = 'تم التحقق بنجاح';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //********************************************************* Reset Password ********************************************************//

    public function reset_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [
                "user_id"           => @strtolower($_POST['user_id']),
                "type"              => @$_POST['role'],
                "password"          => @$_POST['password'],
                "confirm_password"  => @$_POST['confirm_password']
            ];

            $data_err = [
                "user_id"               => '',
                "type"                  => '',
                "password_err"          => '',
                "confirm_password_err"  => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount))
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
            }
            if (empty($data['user_id'])) {  //Check SSD OR Email
                $data_err['user_id_err'] = 'برجاء إدخال الرقم القومى أو البريد الإلكترونى';
            } else {
                if (!filter_var($data['user_id'], 274)) {
                    if (!(filter_var($data['user_id'], 257) && strlen($data['user_id']) == 14)) $data_err['user_id_err'] =  'الرقم القومى أو البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_INT OR // FILTER_VALIDATE_EMAIL
                }
            }

            if (empty($data['password'])) {
                $data_err['password_err'] = 'برجاء إدخال كلمة المرور الجديدة';
            } else {
                if (strlen($data['password'] < 6)) $data_err['password_err'] = 'كلمة المرور يجب الأ تقل عن 6 عناصر';
            }

            if (empty($data['confirm_password'])) {
                $data_err['confirm_password_err'] = 'برجاء تأكيد كلمة المرور الجديدة';
            } else {
                if ($data['password'] != $data['confirm_password']) $data_err['confirm_password_err'] = 'كلمة المرور غير متطابقة';
            }

            if (
                empty($data_err['password_err'])
                && empty($data_err['confirm_password_err'])
                && empty($data_err['user_id_err'])
                && empty($data_err['type_err'])
            ) {

                $password_hash = password_hash($data['password'], "2y");

                $data_password = [
                    "id"        => $data['user_id'],
                    "type"      => $data['type'],
                    "password"  => $password_hash
                ];

                if (@$this->userModel->editPassword($data_password)) {

                    $Message    = 'تم تعديل كلمة المرور بنجاح';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** Active Image Person **************************************************************//

    public function active_image_person()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id"            => $check_token['id'],
                "type"          => $check_token['type'],
                "front_name"    => $_FILES['front_nationtional_card']["name"],
                "back_name"     => $_FILES['back_nationtional_card']["name"],
                "grad_name"     => $_FILES['graduation_cer']["name"],
                "card_name"     => $_FILES['card_id_img']["name"],
                "front_size"    => $_FILES['front_nationtional_card']["size"],
                "back_size"     => $_FILES['back_nationtional_card']["size"],
                "grad_size"     => $_FILES['graduation_cer']["size"],
                "card_size"     => $_FILES['card_id_img']["size"],
                "front_tmp"     => $_FILES['front_nationtional_card']["tmp_name"],
                "back_tmp"      => $_FILES['back_nationtional_card']["tmp_name"],
                "grad_tmp"      => $_FILES['graduation_cer']["tmp_name"],
                "card_tmp"      => $_FILES['card_id_img']["tmp_name"]
            ];
            $data_err = [
                "front_err" => '',
                "back_err"  => '',
                "grad_err"  => '',
                "card_err"  => '',
            ];

            $acount_type = ['doctor', 'pharmacist'];

            if (!in_array($data['type'], $acount_type)) {
                $Message    = 'نوع حسابك غير مطالب بهذة العملية';
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['front_name'])) {
                $data_err['front_err'] = 'برجاء تحميل صورة البطاقة الأمامية';
            } else {
                if ($data['front_size'] > 4000000) $data_err['front_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';
            }
            if (empty($data['back_name'])) {
                $data_err['back_err'] = 'برجاء تحميل صورة البطاقة الخلفية';
            } else {
                if ($data['back_size'] > 4000000) $data_err['back_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';
            }
            if (empty($data['grad_name'])) {
                $data_err['grad_err'] = 'برجاء تحميل صورة شهادة التخرج';
            } else {
                if ($data['grad_size'] > 4000000) $data_err['grad_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';
            }
            if (empty($data['card_name'])) {
                $data_err['card_err'] = 'برجاء تحميل صورة الكارنية الطبى';
            } else {
                if ($data['card_size'] > 4000000) $data_err['card_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';
            }

            if (
                empty($data_err['front_err'])
                && empty($data_err['back_err'])
                && empty($data_err['grad_err'])
                && empty($data_err['card_err'])
            ) {

                @$result = $this->userModel->getSSD($data['type'], $data['id']);

                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                $data_image = [
                    "type"          => $data['type'],
                    "ssd"           => $result->ssd,
                    "front_name"    => $data['front_name'],
                    "back_name"     => $data['back_name'],
                    "grad_name"     => $data['grad_name'],
                    "card_name"     => $data['card_name'],
                    "front_tmp"     => $data['front_tmp'],
                    "back_tmp"      => $data['back_tmp'],
                    "grad_tmp"      => $data['grad_tmp'],
                    "card_tmp"      => $data['card_tmp'],
                    "url"           => URL_ACTIVATION_PERSON
                ];

                @$url_img = addImageActivePerson($data_image);

                if (!$url_img) {
                    $Message    = 'صيغة الملف غير مدعوم';
                    $Status     = 415;
                    userMessage($Status, $Message);
                    die();
                }
                $data_url = [
                    "id"        => $check_token['id'],
                    "type"      => $check_token['type'],
                    "image"     => $url_img
                ];
                if ($this->userModel->editImageActivationPerson($data_url)) {
                    $Message    = 'تم التقديم للمراجعة';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** Active Image Place **************************************************************//

    public function active_image_place($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            $_GET = filter_input_array(1, 513); // INPUT_GET //FILTER_SANITIZE_STRING

            $data = [
                "id"            => $check_token['id'],
                "place_id"      => @$id,
                "place_type"    => $_GET['place_role'],
                "type"          => $check_token['type'],
                "image_name"    => $_FILES['license_img']["name"],
                "image_size"    => $_FILES['license_img']["size"],
                "tmp_name"      => $_FILES['license_img']["tmp_name"]
            ];
            $data_err = [
                "image_err"         => '',
                "place_id_err"      => '',
                "place_role_err"    => ''
            ];

            $acount_type = ['doctor', 'pharmacist'];

            if (!in_array($data['type'], $acount_type)) {
                $Message    = 'ليس لديك الصلاحية';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['place_id'])) {
                $data_err['place_id_err'] = 'يجب إدخال معرف المكان';
            } else {
                if (!filter_var($data['place_id'], 257)) {
                    $data_err['place_id_err'] = 'المعرف غير صالح';
                }
            }
            $place_type = ['clinic', 'pharmacy'];
            if (empty($data['place_type'])) {
                $data_err['place_role_err'] = 'برجاء إدخال نوع المكان';
            } else {
                if (!in_array($data['place_type'], $place_type)) $data_err['place_role_err'] = 'نوع المكان غير مدعوم';
            }
            if (empty($data['image_name'])) {
                $data_err['image_err'] = 'برجاء تحميل صورة';
            } else {
                if ($data['image_size'] > 4000000) $data_err['image_err'] = '(4M)يجب أن يكون حجم الصورة أقل من';  //To Specify The Image Size  < 4M
            }
            if (
                empty($data_err['place_id_err'])
                && empty($data_err['image_err'])
                && empty($data_err['place_role_err'])
            ) {

                @$result = $this->userModel->getPlace($data['place_type'], $data['place_id']);

                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                $data_image = [
                    "type"  => $data['place_type'],
                    "ssd"   => $result->ser_id,
                    "name"  => $data['image_name'],
                    "tmp"   => $data['tmp_name'],
                    "url"   => URL_ACTIVATION_PLACE
                ];

                @$url_img = addImageProfile($data_image);

                if (!$url_img) {
                    $Message    = 'صيغة الملف غير مدعوم';
                    $Status     = 415;
                    userMessage($Status, $Message);
                    die();
                }
                $data_url = [
                    "id"        => $data['place_id'],
                    "type"      => $data['place_type'],
                    "image"     => $url_img
                ];
                if ($this->userModel->editImageActivationPlace($data_url)) {
                    $Message    = 'تم التقديم للمراجعة';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وق لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** View Video **************************************************************//
    public function view_video()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->userModel->getVideo($check_token['type']);
            if (!$result) {
                $Message    = 'لم يتم العثور على بيانات';
                $Status     = 204;
                userMessage($Status, $Message);
                die();
            }

            $data_video = [
                "name"  => $result->video,
                "url"   => URL_VIDEO
            ];

            @$url_video = getVideo($data_video);
            if (!$url_video) {
                $Message    = 'الرجاء المحاولة فى وقت لأحق';
                $Status     = 422;
                userMessage($Status, $Message);
                die();
            }

            $Message    = 'تم جلب البيانات بنجاح';
            $Status     = 200;
            userMessage($Status, $Message, $url_video);
            die();
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //********************************************** Resend Code Activation Email **************************************************************//

    public function resend_active_code()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [ //Array Data
                "type"      => @$_POST['role'],
                "user_id"   => @strtolower($_POST['user_id'])
            ];
            $data_err = [  //Array Error Data 
                "type_err"      => '',
                "user_id_err"   => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount))
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
            }
            if (empty($data['user_id'])) {  //Check SSD OR Email
                $data_err['user_id_err'] = 'برجاء إدخال الرقم القومى أو البريد الإلكترونى';
            } else {
                if (!filter_var($data['user_id'], 274)) {
                    if (!(filter_var($data['user_id'], 257) && strlen($data['user_id']) == 14)) $data_err['user_id_err'] =  'الرقم القومى أو البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_INT OR // FILTER_VALIDATE_EMAIL
                }
            }

            if (empty($data_err['type_err']) && empty($data_err['user_id_err'])) {
                $get_code = [
                    "type"      => $data['type'],
                    "user_id"   => $data['user_id'],
                ];
                @$result = $this->userModel->login($get_code);
                if (!$result) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                @$new_code = random_int(100000, 999999);
                $data_active = [
                    "type"  => $result->role,
                    "id"    => $result->id,
                    "code"  => $new_code
                ];
                if (!$this->userModel->resetCode($data_active)) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
                $data_email = [
                    "type"      => $result->role,
                    "user_name" => $result->name,
                    "email"     => $result->email,
                    "number"    => $new_code
                ];
                $mail_data = registerEmailBody($data_email);  //Function To Get Email Data
                @require_once('../app/helpers/email/mail.php');
                $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                $mail->addAddress($mail_data['email']);
                $mail->Subject = $mail_data['subject'];
                $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                if ($mail->send()) {
                    $Message    = 'تم إرسال كود التفعيل';
                    $Status     = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message    = $data_err;
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************** Doctor Specialist ****************************************************//
    public function doctor_specialist()
    {
        @$result = $this->userModel->getSpecialist();
        if (!$result) {
            $Message    = 'لم يتم العثور على بيانات';
            $Status     = 204;
            userMessage($Status, $Message);
            die();
        }

        $Message    = 'تم جلب البيانات بنجاح';
        $Status     = 200;
        userMessage($Status, $Message, $result);
        die();
    }

    //********************************************************** Get Account Status *************************************************//
    public function view_account_status()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            @$check_token = $this->tokenVerify();
            if (!$check_token) {
                $Message    = 'الرجاء تسجيل الدخول';
                $Status     = 401;
                userMessage($Status, $Message);
                die();
            }

            if ($check_token['type'] == 'doctor' || $check_token['type'] == 'pharmacist') {
                @$isVerify = $this->userModel->getActivation($check_token['id'], $check_token['type']);
                if ($isVerify) {
                    if ($isVerify->isActive == 0) {
                        $status_active = 'waiting';
                    } elseif ($isVerify->isActive == 1) {
                        $status_active = 'success';
                    } else {
                        $status_active = 'error';
                    }
                } else {
                    $status_active = 'none';
                }
            }

            $Message    = 'تم جلب البيانات بنجاح';
            $Status     = 200;
            userMessage($Status, $Message, $status_active);
            die();
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }
}

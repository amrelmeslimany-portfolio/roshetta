<?php

class Users extends Controller
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        echo 'Users';
    }

    //***************************************************** Token Verify ***************************************************//

    public function tokenVerify($Auth)
    {
        @$Auth = explode(" ", $Auth)[1]; // Get Token From Auth
        @$token_out = TokenDecode($Auth);
        if (!$token_out) {
            return false;
        }
        @$token_in = $this->userModel->getToken($token_out);
        if (!$token_in) {
            return false;
        }
        if ($token_in->token !== $Auth) {
            return false;
        } else {
            return $token_out;
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
                "type" => @$_POST['role'],
                "name" => @$_POST['first_name'] . ' ' . @$_POST['last_name'],
                "email" => @$email,
                "ssd" => @$ssd,
                "phone_number" => @$phone_number,
                "birth_date" => @$_POST['birth_date'],
                "gender" => @$_POST['gender'],
                "governorate" => @$_POST['governorate'],
                "password" => @$_POST['password'],
                "confirm_password" => @$_POST['confirm_password'],
            ];
            $data_err = [ //Array Error Data
                "type_err" => '',
                "name_err" => '',
                "email_err" => '',
                "ssd_err" => '',
                "phone_number_err" => '',
                "birth_date_err" => '',
                "gender_err" => '',
                "governorate_err" => '',
                "weight_err" => '',
                "height_err" => '',
                "specialist_err" => '',
                "password_err" => '',
                "confirm_password_err" => ''
            ];

            if (empty($data['type'])) {  // Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
                $Message = $data_err;
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message Alert
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount)) {
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
                    $Message = $data_err;
                    $Status = 400;
                    die(userMessage($Status, $Message));  //Send Message Alert
                } else {
                    if (empty($data['name'])) $data_err['name_err'] = 'برجاء إدخال الإسم';  //Check Name

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
                        if (!filter_var($data['ssd'], 257) || strlen($data['ssd']) !== 14) {
                            $data_err['ssd_err'] = 'الرقم القومى غير صالح';  // FILTER_VALIDATE_INT
                        } else {
                            if ($this->userModel->getUserSSD($data['ssd'], $data['type'])) $data_err['ssd_err'] = 'الرقم القومى موجود من قبل';
                        }
                    }

                    if (empty($data['phone_number'])) {  // Check Phone
                        $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                    } else {
                        if (strlen($data['phone_number']) !== 11) {
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
                        if ($data['password'] !== $data['confirm_password']) $data_err['confirm_password_err'] = 'كلمة المرور غير متطابقة'; //Check Validate Password
                    }

                    switch ($data['type']) {
                        case 'patient':
                            $data_other = [
                                "weight" => $_POST['weight'],
                                "height" => $_POST['height'],
                                "weight_err" => '',
                                "height_err" => ''
                            ];
                            if (empty($data_other['weight'])) $data_err['weight_err'] = 'برجاء إدخال الوزن'; //Check Weight
                            if (empty($data_other['height'])) $data_err['height_err'] = 'برجاء إدخال الطول'; //Check Hight
                            break;
                        case 'doctor':
                            $data_other = [
                                "specialist" => $_POST['specialist'],
                                "specialist_err" => '',
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
                        @$password = password_hash($data['password'], "2y"); //PASSWORD_DEFAULT Hash
                        @$security_code = random_int(100000, 999999);  // Create Random Number

                        switch ($data['type']) {
                            case 'patient':
                                $data = [
                                    "type" => $data['type'],
                                    "name" => $data['name'],
                                    "email" => $data['email'],
                                    "ssd" => $data['ssd'],
                                    "phone_number" => $data['phone_number'],
                                    "birth_date" => $data['birth_date'],
                                    "gender" => $data['gender'],
                                    "governorate" => $data['governorate'],
                                    "weight" => $data_other['weight'],
                                    "height" => $data_other['height'],
                                    "password" => $password,
                                    "security_code" => $security_code
                                ];
                                if (@$this->userModel->registerPatient($data)) {
                                    $Message = 'تم التسجيل بنجاح';
                                    $Status = 201;
                                } else {
                                    $Message = 'فشل التسجيل';
                                    $Status = 422;
                                    die(userMessage($Status, $Message));  //Send Message
                                }
                                break;
                            case 'doctor':
                                $data = [
                                    "type" => $data['type'],
                                    "name" => $data['name'],
                                    "email" => $data['email'],
                                    "ssd" => $data['ssd'],
                                    "phone_number" => $data['phone_number'],
                                    "birth_date" => $data['birth_date'],
                                    "gender" => $data['gender'],
                                    "governorate" => $data['governorate'],
                                    "specialist" => $data_other['specialist'],
                                    "password" => $password,
                                    "security_code" => $security_code
                                ];
                                if (@$this->userModel->registerDoctor($data)) {
                                    $Message = 'تم التسجيل بنجاح';
                                    $Status = 201;
                                } else {
                                    $Message = 'فشل التسجيل';
                                    $Status = 422;
                                    die(userMessage($Status, $Message));  //Send Message
                                }
                                break;
                            default:
                                $data = [
                                    "type" => $data['type'],
                                    "name" => $data['name'],
                                    "email" => $data['email'],
                                    "ssd" => $data['ssd'],
                                    "phone_number" => $data['phone_number'],
                                    "birth_date" => $data['birth_date'],
                                    "gender" => $data['gender'],
                                    "governorate" => $data['governorate'],
                                    "password" => $password,
                                    "security_code" => $security_code
                                ];
                                if ($data['type'] == 'admin') {
                                    if (isset($_SESSION['user'])) {
                                        $type = $_SESSION['user']['type'];
                                        if ($type == 'admin') {
                                            if (@$this->userModel->registerOther($data)) {
                                                $Message = 'تم التسجيل بنجاح';
                                                $Status = 201;
                                            } else {
                                                $Message = 'فشل التسجيل';
                                                $Status = 422;
                                                die(userMessage($Status, $Message));  //Send Message
                                            }
                                        } else {
                                            $Message = 'ليس لديك الصلاحية';
                                            $Status = 403;
                                            die(userMessage($Status, $Message));  //Send Message
                                        }
                                    } else {
                                        $Message = 'ليس لديك الصلاحية';
                                        $Status = 403;
                                        die(userMessage($Status, $Message));  //Send Message
                                    }
                                } else {
                                    if (@$this->userModel->registerOther($data)) {
                                        $Message = 'تم التسجيل بنجاح';
                                        $Status = 201;
                                    } else {
                                        $Message = 'فشل التسجيل';
                                        $Status = 422;
                                        die(userMessage($Status, $Message));  //Send Message
                                    }
                                }
                        }

                        //************************************* Send Email Verify ***********************************//
                        switch ($data['type']) {
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

                        $data_email = [
                            "hi" => $hi,
                            "user_name" => $data['name'],
                            "email" => $data['email'],
                            "number" => $security_code
                        ];
                        $mail_data = registerEmailBody($data_email);  //Function To Get Email Data
                        @require_once('../app/helpers/email/mail.php');
                        $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                        $mail->addAddress($mail_data['email']);
                        $mail->Subject = $mail_data['subject'];
                        $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                        if ($mail->send()) {
                            userMessage($Status, $Message);  //Send Message
                        } else {
                            $Message = 'فشل إرسال كود التفعيل';
                            $Status = 422;
                            userMessage($Status, $Message);  //Send Message
                        }
                        //************************************* End Send Email Verify ***********************************//

                    } else {
                        $Message = $data_err;
                        $Status = 400;
                        die(userMessage($Status, $Message));  //Send Message
                    }
                }
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            die(userMessage($Status, $Message));  //Send Message
        }
    }

    //************************************** LogIn **********************************************************//
    public function login() // Function Login All Users
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST  //FILTER_SANITIZE_STRING

            $data = [ //Array Data
                "type" => @$_POST['role'],
                "user_id" => @$_POST['user_id'],
                "password" => @$_POST['password']
            ];
            $data_err = [  //Array Error Data 
                "type_err" => '',
                "user_id_err" => '',
                "password_err" => ''
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
                    "type" => $data['type'],
                    "user_id" => $data['user_id']
                ];

                @$result = $this->userModel->login($data_login);
                if ($result) {
                    if (password_verify($data['password'], $result->password)) {
                        if ($result->email_isActive == 1) {
                            $name = $result->name;
                            @$token = TokenEncode($result);

                            $data_token = [ // Data For Add Token In User Table
                                "id" => $result->id,
                                "token" => $token,
                                "type" => $result->role
                            ];

                            if (@$this->userModel->editToken($data_token)) {

                                $_SESSION['user'] = [
                                    "id" => $result->id,
                                    "type" => $result->role
                                ];

                                $data_message = [
                                    "token" => $token,
                                    "name" => $name,
                                    "ssd" => $result->ssd,
                                    "image" => $result->profile_img
                                ];

                                //************************************* Send Email Alert ***********************************//

                                switch ($data['type']) {
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
                                $data_mail = [  // Data Email
                                    "hi" => $hi,
                                    "user_name" => $name,
                                    "email" => $result->email,
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

                                $Message = 'تم تسجيل الدخول بنجاح';
                                $Status = 200;
                                die(userMessage($Status, $Message, $data_message));  //Send Message

                            } else {
                                $Message = 'فشل تحديث الرمز';
                                $Status = 422;
                                die(userMessage($Status, $Message));  //Send Message
                            }
                        } else {
                            $Message = 'يجب تفعيل البريد الإلكترونى';
                            $Status = 202;
                            die(userMessage($Status, $Message));  //Send Message
                        }
                    } else {
                        $data_err['password_err'] = 'كلمة المرور غير صحيحة';
                        $Message = $data_err;
                        $Status = 400;
                        die(userMessage($Status, $Message));  //Send Message
                    }
                } else {
                    $data_err['user_id_err'] = 'الرقم القومى أو البريد الإلكترونى غير صحيح';
                    $Message = $data_err;
                    $Status = 400;
                    die(userMessage($Status, $Message));  //Send Message
                }
            } else {
                $Message = $data_err;
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            die(userMessage($Status, $Message));  //Send Message
        }
    }

    //***************************************************** LogOut ***********************************************//
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_Get = filter_input_array(1, 513); // INPUT_GET    //FILTER_SANITIZE_STRING

            if (empty($_Get['Auth'])) {
                $Message = 'برجاء أدخال الرمز';
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message
            }
            @$check_token = $this->tokenVerify($_Get['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message
            }
            $data = [
                "id" => $check_token['id'],
                "type" => $check_token['type'],
                "token" => null
            ];
            if (@$this->userModel->editToken($data)) {
                session_unset();
                session_destroy();
                $Message = 'تم تسجيل الخروج';
                $Status = 200;
                die(userMessage($Status, $Message));  //Send Message
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            die(userMessage($Status, $Message));  //Send Message
        }
    }

    //**************************************** Active Email **********************************************//
    public function active_email()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, 513); //FILTER_SANITIZE_STRING
            $data = [
                "type" => @$_POST['role'],
                "email" => @$_POST['email'],
                "code" => @$_POST['code']
            ];
            $data_err = [
                "type_err" => '',
                "email_err" => '',
                "code_err" => ''
            ];

            if (empty($data['type'])) {  //Check Type
                $data_err['type_err'] = 'برجاء إدخال نوع الحساب';
                $Message = $data_err;
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message
            } else {
                $typeAccount = ['patient', 'doctor', 'assistant', 'pharmacist', 'admin'];
                if (!in_array($data['type'], $typeAccount)) {
                    $data_err['type_err'] = 'نوع الحساب غير صحيح';
                    $Message = $data_err;
                    $Status = 400;
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
                                    if (!filter_var($data['code'], 257) || strlen($data['code']) !== 6) $data_err['code_err'] = 'الكود غير صالح';  // FILTER_VALIDATE_INT
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
                    "type" => $data['type'],
                    "user_id" => $data['email']
                ];
                @$result = $this->userModel->login($data_code);
                if ($result) {
                    $code_user = $result->security_code;
                    if ($code_user !== $data['code']) {
                        $Message = 'الكود غير صحيح';
                        $Status = 400;
                        die(userMessage($Status, $Message));  //Send Message
                    } else {
                        @$new_code = random_int(100000, 999999);
                        $data_active = [
                            "type" => $data['type'],
                            "email" => $data['email'],
                            "code" => $new_code
                        ];

                        if (@$this->userModel->activeEmail($data_active)) {
                            $Message = 'تم تفعيل البريد الإلكترونى';
                            $Status = 201;
                            die(userMessage($Status, $Message));  //Send Message
                        } else {
                            $Message = 'الرجاء المحاولة فى وقت لأحق';
                            $Status = 422;
                            die(userMessage($Status, $Message));  //Send Message
                        }
                    }
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    die(userMessage($Status, $Message));  //Send Message
                }
            } else {
                $Message = $data_err;
                $Status = 400;
                die(userMessage($Status, $Message));  //Send Message
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            die(userMessage($Status, $Message));  //Send Message
        }
    }
}

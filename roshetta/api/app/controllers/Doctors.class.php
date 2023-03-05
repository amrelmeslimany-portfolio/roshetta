<?php

class Doctors extends Controller
{
    private $doctorModel;
    private $userModel;
    public function __construct()
    {
        $this->doctorModel = $this->model('doctor');
        $this->userModel = $this->model('User'); //New User
    }
    public function document()
    {
        $Message = '(API_Doctors)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#8dfbdfbd-2eb1-4bed-be0b-ab0e39dcb8b3';
        userMessage($Status, $Message, $url);
        die();
    }

    //*************************************************** Token Verify **************************************************************//
    private function tokenVerify($Auth)
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

    //***************************************************************** Add Clinic ***********************************************************//
    public function add_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            if (empty($_POST['Auth'])) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            @$check_token = $this->tokenVerify($_POST['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            @$phone_number  = filter_var($_POST['phone_number'], 519);  //FILTER_SANITIZE_INT
            @$price         = filter_var($_POST['price'], 519);  //FILTER_SANITIZE_INT

            $data = [  //Array Data
                "id" => @$check_token['id'],
                "type" => @$check_token['type'],
                "name" => @$_POST['name'],
                "specialist" => @$_POST['specialist'],
                "price" => @$price,
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [ //Array Error Data
                "name_err" => '',
                "specialist_err" => '',
                "price_err" => '',
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
            ];

            if ($data['type'] !== 'doctor') {
                $Message = 'غير مصرح لك القيام بالإضافة';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            @$get_doctor = $this->doctorModel->getDoctorActivation($data['id']);
            if (!$get_doctor) {
                $Message = 'يجب تنشيط الحساب';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if ($get_doctor->isActive !== 1) {
                $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                $Status = 202;
                userMessage($Status, $Message);
                die();
            }

            @$get_clinic = $this->doctorModel->getClinic($data['id']);
            if (!$get_clinic) {
                $Message = 'الرجاء المحاولة فى وقت لأحق';
                $Status = 422;
                userMessage($Status, $Message);
                die();
            }

            if ($get_clinic >= 2) {
                $Message = 'لايمكنك تسجيل أكثر من 2 عيادة';
                $Status = 202;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['specialist'])) $data_err['specialist_err'] = 'برجاء إدخال التخصص';
            if (empty($data['name'])) $data_err['name_err'] = 'برجاء إدخال إسم العيادة';
            if (empty($data['price'])) $data_err['price_err'] = 'برجاء إدخال سعر الكشف';

            if (empty($data['phone_number'])) {  // Check Phone
                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
            } else {
                if (strlen($data['phone_number']) !== 11) {
                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                } else {
                    if ($this->userModel->getUserPhone($data['phone_number'], 'clinic')) $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                }
            }

            if (empty($data['start_working'])) $data_err['start_working_err'] = 'برجاء إدخال موعد الفتح';
            if (empty($data['end_working'])) $data_err['end_working_err'] = 'برجاء إدخال موعد الغلق';
            if (empty($data['governorate'])) $data_err['governorate_err'] = 'برجاء إدخال المحافظة';
            if (empty($data['address'])) $data_err['address_err'] = 'برجاء إدخال العنوان';

            if (
                empty($data_err['specialist_err'])
                && empty($data_err['name_err'])
                && empty($data_err['price_err'])
                && empty($data_err['start_working_err'])
                && empty($data_err['phone_number_err'])
                && empty($data_err['end_working_err'])
                && empty($data_err['address_err'])
                && empty($data_err['governorate_err'])
            ) {

                $data_clinic = [
                    "id" => $data['id'],
                    "owner" => $get_doctor->name,
                    "name" => $data['name'],
                    "specialist" => $data['specialist'],
                    "price" => $data['price'],
                    "start_working" => $data['start_working'],
                    "end_working" => $data['end_working'],
                    "address" => $data['address'],
                    "governorate" => $data['governorate'],
                    "phone_number" => $data['phone_number'],
                    "ser_id" => random_int(100000, 999999) . $data['id'],
                    "image" => 'df-clinic'
                ];

                if ($this->doctorModel->addClinic($data_clinic)) {
                    $Message = 'تم تسجيل العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message = $data_err;
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Edit Clinic ***********************************************************//
    public function edit_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            if (empty($_POST['Auth'])) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            @$check_token = $this->tokenVerify($_POST['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            @$phone_number  = filter_var($_POST['phone_number'], 519);  //FILTER_SANITIZE_INT
            @$price         = filter_var($_POST['price'], 519);  //FILTER_SANITIZE_INT
            @$clinic_id     = filter_var($_POST['clinic_id'], 519);  //FILTER_SANITIZE_INT

            $data = [  //Array Data
                "id" => @$check_token['id'],
                "type" => @$check_token['type'],
                "clinic_id" => @$clinic_id,
                "price" => @$price,
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [ //Array Error Data
                "price_err" => '',
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
                "clinic_id_err" => ''
            ];

            if ($data['type'] !== 'doctor' && $data['type'] !== 'assistant') {
                $Message = 'غير مصرح لك القيام بالتعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                @$data_c = $this->userModel->getPlace('clinic', $data['clinic_id']);
                if (!$data_c) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                } else {
                    if (empty($data['phone_number'])) {  // Check Phone
                        $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                    } else {
                        if (strlen($data['phone_number']) !== 11) {
                            $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                        } else {
                            if ($this->userModel->getUserPhone($data['phone_number'], 'clinic')) {
                                if ($data_c->phone_number !== $data['phone_number']) $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                            }
                        }
                    }
                }
            }

            if (empty($data['price'])) $data_err['price_err'] = 'برجاء إدخال سعر الكشف';
            if (empty($data['start_working'])) $data_err['start_working_err'] = 'برجاء إدخال موعد الفتح';
            if (empty($data['end_working'])) $data_err['end_working_err'] = 'برجاء إدخال موعد الغلق';
            if (empty($data['governorate'])) $data_err['governorate_err'] = 'برجاء إدخال المحافظة';
            if (empty($data['address'])) $data_err['address_err'] = 'برجاء إدخال العنوان';

            if (
                empty($data_err['price_err'])
                && empty($data_err['clinic_id_err'])
                && empty($data_err['start_working_err'])
                && empty($data_err['phone_number_err'])
                && empty($data_err['end_working_err'])
                && empty($data_err['address_err'])
                && empty($data_err['governorate_err'])
            ) {

                $data_clinic = [
                    "doctor_id" => $data['id'],
                    "clinic_id" => $data['clinic_id'],
                    "price" => $data['price'],
                    "start_working" => $data['start_working'],
                    "end_working" => $data['end_working'],
                    "address" => $data['address'],
                    "governorate" => $data['governorate'],
                    "phone_number" => $data['phone_number'],
                ];

                if ($this->doctorModel->editClinic($data_clinic)) {
                    $Message = 'تم تعديل بيانات العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message = $data_err;
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Login Clinic ***********************************************************//
    public function login_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            if (empty($_POST['Auth'])) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            @$check_token = $this->tokenVerify($_POST['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            $data = [
                "id" => $check_token['id'],
                "type" => $check_token['type'],
                "clinic_id" => @$_POST['clinic_id']
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if ($data['type'] !== 'doctor') {
                $Message = 'غير مصرح لك تسجيل الدخول';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_doctor = $this->doctorModel->getDoctorActivation($data['id']);
                if (!$get_doctor) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_doctor->isActive !== 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 202;
                    userMessage($Status, $Message);
                    die();
                }

                @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                if (!$get_clinic) {
                    $Message = 'يجب تنشيط العيادة';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_clinic->isActive !== 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                    $Status = 202;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                $num = $this->doctorModel->numberAppointPres($data['clinic_id']);
                $url = [
                    "place" => "images/place_image/",
                    "person" => "images/profile_image/"
                ];

                $data_login = $this->doctorModel->loginClinic($data['clinic_id'], $data['id']);

                if (!$data_login) {
                    $Message = 'ليس لديك الصلاحية لتسجيل الدخول فى تلك العيادة';
                    $Status = 200;
                    userMessage($Status, $Message);
                    die();
                }

                $data_clinic = viewClinic($data_login, $num, $url);

                $Message = 'تم تسجيل الدخول بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_clinic);
                $_SESSION['clinic'] = $data_login->id;
                die();
            } else {
                $Message = $data_err;
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Add Clinic Image ***********************************************************//
    public function add_clinic_image()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            if (empty($_POST['Auth'])) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            @$check_token = $this->tokenVerify($_POST['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            $data = [
                "id" => $check_token['id'],
                "type" => $check_token['type'],
                "clinic_id" => @$_POST['clinic_id'],
                "image_name" => @$_FILES['image']["name"],
                "image_size" => @$_FILES['image']["size"],
                "tmp_name" => @$_FILES['image']["tmp_name"],
            ];
            $data_err = [
                "image_err" => '',
                "clinic_id_err" => ''
            ];

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    if (!$result) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        if ($result->doctor_id !== $data['id']) {
                            $Message = 'ليس لديك الصلاحية لتغيير الصورة';
                            $Status = 403;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data['image_name'])) {
                $data_err['image_err'] = 'برجاء تحميل صورة';
            } else {
                if ($data['image_size'] > 2000000) $data_err['image_err'] = '(2M)يجب أن يكون حجم الصورة أقل من';  //To Specify The Image Size  < 2M
            }
            if (empty($data_err['image_err']) && empty($data_err['clinic_id_err'])) {

                $data_image = [
                    "type" => 'clinic',
                    "ssd" => $result->ser_id,
                    "name" => $data['image_name'],
                    "tmp" => $data['tmp_name'],
                    "url" => '/images/place_image/'
                ];

                @$url_img = addImageProfile($data_image);

                if (!$url_img) {
                    $Message = 'صيغة الملف غير مدعوم';
                    $Status = 415;
                    userMessage($Status, $Message);
                    die();
                }
                $data_url = [
                    "id" => $data['clinic_id'],
                    "type" => 'clinic',
                    "image" => $url_img
                ];
                if ($this->doctorModel->editImage($data_url)) {
                    $Message = 'تم تحديث صورة العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وق لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                $Message = $data_err;
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Delete Clinic Image ***********************************************************//
    public function remove_clinic_image()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST  //FILTER_SANITIZE_STRING

            if (empty($_POST['Auth'])) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            @$check_token = $this->tokenVerify($_POST['Auth']);
            if (!$check_token) {
                $Message = 'الرجاء تسجيل الدخول';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
            $data = [
                "id" => $check_token['id'],
                "type" => $check_token['type'],
                "clinic_id" => @$_POST['clinic_id']
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    if (!$result) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        if ($result->doctor_id !== $data['id']) {
                            $Message = 'ليس لديك الصلاحية لحذف الصورة';
                            $Status = 403;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                $data_image = [
                    "type" => 'clinic',
                    "ssd" => $result->ser_id,
                    "url" => 'images/place_image/'
                ];

                if ($result->logo !== 'df-clinic') {
                    @$url_img = removeImage($data_image);
                    if (!$url_img) {
                        $Message = 'الرجاء المحاولة فى وق لأحق';
                        $Status = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                }

                $data_url = [
                    "id" => $data['clinic_id'],
                    "type" => 'clinic',
                    "image" => 'df-clinic'
                ];

                if ($this->doctorModel->editImage($data_url)) {
                    $Message = 'تم حذف صورة العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وق لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

            } else {
                $Message = $data_err;
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }
}

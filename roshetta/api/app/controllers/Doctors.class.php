<?php

class Doctors extends Controller
{

    private $doctorModel, $userModel, $patientModel, $CheckToken;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->userModel = new User();
        $this->patientModel = new Patient();
        $this->CheckToken = $this->tokenVerify();
        if (!$this->CheckToken) {
            $Message = 'الرجاء تسجيل الدخول';
            $Status = 401;
            userMessage($Status, $Message);
            die();
        }
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
    private function tokenVerify()
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization']) || isset($headers['Authorization'])) {
            @$Auth = explode(" ", $headers['authorization'] ? $headers['authorization'] : $headers['Authorization'])[1]; // Get Token From Auth
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

    //***************************************************************** Add Clinic ***********************************************************//
    public function add_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$phone_number = filter_var($_POST['phone_number'], 519); //FILTER_SANITIZE_INT
            @$price = filter_var($_POST['price'], 519); //FILTER_SANITIZE_INT

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "name" => @$_POST['name'],
                "specialist" => @$_POST['specialist'],
                "price" => @$price,
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [
                //Array Error Data
                "name_err" => '',
                "specialist_err" => '',
                "price_err" => '',
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
            ];

            if ($data['type'] != 'doctor') {
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

            if ($get_doctor->isActive != 1) {
                $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            @$get_clinic = $this->doctorModel->getClinic($data['id']);
            if (@$get_clinic >= 2) {
                $Message = 'لايمكنك تسجيل أكثر من 2 عيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['specialist']))
                $data_err['specialist_err'] = 'برجاء إدخال التخصص';
            if (empty($data['name']))
                $data_err['name_err'] = 'برجاء إدخال إسم العيادة';
            if (empty($data['price']))
                $data_err['price_err'] = 'برجاء إدخال سعر الكشف';

            if (empty($data['phone_number'])) { // Check Phone
                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
            } else {
                if (strlen($data['phone_number']) != 11) {
                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                } else {
                    if ($this->userModel->getUserPhone($data['phone_number'], 'clinic'))
                        $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                }
            }

            if (empty($data['start_working']))
                $data_err['start_working_err'] = 'برجاء إدخال موعد الفتح';
            if (empty($data['end_working']))
                $data_err['end_working_err'] = 'برجاء إدخال موعد الغلق';
            if (empty($data['governorate']))
                $data_err['governorate_err'] = 'برجاء إدخال المحافظة';
            if (empty($data['address']))
                $data_err['address_err'] = 'برجاء إدخال العنوان';

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
                    "image" => DF_IMAGE_CLINIC

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
    public function edit_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$phone_number = filter_var($_POST['phone_number'], 519); //FILTER_SANITIZE_INT
            @$price = filter_var($_POST['price'], 519); //FILTER_SANITIZE_INT
            @$clinic_id = filter_var($id, 519); //FILTER_SANITIZE_INT

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "clinic_id" => @$clinic_id,
                "price" => @$price,
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [
                //Array Error Data
                "price_err" => '',
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك القيام بالتعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    @$data_c = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    if (!$data_c) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic']) {
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                        } else {
                            if (empty($data['phone_number'])) { // Check Phone
                                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                            } else {
                                if (strlen($data['phone_number']) != 11) {
                                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                                } else {
                                    if ($this->userModel->getUserPhone($data['phone_number'], 'clinic')) {
                                        if ($data_c->phone_number != $data['phone_number'])
                                            $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (empty($data['price']))
                $data_err['price_err'] = 'برجاء إدخال سعر الكشف';
            if (empty($data['start_working']))
                $data_err['start_working_err'] = 'برجاء إدخال موعد الفتح';
            if (empty($data['end_working']))
                $data_err['end_working_err'] = 'برجاء إدخال موعد الغلق';
            if (empty($data['governorate']))
                $data_err['governorate_err'] = 'برجاء إدخال المحافظة';
            if (empty($data['address']))
                $data_err['address_err'] = 'برجاء إدخال العنوان';

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
                    $new_data = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    $num = $this->doctorModel->numberAppointPres($data['clinic_id']);
                    $url = [
                        "place" => URL_PLACE,
                        "person" => URL_PERSON
                    ];

                    $data_clinic = viewClinic($new_data, $num, $url);
                    $Message = 'تم تعديل بيانات العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message, $data_clinic);
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
    public function login_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
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
                    @$result = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    if (!$result) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                $num = $this->doctorModel->numberAppointPres($data['clinic_id']);
                $url = [
                    "place" => URL_PLACE,
                    "person" => URL_PERSON
                ];

                $data_login = $this->doctorModel->loginClinic($data['clinic_id'], $data['id']);

                if (!$data_login) {
                    $Message = 'ليس لديك الصلاحية لتسجيل الدخول فى تلك العيادة';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if (!$this->doctorModel->editStatus($data['clinic_id'], 1)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$data_login = $this->doctorModel->loginClinic($data['clinic_id'], $data['id']);

                //Delete Old Appointment

                @$appoint = $this->doctorModel->getDateAppoint($data['clinic_id']);
                if (!$appoint) {
                    //******* */
                } else {
                    $time = time() - (2 * 24 * 60 * 60);
                    $date = date('Y-m-d', $time);
                    foreach ($appoint as $element) {
                        if ($element['appoint_date'] <= $date) {
                            if (!$this->doctorModel->deleteAppointOld($data['clinic_id'], $element['id'])) {
                                //********** */
                            }
                        }
                    }
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
    public function add_clinic_image($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "image_name" => @$_FILES['image']["name"],
                "image_size" => @$_FILES['image']["size"],
                "tmp_name" => @$_FILES['image']["tmp_name"],
            ];
            $data_err = [
                "image_err" => '',
                "clinic_id_err" => ''
            ];

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

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
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['image_name'])) {
                $data_err['image_err'] = 'برجاء تحميل صورة';
            } else {
                if ($data['image_size'] > 4000000)
                    $data_err['image_err'] = '(4M)يجب أن يكون حجم الصورة أقل من'; //To Specify The Image Size  < 4M
            }
            if (empty($data_err['image_err']) && empty($data_err['clinic_id_err'])) {

                $data_image = [
                    "type" => 'clinic',
                    "ssd" => $result->ser_id,
                    "name" => $data['image_name'],
                    "tmp" => $data['tmp_name'],
                    "url" => URL_PLACE
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
                    @$new_image = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    $url = URL_PLACE;
                    $new_image = getImage($new_image->logo, $url);
                    $Message = 'تم تحديث صورة العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message, $new_image);
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
    public function remove_clinic_image($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

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
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                $data_image = [
                    "type" => 'clinic',
                    "ssd" => $result->ser_id,
                    "url" => URL_PLACE
                ];

                if ($result->logo != DF_IMAGE_CLINIC) {

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
                    "image" => DF_IMAGE_CLINIC

                ];

                if ($this->doctorModel->editImage($data_url)) {
                    @$new_image = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    $url = URL_PLACE;
                    $new_image = getImage($new_image->logo, $url);
                    $Message = 'تم حذف صورة العيادة بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message, $new_image);
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

    //***************************************************************** Logout Clinic ***********************************************************//
    public function logout_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if (!isset($_SESSION['clinic'])) {
                $Message = 'أنت بافعل خارج العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id']))
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    if ($data['clinic_id'] != $_SESSION['clinic'])
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                if (!$this->doctorModel->editStatus($data['clinic_id'], 0)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                unset($_SESSION['clinic']);

                $Message = 'تم تسجيل الخروج بنجاح';
                $Status = 201;
                userMessage($Status, $Message);
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

    //***************************************************************** View Clinic ***********************************************************//
    public function view_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على العيادات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->doctorModel->getClinicDoc($data['id']);
            if (!$result) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }

            $url = URL_PLACE;
            $new_data = [];
            foreach ($result as $element) {
                $element['logo'] = getImage($element['logo'], $url);
                @$isVerify = $this->userModel->getActivation($element['id'], 'clinic');
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
                $element['isVerify'] = $status_active;
                $new_data[] = $element;
            }

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $new_data);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** View Assistant Clinic ***********************************************************//
    public function view_assistant($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => '',
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على المساعد';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                @$result = $this->doctorModel->getAssistant($data['clinic_id']);
                if (!$result) {
                    $Message = 'لم يتم العثور على بيانات';
                    $Status = 204;
                    userMessage($Status, $Message);
                    die();
                }

                $url = URL_PERSON;
                $new_data = [];
                foreach ($result as $element) {
                    $element['profile_img'] = getImage($element['profile_img'], $url);
                    $new_data[] = $element;
                }
                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $new_data);
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

    //***************************************************************** Add Assistant Clinic ***********************************************************//
    public function add_assistant($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 519); //INPUT_GET   //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "assistant_id" => @$_GET['assistant_id'],
            ];

            $data_err = [
                "clinic_id_err" => '',
                "assistant_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك التعديل على المساعد';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['assistant_id'])) {
                $data_err['assistant_id_err'] = 'برجاء إدخال معرف المساعد';
            } else {
                if (!$this->userModel->getPlace('assistant', $data['assistant_id']))
                    $data_err['assistant_id_err'] = 'معرف المساعد غير صحيح';
            }

            if (empty($data_err['clinic_id_err']) && empty($data_err['assistant_id_err'])) {

                if (!$this->doctorModel->editAssistant($data['clinic_id'], $data['assistant_id'])) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$result = $this->doctorModel->getAssistant($data['clinic_id']);

                $url = URL_PERSON;
                $new_data = [];
                foreach ($result as $element) {
                    $element['profile_img'] = getImage($element['profile_img'], $url);
                    $new_data[] = $element;
                }

                $Message = 'تم إضافة المساعد بنجاح';
                $Status = 201;
                userMessage($Status, $Message, $new_data);
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

    //***************************************************************** Delete Assistant Clinic ***********************************************************//
    public function delete_assistant($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => '',
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على المساعد';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                if (!$this->doctorModel->editAssistant($data['clinic_id'], null)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم حذف المساعد بنجاح';
                $Status = 201;
                userMessage($Status, $Message);
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

    //***************************************************************** Modify Appoint Status ***********************************************************//
    public function modify_appoint_status($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 513); //INPUT_GET   //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "appointment_id" => @$_GET['appointment_id'],
            ];

            $data_err = [
                "clinic_id_err" => '',
                "appointment_id_err" => '',
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك التعديل على الموعد';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['appointment_id'])) {
                $data_err['appointment_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                if (!$this->userModel->getPlace('appointment', $data['appointment_id']))
                    $data_err['appointment_id_err'] = 'معرف الموعد غير صحيح';
            }

            if (empty($data_err['clinic_id_err']) && empty($data_err['appointment_id_err'])) {

                if (!$this->doctorModel->editAppointStatus($data['clinic_id'], $data['appointment_id'], 2)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم الكشف بنجاح';
                $Status = 201;
                userMessage($Status, $Message);
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

    //***************************************************************** View Appointment ***********************************************************//

    public function view_appoint($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513); // INPUT_POST    //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "filter" => @$_GET['filter'],
                "clinic_id" => @$id,
                "date" => @$_GET['date'],
                "status" => @$_GET['status']
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على المواعيد';
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
                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }
            if (empty($data_err['clinic_id_err'])) {

                $date = $data['date'];
                $case = $data['status'];

                if (empty($data['date'])) {
                    $date = date("Y-m-d");
                }
                if ($data['status'] != 0 && empty($data['status'])) {
                    $case = 1;
                }

                if (!empty($data['filter'])) {
                    @$result = $this->doctorModel->filterAppoint($data['clinic_id'], $date, $case, $data['filter']);
                    if (!$result) {
                        $Message = 'لم يتم العثور على بيانات';
                        $Status = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {
                    @$result = $this->doctorModel->getAppointClinic($data['clinic_id'], $date, $case);
                    if (!$result) {
                        $Message = 'لم يتم العثور على بيانات';
                        $Status = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                }

                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $result);
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

    //***************************************************************** View Patient Details ***********************************************************//
    public function view_patient_details($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 519); //INPUT_GET   //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "patient_id" => @$_GET['patient_id'],
                "appoint_id" => @$_GET['appoint_id'],
            ];

            $data_err = [
                "clinic_id_err" => '',
                "patient_id_err" => '',
                "appoint_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['patient_id'])) {
                $data_err['patient_id_err'] = 'برجاء إدخال معرف المريض';
            } else {
                @$data_patient = $this->userModel->getPlace('patient', $data['patient_id']);
                if (!$data_patient)
                    $data_err['patient_id_err'] = 'معرف المريض غير صحيح';
            }

            if (empty($data['appoint_id'])) {
                $data_err['appoint_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                @$data_appoint = $this->userModel->getPlace('appointment', $data['appoint_id']);
                if (!$data_appoint)
                    $data_err['appoint_id_err'] = 'معرف الموعد غير صحيح';
            }

            if (empty($data_err['clinic_id_err']) && empty($data_err['patient_id_err']) && empty($data_err['appoint_id_err'])) {

                $disease = $this->patientModel->getDataDisease($data['patient_id']);
                $url = URL_PERSON;
                $data_message = [
                    "patient" => [
                        "patient_id" => $data_patient->id,
                        "name" => $data_patient->name,
                        "weight" => $data_patient->weight,
                        "height" => $data_patient->height,
                        "phone_number" => $data_patient->phone_number,
                        "age" => userAge($data_patient->birth_date),
                        "image" => getImage($data_patient->profile_img, $url),
                        "appoint_case" => $data_appoint->appoint_case,
                    ],
                    "disease" => $disease
                ];

                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_message);
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

    //***************************************************************** View Disease Prescript ***********************************************************//
    public function view_disease_prescript($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 519); //INPUT_GET   //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "disease_id" => @$_GET['disease_id'],
            ];

            $data_err = [
                "clinic_id_err" => '',
                "disease_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['disease_id'])) {
                $data_err['disease_id_err'] = 'برجاء إدخال معرف التشخيص';
            } else {
                $data_patient = $this->userModel->getPlace('disease', $data['disease_id']);
                if (!$data_patient)
                    $data_err['disease_id_err'] = 'معرف التشخيص غير صحيح';
            }

            if (empty($data_err['clinic_id_err']) && empty($data_err['disease_id_err'])) {

                @$data_message = $this->doctorModel->getDiseasePrescript($data['disease_id']);

                if (!$data_message) {
                    $Message = 'لم يتم العثور على بيانات';
                    $Status = 204;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_message);
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

    //***************************************************************** View Disease Prescript Details ***********************************************************//
    public function view_disease_prescript_details($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 519); // INPUT_GET    //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "clinic_id" => @$id,
                "prescript_id" => @$_GET['prescript_id']
            ];

            $data_err = [
                "prescript_id_err" => '',
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على التفاصيل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['prescript_id'])) {
                $data_err['prescript_id_err'] = 'برجاء إدخال معرف الروشتة';
            } else {
                if (!filter_var($data['prescript_id'], 257)) {
                    $data_err['prescript_id_err'] = 'معرف الروشتة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['prescript_id'], 'prescript'))
                        $data_err['prescript_id_err'] = 'معرف الروشتة غير صحيح';
                }
            }

            if (empty($data_err['prescript_id_err'])) {

                @$result_prescript = $this->doctorModel->getDiseasePrescriptDetails($data['prescript_id']);
                if (!$result_prescript) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$result_medicine = $this->patientModel->getPrescriptMedicine($data['prescript_id']);
                if (!$result_medicine) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$decode_medicine = decodeMedicine($result_medicine);
                if (!$decode_medicine) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $url = URL_PLACE;
                $new_result_prescript = [];
                foreach ($result_prescript as $element) {
                    $element['clinic_logo'] = getImage($element['clinic_logo'], $url);
                    $new_result_prescript[] = $element;
                }

                $new_decode_medicine = [];
                foreach ($decode_medicine as $element) {
                    $new_decode_medicine[] = $element;
                }

                $data_message = [
                    "prescript_data" => $new_result_prescript,
                    "medicine_data" => $new_decode_medicine
                ];

                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_message);
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

    //***************************************************************** Chat ***********************************************************//
    public function chat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST    //FILTER_SANITIZE_STRING
            $_GET = filter_input_array(1, 519); // INPUT_GET    //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "message" => @$_POST['message'],
                "chat_id" => @$_GET['chat_id'],
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك الإطلاع على التفاصيل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!empty($data['chat_id'])) {
                if (!$this->doctorModel->deleteChat($data['chat_id'], $data['id'])) {
                    @$data_message = $this->doctorModel->getChat();
                    if (!$data_message) {
                        $data_message = null;
                    }
                }
                @$data_message = $this->doctorModel->getChat();
                if (!$data_message) {
                    $data_message = null;
                }
            }

            if (!empty($data['message'])) {

                @$get_doctor = $this->userModel->getPlace($data['type'], $data['id']);
                if (!$get_doctor) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }
                $data_chat = [
                    "name" => $get_doctor->name,
                    "id" => $data['id'],
                    "time" => date("h:i"),
                    "image" => $get_doctor->profile_img,
                    "message" => $data['message']
                ];

                if (!$this->doctorModel->addChat($data_chat)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$data_message = $this->doctorModel->getChat();
                if (!$data_message) {
                    $data_message = null;
                }
            } else {
                @$data_message = $this->doctorModel->getChat();
                if (!$data_message) {
                    $data_message = null;
                }
            }

            if (empty($data_message)) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }

            $new_data_message = [];

            $url = URL_PERSON;
            foreach ($data_message as $element) {
                $element['image'] = getImage($element['image'], $url);
                if ($element['doctor_id'] == $data['id']) {
                    $element['name'] = '1';
                    unset($element['image']);
                }
                unset($element['doctor_id']);
                $new_data_message[] = $element;
            }

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $new_data_message);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Add Patient Disease And Prescript And Medicine ***********************************************************//
    public function add_patient_prescript($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING
            $_GET = filter_input_array(1, 513); //INPUT_GET   //FILTER_SANITIZE_STRING

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "patient_id" => @$_GET['patient_id'],
                "disease_id" => @$_GET['disease_id'],
                "clinic_id" => @$id,
                "name" => @$_POST['name'],
                "place" => @$_POST['place'],
                "rediscovery_date" => @$_POST['rediscovery_date'],
                "medicine" => @$_POST['medicine'],
                "type_prescript" => @$_GET['type'],
                "appoint_id" => @$_GET['appoint_id'],
            ];

            $data_err = [
                "clinic_id_err" => '',
                "patient_id_err" => '',
                "name_err" => '',
                "place_err" => '',
                "rediscovery_date_err" => '',
                "medicine_err" => '',
                "type_prescript_err" => '',
                "disease_id_err" => '',
                "appoint_id_err" => ''
            ];

            if ($data['type'] != 'doctor') {
                $Message = 'غير مصرح لك إضافة تشخيص';
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

                if ($get_doctor->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (!isset($_SESSION['clinic'])) {
                $Message = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        @$get_clinic = $this->doctorModel->getClinicActivation($data['clinic_id']);
                        if (!$get_clinic) {
                            $Message = 'يجب تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic'])
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['type_prescript'])) {
                $data_err['type_prescript_err'] = 'برجاء تحديد النوع';
            } else {
                $types = ['new', 'rediscovery'];
                if (!in_array($data['type_prescript'], $types)) {
                    $data_err['type_prescript_err'] = 'النوع غير مدعوم';
                }
            }

            if (empty($data['patient_id'])) {
                $data_err['patient_id_err'] = 'برجاء إدخال معرف المريض';
            } else {
                if (!$this->userModel->getPlace('patient', $data['patient_id'])) {
                    $data_err['patient_id_err'] = 'معرف المريض غير صحيح';
                }
            }

            if (empty($data['appoint_id'])) {
                $data_err['appoint_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                @$data_appoint = $this->userModel->getPlace('appointment', $data['appoint_id']);
                if (!$data_appoint) {
                    $data_err['appoint_id_err'] = 'معرف الموعد غير صحيح';
                } else {
                    if ($data_appoint->patient_id != $data['patient_id']) {
                        $data_err['appoint_id_err'] = 'هذا الحجز ليس لهذا المريض';
                    } else {
                        if ($data_appoint->appoint_case != 1)
                            $data_err['appoint_id_err'] = 'حالة الحجز لاتسمح بإضافة روشتة';
                    }
                }
            }

            if (
                empty($data_err['clinic_id_err'])
                && empty($data_err['type_prescript_err'])
                && empty($data_err['patient_id_err'])
                && empty($data_err['appoint_id_err'])
            ) {

                if ($data['type_prescript'] == 'new') {

                    if (empty($data['name']))
                        $data_err['name_err'] = 'برجاء إدخال التشخيص';
                    if (empty($data['place']))
                        $data_err['place_err'] = 'برجاء إدخال مكان الإصابة';
                    if (empty($data['rediscovery_date']))
                        $data_err['rediscovery_date_err'] = 'برجاء إدخال ميعاد إعادة الكشف';
                    foreach ($data['medicine'] as $medicine) {
                        if (empty($medicine['name']) || empty($medicine['size']) || empty($medicine['discription']) || empty($medicine['duration']))
                            $data_err['medicine_err'] = 'برجاء إدخال جميع بيانات الادوية';
                    }
                    // Add New Disease

                    if (
                        empty($data_err['name_err'])
                        && empty($data_err['place_err'])
                        && empty($data_err['rediscovery_date_err'])
                        && empty($data_err['medicine_err'])
                    ) {

                        $data_disease = [
                            "name" => $data['name'],
                            "place" => $data['place'],
                            "clinic_id" => $data['clinic_id'],
                            "patient_id" => $data['patient_id'],
                            "doctor_id" => $data['id'],
                            "date" => date('Y-m-d')
                        ];
                        if (!$this->doctorModel->addDisease($data_disease)) {
                            $Message = 'فشل إضافة المرض';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }
                        @$result_dis = $this->doctorModel->getDiseaseNew($data_disease);
                        if (!$result_dis) {
                            $Message = 'فشل جلب بيانات المرض';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        //Add New Prescript

                        $data_pres = [
                            "clinic_id" => $data['clinic_id'],
                            "patient_id" => $data['patient_id'],
                            "disease_id" => $result_dis->id,
                            "doctor_id" => $data['id'],
                            "rediscovery_date" => $data['rediscovery_date'],
                            "created_date" => date('Y-m-d'),
                            "ser_id" => random_int(100000, 999999) . $result_dis->id
                        ];

                        if (!$this->doctorModel->addPrescript($data_pres)) {
                            $Message = 'فشل إضافة الروشتة';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        $new_appoint = [
                            "clinic_id" => $data['clinic_id'],
                            "patient_id" => $data['patient_id'],
                            "appoint_date" => $data['rediscovery_date']
                        ];

                        if (@$this->patientModel->addAppointPatient($new_appoint)) {
                            //***********/
                        }

                        @$result_pre = $this->doctorModel->getPrescriptNew($data_pres['ser_id']);
                        if (!$result_pre) {
                            $Message = 'فشل جلب بيانات الروشتة';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        // Add New Medicine

                        $data_med = [
                            "prescript_id" => $result_pre->id,
                            "medicine_data" => base64_encode(serialize($data['medicine']))
                        ];

                        if (!$this->doctorModel->addMedicine($data_med)) {
                            $Message = 'فشل إضافة الأدوية';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        @$this->doctorModel->editAppointStatus($data['clinic_id'], $data['appoint_id'], 2);

                        $Message = 'تم إضافة الأدوية بنجاح';
                        $Status = 201;
                        userMessage($Status, $Message, ['disease_id' => $result_dis->id]);
                        die();
                    } else {
                        $Message = $data_err;
                        $Status = 400;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {

                    if (empty($data['rediscovery_date']))
                        $data_err['rediscovery_date_err'] = 'برجاء إدخال ميعاد إعادة الكشف';
                    if (empty($data['disease_id'])) {
                        $data_err['disease_id_err'] = 'برجاء إدخال معرف التشخيص';
                    } else {
                        @$disease_data = $this->userModel->getPlace('disease', $data['disease_id']);
                        if (!$disease_data) {
                            $data_err['disease_id_err'] = 'معرف التشخيص غير صحيح';
                        }
                    }
                    foreach ($data['medicine'] as $medicine) {
                        if (empty($medicine['name']) || empty($medicine['size']) || empty($medicine['discription']) || empty($medicine['duration']))
                            $data_err['medicine_err'] = 'برجاء إدخال جميع بيانات الادوية';
                    }

                    if (
                        empty($data_err['rediscovery_date_err'])
                        && empty($data_err['medicine_err'])
                        && empty($data_err['disease_id_err'])
                    ) {

                        // Add New Prescript

                        $data_pres = [
                            "clinic_id" => $data['clinic_id'],
                            "patient_id" => $data['patient_id'],
                            "disease_id" => $data['disease_id'],
                            "doctor_id" => $data['id'],
                            "rediscovery_date" => $data['rediscovery_date'],
                            "created_date" => date('Y-m-d'),
                            "ser_id" => random_int(100000, 999999) . $data['patient_id']
                        ];

                        if (!$this->doctorModel->addPrescript($data_pres)) {
                            $Message = 'فشل إضافة الروشتة';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        @$result_pre = $this->doctorModel->getPrescriptNew($data_pres['ser_id']);
                        if (!$result_pre) {
                            $Message = 'فشل جلب بيانات الروشتة';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        // Add New Medicine

                        $data_med = [
                            "prescript_id" => $result_pre->id,
                            "medicine_data" => base64_encode(serialize($data['medicine']))
                        ];

                        if (!$this->doctorModel->addMedicine($data_med)) {
                            $Message = 'فشل إضافة الأدوية';
                            $Status = 422;
                            userMessage($Status, $Message);
                            die();
                        }

                        $data_message = [
                            'id' => $result_pre->id,
                            'ser_id' => $result_pre->ser_id,
                            'date' => $result_pre->created_date,
                            'disease_name' => $disease_data->name
                        ];

                        @$this->doctorModel->editAppointStatus($data['clinic_id'], $data['appoint_id'], 2);

                        $Message = 'تم إضافة الأدوية بنجاح';
                        $Status = 201;
                        userMessage($Status, $Message, $data_message);
                        die();
                    } else {
                        $Message = $data_err;
                        $Status = 400;
                        userMessage($Status, $Message);
                        die();
                    }
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
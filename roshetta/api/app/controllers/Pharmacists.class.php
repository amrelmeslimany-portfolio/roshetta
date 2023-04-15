<?php

class Pharmacists extends Controller
{
    private $CheckToken, $doctorModel, $userModel, $pharmacistModel;
    public function __construct()
    {
        $this->pharmacistModel = $this->model('pharmacist');
        $this->userModel = $this->model('User');
        $this->doctorModel = $this->model('doctor');
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
        $Message = '(API_Pharmacists)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#e033cd90-661d-4a54-abe0-c1f2024e5f07';
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

    //***************************************************************** Add Pharmacy ***********************************************************//
    public function add_pharmacy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$phone_number = filter_var($_POST['phone_number'], 519); //FILTER_SANITIZE_INT

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "name" => @$_POST['name'],
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [
                //Array Error Data
                "name_err" => '',
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك القيام بالإضافة';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
            if (!$get_pharmacist) {
                $Message = 'يجب تنشيط الحساب';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if ($get_pharmacist->isActive != 1) {
                $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            @$get_pharmacy = $this->pharmacistModel->getPharmacy($data['id']);
            if (@$get_pharmacy >= 2) {
                $Message = 'لايمكنك تسجيل أكثر من 2 صيدلية';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['name']))
                $data_err['name_err'] = 'برجاء إدخال إسم العيادة';

            if (empty($data['phone_number'])) { // Check Phone
                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
            } else {
                if (strlen($data['phone_number']) != 11) {
                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                } else {
                    if ($this->userModel->getUserPhone($data['phone_number'], 'pharmacy'))
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
                empty($data_err['name_err'])
                && empty($data_err['start_working_err'])
                && empty($data_err['phone_number_err'])
                && empty($data_err['end_working_err'])
                && empty($data_err['address_err'])
                && empty($data_err['governorate_err'])
            ) {

                $data_pharmacy = [
                    "id" => $data['id'],
                    "owner" => $get_pharmacist->name,
                    "name" => $data['name'],
                    "start_working" => $data['start_working'],
                    "end_working" => $data['end_working'],
                    "address" => $data['address'],
                    "governorate" => $data['governorate'],
                    "phone_number" => $data['phone_number'],
                    "ser_id" => random_int(100000, 999999) . $data['id'],
                    "image" => DF_IMAGE_PHARMACY

                ];

                if ($this->pharmacistModel->addPharmacy($data_pharmacy)) {
                    $Message = 'تم تسجيل الصيدلية بنجاح';
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

    //***************************************************************** Edit Pharmacy ***********************************************************//
    public function edit_pharmacy($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$phone_number = filter_var($_POST['phone_number'], 519); //FILTER_SANITIZE_INT
            @$pharmacy_id = filter_var($id, 519); //FILTER_SANITIZE_INT

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "pharmacy_id" => @$pharmacy_id,
                "phone_number" => @$phone_number,
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
            ];
            $data_err = [
                //Array Error Data
                "phone_number_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "governorate_err" => '',
                "address_err" => '',
                "pharmacy_id_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك القيام بالتعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['pharmacy'])) {
                $Message = 'الرجاء تسجيل الدخول إلى الصيدلية';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$data_p = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$data_p) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['pharmacy_id'] != $_SESSION['pharmacy']) {
                            $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                        } else {
                            if (empty($data['phone_number'])) { // Check Phone
                                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                            } else {
                                if (strlen($data['phone_number']) != 11) {
                                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                                } else {
                                    if ($this->userModel->getUserPhone($data['phone_number'], 'pharmacy')) {
                                        if ($data_p->phone_number != $data['phone_number'])
                                            $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                                    }
                                }
                            }
                        }
                    }
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
                empty($data_err['pharmacy_id_err'])
                && empty($data_err['start_working_err'])
                && empty($data_err['phone_number_err'])
                && empty($data_err['end_working_err'])
                && empty($data_err['address_err'])
                && empty($data_err['governorate_err'])
            ) {

                $data_pharmacy = [
                    "pharmacist_id" => $data['id'],
                    "pharmacy_id" => $data['pharmacy_id'],
                    "start_working" => $data['start_working'],
                    "end_working" => $data['end_working'],
                    "address" => $data['address'],
                    "governorate" => $data['governorate'],
                    "phone_number" => $data['phone_number'],
                ];

                if ($this->pharmacistModel->editPharmacy($data_pharmacy)) {
                    $new_data = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    $num = $this->pharmacistModel->numberPrescript($data['pharmacy_id']);
                    $url = [
                        "place" => URL_PLACE,
                        "person" => URL_PERSON
                    ];
                    $data_pharmacy = viewPharmacy($new_data, $num, $url);
                    $Message = 'تم تعديل بيانات الصيدلية بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message, $data_pharmacy);
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

    //***************************************************************** Login Pharmacy ***********************************************************//
    public function login_pharmacy($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id
            ];

            $data_err = [
                "pharmacy_id_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك تسجيل الدخول';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
                if (!$get_pharmacist) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_pharmacist->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data_err['pharmacy_id_err'])) {

                $num = $this->pharmacistModel->numberPrescript($data['pharmacy_id']);
                $url = [
                    "place" => URL_PLACE,
                    "person" => URL_PERSON
                ];

                $data_login = $this->pharmacistModel->loginPharmacy($data['pharmacy_id'], $data['id']);

                if (!$data_login) {
                    $Message = 'ليس لديك الصلاحية لتسجيل الدخول فى تلك الصيدلية';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if (!$this->pharmacistModel->editStatus($data['pharmacy_id'], 1)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                if (!$this->pharmacistModel->deleteOrder($data['pharmacy_id'])) {
                    /********** */
                }

                @$data_login = $this->pharmacistModel->loginPharmacy($data['pharmacy_id'], $data['id']);

                $data_pharmacy = viewPharmacy($data_login, $num, $url);
                $Message = 'تم تسجيل الدخول بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_pharmacy);
                $_SESSION['pharmacy'] = $data_login->id;
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

    //***************************************************************** View Pharmacy ***********************************************************//
    public function view_pharmacy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك الإطلاع على الصيداليات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->pharmacistModel->getPharmacistDoc($data['id']);
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
                @$isVerify = $this->userModel->getActivation($element['id'], 'pharmacy');
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

    //***************************************************************** View Prescript Patient ***********************************************************//
    public function view_prescript($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id,
                "user_id" => @$_GET['user_id'],
                "type_filter" => @$_GET['type']
            ];

            $data_err = [
                "pharmacy_id_err" => '',
                "user_id_err" => '',
                "type_filter_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
                if (!$get_pharmacist) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_pharmacist->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data['user_id']))
                $data_err['user_id_err'] = 'برجاء إدخال المعرف';
            if (empty($data['type_filter'])) {
                $data_err['type_filter_err'] = 'برجاء إدخال النوع';
            } else {
                $type_filter = ['ssd', 'ser_id', 'prescript_id'];
                if (!in_array($data['type_filter'], $type_filter))
                    $data_err['type_filter_err'] = 'النوع غير مدعوم';
            }

            if (empty($data_err['pharmacy_id_err']) && empty($data_err['user_id_err']) && empty($data_err['type_filter_err'])) {

                if ($data['type_filter'] == 'ssd') {
                    $data_message = $this->pharmacistModel->getPrescript($data['user_id']);
                    if (!$data_message) {
                        $Message = 'لم يتم العثور على بيانات';
                        $Status = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {
                    $data_pre = $this->pharmacistModel->getPrescriptDetails($data['user_id']);
                    if (!$data_pre) {
                        $Message = 'لم يتم العثور على بيانات';
                        $Status = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                    $data_med = $this->pharmacistModel->getPrescriptMedicine($data['user_id']);
                    if (!$data_med) {
                        $Message = 'لم يتم العثور على بيانات';
                        $Status = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                    @$decode_medicine = decodeMedicine($data_med);
                    if (!$decode_medicine) {
                        $Message = 'الرجاء المحاولة فى وقت لأحق';
                        $Status = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                    $url = URL_PLACE;
                    $new_data_pre = [];
                    foreach ($data_pre as $element) {
                        $element['clinic_logo'] = getImage($element['clinic_logo'], $url);
                        $new_data_pre[] = $element;
                    }

                    $new_decode_medicine = [];
                    foreach ($decode_medicine as $element) {
                        $new_decode_medicine[] = $element;
                    }

                    $data_message = [
                        "prescript_data" => $new_data_pre,
                        "medicine_data" => $new_decode_medicine
                    ];
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

    //***************************************************************** View Order Pharmacy ***********************************************************//
    public function view_order_pharmacy($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id,
                "filter" => @$_GET['filter'],
                "type_pre" => @$_GET['type']
            ];

            $data_err = [
                "pharmacy_id_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك الإطلاع على الطلبات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
                if (!$get_pharmacist) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_pharmacist->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data_err['pharmacy_id_err'])) {

                if (empty($data['type_pre']) || $data['type_pre'] == 'wating') {
                    if (empty($data['filter'])) {
                        $data_order = $this->pharmacistModel->getOrder($data['pharmacy_id']);
                        if (!$data_order) {
                            $Message = 'لم يتم العثور على بيانات';
                            $Status = 204;
                            userMessage($Status, $Message);
                            die();
                        }
                    } else {
                        $data_order = $this->pharmacistModel->getOrderFilter($data['pharmacy_id'], $data['filter']);
                        if (!$data_order) {
                            $Message = 'لم يتم العثور على بيانات';
                            $Status = 204;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                } else {
                    if (empty($data['filter'])) {
                        $data_order = $this->pharmacistModel->getOrderPay($data['pharmacy_id']);
                        if (!$data_order) {
                            $Message = 'لم يتم العثور على بيانات';
                            $Status = 204;
                            userMessage($Status, $Message);
                            die();
                        }
                    } else {
                        $data_order = $this->pharmacistModel->getOrderPayFilter($data['pharmacy_id'], $data['filter']);
                        if (!$data_order) {
                            $Message = 'لم يتم العثور على بيانات';
                            $Status = 204;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }

                $Message = 'تم جلب البيانات بنجاح';
                $Status = 200;
                userMessage($Status, $Message, $data_order);
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

    //***************************************************************** Pay Prescript ***********************************************************//
    public function confirm_pay_prescript($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id,
                "prescript_id" => @$_GET['prescript_id']
            ];

            $data_err = [
                "pharmacy_id_err" => '',
                "prescript_id_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك صرف روشتات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
                if (!$get_pharmacist) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_pharmacist->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data['prescript_id'])) {
                $data_err['prescript_id_err'] = 'برجاء إدخال معرف الروشتة';
            } else {
                if (!$this->userModel->getPlace('prescript', $data['prescript_id']))
                    $data_err['prescript_id_err'] = 'معرف الروشتة غير صحيح';
            }

            if (empty($data_err['pharmacy_id_err']) && empty($data_err['prescript_id_err'])) {

                if ($this->pharmacistModel->payPrescript($data['pharmacy_id'], $data['prescript_id'])) {
                    $Message = 'تم الصرف بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'الرجاء المحاولة فى وقت لأحق';
                $Status = 422;
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

    //***************************************************************** Pay Prescript Order ***********************************************************//
    public function confirm_pay_prescript_order($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id,
                "order_id" => @$_GET['order_id']
            ];

            $data_err = [
                "pharmacy_id_err" => '',
                "order_id_err" => ''
            ];

            if ($data['type'] != 'pharmacist') {
                $Message = 'غير مصرح لك صرف روشتات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            } else {
                @$get_pharmacist = $this->pharmacistModel->getPharmacistActivation($data['id']);
                if (!$get_pharmacist) {
                    $Message = 'يجب تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if ($get_pharmacist->isActive != 1) {
                    $Message = 'الرجاء الإنتظار حتى يتم تنشيط الحساب';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data['order_id'])) {
                $data_err['order_id_err'] = 'برجاء إدخال معرف الطلب';
            } else {
                @$data_order = $this->userModel->getPlace('pharmacy_order', $data['order_id']);
                if (!$data_order)
                    $data_err['order_id_err'] = 'معرف الطلب غير صحيح';
            }

            if (empty($data_err['pharmacy_id_err']) && empty($data_err['order_id_err'])) {

                if (!$this->pharmacistModel->payPrescript($data['pharmacy_id'], $data_order->prescript_id)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                if (!$this->pharmacistModel->editStatusPre($data['order_id'])) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم الصرف بنجاح';
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

    //***************************************************************** Add Pharmacy Image ***********************************************************//
    public function add_pharmacy_image($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id,
                "image_name" => @$_FILES['image']["name"],
                "image_size" => @$_FILES['image']["size"],
                "tmp_name" => @$_FILES['image']["tmp_name"],
            ];
            $data_err = [
                "image_err" => '',
                "pharmacy_id_err" => ''
            ];

            if (!isset($_SESSION['pharmacy'])) {
                $Message = 'الرجاء تسجيل الدخول إلى الصيدلية';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['pharmacy_id'] != $_SESSION['pharmacy'])
                            $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    }
                }
            }

            if (empty($data['image_name'])) {
                $data_err['image_err'] = 'برجاء تحميل صورة';
            } else {
                if ($data['image_size'] > 4000000)
                    $data_err['image_err'] = '(4M)يجب أن يكون حجم الصورة أقل من'; //To Specify The Image Size  < 4M
            }
            if (empty($data_err['image_err']) && empty($data_err['pharmacy_id_err'])) {

                $data_image = [
                    "type" => 'pharmacy',
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
                    "id" => $data['pharmacy_id'],
                    "type" => 'pharmacy',
                    "image" => $url_img
                ];
                if ($this->doctorModel->editImage($data_url)) {
                    @$new_image = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    $url = URL_PLACE;
                    $new_image = getImage($new_image->logo, $url);
                    $Message = 'تم تحديث صورة الصيدلية بنجاح';
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

    //***************************************************************** Delete Pharmacy Image ***********************************************************//
    public function remove_pharmacy_image($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id
            ];

            $data_err = [
                "pharmacy_id_err" => ''
            ];

            if (!isset($_SESSION['pharmacy'])) {
                $Message = 'الرجاء تسجيل الدخول إلى الصيدلية';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    @$result = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    if (!$result) {
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    } else {
                        @$get_pharmacy = $this->pharmacistModel->getPharmacyActivation($data['pharmacy_id']);
                        if (!$get_pharmacy) {
                            $Message = 'يجب تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_pharmacy->isActive != 1) {
                            $Message = 'الرجاء الإنتظار حتى يتم تنشيط الصيدلية';
                            $Status = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['pharmacy_id'] != $_SESSION['pharmacy'])
                            $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    }
                }
            }

            if (empty($data_err['pharmacy_id_err'])) {

                $data_image = [
                    "type" => 'pharmacy',
                    "ssd" => $result->ser_id,
                    "url" => URL_PLACE
                ];

                if ($result->logo != DF_IMAGE_PHARMACY) {

                    @$url_img = removeImage($data_image);
                    if (!$url_img) {
                        $Message = 'الرجاء المحاولة فى وق لأحق';
                        $Status = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                }

                $data_url = [
                    "id" => $data['pharmacy_id'],
                    "type" => 'pharmacy',
                    "image" => DF_IMAGE_PHARMACY

                ];

                if ($this->doctorModel->editImage($data_url)) {
                    @$new_image = $this->userModel->getPlace('pharmacy', $data['pharmacy_id']);
                    $url = URL_PLACE;
                    $new_image = getImage($new_image->logo, $url);
                    $Message = 'تم حذف صورة الصيدلية بنجاح';
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

    //***************************************************************** Logout Pharmacy ***********************************************************//
    public function logout_pharmacy($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "pharmacy_id" => @$id
            ];

            $data_err = [
                "pharmacy_id_err" => ''
            ];

            if (!isset($_SESSION['pharmacy'])) {
                $Message = 'أنت بافعل خارج الصيدلية';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    if (!$this->userModel->getPlace('pharmacy', $data['pharmacy_id']))
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                    if ($data['pharmacy_id'] != $_SESSION['pharmacy'])
                        $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                }
            }

            if (empty($data_err['pharmacy_id_err'])) {

                if (!$this->pharmacistModel->editStatus($data['pharmacy_id'], 0)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                unset($_SESSION['pharmacy']);

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
}
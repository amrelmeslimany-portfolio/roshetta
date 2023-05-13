<?php

class Assistants extends Controller
{
    private $CheckToken, $doctorModel, $userModel, $assistantModel;
    public function __construct()
    {
        $this->assistantModel   = new Assistant();
        $this->userModel        = new User();
        $this->doctorModel      = new Doctor();
        $this->CheckToken       = $this->tokenVerify();
        if (!$this->CheckToken) {
            $Message    = 'الرجاء تسجيل الدخول';
            $Status     = 401;
            userMessage($Status, $Message);
            die();
        }
    }
    public function document()
    {
        $Message    = '(API_Assistants)برجاء الإطلاع على شرح';
        $Status     = 400;
        $url        = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#f5502a92-aae1-4466-8ce1-350b62f12f63';
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

    //***************************************************************** Login Clinic ***********************************************************//
    public function login_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id"        => $this->CheckToken['id'],
                "type"      => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'assistant') {
                $Message    = 'غير مصرح لك تسجيل الدخول';
                $Status     = 403;
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
                            $Message    = 'يجب تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message    = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status     = 400;
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

                $data_login = $this->assistantModel->loginClinic($data['clinic_id'], $data['id']);

                if (!$data_login) {
                    $Message    = 'ليس لديك الصلاحية لتسجيل الدخول فى تلك العيادة';
                    $Status     = 400;
                    userMessage($Status, $Message);
                    die();
                }

                if (!$this->doctorModel->editStatus($data['clinic_id'], 1)) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$data_login = $this->assistantModel->loginClinic($data['clinic_id'], $data['id']);

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
                $Message    = 'تم تسجيل الدخول بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_clinic);
                $_SESSION['clinic'] = $data_login->id;
                die();
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

    //***************************************************************** Edit Clinic ***********************************************************//
    public function edit_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); //INPUT_POST   //FILTER_SANITIZE_STRING

            @$phone_number  = filter_var($_POST['phone_number'], 519);  //FILTER_SANITIZE_INT
            @$price         = filter_var($_POST['price'], 519);  //FILTER_SANITIZE_INT
            @$clinic_id     = filter_var($id, 519);  //FILTER_SANITIZE_INT

            $data = [  //Array Data
                "id"            => @$this->CheckToken['id'],
                "type"          => @$this->CheckToken['type'],
                "clinic_id"     => @$clinic_id,
                "price"         => @$price,
                "phone_number"  => @$phone_number,
                "governorate"   => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working"   => @$_POST['end_working'],
                "address"       => @$_POST['address'],
            ];
            $data_err = [ //Array Error Data
                "price_err"         => '',
                "phone_number_err"  => '',
                "start_working_err" => '',
                "end_working_err"   => '',
                "governorate_err"   => '',
                "address_err"       => '',
                "clinic_id_err"     => ''
            ];

            if ($data['type'] != 'assistant') {
                $Message    = 'غير مصرح لك القيام بالتعديل';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['clinic'])) {
                $Message    = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status     = 400;
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
                            $Message    = 'يجب تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message    = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic']) {
                            $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                        } else {
                            if (empty($data['phone_number'])) {  // Check Phone
                                $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                            } else {
                                if (strlen($data['phone_number']) != 11) {
                                    $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                                } else {
                                    if ($this->userModel->getUserPhone($data['phone_number'], 'clinic')) {
                                        if ($data_c->phone_number != $data['phone_number']) $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                                    }
                                }
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
                    "assistant_id"  => $data['id'],
                    "clinic_id"     => $data['clinic_id'],
                    "price"         => $data['price'],
                    "start_working" => $data['start_working'],
                    "end_working"   => $data['end_working'],
                    "address"       => $data['address'],
                    "governorate"   => $data['governorate'],
                    "phone_number"  => $data['phone_number'],
                ];

                if ($this->assistantModel->editClinic($data_clinic)) {
                    $new_data = $this->userModel->getPlace('clinic', $data['clinic_id']);
                    $num = $this->doctorModel->numberAppointPres($data['clinic_id']);
                    $url = [
                        "place"     => URL_PLACE,
                        "person"    => URL_PERSON
                    ];

                    $data_clinic = viewClinic($new_data, $num, $url);
                    $Message    = 'تم تعديل بيانات العيادة بنجاح';
                    $Status     = 201;
                    userMessage($Status, $Message, $data_clinic);
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

    //***************************************************************** Logout Clinic ***********************************************************//
    public function logout_clinic($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"        => $this->CheckToken['id'],
                "type"      => $this->CheckToken['type'],
                "clinic_id" => @$id
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if (!isset($_SESSION['clinic'])) {
                $Message    = 'أنت بافعل خارج العيادة';
                $Status     = 400;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->userModel->getPlace('clinic', $data['clinic_id'])) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    if ($data['clinic_id'] != $_SESSION['clinic']) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                if (!$this->doctorModel->editStatus($data['clinic_id'], 0)) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                unset($_SESSION['clinic']);

                $Message    = 'تم تسجيل الخروج بنجاح';
                $Status     = 201;
                userMessage($Status, $Message);
                die();
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

    //***************************************************************** Modify Appoint Status ***********************************************************//
    public function modify_appoint_status($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id"                => $this->CheckToken['id'],
                "type"              => $this->CheckToken['type'],
                "clinic_id"         => @$id,
                "appointment_id"    => @$_GET['appointment_id'],
            ];

            $data_err = [
                "clinic_id_err"         => '',
                "appointment_id_err"    => '',
            ];

            if ($data['type'] != 'assistant') {
                $Message    = 'غير مصرح لك التعديل على الموعد';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['clinic'])) {
                $Message    = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status     = 400;
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
                            $Message    = 'يجب تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message    = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic']) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }

            if (empty($data['appointment_id'])) {
                $data_err['appointment_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                if (!$this->userModel->getPlace('appointment', $data['appointment_id'])) $data_err['appointment_id_err'] = 'معرف الموعد غير صحيح';
            }

            if (empty($data_err['clinic_id_err']) && empty($data_err['appointment_id_err'])) {

                if (!$this->doctorModel->editAppointStatus($data['clinic_id'], $data['appointment_id'], 1)) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message    = 'تم التحويل للدكتور بنجاح';
                $Status     = 201;
                userMessage($Status, $Message);
                die();
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

    //***************************************************************** View Clinic ***********************************************************//
    public function view_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"    => $this->CheckToken['id'],
                "type"  => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'assistant') {
                $Message    = 'غير مصرح لك الإطلاع على العيادات';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->assistantModel->getClinicAss($data['id']);
            if (!$result) {
                $Message    = 'لم يتم العثور على بيانات';
                $Status     = 204;
                userMessage($Status, $Message);
                die();
            }

            $url = URL_PLACE;
            $new_data = [];
            foreach ($result as $element) {
                $element['logo'] = getImage($element['logo'], $url);
                @$isVerify = $this->userModel->getActivation($element['id'],'clinic');
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
            
            $Message    = 'تم جلب البيانات بنجاح';
            $Status     = 200;
            userMessage($Status, $Message, $new_data);
            die();
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** View Appointment ***********************************************************//
    public function view_appoint($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513); // INPUT_GET    //FILTER_SANITIZE_STRING

            $data = [
                "id"        => $this->CheckToken['id'],
                "type"      => $this->CheckToken['type'],
                "filter"    => @$_GET['filter'],
                "clinic_id" => @$id,
                "date"      => @$_GET['date'],
                "status"    => @$_GET['status']
            ];

            $data_err = [
                "clinic_id_err" => ''
            ];

            if ($data['type'] != 'assistant') {
                $Message    = 'غير مصرح لك الإطلاع على المواعيد';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!isset($_SESSION['clinic'])) {
                $Message    = 'الرجاء تسجيل الدخول إلى العيادة';
                $Status     = 400;
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
                            $Message    = 'يجب تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($get_clinic->isActive != 1) {
                            $Message    = 'الرجاء الإنتظار حتى يتم تنشيط العيادة';
                            $Status     = 400;
                            userMessage($Status, $Message);
                            die();
                        }
                        if ($data['clinic_id'] != $_SESSION['clinic']) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    }
                }
            }
            if (empty($data_err['clinic_id_err'])) {

                $date = $data['date'];
                $case = $data['status'];

                if (empty($data['date'])) {
                    $date = date("Y-m-d");
                } 
                if (empty($data['status'])) {
                    $case = '0';
                } 

                if (!empty($data['filter'])) {
                    @$result = $this->doctorModel->filterAppoint($data['clinic_id'], $date, $case, $data['filter']);
                    if (!$result) {
                        $Message    = 'لم يتم العثور على بيانات';
                        $Status     = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                } else {
                    @$result = $this->doctorModel->getAppointClinic($data['clinic_id'], $date, $case);
                    if (!$result) {
                        $Message    = 'لم يتم العثور على بيانات';
                        $Status     = 204;
                        userMessage($Status, $Message);
                        die();
                    }
                }

                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $result);
                die();
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
}

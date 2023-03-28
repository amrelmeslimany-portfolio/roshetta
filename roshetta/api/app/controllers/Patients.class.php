<?php

class Patients extends Controller  // Extends The Controller
{
    private $CheckToken, $userModel, $patientModel;
    public function __construct()
    {
        $this->patientModel = $this->model('Patient'); //New Patient
        $this->userModel    = $this->model('User'); //New User
        $this->CheckToken   = $this->tokenVerify();
        if (!$this->CheckToken) {
            $Message    = 'الرجاء تسجيل الدخول';
            $Status     = 401;
            userMessage($Status, $Message);
            die();
        }
    }
    public function document()
    {
        $Message    = '(API_Patients)برجاء الإطلاع على شرح';
        $Status     = 400;
        $url        = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#2e23d72c-423b-406b-91e1-e277e25ba2e0';
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

    //*************************************************** Add Appointment **************************************************************//

    public function add_appointment($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513); // INPUT_POST //FILTER_SANITIZE_STRING

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "appoint_date"  => @$_POST['appoint_date'],
                "clinic_id"     => @$id
            ];

            $data_err = [
                "appoint_date_err"  => '',
                "clinic_id_err"     => ''
            ];
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك القيام بالحجز';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['appoint_date'])) $data_err['appoint_date_err'] = 'برجاء إدخال تاريخ الحجز';

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['clinic_id'], 'clinic')) {
                        $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                    } else {
                        $appoint_data = $this->patientModel->getAppointStatus($data['id']);
                        if ($appoint_data) {
                            foreach ($appoint_data as $app) {
                                if ($data['clinic_id'] == $app['clinic_id']) {
                                    if ($app['appoint_case'] == 0) {
                                        $Message    = 'لديك حجز بالفعل';
                                        $Status     = 400;
                                        userMessage($Status, $Message);
                                        die();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (empty($data_err['appoint_date_err']) && empty($data_err['clinic_id_err'])) {

                $data_appoint = [
                    "patient_id"    => $data['id'],
                    "clinic_id"     => $data['clinic_id'],
                    "appoint_date"  => $data['appoint_date']
                ];

                if ($this->patientModel->addAppointPatient($data_appoint)) {
                    $Message    = 'تم إضافة الحجز بنجاح';
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

    //*************************************************** View Appointment **************************************************************//

    public function view_appointment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"    => $this->CheckToken['id'],
                "type"  => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على المواعيد';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->patientModel->getDataAppointPatient($data['id']);

            if (!$result) {
                $Message    = 'الرجاء المحاولة فى وقت لأحق';
                $Status     = 422;
                userMessage($Status, $Message);
                die();
            }
            $url = URL_PLACE;
            $new_result = [];
            foreach ($result as $element) {
                $element['logo'] = getImage($element['logo'], $url);
                $new_result[] = $element;
            }

            $Message    = 'تم جلب البيانات بنجاح';
            $Status     = 200;
            userMessage($Status, $Message, $new_result);
            die();
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** Edit Appointment **************************************************************//

    public function edit_appointment($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST  = filter_input_array(0, 513); // INPUT_POST //FILTER_SANITIZE_STRING
            $_GET   = filter_input_array(1, 513); // INPUT_GET //FILTER_SANITIZE_NUMBER_INT

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "appoint_id"    => @$_GET['appoint_id'],
                "appoint_date"  => @$_POST['appoint_date'],
                "clinic_id"     => @$id
            ];

            $data_err = [
                "appoint_date_err"  => '',
                "clinic_id_err"     => '',
                "appoint_id_err"    => ''
            ];
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك القيام بالتعديل';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['appoint_date'])) $data_err['appoint_date_err'] = 'برجاء إدخال تاريخ الحجز';

            if (empty($data['clinic_id'])) {
                $data_err['clinic_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['clinic_id'], 257)) {
                    $data_err['clinic_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['clinic_id'], 'clinic')) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }
            if (empty($data['appoint_id'])) {
                $data_err['appoint_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                if (!filter_var($data['appoint_id'], 257)) {
                    $data_err['appoint_id_err'] = 'معرف الموعد غير صالح';
                } else {
                    if (!$this->patientModel->getAppoint($data['appoint_id'])) $data_err['appoint_id_err'] = 'معرف الموعد غير صحيح';
                }
            }

            if (empty($data_err['appoint_date_err']) && empty($data_err['clinic_id_err']) && empty($data_err['appoint_id_err'])) {

                $data_appoint = [
                    "appoint_id"    => $data['appoint_id'],
                    "clinic_id"     => $data['clinic_id'],
                    "appoint_date"  => $data['appoint_date']
                ];

                if ($this->patientModel->editAppointPatient($data_appoint)) {
                    $Message    = 'تم تعديل الحجز بنجاح';
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

    //*************************************************** Delete Appointment **************************************************************//
    public function delete_appointment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 513); // INPUT_GET    //FILTER_SANITIZE_STRING

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "appoint_id"    => @$_GET['appoint_id']
            ];

            $data_err = [
                "appoint_id_err" => ''
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك القيام بالحذف';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['appoint_id'])) {
                $data_err['appoint_id_err'] = 'برجاء إدخال معرف الموعد';
            } else {
                if (!filter_var($data['appoint_id'], 257)) {
                    $data_err['appoint_id_err'] = 'معرف الموعد غير صالح';
                } else {
                    if (!$this->patientModel->getAppoint($data['appoint_id'])) $data_err['appoint_id_err'] = 'معرف الموعد غير صحيح';
                }
            }

            if (empty($data_err['appoint_id_err'])) {

                if ($this->patientModel->deleteAppointPatient($data['appoint_id'])) {
                    $Message    = 'تم حذف الحجز بنجاح';
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

    //*************************************************** View Clinic **************************************************************//

    public function view_clinic()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_Get = filter_input_array(1, 513); // INPUT_GET    //FILTER_SANITIZE_STRING

            $data = [
                "id"        => $this->CheckToken['id'],
                "type"      => $this->CheckToken['type'],
                "filter"    => @$_Get['filter']
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على العيادات';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!empty($data['filter'])) {
                @$result = $this->patientModel->filterClinic($data['filter']);
                if (!$result) {
                    $Message    = 'لم يتم العثور على بيانات';
                    $Status     = 204;
                    userMessage($Status, $Message);
                    die();
                }
            } else {
                @$result = $this->patientModel->getDataClinic();
                if (!$result) {
                    $Message    = 'لم يتم العثور على بيانات';
                    $Status     = 204;
                    userMessage($Status, $Message);
                    die();
                }
            }

            // $url = URL_PLACE;
            // $new_data = [];
            // foreach ($result as $element) {
            //     @$data_app = $this->patientModel->getDateAppointClinic($element['clinic_id'], $data['id']);
            //     $appoint_case = 1;
            //     $appoint_date = null;
            //     if ($data_app) {
            //         if ($data_app->appoint_case == 0) {
            //             $appoint_case = 0;
            //             $appoint_date = $data_app->appoint_date;
            //         } 
            //     } 
            //     $element['logo'] = getImage($element['logo'], $url);
            //     $element['appoint_case'] = $appoint_case;
            //     $element['appoint_date'] = $appoint_date;
            //     $new_data[] = $element;
            // }

            @$data_app = $this->patientModel->getAppointStatus($data['id']);
            $url = URL_PLACE;
            $new_data = [];
            foreach ($result as $element) {
                $appoint_case = 1;
                $appoint_date = null;
                if ($data_app) {
                    foreach ($data_app as $app) {
                        if ($element['clinic_id'] == $app['clinic_id']) {
                            if ($app['appoint_case'] == 0) {
                                $appoint_case = 0;
                                $appoint_date = $app['appoint_date'];
                            }
                        }
                    }
                }
                $element['logo'] = getImage($element['logo'], $url);
                $element['appoint_case'] = $appoint_case;
                $element['appoint_date'] = $appoint_date;
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

    //*************************************************** View Clinic Details **************************************************************//

    public function view_clinic_details($id = null)
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
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على التفاصيل';
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
                    if (!$this->patientModel->getPlace($data['clinic_id'], 'clinic')) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                @$result = $this->patientModel->viewClinicDetails($data['clinic_id'], $data['id']);
                if (!$result) {
                    $Message    = 'لم يتم العثور على بيانات';
                    $Status     = 204;
                    userMessage($Status, $Message);
                    die();
                }

                $url = URL_PLACE;
                $data_message = clinicMessageDetails($result, $url);
                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_message);
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

    //*************************************************** View Disease **************************************************************//

    public function view_disease()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"    => $this->CheckToken['id'],
                "type"  => $this->CheckToken['type']
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على العيادات';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->patientModel->getDataDisease($data['id']);

            if (!$result) {
                $Message    = 'لم يتم العثور على مرض';
                $Status     = 204;
                userMessage($Status, $Message);
                die();
            }

            $Message    = 'تم جلب البيانات بنجاح';
            $Status     = 200;
            userMessage($Status, $Message, $result);
            die();
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** View Pharmacy **************************************************************//

    public function view_pharmacy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"    => $this->CheckToken['id'],
                "type"  => $this->CheckToken['type']
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على الصيداليات';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->patientModel->getDataPharmacy();

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

    //*************************************************** View Pharmacy Details **************************************************************//

    public function view_pharmacy_details($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "pharmacy_id"   => @$id
            ];

            $data_err = [
                "pharmacy_id_err" => ''
            ];
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على التفاصيل';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف الصيدلية';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['pharmacy_id'], 'pharmacy')) $data_err['pharmacy_id_err'] = 'معرف الصيدلية غير صحيح';
                }
            }

            if (empty($data_err['pharmacy_id_err'])) {

                @$result = $this->patientModel->viewPharmacyDetails($data['pharmacy_id'], $data['id']);
                if (!$result) {
                    $Message    = 'لم يتم العثور على بيانات';
                    $Status     = 204;
                    userMessage($Status, $Message);
                    die();
                }

                $url = URL_PLACE;
                $data_message = pharmacyMessageDetails($result, $url);
                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_message);
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

    //*************************************************** View Prescript **************************************************************//

    public function view_prescript()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id"    => $this->CheckToken['id'],
                "type"  => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على الروشتات';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->patientModel->getDataPrescript($data['id']);

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
        } else {
            $Message    = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status     = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** Send Prescript Pharmacy **************************************************************//

    public function send_prescript_pharmacy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 519); // INPUT_GET //FILTER_SANITIZE_NUMBER_INT

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "prescript_id"  => @$_GET['prescript_id'],
                "pharmacy_id"   => @$_GET['pharmacy_id']
            ];

            $data_err = [
                "prescript_id_err"  => '',
                "pharmacy_id_err"   => ''
            ];
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك القيام بالإرسال';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['pharmacy_id'])) {
                $data_err['pharmacy_id_err'] = 'برجاء إدخال معرف العيادة';
            } else {
                if (!filter_var($data['pharmacy_id'], 257)) {
                    $data_err['pharmacy_id_err'] = 'معرف العيادة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['pharmacy_id'], 'pharmacy')) $data_err['pharmacy_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data['prescript_id'])) {
                $data_err['prescript_id_err'] = 'برجاء إدخال معرف الروشتة';
            } else {
                if (!filter_var($data['prescript_id'], 257)) {
                    $data_err['prescript_id_err'] = 'معرف الروشتة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['prescript_id'], 'prescript')) $data_err['prescript_id_err'] = 'معرف الروشتة غير صحيح';
                }
            }

            if (empty($data_err['prescript_id_err']) && empty($data_err['pharmacy_id_err'])) {

                $data_order = [
                    "patient_id"    => $data['id'],
                    "prescript_id"  => $data['prescript_id'],
                    "pharmacy_id"   => $data['pharmacy_id']
                ];

                if ($this->patientModel->addOrderPatient($data_order)) {
                    $Message    = 'تم إضافة الطلب بنجاح';
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

    //*************************************************** View Prescript Details **************************************************************//

    public function view_prescript_details()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 519); // INPUT_GET //FILTER_SANITIZE_NUMBER_INT

            $data = [
                "id"            => $this->CheckToken['id'],
                "type"          => $this->CheckToken['type'],
                "prescript_id"  => @$_GET['prescript_id']
            ];

            $data_err = [
                "prescript_id_err" => ''
            ];

            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على التفاصيل';
                $Status     = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['prescript_id'])) {
                $data_err['prescript_id_err'] = 'برجاء إدخال معرف الروشتة';
            } else {
                if (!filter_var($data['prescript_id'], 257)) {
                    $data_err['prescript_id_err'] = 'معرف الروشتة غير صالح';
                } else {
                    if (!$this->patientModel->getPlace($data['prescript_id'], 'prescript')) $data_err['prescript_id_err'] = 'معرف الروشتة غير صحيح';
                }
            }

            if (empty($data_err['prescript_id_err'])) {

                @$result_prescript = $this->patientModel->getPrescriptDetails($data['prescript_id'], $data['id']);
                if (!$result_prescript) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$result_medicine = $this->patientModel->getPrescriptMedicine($data['prescript_id']);
                if (!$result_medicine) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
                    userMessage($Status, $Message);
                    die();
                }

                @$decode_medicine = decodeMedicine($result_medicine);
                if (!$decode_medicine) {
                    $Message    = 'الرجاء المحاولة فى وقت لأحق';
                    $Status     = 422;
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
                    "prescript_data"    => $new_result_prescript,
                    "medicine_data"     => $new_decode_medicine
                ];

                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_message);
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

    //*************************************************** View Appoint Date **************************************************************//

    public function view_appoint_date($id = null)
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
            if ($data['type'] != 'patient') {
                $Message    = 'غير مصرح لك الإطلاع على التفاصيل';
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
                    if (!$this->patientModel->getPlace($data['clinic_id'], 'clinic')) $data_err['clinic_id_err'] = 'معرف العيادة غير صحيح';
                }
            }

            if (empty($data_err['clinic_id_err'])) {

                @$data_appoint = $this->patientModel->getDateAppoint($data['clinic_id'], $data['id']);
                if (!$data_appoint) {
                    $Message    = 'لم يتم العثور على بيانات';
                    $Status     = 204;
                    userMessage($Status, $Message);
                    die();
                }
                $Message    = 'تم جلب البيانات بنجاح';
                $Status     = 200;
                userMessage($Status, $Message, $data_appoint->appoint_date);
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

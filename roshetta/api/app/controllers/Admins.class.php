<?php

class Admins extends Controller
{
    private $CheckToken, $pharmacistModel, $doctorModel, $userModel, $adminModel;
    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->userModel = new User();
        $this->doctorModel = new Doctor();
        $this->pharmacistModel = new Pharmacist();
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
        $Message = '(API_Admins)برجاء الإطلاع على شرح';
        $Status = 400;
        $url = 'https://documenter.getpostman.com/view/25605546/2s93CRMCfA#ac878ddb-58e6-4ba6-82fc-48991a6ca4dd';
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

    //*************************************************** View Activation Users And Places **************************************************************//
    public function view_activation()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "status" => @$_GET['status'],
                "filter" => @$_GET['filter']
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!empty($data['type_user'])) {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic'];
                if (!in_array($data['type_user'], $types)) {
                    $Message = 'النوع غير مدعوم';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            if (empty($data['status'])) {
                $data['status'] = 0;
            }

            @$data_active = $this->adminModel->getActivationData($data['type_user'], $data['status'], $data['filter']);
            if (!$data_active) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }

            $url_person = URL_PERSON;
            $url_place = URL_PLACE;

            $new_doctor = [];
            if (!empty($data_active['doctor'])) {
                foreach ($data_active['doctor'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_doctor[] = $values;
                }
            }

            $new_pharmacist = [];
            if (!empty($data_active['pharmacist'])) {
                foreach ($data_active['pharmacist'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_pharmacist[] = $values;
                }
            }

            $new_clinic = [];
            if (!empty($data_active['clinic'])) {
                foreach ($data_active['clinic'] as $values) {
                    $values['logo'] = getImage($values['logo'], $url_place);
                    $new_clinic[] = $values;
                }
            }

            $new_pharmacy = [];
            if (!empty($data_active['pharmacy'])) {
                foreach ($data_active['pharmacy'] as $values) {
                    $values['logo'] = getImage($values['logo'], $url_place);
                    $new_pharmacy[] = $values;
                }
            }

            $data_message = array_merge($new_doctor, $new_pharmacist, $new_clinic, $new_pharmacy);

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $data_message);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //*************************************************** Active And Not Active Users And Places **************************************************************//
    public function activation_user_place()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "activation_id" => @$_GET['activation_id'],
                "status" => @$_GET['status'],
            ];

            $data_err = [
                "type_err" => '',
                "activation_id_err" => '',
                "status_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء تحديد نوع الحساب';
            } else {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if ($data['type_user'] == 'doctor' || $data['type_user'] == 'pharmacist') {
                        $type = 'activation_person';
                    } else {
                        $type = 'activation_place';
                    }
                }
            }

            if (empty($data['activation_id'])) {
                $data_err['activation_id_err'] = 'برجاء إدخال معرف الحساب';
            } else {
                @$data_active = $this->userModel->getPlace($type, $data['activation_id']);
                if (!$data_active) {
                    $data_err['activation_id_err'] = 'المعرف غير صحيح';
                } else {
                    if ($data_active->isActive == $data['status']) {
                        if ($data['status'] == 1) {
                            $Message = 'الحساب مفعل بالفعل';
                            $Status = 202;
                            userMessage($Status, $Message);
                            die();
                        } else {
                            $Message = 'الحساب غير مفعل بالفعل';
                            $Status = 202;
                            userMessage($Status, $Message);
                            die();
                        }
                    }
                }
            }

            if (empty($data['status'])) {
                $data['status'] = 0;
            } else {
                $sta = [0, 1, -1];
                if (!in_array($data['status'], $sta))
                    $data_err['status_err'] = 'نوع الحالة غير صالح';
            }

            if (empty($data_err['activation_id_err']) && empty($data_err['type_err']) && empty($data_err['status_err'])) {

                if (!$this->adminModel->editStatus($type, $data['type_user'], $data['activation_id'], $data['status'])) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                if ($data['status'] == 1) {

                    if ($data['type_user'] == 'doctor' || $data['type_user'] == 'pharmacist') {
                        $data_user = $this->userModel->getPlace($data['type_user'], $data_active->user_id);
                    } else {
                        $data_place = $this->userModel->getPlace($data['type_user'], $data_active->place_id);
                        if ($data['type_user'] == 'clinic') {
                            $data_user = $this->userModel->getPlace('doctor', $data_place->doctor_id);
                        } else {
                            $data_user = $this->userModel->getPlace('pharmacist', $data_place->pharmacist_id);
                        }
                    }
                    //************************************* Send Email Success ***********************************//
                    $data_email = [
                        "type" => $data['type_user'],
                        "user_name" => $data_user->name,
                        "email" => $data_user->email,
                    ];
                    $mail_data = activeEmailBody($data_email); //Function To Get Email Data
                    @require_once('app/helpers/email/mail.php');
                    $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                    $mail->addAddress($mail_data['email']);
                    $mail->Subject = $mail_data['subject'];
                    $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);
                    @$mail->send();

                    $Message = 'تم تنشيط الحساب بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                    //************************************* End Send Email Success ***********************************//

                } elseif ($data['status'] == 0) {
                    $Message = 'تم إلغاء تنشيط الحساب بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'تم رفض المستندات بنجاح';
                    $Status = 201;
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

    //***************************************************************** View Activation Image ***********************************************************//
    public function view_activation_image()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "activation_id" => @$_GET['activation_id']
            ];

            $data_err = [
                "type_err" => '',
                "activation_id_err" => '',
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء تحديد نوع الحساب';
            } else {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if ($data['type_user'] == 'doctor' || $data['type_user'] == 'pharmacist') {
                        $type = 'activation_person';
                    } else {
                        $type = 'activation_place';
                    }
                }
            }

            if (empty($data['activation_id'])) {
                $data_err['activation_id_err'] = 'برجاء إدخال معرف الحساب';
            } else {
                @$data_active = $this->userModel->getPlace($type, $data['activation_id']);
                if (!$data_active) {
                    $data_err['activation_id_err'] = 'المعرف غير صحيح';
                }
            }

            if (empty($data_err['activation_id_err']) && empty($data_err['type_err'])) {

                if ($type == 'activation_person') {

                    $url = URL_ACTIVATION_PERSON;
                    @$data_image = getImageActive($data_active->images, $url);

                    $Message = 'تم جلب البيانات بنجاح';
                    $Status = 200;
                    userMessage($Status, $Message, $data_image);
                    die();
                } else {
                    $url = URL_ACTIVATION_PLACE;
                    @$data_image = getImage($data_active->license_img, $url);

                    $Message = 'تم جلب البيانات بنجاح';
                    $Status = 200;
                    userMessage($Status, $Message, $data_image);
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

    //***************************************************************** Add Video ***********************************************************//
    public function add_video()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "video_name" => @$_FILES['video']["name"],
                "video_size" => @$_FILES['video']["size"],
                "tmp_name" => @$_FILES['video']["tmp_name"],
            ];
            $data_err = [
                "video_err" => '',
                "type_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك إضافة تلك البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء تحديد نوع الحساب';
            } else {
                $types = ['doctor', 'patient', 'pharmacist', 'assistant'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                }
            }

            if (empty($data['video_name'])) {
                $data_err['video_err'] = 'برجاء تحميل فيديو';
            } else {
                if ($data['video_size'] > 4000000)
                    $data_err['video_err'] = '(4M)يجب أن يكون حجم الفيديو أقل من'; //To Specify The Image Size  < 4M
            }
            if (empty($data_err['video_err']) && empty($data_err['type_err'])) {

                @$get_video = $this->userModel->getVideo($data['type_user']);
                if (!$get_video) {
                    $db_name = null;
                } else {
                    $db_name = $get_video->video;
                }

                $data_video = [
                    "type" => $data['type_user'],
                    "db_name" => $db_name,
                    "name" => $data['video_name'],
                    "tmp" => $data['tmp_name'],
                ];

                @$url_video = addVideo($data_video);

                if (!$url_video) {
                    $Message = 'صيغة الملف غير مدعوم';
                    $Status = 415;
                    userMessage($Status, $Message);
                    die();
                }
                $data_url = [
                    "type" => $data['type_user'],
                    "video" => $url_video
                ];

                if ($this->adminModel->editVideo($data_url)) {
                    $Message = 'تم إضافة الفيديو بنجاح';
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

    //*************************************************** View Video **************************************************************//
    public function view_video()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            @$result = $this->adminModel->getVideoUser();
            if (!$result) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }
            $url = URL_VIDEO;
            $new_video = [];
            foreach ($result as $element) {
                $element['video'] = getVideo(["name" => $element['video'], "url" => $url]);
                $new_video[] = $element;
            }

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $new_video);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //***************************************************************** Delete Video ***********************************************************//
    public function remove_video()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "type_user" => @$_GET['type']
            ];

            $data_err = [
                "type_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك حذف تلك البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء تحديد نوع الحساب';
            } else {
                $types = ['doctor', 'patient', 'pharmacist', 'assistant'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    @$get_video = $this->adminModel->getVideoByType($data['type_user']);
                    if (!$get_video) {
                        $data_err['type_err'] = 'النوع غير موجود';
                    }
                }
            }

            if (empty($data_err['type_err'])) {

                $types = ['df_doctor', 'df_patient', 'df_pharmacist', 'df_assistant'];
                if (!in_array(explode(".", $get_video->video)[0], $types)) {
                    if (!removeVideo($get_video->video)) {
                        $Message = 'الرجاء المحاولة فى وق لأحق';
                        $Status = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                }

                switch ($data['type_user']) {
                    case 'doctor':
                        $url_video = DF_VIDEO_DO;
                        break;
                    case 'pharmacist':
                        $url_video = DF_VIDEO_PH;
                        break;
                    case 'assistant':
                        $url_video = DF_VIDEO_AS;
                        break;
                    default:
                        $url_video = DF_VIDEO_PA;
                }

                $data_url = [
                    "type" => $data['type_user'],
                    "video" => $url_video
                ];

                if ($this->adminModel->editVideo($data_url)) {
                    $Message = 'تم حذف الفيديو بنجاح';
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

    //************************************************ View Users And Places ***************************************************************//
    public function view_users()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "filter" => @$_GET['filter']
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!empty($data['type_user'])) {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $Message = 'النوع غير مدعوم';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }
            }

            @$data_user = $this->adminModel->getDataUser($data['type_user'], $data['filter']);
            if (!$data_user) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 204;
                userMessage($Status, $Message);
                die();
            }

            $url_person = URL_PERSON;
            $url_place = URL_PLACE;

            $new_doctor = [];
            if (!empty($data_user['doctor'])) {
                foreach ($data_user['doctor'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_doctor[] = $values;
                }
            }

            $new_pharmacist = [];
            if (!empty($data_user['pharmacist'])) {
                foreach ($data_user['pharmacist'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_pharmacist[] = $values;
                }
            }

            $new_patient = [];
            if (!empty($data_user['patient'])) {
                foreach ($data_user['patient'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_patient[] = $values;
                }
            }

            $new_assistant = [];
            if (!empty($data_user['assistant'])) {
                foreach ($data_user['assistant'] as $values) {
                    $values['profile_img'] = getImage($values['profile_img'], $url_person);
                    $new_assistant[] = $values;
                }
            }

            $new_clinic = [];
            if (!empty($data_user['clinic'])) {
                foreach ($data_user['clinic'] as $values) {
                    $values['logo'] = getImage($values['logo'], $url_place);
                    array_push($values, $values['type'] = "clinic");
                    unset($values[0]);
                    $new_clinic[] = $values;
                }
            }

            $new_pharmacy = [];
            if (!empty($data_user['pharmacy'])) {
                foreach ($data_user['pharmacy'] as $values) {
                    $values['logo'] = getImage($values['logo'], $url_place);
                    array_push($values, $values['type'] = "pharmacy");
                    unset($values[0]);
                    $new_pharmacy[] = $values;
                }
            }

            $data_message = array_merge($new_doctor, $new_pharmacist, $new_patient, $new_assistant, $new_clinic, $new_pharmacy);

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $data_message);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************ View Users And Places Details ***************************************************************//
    public function view_users_details($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "user_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "user_id_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['user_id'])) {
                        $data_err['user_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        @$data_user = $this->userModel->getPlace($data['type_user'], $data['user_id']);
                        if (!$data_user)
                            $data_err['user_id_err'] = 'المعرف غير صحيح';
                    }
                }
            }

            if (empty($data_err['type_err']) && empty($data_err['user_id_err'])) {

                $url_person = URL_PERSON;
                $url_place = [
                    "place" => URL_PLACE,
                    "person" => URL_PERSON
                ];
                if ($data['type_user'] == 'clinic' || $data['type_user'] == 'pharmacy') {
                    if ($data['type_user'] == 'clinic') {
                        $num_clinic = $this->doctorModel->numberAppointPres($data['user_id']);
                        $data_user = viewClinic($data_user, $num_clinic, $url_place);
                    } else {
                        $num_pharmacy = $this->pharmacistModel->numberPrescript($data['user_id']);
                        $data_user = viewPharmacy($data_user, $num_pharmacy, $url_place);
                    }
                } else {
                    @$data_user = messageProfile($data_user, $url_person);
                }

                if (!empty($data_user)) {
                    $Message = 'تم جلب البيانات بنجاح';
                    $Status = 200;
                    userMessage($Status, $Message, $data_user);
                    die();
                } else {
                    $Message = 'لم يتم العثور على بيانات';
                    $Status = 204;
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

    //************************************************ Delete Users And Places ***************************************************************//
    public function remove_user_place($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "user_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "user_id_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك حذف تلك البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['doctor', 'pharmacy', 'pharmacist', 'clinic', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['user_id'])) {
                        $data_err['user_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        if (!$this->userModel->getPlace($data['type_user'], $data['user_id']))
                            $data_err['user_id_err'] = 'المعرف غير صحيح';
                    }
                }
            }

            if (empty($data_err['type_err']) && empty($data_err['user_id_err'])) {

                if (!$this->adminModel->deleteUserPlace($data['type_user'], $data['user_id'])) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 400;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم الحذف بنجاح';
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

    //************************************************ View Message Users ***************************************************************//
    public function view_message()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "type_user" => @$_GET['type'],
                "status" => @$_GET['status']
            ];

            $data_err = [
                "type_err" => '',
                "status_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (!empty($data['type_user'])) {
                $types = ['doctor', 'patient', 'pharmacist', 'assistant'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                }
            }

            if (empty($data['status'])) {
                $data['status'] = 0;
            } else {
                $sta = [0, 1];
                if (!in_array($data['status'], $sta))
                    $data_err['status_err'] = 'نوع الحالة غير صالح';
            }

            if (empty($data_err['type_err']) && empty($data_err['status_err'])) {
                @$data_message = $this->adminModel->getMessageUser($data['type_user'], $data['status']);
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

    //************************************************ Reply Message Users ***************************************************************//
    public function reply_message_user($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "message" => @$_POST['message'],
                "message_id" => @$id
            ];

            $data_err = [
                "message_err" => '',
                "message_id_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الرد على الرسائل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['message']))
                $data_err['message_err'] = 'برجاء إدخال الرد';

            if (empty($data['message_id'])) {
                $data_err['message_id_err'] = 'برجاء إدخال المعرف';
            } else {
                @$data_user = $this->userModel->getPlace('message', $data['message_id']);
                if (!$data_user)
                    $data_err['message_id_err'] = 'المعرف غير صحيح';
            }

            if (empty($data_err['message_err']) && empty($data_err['message_id_err'])) {

                //************************************* Send Email Success ***********************************//
                $data_email = [
                    "type" => $data_user->role,
                    "user_name" => $data_user->name,
                    "email" => $data_user->email,
                    "message" => $data['message']
                ];
                $mail_data = replyMessage($data_email); //Function To Get Email Data
                @require_once('app/helpers/email/mail.php');
                $mail->setFrom('roshettateam@gmail.com', $mail_data['name']);
                $mail->addAddress($mail_data['email']);
                $mail->Subject = $mail_data['subject'];
                $mail->Body = emailBody($mail_data['icon'], $mail_data['body']);

                if (@$mail->send()) {
                    if (!$this->adminModel->replyMessageUser($data['message_id'])) {
                        $Message = '(فشل تعديل الحالة) تم الرد بنجاح';
                        $Status = 422;
                        userMessage($Status, $Message);
                        die();
                    }
                    $Message = 'تم الرد بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }
                //************************************* End Send Email Success ***********************************//
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

    //************************************************ View Number Users And Places ***************************************************************//
    public function view_number_all()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك الإطلاع على البيانات';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }
            @$data_message = $this->adminModel->getNumberAll();
            if (!$data_message) {
                $Message = 'لم يتم العثور على بيانات';
                $Status = 400;
                userMessage($Status, $Message);
                die();
            }

            $Message = 'تم جلب البيانات بنجاح';
            $Status = 200;
            userMessage($Status, $Message, $data_message);
            die();
        } else {
            $Message = 'غير مصرح الدخول عبر هذة الطريقة';
            $Status = 405;
            userMessage($Status, $Message);
            die();
        }
    }

    //************************************************ Edit Password User ***************************************************************//
    public function edit_password_user($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513);
            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "password" => @$_POST['password'],
                "confirm_password" => @$_POST['confirm_password'],
                "type_user" => @$_GET['type'],
                "user_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "user_id_err" => '',
                "password_err" => '',
                "confirm_password_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك التعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['doctor', 'pharmacist', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['user_id'])) {
                        $data_err['user_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        if (!$this->userModel->getPlace($data['type_user'], $data['user_id']))
                            $data_err['user_id_err'] = 'المعرف غير صحيح';
                    }
                }
            }

            if (empty($data['password'])) {
                $data_err['password_err'] = 'برجاء إدخال كلمة المرور'; // Check User Password
            } else {
                if (strlen($data['password']) < 6)
                    $data_err['password_err'] = 'كلمة المرور يجب الأ تقل عن 6 عناصر'; // Check Length Password
            }
            if (empty($data['confirm_password'])) {
                $data_err['confirm_password_err'] = 'برجاء تأكيد كلمة المرور'; // Check Confirm Password
            } else {
                if ($data['password'] != $data['confirm_password'])
                    $data_err['confirm_password_err'] = 'كلمة المرور غير متطابقة'; //Check Validate Password
            }

            if (
                empty($data_err['type_err'])
                && empty($data_err['user_id_err'])
                && empty($data_err['password_err'])
                && empty($data_err['confirm_password_err'])
            ) {
                @$password = password_hash($data['password'], "2y"); //PASSWORD_DEFAULT Hash
                $data_password = [
                    "id" => $data['user_id'],
                    "type" => $data['type_user'],
                    "password" => $password
                ];

                if (!@$this->userModel->editPassword($data_password)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم التعديل بنجاح';
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

    //************************************************ Edit Email And SSD User ***************************************************************//
    public function edit_email_ssd_user($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513);
            $_GET = filter_input_array(1, 513);

            $data = [
                //Array Data
                "id" => @$this->CheckToken['id'],
                "type" => @$this->CheckToken['type'],
                "user_q" => @strtolower($_POST['user_q']),
                "type_user_q" => @$_GET['type_user_q'],
                "type_user" => @$_GET['type'],
                "user_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "user_id_err" => '',
                "user_q_err" => '',
                "type_user_q_err" => '',
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك التعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['doctor', 'pharmacist', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['user_id'])) {
                        $data_err['user_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        if (!$this->userModel->getPlace($data['type_user'], $data['user_id']))
                            $data_err['user_id_err'] = 'المعرف غير صحيح';
                    }
                }
            }

            if (empty($data['type_user_q'])) {
                $data_err['type_user_q_err'] = 'برجاء إدخال نوع التعديل';
            } else {
                $types_q = ['email', 'ssd'];
                if (!in_array($data['type_user_q'], $types_q)) {
                    $data_err['type_user_q_err'] = 'النوع غير مدعوم';
                } else {
                    if ($data['type_user_q'] == 'email') {
                        if (empty($data['user_q'])) {
                            $data_err['user_q_err'] = 'برجاء إدخال البريد الإلكترونى';
                        } else {
                            if (!filter_var($data['user_q'], 274)) {
                                $data_err['user_q_err'] = 'البريد الإلكترونى غير صالح'; // FILTER_VALIDATE_EMAIL
                            } else {
                                if ($this->userModel->getUserEmail($data['user_q'], $data['type_user']))
                                    $data_err['user_q_err'] = 'البريد الإلكترونى موجود من قبل';
                            }
                        }
                    } else {
                        if (empty($data['user_q'])) { // Check SSD
                            $data_err['user_q_err'] = 'برجاء إدخال الرقم القومى';
                        } else {
                            if (!filter_var($data['user_q'], 257) || strlen($data['user_q']) != 14) {
                                $data_err['user_q_err'] = 'الرقم القومى غير صالح'; // FILTER_VALIDATE_INT
                            } else {
                                if ($this->userModel->getUserSSD($data['user_q'], $data['type_user']))
                                    $data_err['user_q_err'] = 'الرقم القومى موجود من قبل';
                            }
                        }
                    }
                }
            }

            if (
                empty($data_err['type_err'])
                && empty($data_err['user_id_err'])
                && empty($data_err['user_q_err'])
                && empty($data_err['type_user_q_err'])
            ) {

                $data_update = [
                    "id" => $data['user_id'],
                    "role" => $data['type_user'],
                    "type" => $data['type_user_q'],
                    "data" => $data['user_q']
                ];

                if (!$this->adminModel->updateEmailSSD($data_update)) {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 422;
                    userMessage($Status, $Message);
                    die();
                }

                $Message = 'تم التعديل بنجاح';
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

    //************************************************ Edit Users Data ***************************************************************//
    public function edit_profile_user($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513);
            $_GET = filter_input_array(1, 513);

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "name" => @$_POST['name'],
                "phone_number" => @$_POST['phone_number'],
                "governorate" => @$_POST['governorate'],
                "birth_date" => @$_POST['birth_date'],
                "gender" => @$_POST['gender'],
                "weight" => @$_POST['weight'],
                "height" => @$_POST['height'],
                "type_user" => @$_GET['type'],
                "specialist" => @$_POST['specialist'],
                "user_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "user_id_err" => '',
                "phone_number_err" => '',
                "governorate_err" => '',
                "weight_err" => '',
                "height_err" => '',
                "name_err" => '',
                "birth_date_err" => '',
                "gender_err" => '',
                "specialist_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك التعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['doctor', 'pharmacist', 'assistant', 'patient'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['user_id'])) {
                        $data_err['user_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        if (!$this->userModel->getPlace($data['type_user'], $data['user_id']))
                            $data_err['user_id_err'] = 'المعرف غير صحيح';
                    }

                    if (empty($data['phone_number'])) {
                        $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                    } else {
                        if (!filter_var($data['phone_number'], 519) || strlen($data['phone_number']) != 11) {
                            $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                        } else {
                            @$result = $this->userModel->getUserPhone($data['phone_number'], $data['type_user']);
                            if ($result) {
                                if ($data['user_id'] != $result->id)
                                    $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                            }
                        }
                    }
                }
            }

            if (empty($data['governorate']))
                $data_err['governorate_err'] = 'برجاء إختيار المحافظة';
            if (empty($data['name']))
                $data_err['name_err'] = 'برجاء إدخال الإسم';
            if (empty($data['birth_date']))
                $data_err['birth_date_err'] = 'برجاء إختيار تاريخ الميلاد';
            if (empty($data['gender']))
                $data_err['gender_err'] = 'برجاء إختيار النوع';
            if ($data['type_user'] == 'doctor') {
                if (empty($data['specialist']))
                    $data_err['specialist_err'] = 'برجاء إختيار التخصص';
            }
            if ($data['type_user'] == 'patient') {
                if (empty($data['weight']))
                    $data_err['weight_err'] = 'برجاء إدخال الوزن';
                if (empty($data['height']))
                    $data_err['height_err'] = 'برجاء إدخال الطول';
            }

            if (
                empty($data_err['phone_number_err'])
                && empty($data_err['type_err'])
                && empty($data_err['user_id_err'])
                && empty($data_err['governorate_err'])
                && empty($data_err['weight_err'])
                && empty($data_err['height_err'])
                && empty($data_err['specialist_err'])
                && empty($data_err['gender_err'])
                && empty($data_err['birth_date_err'])
                && empty($data_err['name_err'])
            ) {
                switch ($data['type_user']) {
                    case 'patient':
                        $data_user = [
                            "id" => $data['user_id'],
                            "type" => $data['type_user'],
                            "phone_number" => $data['phone_number'],
                            "governorate" => $data['governorate'],
                            "weight" => $data['weight'],
                            "height" => $data['height'],
                            "name" => $data['name'],
                            "birth_date" => $data['birth_date'],
                            "gender" => $data['gender'],
                        ];
                        break;
                    case 'doctor':
                        $data_user = [
                            "id" => $data['user_id'],
                            "type" => $data['type_user'],
                            "phone_number" => $data['phone_number'],
                            "governorate" => $data['governorate'],
                            "specialist" => $data['specialist'],
                            "name" => $data['name'],
                            "birth_date" => $data['birth_date'],
                            "gender" => $data['gender'],
                        ];
                        break;
                    default:
                        $data_user = [
                            "id" => $data['user_id'],
                            "type" => $data['type_user'],
                            "phone_number" => $data['phone_number'],
                            "governorate" => $data['governorate'],
                            "name" => $data['name'],
                            "birth_date" => $data['birth_date'],
                            "gender" => $data['gender'],
                        ];
                }

                if ($this->adminModel->editUserProfile($data_user)) {
                    $Message = 'تم التعديل بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 400;
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

    //************************************************ Edit Places Data ***************************************************************//
    public function edit_profile_place($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(0, 513);
            $_GET = filter_input_array(1, 513);

            $data = [
                "id" => $this->CheckToken['id'],
                "type" => $this->CheckToken['type'],
                "name" => @$_POST['name'],
                "phone_number" => @$_POST['phone_number'],
                "governorate" => @$_POST['governorate'],
                "start_working" => @$_POST['start_working'],
                "end_working" => @$_POST['end_working'],
                "address" => @$_POST['address'],
                "owner" => @$_POST['owner'],
                "price" => @$_POST['price'],
                "type_user" => @$_GET['type'],
                "specialist" => @$_POST['specialist'],
                "place_id" => @$id
            ];

            $data_err = [
                "type_err" => '',
                "place_id_err" => '',
                "phone_number_err" => '',
                "governorate_err" => '',
                "start_working_err" => '',
                "end_working_err" => '',
                "name_err" => '',
                "address_err" => '',
                "price_err" => '',
                "specialist_err" => '',
                "owner_err" => ''
            ];

            if ($data['type'] != 'admin') {
                $Message = 'غير مصرح لك التعديل';
                $Status = 403;
                userMessage($Status, $Message);
                die();
            }

            if (empty($data['type_user'])) {
                $data_err['type_err'] = 'برجاء إدخال النوع';
            } else {
                $types = ['clinic', 'pharmacy'];
                if (!in_array($data['type_user'], $types)) {
                    $data_err['type_err'] = 'النوع غير مدعوم';
                } else {
                    if (empty($data['place_id'])) {
                        $data_err['place_id_err'] = 'برجاء إدخال المعرف';
                    } else {
                        if (!$this->userModel->getPlace($data['type_user'], $data['place_id']))
                            $data_err['place_id_err'] = 'المعرف غير صحيح';
                    }

                    if (empty($data['phone_number'])) {
                        $data_err['phone_number_err'] = 'برجاء إدخال رقم الهاتف';
                    } else {
                        if (!filter_var($data['phone_number'], 519) || strlen($data['phone_number']) != 11) {
                            $data_err['phone_number_err'] = 'رقم الهاتف غير صالح';
                        } else {
                            @$result = $this->userModel->getUserPhone($data['phone_number'], $data['type_user']);
                            if ($result) {
                                if ($data['place_id'] != $result->id)
                                    $data_err['phone_number_err'] = 'رقم الهاتف موجود من قبل';
                            }
                        }
                    }
                }
            }

            if (empty($data['governorate']))
                $data_err['governorate_err'] = 'برجاء إختيار المحافظة';
            if (empty($data['name']))
                $data_err['name_err'] = 'برجاء إدخال الإسم';
            if (empty($data['start_working']))
                $data_err['start_working_err'] = 'برجاء إختيار ميعاد الفتح';
            if (empty($data['end_working']))
                $data_err['end_working_err'] = 'برجاء إختيار ميعاد الغلق';
            if (empty($data['owner']))
                $data_err['owner_err'] = 'برجاء إدخال إسم المالك';
            if (empty($data['address']))
                $data_err['address_err'] = 'برجاء إدخال العنوان';

            if ($data['type_user'] == 'clinic') {
                if (empty($data['specialist']))
                    $data_err['specialist_err'] = 'برجاء إختيار التخصص';
                if (empty($data['price']))
                    $data_err['price_err'] = 'برجاء إدخال السعر';
            }

            if (
                empty($data_err['phone_number_err'])
                && empty($data_err['type_err'])
                && empty($data_err['place_id_err'])
                && empty($data_err['governorate_err'])
                && empty($data_err['start_working_err'])
                && empty($data_err['end_working_err'])
                && empty($data_err['specialist_err'])
                && empty($data_err['owner_err'])
                && empty($data_err['address_err'])
                && empty($data_err['name_err'])
                && empty($data_err['price_err'])
            ) {
                switch ($data['type_user']) {
                    case 'clinic':
                        $data_place = [
                            "id" => $data['place_id'],
                            "type" => $data['type_user'],
                            "phone_number" => $data['phone_number'],
                            "governorate" => $data['governorate'],
                            "specialist" => $data['specialist'],
                            "price" => $data['price'],
                            "name" => $data['name'],
                            "address" => $data['address'],
                            "owner" => $data['owner'],
                            "start_working" => $data['start_working'],
                            "end_working" => $data['end_working']
                        ];
                        break;
                    default:
                        $data_place = [
                            "id" => $data['place_id'],
                            "type" => $data['type_user'],
                            "phone_number" => $data['phone_number'],
                            "governorate" => $data['governorate'],
                            "name" => $data['name'],
                            "address" => $data['address'],
                            "owner" => $data['owner'],
                            "start_working" => $data['start_working'],
                            "end_working" => $data['end_working'],
                        ];
                }

                if ($this->adminModel->editPlaceProfile($data_place)) {
                    $Message = 'تم التعديل بنجاح';
                    $Status = 201;
                    userMessage($Status, $Message);
                    die();
                } else {
                    $Message = 'الرجاء المحاولة فى وقت لأحق';
                    $Status = 400;
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
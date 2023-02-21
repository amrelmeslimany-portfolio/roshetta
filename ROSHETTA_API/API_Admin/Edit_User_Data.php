<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])       && !empty($_POST['phone_number'])
                && isset($_POST['weight'])          && !empty($_POST['weight'])
                && isset($_POST['patient_name'])    && !empty($_POST['patient_name'])
                && isset($_POST['height'])          && !empty($_POST['height'])
                && isset($_POST['birth_date'])      && !empty($_POST['birth_date'])
                && isset($_POST['gender'])          && !empty($_POST['gender'])
                && isset($_POST['governorate'])     && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String' 

                $patient_id     = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
                $phone_number   = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $weight         = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_INT);
                $height         = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_INT);
                $birth_date     = $_POST['birth_date'];
                $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                $patient_name   = filter_var($_POST['patient_name'], FILTER_SANITIZE_STRING);
                $governorate    = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    //UpDate Patient Table

                    $Update = $database->prepare("UPDATE patient SET phone_number = :phone_number , weight = :weight , height = :height , governorate = :governorate , name = :patient_name , gender = :gender , birth_date = :birth_date  WHERE id = :id");

                    $Update->bindparam("id", $patient_id);
                    $Update->bindparam("phone_number", $phone_number);
                    $Update->bindparam("weight", $weight);
                    $Update->bindparam("height", $height);
                    $Update->bindparam("governorate", $governorate);
                    $Update->bindparam("patient_name", $patient_name);
                    $Update->bindparam("gender", $gender);
                    $Update->bindparam("birth_date", $birth_date);
                    $Update->execute();

                    if ($Update->rowCount() > 0) {

                        $Message = "تم تعديل البيانات بنجاح";
                        print_r(json_encode(Message(null,$Message,201)));
                        header("refresh:2;");

                    } else {
                        $Message = "فشل تعديل البيانات";
                        print_r(json_encode(Message(null,$Message,422)));
                    }

                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null,$Message,400)));
                }

            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } elseif (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])       && !empty($_POST['phone_number'])
                && isset($_POST['doctor_name'])     && !empty($_POST['doctor_name'])
                && isset($_POST['specialist'])      && !empty($_POST['specialist'])
                && isset($_POST['birth_date'])      && !empty($_POST['birth_date'])
                && isset($_POST['gender'])          && !empty($_POST['gender'])
                && isset($_POST['governorate'])     && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String' 

                $doctor_id          = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT);
                $phone_number       = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $specialist         = filter_var($_POST['specialist'], FILTER_SANITIZE_STRING);
                $birth_date         = $_POST['birth_date'];
                $gender             = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                $doctor_name        = filter_var($_POST['doctor_name'], FILTER_SANITIZE_STRING);
                $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    $check_phone = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number ");
                    $check_phone->bindParam("phone_number", $phone_number);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        $check_phone_id = $database->prepare("SELECT * FROM doctor WHERE phone_number = :phone_number AND id = :id ");
                        $check_phone_id->bindParam("phone_number", $phone_number);
                        $check_phone_id->bindParam("id", $doctor_id);
                        $check_phone_id->execute();

                        if ($check_phone_id->rowCount() > 0) {

                            //UpDate Doctor Table

                            $Update = $database->prepare("UPDATE doctor SET phone_number = :phone_number , specialist = :specialist , governorate = :governorate , name = :doctor_name , gender = :gender , birth_date = :birth_date WHERE id = :id");

                            $Update->bindparam("id", $doctor_id);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("specialist", $specialist);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("doctor_name", $doctor_name);
                            $Update->bindparam("gender", $gender);
                            $Update->bindparam("birth_date", $birth_date);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                $Message = "تم تعديل البيانات بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل البيانات";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        } else {
                            $Message = "رقم الهاتف موجود من قبل";
                            print_r(json_encode(Message(null,$Message,400)));
                            die("");
                        }

                    } else {

                        //UpDate Doctor Table

                        $Update = $database->prepare("UPDATE doctor SET phone_number = :phone_number , specialist = :specialist , governorate = :governorate , name = :doctor_name , gender = :gender , birth_date = :birth_date WHERE id = :id");

                        $Update->bindparam("id", $doctor_id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("specialist", $specialist);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("doctor_name", $doctor_name);
                        $Update->bindparam("gender", $gender);
                        $Update->bindparam("birth_date", $birth_date);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    }
                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null,$Message,400)));
                }

            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

        } elseif (isset($_POST['pharmacist_id']) && !empty($_POST['pharmacist_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])           && !empty($_POST['phone_number'])
                && isset($_POST['pharmacist_name'])     && !empty($_POST['pharmacist_name'])
                && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
                && isset($_POST['gender'])              && !empty($_POST['gender'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String'

                $pharmacist_id      = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT);
                $phone_number       = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $birth_date         = $_POST['birth_date'];
                $gender             = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                $pharmacist_name    = filter_var($_POST['pharmacist_name'], FILTER_SANITIZE_STRING);
                $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    $check_phone = $database->prepare("SELECT * FROM pharmacist WHERE phone_number = :phone_number");
                    $check_phone->bindParam("phone_number", $phone_number);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        $check_phone_id = $database->prepare("SELECT * FROM pharmacist WHERE id = :id AND phone_number = :phone_number");
                        $check_phone_id->bindParam("id", $pharmacist_id);
                        $check_phone_id->bindParam("phone_number", $phone_number);
                        $check_phone_id->execute();

                        if ($check_phone_id->rowCount() > 0) {

                            //UpDate Pharmacist Table

                            $Update = $database->prepare("UPDATE pharmacist SET phone_number = :phone_number , governorate = :governorate , name = :pharmacist_name , gender = :gender , birth_date = :birth_date  WHERE id = :id");

                            $Update->bindparam("id", $pharmacist_id);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("pharmacist_name", $pharmacist_name);
                            $Update->bindparam("gender", $gender);
                            $Update->bindparam("birth_date", $birth_date);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                $Message = "تم تعديل البيانات بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل البيانات";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        } else {
                            $Message = "رقم الهاتف موجود من قبل";
                            print_r(json_encode(Message(null,$Message,400)));
                            die("");
                        }

                    } else {

                        //UpDate Pharmacist Table

                        $Update = $database->prepare("UPDATE pharmacist SET phone_number = :phone_number , governorate = :governorate , name = :pharmacist_name , gender = :gender , birth_date = :birth_date WHERE id = :id");

                        $Update->bindparam("id", $pharmacist_id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("pharmacist_name", $pharmacist_name);
                        $Update->bindparam("gender", $gender);
                        $Update->bindparam("birth_date", $birth_date);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    }
                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null,$Message,400)));
                }

            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }

        } elseif (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            //I Expect To Receive This Data

            if (
                isset($_POST['phone_number'])           && !empty($_POST['phone_number'])
                && isset($_POST['assistant_name'])      && !empty($_POST['assistant_name'])
                && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
                && isset($_POST['gender'])              && !empty($_POST['gender'])
                && isset($_POST['governorate'])         && !empty($_POST['governorate'])
            ) {

                //Filter Data 'Number_Int' And 'String' 

                $assistant_id       = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);
                $phone_number       = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
                $birth_date         = $_POST['birth_date'];
                $gender             = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                $assistant_name     = filter_var($_POST['assistant_name'], FILTER_SANITIZE_STRING);
                $governorate        = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);

                if (strlen($phone_number) == 11) {

                    $check_phone = $database->prepare("SELECT * FROM assistant WHERE phone_number = :phone_number");
                    $check_phone->bindParam("phone_number", $phone_number);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        $check_phone_id = $database->prepare("SELECT * FROM assistant WHERE id = :id AND phone_number = :phone_number");
                        $check_phone_id->bindParam("id", $assistant_id);
                        $check_phone_id->bindParam("phone_number", $phone_number);
                        $check_phone_id->execute();

                        if ($check_phone_id->rowCount() > 0) {

                            //UpDate Assistant Table

                            $Update = $database->prepare("UPDATE assistant SET phone_number = :phone_number , governorate = :governorate , name = :assistant_name , gender = :gender , birth_date = :birth_date  WHERE id = :id");

                            $Update->bindparam("id", $assistant_id);
                            $Update->bindparam("phone_number", $phone_number);
                            $Update->bindparam("governorate", $governorate);
                            $Update->bindparam("assistant_name", $assistant_name);
                            $Update->bindparam("gender", $gender);
                            $Update->bindparam("birth_date", $birth_date);
                            $Update->execute();

                            if ($Update->rowCount() > 0) {

                                $Message = "تم تعديل البيانات بنجاح";
                                print_r(json_encode(Message(null,$Message,201)));
                                header("refresh:2;");

                            } else {
                                $Message = "فشل تعديل البيانات";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {
                            $Message = "رقم الهاتف موجود من قبل";
                            print_r(json_encode(Message(null,$Message,400)));
                            die("");
                        }

                    } else {

                        //UpDate Assistant Table

                        $Update = $database->prepare("UPDATE assistant SET phone_number = :phone_number , governorate = :governorate , name = :assistant_name , gender = :gender , birth_date = :birth_date WHERE id = :id");

                        $Update->bindparam("id", $assistant_id);
                        $Update->bindparam("phone_number", $phone_number);
                        $Update->bindparam("governorate", $governorate);
                        $Update->bindparam("assistant_name", $assistant_name);
                        $Update->bindparam("gender", $gender);
                        $Update->bindparam("birth_date", $birth_date);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            $Message = "تم تعديل البيانات بنجاح";
                            print_r(json_encode(Message(null,$Message,201)));
                            header("refresh:2;");

                        } else {
                            $Message = "فشل تعديل البيانات";
                            print_r(json_encode(Message(null,$Message,422)));
                        }
                    }
                } else {
                    $Message = "رقم الهاتف غير صالح";
                    print_r(json_encode(Message(null,$Message,400)));
                }
            } else {
                $Message = "يجب اكمال البيانات";
                print_r(json_encode(Message(null,$Message,400)));
            }
        } else {
            $Message = "يجب اكمال البيانات";
            print_r(json_encode(Message(null,$Message,400)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null,$Message,405)));
}
?>
<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['admin'])) {

        require_once("../API_C_A/Connection.php"); //Connect To DataBases

        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {

            //I Expect To Receive This Data

            if (isset($_POST['ssd']) && !empty($_POST['ssd'])) {

                $id     = filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
                $ssd    = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT);

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE  && strlen($ssd) == 14) {

                    $check_ssd = $database->prepare("SELECT * FROM patient WHERE  ssd = :ssd");
                    $check_ssd->bindParam("ssd", $ssd);
                    $check_ssd->execute();

                    if ($check_ssd->rowCount() > 0) {

                        print_r(json_encode(["Error" => "الرقم القومى موجود من قبل"]));
                        die();

                    } else {

                        //UpDate Patient Table

                        $Update = $database->prepare("UPDATE patient SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل الرقم القومى بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل الرقم القومى"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى غير صالح للاستخدام"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {

            //I Expect To Receive This Data

            if (isset($_POST['ssd']) && !empty($_POST['ssd'])) {

                $id     = filter_var($_POST['doctor_id'], FILTER_SANITIZE_NUMBER_INT);
                $ssd    = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT);

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE  && strlen($ssd) == 14) {

                    $check_ssd = $database->prepare("SELECT * FROM doctor WHERE  ssd = :ssd");
                    $check_ssd->bindParam("ssd", $ssd);
                    $check_ssd->execute();

                    if ($check_ssd->rowCount() > 0) {

                        print_r(json_encode(["Error" => "الرقم القومى موجود من قبل"]));
                        die();

                    } else {

                        //UpDate Doctor Table

                        $Update = $database->prepare("UPDATE doctor SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل الرقم القومى بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل الرقم القومى"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى غير صالح للاستخدام"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['pharmacist_id']) && !empty($_POST['pharmacist_id'])) {

            //I Expect To Receive This Data

            if (isset($_POST['ssd']) && !empty($_POST['ssd'])) {

                $id     = filter_var($_POST['pharmacist_id'], FILTER_SANITIZE_NUMBER_INT);
                $ssd    = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT);

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE  && strlen($ssd) == 14) {

                    $check_ssd = $database->prepare("SELECT * FROM pharmacist WHERE  ssd = :ssd");
                    $check_ssd->bindParam("ssd", $ssd);
                    $check_ssd->execute();

                    if ($check_ssd->rowCount() > 0) {

                        print_r(json_encode(["Error" => "الرقم القومى موجود من قبل"]));
                        die();

                    } else {

                        //UpDate Pharmacist Table

                        $Update = $database->prepare("UPDATE pharmacist SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل الرقم القومى بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل الرقم القومى"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى غير صالح للاستخدام"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } elseif (isset($_POST['assistant_id']) && !empty($_POST['assistant_id'])) {

            //I Expect To Receive This Data

            if (isset($_POST['ssd']) && !empty($_POST['ssd'])) {

                $id     = filter_var($_POST['assistant_id'], FILTER_SANITIZE_NUMBER_INT);
                $ssd    = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT);

                if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE  && strlen($ssd) == 14) {

                    $check_ssd = $database->prepare("SELECT * FROM assistant WHERE  ssd = :ssd");
                    $check_ssd->bindParam("ssd", $ssd);
                    $check_ssd->execute();

                    if ($check_ssd->rowCount() > 0) {

                        print_r(json_encode(["Error" => "الرقم القومى موجود من قبل"]));
                        die();

                    } else {

                        //UpDate Assistant Table

                        $Update = $database->prepare("UPDATE assistant SET ssd = :ssd WHERE id = :id ");
                        $Update->bindparam("id", $id);
                        $Update->bindparam("ssd", $ssd);
                        $Update->execute();

                        if ($Update->rowCount() > 0) {

                            print_r(json_encode(["Message" => "تم تعديل الرقم القومى بنجاح"]));

                            header("refresh:2;");

                        } else {
                            print_r(json_encode(["Error" => "فشل تعديل الرقم القومى"]));
                        }
                    }
                } else {
                    print_r(json_encode(["Error" => "الرقم القومى غير صالح للاستخدام"]));
                }

            } else {
                print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
            }

        } else {
            print_r(json_encode(["Error" => "فشل العثور على معرف المستخدم"]));
        }

    } else {
        print_r(json_encode(["Error" => "ليس لديك الصلاحية"]));
    }

} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
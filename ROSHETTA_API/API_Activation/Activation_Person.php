<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBase
require_once("../API_Function/All_Function.php"); //All Function

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    if (isset($_SESSION['pharmacist']) || isset($_SESSION['doctor'])) { //If Find Pharmacist Or Doctor Session 
        
        if (isset($_SESSION['doctor'])) {
            $table_name = 'doctor'; 
            $id         = $_SESSION['doctor'];
        } else {
            $table_name = 'pharmacist';
            $id         = $_SESSION['pharmacist'];
        }

        //I Expect To Receive This Data

        $front_name         = $_FILES["front_nationtional_card"]["name"];
        $back_name          = $_FILES["back_nationtional_card"]["name"];
        $graduation_name    = $_FILES["graduation_cer"]["name"];
        $card_name          = $_FILES["card_id_img"]["name"];

        $front_size         = $_FILES["front_nationtional_card"]["size"];
        $back_size          = $_FILES["back_nationtional_card"]["size"];
        $graduation_size    = $_FILES["graduation_cer"]["size"];
        $card_size          = $_FILES["card_id_img"]["size"];

        $front_tmp          = $_FILES["front_nationtional_card"]["tmp_name"];
        $back_tmp           = $_FILES["back_nationtional_card"]["tmp_name"];
        $graduation_tmp     = $_FILES["graduation_cer"]["tmp_name"];
        $card_tmp           = $_FILES["card_id_img"]["tmp_name"];

        //To Get The Image Formul

        $check_formul_front = explode(".", $front_name);
        $formul_front       = end($check_formul_front);

        $check_formul_back  = explode(".", $back_name);
        $formul_back        = end($check_formul_back);

        $check_formul_graduation    = explode(".", $graduation_name);
        $formul_graduation          = end($check_formul_graduation);

        $check_formul_card  = explode(".", $card_name);
        $formul_card        = end($check_formul_card);

        $allowed_formulas = array("jpg", "jpeg", "png"); //Allowed Formulas For The Image

        if (
            in_array($formul_front, $allowed_formulas)
            && in_array($formul_back, $allowed_formulas)
            && in_array($formul_graduation, $allowed_formulas)
            && in_array($formul_card, $allowed_formulas)
        ) {

            if (
                $front_size > 1000000 //To Specify The Image Size < 1M
                || $back_size > 1000000
                || $graduation_size > 1000000
                || $card_size > 1000000
            ) {

                $Message = "(1M)يجب أن يكون حجم الصورة أقل من";
                print_r(json_encode(Message(null,$Message,400)));
                header("refresh:2;");

            } else {

                //To Input A Random Name For The Image

                $front_new_name         = bin2hex(random_bytes(10)) . '.' . $formul_front;
                $back_new_name          = bin2hex(random_bytes(10)) . '.' . $formul_back;
                $graduation_new_name    = bin2hex(random_bytes(10)) . '.' . $formul_graduation;
                $card_new_name          = bin2hex(random_bytes(10)) . '.' . $formul_card;

                $HTTP_HOST      = $_SERVER['HTTP_HOST']; //To Find Out The Server Name And Port
                $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME']; //To Find The Type Of Connection [HTTP , HTTPS]

                $get_data = $database->prepare("SELECT * FROM $table_name WHERE id = :id");
                $get_data->bindparam("id", $id);
                $get_data->execute();

                if ($get_data->rowCount() > 0 ) {
                    $data_user      = $get_data->fetchObject();
                    $folder_user    = $data_user->ssd;
                } else {
                    $folder_user = 'UNKNOWN';
                }

                if (isset($_SESSION['pharmacist'])) { //If Find Pharmacist Session

                    $pharmacist_id = $_SESSION['pharmacist'];

                    $pharmacist_folder_name = $folder_user;
                    $pharmacist_folder_link = 'IMG/Person_Img/Pharmacists/' . $pharmacist_folder_name . '/' . ''; //File Link

                    if (is_dir($pharmacist_folder_link)) { //If The File Exists

                        $scandir_pharmacist = scandir($pharmacist_folder_link); //To Displays File Data In Array
                        foreach ($scandir_pharmacist as $folder_content_pharmacist) {

                            if (is_file($pharmacist_folder_link . $folder_content_pharmacist)) {

                                unlink($pharmacist_folder_link . $folder_content_pharmacist); //To Delete File Data

                                move_uploaded_file($front_tmp, $pharmacist_folder_link . $front_new_name); //To Transfer The New Image To The File
                                move_uploaded_file($back_tmp, $pharmacist_folder_link . $back_new_name);
                                move_uploaded_file($graduation_tmp, $pharmacist_folder_link . $graduation_new_name);
                                move_uploaded_file($card_tmp, $pharmacist_folder_link . $card_new_name);

                                $front_nationtional_card    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $front_new_name; //The Path WithIn The DataBase
                                $back_nationtional_card     = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $back_new_name;
                                $graduation_cer             = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $graduation_new_name;
                                $card_id_img                = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $card_new_name;

                                $check_pharmacist = $database->prepare("SELECT * FROM activation_person,pharmacist  WHERE  activation_person.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
                                $check_pharmacist->bindparam("id", $pharmacist_id);
                                $check_pharmacist->execute();

                                if ($check_pharmacist->rowCount() > 0) {

                                    //UpDate Activation_Person Table

                                    $uploadfile = $database->prepare("UPDATE activation_person SET front_nationtional_card = :front_nationtional_card , back_nationtional_card = :back_nationtional_card , graduation_cer = :graduation_cer , card_id_img = :card_id_img , isactive = 0 WHERE pharmacist_id = :pharmacist_id");

                                    $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                                    $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                                    $uploadfile->bindparam("graduation_cer", $graduation_cer);
                                    $uploadfile->bindparam("card_id_img", $card_id_img);
                                    $uploadfile->bindparam("pharmacist_id", $pharmacist_id);

                                    if ($uploadfile->execute()) {

                                        if ($uploadfile->rowCount() > 0) {

                                            $Message = "تم التقديم للمراجعة";
                                            print_r(json_encode(Message(null,$Message,201)));
                                            header("refresh:2;");

                                        } //not else

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                } else {

                                    //Add To Activation_Person Table

                                    $uploadfile = $database->prepare("INSERT INTO activation_person(front_nationtional_card,back_nationtional_card,graduation_cer,card_id_img,pharmacist_id,isactive)
                                                                                        VALUES(:front_nationtional_card,:back_nationtional_card,:graduation_cer,:card_id_img,:pharmacist_id,0)");

                                    $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                                    $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                                    $uploadfile->bindparam("graduation_cer", $graduation_cer);
                                    $uploadfile->bindparam("card_id_img", $card_id_img);
                                    $uploadfile->bindparam("pharmacist_id", $pharmacist_id);

                                    if ($uploadfile->execute()) {

                                        if ($uploadfile->rowCount() > 0) {

                                            $Message = "تم التقديم للمراجعة";
                                            print_r(json_encode(Message(null,$Message,201)));
                                            header("refresh:2;");

                                        } else {
                                            $Message = "فشل رفع الملف";
                                            print_r(json_encode(Message(null,$Message,422)));
                                        }

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($pharmacist_folder_link); //To Create A New File

                        move_uploaded_file($front_tmp, $pharmacist_folder_link . $front_new_name); //To Transfer The New Image To The File
                        move_uploaded_file($back_tmp, $pharmacist_folder_link . $back_new_name);
                        move_uploaded_file($graduation_tmp, $pharmacist_folder_link . $graduation_new_name);
                        move_uploaded_file($card_tmp, $pharmacist_folder_link . $card_new_name);

                        $front_nationtional_card    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $front_new_name; //The Path WithIn The DataBase
                        $back_nationtional_card     = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $back_new_name;
                        $graduation_cer             = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $graduation_new_name;
                        $card_id_img                = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $pharmacist_folder_link . $card_new_name;

                        $check_pharmacist = $database->prepare("SELECT * FROM activation_person,pharmacist  WHERE  activation_person.pharmacist_id = pharmacist.id  AND pharmacist.id = :id ");
                        $check_pharmacist->bindparam("id", $pharmacist_id);
                        $check_pharmacist->execute();

                        if ($check_pharmacist->rowCount() > 0) {

                            //UpDate Activation_Person Table

                            $uploadfile = $database->prepare("UPDATE activation_person SET front_nationtional_card = :front_nationtional_card , back_nationtional_card = :back_nationtional_card , graduation_cer = :graduation_cer , card_id_img = :card_id_img , isactive = 0 WHERE pharmacist_id = :pharmacist_id");

                            $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                            $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                            $uploadfile->bindparam("graduation_cer", $graduation_cer);
                            $uploadfile->bindparam("card_id_img", $card_id_img);
                            $uploadfile->bindparam("pharmacist_id", $pharmacist_id);

                            if ($uploadfile->execute()) {

                                if ($uploadfile->rowCount() > 0) {

                                    $Message = "تم التقديم للمراجعة";
                                    print_r(json_encode(Message(null,$Message,201)));
                                    header("refresh:2;");

                                } //not else

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {

                            //Add To Activation_Person Table

                            $uploadfile = $database->prepare("INSERT INTO activation_person(front_nationtional_card,back_nationtional_card,graduation_cer,card_id_img,pharmacist_id,isactive)
                                                                                        VALUES(:front_nationtional_card,:back_nationtional_card,:graduation_cer,:card_id_img,:pharmacist_id,0)");

                            $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                            $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                            $uploadfile->bindparam("graduation_cer", $graduation_cer);
                            $uploadfile->bindparam("card_id_img", $card_id_img);
                            $uploadfile->bindparam("pharmacist_id", $pharmacist_id);

                            if ($uploadfile->execute()) {

                                if ($uploadfile->rowCount() > 0) {

                                    $Message = "تم التقديم للمراجعة";
                                    print_r(json_encode(Message(null,$Message,201)));
                                    header("refresh:2;");

                                } else {
                                    $Message = "فشل رفع الملف";
                                    print_r(json_encode(Message(null,$Message,422)));
                                }

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        }
                    }

                } elseif (isset($_SESSION['doctor'])) {

                    $doctor_id = $_SESSION['doctor'];

                    $doctor_folder_name = $folder_user;
                    $doctor_folder_link = 'IMG/Person_Img/Doctors/' . $doctor_folder_name . '/' . ''; //File Link

                    if (is_dir($doctor_folder_link)) { //If The File Exists

                        $scandir_doctor = scandir($doctor_folder_link); //To Displays File Data In Array
                        foreach ($scandir_doctor as $folder_content_doctor) {

                            if (is_file($doctor_folder_link . $folder_content_doctor)) {

                                unlink($doctor_folder_link . $folder_content_doctor); //To Delete File Data

                                move_uploaded_file($front_tmp, $doctor_folder_link . $front_new_name); //To Transfer The New Image To The File
                                move_uploaded_file($back_tmp, $doctor_folder_link . $back_new_name);
                                move_uploaded_file($graduation_tmp, $doctor_folder_link . $graduation_new_name);
                                move_uploaded_file($card_tmp, $doctor_folder_link . $card_new_name);

                                $front_nationtional_card    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $front_new_name; //The Path WithIn The DataBase
                                $back_nationtional_card     = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $back_new_name;
                                $graduation_cer             = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $graduation_new_name;
                                $card_id_img                = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $card_new_name;

                                $check_doctor = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
                                $check_doctor->bindparam("id", $doctor_id);
                                $check_doctor->execute();

                                if ($check_doctor->rowCount() > 0) {

                                    //UpDate Activation_Person Table

                                    $uploadfile = $database->prepare("UPDATE activation_person SET front_nationtional_card = :front_nationtional_card , back_nationtional_card = :back_nationtional_card , graduation_cer = :graduation_cer , card_id_img = :card_id_img , isactive = 0 WHERE doctor_id = :doctor_id");

                                    $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                                    $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                                    $uploadfile->bindparam("graduation_cer", $graduation_cer);
                                    $uploadfile->bindparam("card_id_img", $card_id_img);
                                    $uploadfile->bindparam("doctor_id", $doctor_id);

                                    if ($uploadfile->execute()) {

                                        if ($uploadfile->rowCount() > 0) {

                                            $Message = "تم التقديم للمراجعة";
                                            print_r(json_encode(Message(null,$Message,201)));
                                            header("refresh:2;");

                                        } //not else

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }
                                } else {

                                    //Add To Activation_Person Table

                                    $uploadfile = $database->prepare("INSERT INTO activation_person(front_nationtional_card,back_nationtional_card,graduation_cer,card_id_img,doctor_id,isactive)
                                                                                        VALUES(:front_nationtional_card,:back_nationtional_card,:graduation_cer,:card_id_img,:doctor_id,0)");

                                    $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                                    $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                                    $uploadfile->bindparam("graduation_cer", $graduation_cer);
                                    $uploadfile->bindparam("card_id_img", $card_id_img);
                                    $uploadfile->bindparam("doctor_id", $doctor_id);

                                    if ($uploadfile->execute()) {

                                        if ($uploadfile->rowCount() > 0) {

                                            $Message = "تم التقديم للمراجعة";
                                            print_r(json_encode(Message(null,$Message,201)));
                                            header("refresh:2;");

                                        } else {
                                            $Message = "فشل رفع الملف";
                                            print_r(json_encode(Message(null,$Message,422)));
                                        }

                                    } else {
                                        $Message = "فشل رفع الملف";
                                        print_r(json_encode(Message(null,$Message,422)));
                                    }

                                }
                            }
                        }
                    } else { //If The File Does Not Exists

                        mkdir($doctor_folder_link); //To Create A New File

                        move_uploaded_file($front_tmp, $doctor_folder_link . $front_new_name); //To Transfer The New Image To The File
                        move_uploaded_file($back_tmp, $doctor_folder_link . $back_new_name);
                        move_uploaded_file($graduation_tmp, $doctor_folder_link . $graduation_new_name);
                        move_uploaded_file($card_tmp, $doctor_folder_link . $card_new_name);

                        $front_nationtional_card    = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $front_new_name; //The Path WithIn The DataBase
                        $back_nationtional_card     = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $back_new_name;
                        $graduation_cer             = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $graduation_new_name;
                        $card_id_img                = $REQUEST_SCHEME . "://" . $HTTP_HOST . "/ROSHETTA_API/API_Activation/" . $doctor_folder_link . $card_new_name;

                        $check_doctor = $database->prepare("SELECT * FROM activation_person,doctor  WHERE  activation_person.doctor_id = doctor.id  AND doctor.id = :id ");
                        $check_doctor->bindparam("id", $doctor_id);
                        $check_doctor->execute();

                        if ($check_doctor->rowCount() > 0) {

                            //UpDate Activation_Person Table

                            $uploadfile = $database->prepare("UPDATE activation_person SET front_nationtional_card = :front_nationtional_card , back_nationtional_card = :back_nationtional_card , graduation_cer = :graduation_cer , card_id_img = :card_id_img , isactive = 0 WHERE doctor_id = :doctor_id");

                            $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                            $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                            $uploadfile->bindparam("graduation_cer", $graduation_cer);
                            $uploadfile->bindparam("card_id_img", $card_id_img);
                            $uploadfile->bindparam("doctor_id", $doctor_id);

                            if ($uploadfile->execute()) {

                                if ($uploadfile->rowCount() > 0) {

                                    $Message = "تم التقديم للمراجعة";
                                    print_r(json_encode(Message(null,$Message,201)));
                                    header("refresh:2;");

                                } //not else

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }
                        } else {

                            //Add To Activation_Person Table

                            $uploadfile = $database->prepare("INSERT INTO activation_person(front_nationtional_card,back_nationtional_card,graduation_cer,card_id_img,doctor_id,isactive)
                                                                                        VALUES(:front_nationtional_card,:back_nationtional_card,:graduation_cer,:card_id_img,:doctor_id,0)");

                            $uploadfile->bindparam("front_nationtional_card", $front_nationtional_card);
                            $uploadfile->bindparam("back_nationtional_card", $back_nationtional_card);
                            $uploadfile->bindparam("graduation_cer", $graduation_cer);
                            $uploadfile->bindparam("card_id_img", $card_id_img);
                            $uploadfile->bindparam("doctor_id", $doctor_id);

                            if ($uploadfile->execute()) {

                                if ($uploadfile->rowCount() > 0) {

                                    $Message = "تم التقديم للمراجعة";
                                    print_r(json_encode(Message(null,$Message,201)));
                                    header("refresh:2;");

                                } else {
                                    $Message = "فشل رفع الملف";
                                    print_r(json_encode(Message(null,$Message,422)));
                                }

                            } else {
                                $Message = "فشل رفع الملف";
                                print_r(json_encode(Message(null,$Message,422)));
                            }

                        }
                    }
                } else {
                    $Message = "فشل العثور على مستخدم";
                    print_r(json_encode(Message(null,$Message,401)));
                }
            }
        } else {
            $Message = "صيغة الملف غير مدعومة";
            print_r(json_encode(Message(null,$Message,415)));
        }
    } else {
        $message = "ليس لديك الصلاحية";
        print_r(json_encode(Message(null , $message , 403)));
    }
} else { //If The Entry Method Is Not 'POST'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة"; 
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
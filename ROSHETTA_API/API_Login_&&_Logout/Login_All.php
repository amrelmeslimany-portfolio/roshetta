<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

session_start();
session_regenerate_id();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_SESSION['admin'])) { //Allow Access Via 'POST' Method Or Admin

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) {

        if (
            isset($_POST['user_id'])            && !empty($_POST['user_id'])
            && isset($_POST['password'])        && !empty($_POST['password'])
        ) {

            $user_id          = $_POST['user_id'];
            $password_user = $_POST['password'];

            if (filter_var($user_id, FILTER_VALIDATE_INT) !== FALSE  || filter_var($user_id, FILTER_VALIDATE_EMAIL) !== FALSE) {

                require_once("../API_C_A/Connection.php"); //Connect To DataBases

                if ($_POST['role'] === "patient") {

                    //Verify Patients Table

                    $LoginPatient = $database->prepare("SELECT * FROM patient WHERE ssd = :ssd OR email = :email");
                    $LoginPatient->bindparam("ssd", $user_id);
                    $LoginPatient->bindparam("email", $user_id);
                    $LoginPatient->execute();

                    if ($LoginPatient->rowCount() > 0) {

                        $patient          = $LoginPatient->fetchObject();
                        $password_patient = $patient->password;

                        if (password_verify($password_user, $password_patient)) {

                            $data_message = array(

                                "Message"       => $patient->patient_name . " : مرحبا بك ",
                                "Account_Type"  => $patient->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['patient'] = $patient;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "doctor") {

                    //Verify Doctors Table

                    $LoginDoctor = $database->prepare("SELECT * FROM doctor WHERE ssd = :ssd OR email = :email");
                    $LoginDoctor->bindparam("ssd", $user_id);
                    $LoginDoctor->bindparam("email", $user_id);
                    $LoginDoctor->execute();

                    if ($LoginDoctor->rowCount() > 0) {

                        $doctor          = $LoginDoctor->fetchObject();
                        $password_doctor = $doctor->password;

                        if (password_verify($password_user, $password_doctor)) {

                            $data_message = array(

                                "Message"       => $doctor->doctor_name . " : مرحبا بك ",
                                "Account_Type"  => $doctor->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['doctor'] = $doctor;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "pharmacist") {

                    //Verify Pharmacists Table

                    $LoginPharmacist = $database->prepare("SELECT * FROM pharmacist WHERE ssd = :ssd OR email = :email");
                    $LoginPharmacist->bindparam("ssd", $user_id);
                    $LoginPharmacist->bindparam("email", $user_id);
                    $LoginPharmacist->execute();

                    if ($LoginPharmacist->rowCount() > 0) {

                        $pharmacist          = $LoginPharmacist->fetchObject();
                        $password_pharmacist = $pharmacist->password;

                        if (password_verify($password_user, $password_pharmacist)) {

                            $data_message = array(

                                "Message"       => $pharmacist->pharmacist_name . " : مرحبا بك ",
                                "Account_Type"  => $pharmacist->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['pharmacist'] = $pharmacist;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "assistant") {

                    //Verify Assistants Table

                    $LoginAssistant = $database->prepare("SELECT * FROM assistant WHERE ssd = :ssd OR email = :email");
                    $LoginAssistant->bindparam("ssd", $user_id);
                    $LoginAssistant->bindparam("email", $user_id);
                    $LoginAssistant->execute();

                    if ($LoginAssistant->rowCount() > 0) {

                        $assistant          = $LoginAssistant->fetchObject();
                        $password_assistant = $assistant->password;

                        if (password_verify($password_user, $assistant->password)) {

                            $data_message = array(

                                "Message"       => $assistant->assistant_name . " : مرحبا بك ",
                                "Account_Type"  => $assistant->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['assistant'] = $assistant;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } elseif ($_POST['role'] === "admin") {

                    //Verify Admins Table

                    $LoginAdmin = $database->prepare("SELECT * FROM admin WHERE ssd = :ssd OR email = :email");
                    $LoginAdmin->bindparam("ssd", $user_id);
                    $LoginAdmin->bindparam("email", $user_id);
                    $LoginAdmin->execute();

                    if ($LoginAdmin->rowCount() > 0) {

                        $admin          = $LoginAdmin->fetchObject();
                        $password_admin = $admin->password;

                        if (password_verify($password_user, $password_admin)) {

                            $data_message = array(

                                "Message"       => $admin->admin_name . " : مرحبا بك ",
                                "Account_Type"  => $admin->role

                            );

                            print_r(json_encode($data_message));

                            $_SESSION['admin'] = $admin;

                        } else {
                            print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                        }
                    } else {
                        print_r(json_encode(["Error" => "الرقم القومى او كلمة المرور غير صحيح"]));
                    }
                } else {
                    print_r(json_encode(["Error" => "فشل فى التعرف على نوع الحساب"]));
                }
            } else {
                print_r(json_encode(["Error" => "الرقم القومى او الايميل غير صالح"]));
            }
        } else { //If Didn't Find SSD Or PASSWORD
            print_r(json_encode(["Error" => "يجب اكمال البيانات"]));
        }
    } else { //If Didn't Find The Role
        print_r(json_encode(["Error" => "يجب تحديد نوع الحساب"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
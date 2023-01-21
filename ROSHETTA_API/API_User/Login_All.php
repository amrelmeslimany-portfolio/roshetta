<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Allow Access Via 'POST' Method Only

    //I Expect To Receive This Data

    if (isset($_POST['role']) && !empty($_POST['role'])) {

        if (
            isset($_POST['ssd']) && !empty($_POST['ssd'])
            && isset($_POST['password']) && !empty($_POST['password'])
        ) {

            $ssd           = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'
            $password_user = $_POST['password'];

            if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE) {

                session_start();

                require_once("../API_C_A/Connection.php"); //Connect To DataBases

                if ($_POST['role'] === "patient") {

                    //Verify Patients Table

                    $LoginPatient = $database->prepare("SELECT * FROM patient WHERE ssd = :ssd ");
                    $LoginPatient->bindparam("ssd", $ssd);
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

                    $LoginDoctor = $database->prepare("SELECT * FROM doctor WHERE ssd = :ssd ");
                    $LoginDoctor->bindparam("ssd", $ssd);
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

                    $LoginPharmacist = $database->prepare("SELECT * FROM pharmacist WHERE ssd = :ssd ");
                    $LoginPharmacist->bindparam("ssd", $ssd);
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

                    $LoginAssistant = $database->prepare("SELECT * FROM assistant WHERE ssd = :ssd ");
                    $LoginAssistant->bindparam("ssd", $ssd);
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

                    $LoginAdmin = $database->prepare("SELECT * FROM admin WHERE ssd = :ssd ");
                    $LoginAdmin->bindparam("ssd", $ssd);
                    $LoginAdmin->execute();

                    if ($LoginAdmin->rowCount() > 0) {

                        $admin          = $LoginAdmin->fetchObject();
                        $password_admin = $admin->password;

                        if (password_verify($password_user, $password_admin)) {

                            print_r(json_encode(["Message" => $admin->role . " : نوع الحساب"]));

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
                print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
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
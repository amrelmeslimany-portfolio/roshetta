<?php

require_once("../API_C_A/Allow.php"); //Allow All Headers 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Allow Access Via 'POST' Method Only

    //I Expect To Receive This Data

    if (
        isset($_POST['first_name'])             && !empty($_POST['first_name'])
        && isset($_POST['last_name'])           && !empty($_POST['last_name'])
        && isset($_POST['gender'])              && !empty($_POST['gender'])
        && isset($_POST['ssd'])                 && !empty($_POST['ssd'])
        && isset($_POST['phone_number'])        && !empty($_POST['phone_number'])
        && isset($_POST['birth_date'])          && !empty($_POST['birth_date'])
        && isset($_POST['password'])            && !empty($_POST['password'])
        && isset($_POST['confirm_password'])    && !empty($_POST['confirm_password'])
    ) {

        if ($_POST['password'] == $_POST['confirm_password']) {

            require_once("../API_C_A/Connection.php"); //Connect To DataBases

            $ssd = filter_var($_POST['ssd'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'INT'

            if (filter_var($ssd, FILTER_VALIDATE_INT) !== FALSE) {

                //Verify That It Has Not Been Present Before

                $checkssd = $database->prepare("SELECT * FROM admin WHERE ssd =:ssd");
                $checkssd->bindparam("ssd", $ssd);
                $checkssd->execute();

                if ($checkssd->rowCount() > 0) {

                    print_r(json_encode(["Error" => "موجود من قبل"]));

                } else {

                    $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT); //Filter Data 'Int'

                    $check_phone = $database->prepare("SELECT * FROM admin WHERE phone_number = :phone_number");
                    $check_phone->bindparam("phone_number", $phone_number);
                    $check_phone->execute();

                    if ($check_phone->rowCount() > 0) {

                        print_r(json_encode(["Error" => "رقم الهاتف موجود من قبل"]));

                    } else {

                        //Filter Data 'STRING' && Hash Password

                        $first_name     = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                        $last_name      = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                        $admin_name     = $first_name . ' ' . $last_name;
                        $gender         = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                        $password_hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $birth_date     = $_POST['birth_date'];

                        //Add To Pharmcists Table

                        $addData = $database->prepare("INSERT INTO admin(admin_name,gender,ssd,phone_number,birth_date,password,role)
                                                                    VALUES(:admin_name,:gender,:ssd,:phone_number,:birth_date,:password,'ADMIN')");

                        $addData->bindparam("admin_name", $admin_name);
                        $addData->bindparam("gender", $gender);
                        $addData->bindparam("ssd", $ssd);
                        $addData->bindparam("phone_number", $phone_number);
                        $addData->bindparam("birth_date", $birth_date);
                        $addData->bindparam("password", $password_hash);

                        if ($addData->execute()) {

                            print_r(json_encode(["Message" => "تم تسجيل مدير بنجاح"]));

                        } else {
                            print_r(json_encode(["Error" => "فشل تسجيل المدير"]));

                        }
                    }
                }

            } else {
                print_r(json_encode(["Error" => "يجب ادخال بيانات من نوع الارقام"]));
            }
        } else {
            print_r(json_encode(["Error" => "كلمة المرور غير متطابقة"]));
        }
    } else {
        print_r(json_encode(["Error" => "يجب عليك اكمال جميع البيانات"]));
    }
} else { //If The Entry Method Is Not 'POST'
    print_r(json_encode(["Error" => "غير مسرح بالدخول عبر هذة الطريقة"]));
}
?>
<?php

    require_once("../API_C_A/Allow.php");   //Allow All Headers

    if($_SERVER['REQUEST_METHOD'] == 'POST'){   //Allow Access Via 'POST' Method Only
    
        //I Expect To Receive This Data

        if(isset($_POST['medicne_name'])       && ! empty($_POST['medicne_name'])
            && isset($_POST['medicne_size'])   && ! empty($_POST['medicne_size'])
            && isset($_POST['duration'])       && ! empty($_POST['duration'])
            && isset($_POST['description'])    && ! empty($_POST['description'])){

                session_start();
                session_regenerate_id();

                if(isset($_SESSION['prescript']) && isset($_SESSION['doctor'])){

                    if($_SESSION['doctor']->role === "DOCTOR"){
                        
                        require_once("../API_C_A/Connection.php");    //Connect To DataBase

                        $d_id = $_SESSION['doctor']->id;

                        //Check Activation

                        $checkActivation = $database->prepare("SELECT * FROM activation_person WHERE  activation_person.user_id = doctor.id  AND doctor.id = :id ");
                        $checkActivation->bindparam("id",$d_id);
                        $checkActivation->execute();

                            if($checkActivation->rowCount() > 0){

                                $Activation = $checkActivation->fetchObject();

                                    if($Activation->isactive == 1 ){

                                        //Filter Data 'String'

                                        $medicne_name    = filter_var($_POST['medicne_name'], FILTER_SANITIZE_STRING);
                                        $medicne_size    = filter_var($_POST['medicne_size'], FILTER_SANITIZE_STRING);
                                        $duration        = filter_var($_POST['duration'], FILTER_SANITIZE_STRING);
                                        $description     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                                        $prescript_id    = $_SESSION['prescript']->id;

                                        //Add To Medcine Table

                                        $addMedicne = $database->prepare("INSERT INTO medicne(medicne_name,medicne_size,duration,description,prescript_id)
                                                                        VALUES(:medicne_name,:medicne_size,:duration,:description,:prescript_id)");

                                            $addMedicne->bindparam("medicne_name",$medicne_name);
                                            $addMedicne->bindparam("medicne_size",$medicne_size);
                                            $addMedicne->bindparam("duration",$duration);
                                            $addMedicne->bindparam("description",$description);
                                            $addMedicne->bindparam("prescript_id",$prescript_id);

                                                if($addMedicne->execute()){

                                                    print_r(json_encode(["Message"=>"تم اضافة الدواء بنجاح"]));
                                                                                                                                                                                                         
                                                    $get_Medicne_Prescript = $database->prepare("SELECT  id,medicne_name,medicne_size,duration,description  FROM  medicne  WHERE  medicne.prescript_id = prescript.id  AND prescript.id = :id ");

                                                        $get_Medicne_Prescript->bindparam("id",$prescript_id);

                                                            if($get_Medicne_Prescript->execute()){

                                                                $get_Medicne_Prescript = $get_Medicne_Prescript->fetchAll(PDO::FETCH_ASSOC);

                                                                    print_r(json_encode($get_Medicne_Prescript));

                                                            }else{
                                                                print_r(json_encode(["Error"=>"فشل جلب الدواء"]));
                                                                die(""); 
                                                            }
                                                }else{
                                                    print_r(json_encode(["Error"=>"فشل اضافة الدواء"])); 
                                                    die("");
                                                }
                                    }else{
                                        print_r(json_encode(["Error"=>"الرجاء الانتظار حتى يتم المراجعة من قبل الادمن"]));
                                        die("");
                                    }

                            }else{
                                print_r(json_encode(["Error"=>"يجب تفعيل الحساب"]));
                                die("");
                            }
                    }else{
                        print_r(json_encode(["Error"=>"ليس لديك الصلاحية"]));
                        die("");
                    }

                }else{
                    print_r(json_encode(["Error"=>"فشل العثور على الشيشن"]));
                    die("");
                }

        }else{
                print_r(json_encode(["Error"=>"يجب عليك اكمال جميع البيانات"]));
        } 
    }else{  //If The Entry Method Is Not 'POST'
        print_r(json_encode(["Error"=>"غير مسرح بالدخول عبر هذة الطريقة"]));
    }
?>
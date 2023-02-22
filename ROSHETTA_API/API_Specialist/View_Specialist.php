<?php 

require_once("../API_C_A/Allow.php"); //Allow All Headers
require_once("../API_C_A/Connection.php"); //Connect To DataBases 
require_once("../API_Function/All_Function.php"); //All Function

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //Allow Access Via 'GET' Method 

    $get_specialist = $database->prepare("SELECT name,ar_name FROM Specialist");
    $get_specialist->execute();

    if ($get_specialist->rowCount() > 0) {

        $data_specialist = $get_specialist->fetchAll(PDO::FETCH_ASSOC);
        $Message = "تم جلب البيانات";
        print_r(json_encode(Message($data_specialist, $Message, 200)));
    } else {
        $Message = "لم يتم العثور على بيانات";
        print_r(json_encode(Message(null, $Message, 204)));
    }
} else { //If The Entry Method Is Not 'GET'
    $Message = "غير مسموح بالدخول عبر هذة الطريقة";
    print_r(json_encode(Message(null, $Message, 405)));
}
?>
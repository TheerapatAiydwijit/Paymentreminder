<?php
include('../include/Frequently.php');
include('LineFunction.php');
$datas = file_get_contents('php://input');
$deCode = json_decode($datas, true);
$userId = $deCode['events'][0]['source']['userId'];
$text = $deCode['events'][0]['message']['text'];
$masskey = getcurldata(3);
$Headers['Authorization'] = $masskey['Username'];
$Headers['url'] = "https://api.line.me/v2/bot/message/push";
$messages = [];
$messages['to'] = $userId;
// $string = "เริมต้น";
if (chekloginlines($userId)) {
    $data = $deCode['events'][0]['postback']['data'];
    if ($data == "action=CurrentMonth" || $text == "รายชื่อประจำเดือน") {
        $date = date('Y-m-01');
        $current = date("m", strtotime($date));
        $month = date("m", strtotime($date . "+1 month"));
        $customer_all = getcustomer($month);
        $messages['messages'][0] = flexmessage($customer_all);
        // $string = "โดเมน:".$customer_all[0]['Domain']."บริษัท: ".$customer_all[0]['Company']." กำหนดจ่ายวันที่:".$customer_all[0]['Duedate'];
    } elseif ($data == "action=Logout" || $text == "ออกจากระบบ") {
        $string = "action=Logout";
        $unlink = unlinkLineID($userId);
        if($unlink['status'] == "success"){
            $loginout = UnlinkRichMenu($userId, $Headers);
            if($loginout['status'] == "success"){
                $string = "ออกจากระบบในอุปกรณ์เสร็จสิ้น";
            }else{
                $string = "พบปัญหาในระหว่างการออกจากระบบ".$loginout['messages'];
            }
        }else{
            $string = "พบปัญหาในระหว่างการออกจากระบบ".$unlink['messages'];
        }
        $messages['messages'][0] = getFormatTextMessage($string, "0");
    }
    // file_put_contents('log.txt', $data  . PHP_EOL, FILE_APPEND);
} else {
    if ($text == "รายชื่อประจำเดือน") {
        $string = "การยืนยันสิทธิในการเข้าถึงล้มเหลว";
        $messages['messages'][0] = getFormatTextMessage($string, "0");
    } elseif ($text == "ออกจากระบบ") {
        $string = "การยืนยันสิทธิในการเข้าถึงล้มเหลว";
        $messages['messages'][0] = getFormatTextMessage($string, "0");
    }
}
$Body = json_encode($messages, JSON_UNESCAPED_UNICODE);
$sendre = responsemanage($Headers, $Body);
// ดึงข้อมูลลูกค้าออกมาใช้
function getcustomer($month)
{
    include("../include/Connect.php");
    $sqllike = "-" . $month . "-";
    $sql = "SELECT * FROM customer WHERE Duedate LIKE '%$sqllike%' AND Sdelete='0' ORDER BY Duedate";
    $quray = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
    $returnarray = array();
    $year = date("Y");
    if($month =="01"){
        $year = date("m", strtotime($year . "+1 year"));
    }
    while ($data = mysqli_fetch_array($quray, MYSQLI_ASSOC)) {
        $Co_id = $data['Co_id'];
        $Company = $data['Company'];
        $Domain = $data['Domain'];
        $Rates = $data['Rates'];
        $Duedate = $data['Duedate'];
        $strMonth = date("n", strtotime($Duedate));
        $strDay = date("j", strtotime($Duedate));
        $Duedate =$year."-".$strMonth."-".$strDay; 
        $Coarray = array(
            "Co_id" => $Co_id,
            "Company" => $Company,
            "Domain" => $Domain,
            "Rates" => $Rates,
            "Duedate" => DateThai($Duedate)
        );
        array_push($returnarray, $Coarray);
    }
    return $returnarray;
}
function Loginlogout($userId, $masskey)
{
    include("../include/Connect.php");
}
// 
function unlinkLineID($userId)
{
    include("../include/Connect.php");
    $sql = "DELETE FROM user_line_control WHERE userLineID='$userId'";
    if (mysqli_query($conn, $sql)) {
        $return = array(
            "status" => "success",
            "message" => "ลบข้อมูลในดาต้าเบสเสร็จสิ้น"
        );
    } else {
        $return = array(
            "status" => "error",
            "message" => mysqli_error($conn)
        );
    }
    return $return;
}
// 
function UnlinkRichMenu($userId, $Headers)
{
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.line.me/v2/bot/richmenu/bulk/unlink',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "userIds":["'.$userId.'"]
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$Headers['Authorization'],
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
    // file_put_contents('log.txt', $response . PHP_EOL, FILE_APPEND);
    if (curl_error($curl)) {
        file_put_contents('log.txt', curl_error($curl) . PHP_EOL, FILE_APPEND);
        $return = array(
                        "status" =>"error",
                        "message" =>curl_error($curl)
        );
    } else {    
        $return = array(
            "status" =>"success",
            "message" =>"เสร็ยจสิ้น"
);
    }
    curl_close($curl);
return $return;
}

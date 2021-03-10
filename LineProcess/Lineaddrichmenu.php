<?php
include('LineFunction.php');
$user_line_Id = $_POST['uid'];
$username = $_POST['username'];
$password = $_POST['password'];
$login = cheklogin($username, $password);
if ($login['status'] == 'success') {
  $userid = $login['data'];
  $insert = insert_line_control($userid, $user_line_Id);
  if ($insert['status'] == "success") {
    $curldata = getcurldata(3);
    if ($curldata['status'] == "success") {
      file_put_contents('log.txt', $curldata['Password'] . PHP_EOL, FILE_APPEND);
      $setrichmenu = setrichmenu($curldata, $user_line_Id);
      if ($setrichmenu['status'] == "success") {
        $return = array(
          "status" => "success",
          "Message" => "เพิ่มrichenuให้ผู้ควบคุมเรียบร้อย"
        );
      } else {
        $return = array(
          "status" => "error",
          "Message" => $setrichmenu['Message']
        );
      }
    } else {
      $return = array(
        "status" => "error",
        "Message" => $curldata['Message']
      );
    }
  } else {
    $return = array(
      "status" => "error",
      "Message" => $insert['Message']
    );
  }
} else {
  $return = array(
    "status" => "error",
    "Message" => $login['Message']
  );
}
// $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
// file_put_contents('log.txt', $jsonreturn . PHP_EOL, FILE_APPEND);
if($return['status'] == "success"){
  $string ="เข้าสู่ระบบเสร็ยจสิ้น";
}else{
  $string = "ไม่สามารถเข้าสู่ระบบได้เนื่องจาก".$return['Message'];
}
$masskey = getcurldata(3);
$Headers['Authorization'] = $masskey['Username'];
$Headers['url'] = "https://api.line.me/v2/bot/message/push";
$messages = [];
$messages['to'] = $user_line_Id;
$messages['messages'][0] = getFormatTextMessage($string, "0");
$Body = json_encode($messages, JSON_UNESCAPED_UNICODE);
$sendre = responsemanage($Headers, $Body);




<?php
include("Preparedatafunction.php");
include("../include/Connect.php");
$emailstatus = "SELECT Username,Password FROM account WHERE typeacc='1' AND status='1' LIMIT 1";
$quarystatus = mysqli_query($conn, $emailstatus);
$fecthquarystatus = mysqli_fetch_array($quarystatus, MYSQLI_ASSOC);
$account = array(
  "Username" => $fecthquarystatus['Username'],
  "Password" => $fecthquarystatus['Password']
);
if ($quarystatus->num_rows > 0) {
  $sedemailchek = "SELECT * FROM sendemail WHERE status='0'";
  $chek = mysqli_query($conn, $sedemailchek);
  if($chek ->num_rows > 0){
     while ($sendemail = mysqli_fetch_array($chek)) {
    $Co_id = $sendemail['Co_id'];
    $resultmail = Sendinformation($Co_id, $account);
    if ($resultmail['status'] == "success") {
      $text = $resultmail['Message'];
      $return = Insert($text, "1");
      if ($return['status'] == "error") {
        echo $return['Message'];
      }
      $send_id = $sendemail['send_id'];
      $Bill_id = $resultmail['bill_id'];
      $textemail = $resultmail['Email'];
      $quary = mysqli_query($conn, "UPDATE sendemail SET status='1',Bill_id='$Bill_id',Textemail='$textemail' WHERE send_id='$send_id'") or die("Error : " . mysqli_error($conn));
    } else {
      $text = $resultmail['Message'];
      $return = Insert($text, "2");
      if ($return['status'] == "error") {
        echo $return['Message'];
      }
    }
    $LineMass = sedmass($text, "9");
  }
  }
  $date = date('Y-m-01');
  $month = date("m", strtotime($date . "+1 month"));
  $today = "-" . $month . "-" . date('d');
  $coumer = "SELECT Co_id FROM customer WHERE Duedate LIKE '%$today%' AND Sdelete='0'";
  $customer = mysqli_query($conn, $coumer);
  $year = date("Y");
  while ($Co_iddata = mysqli_fetch_array($customer, MYSQLI_ASSOC)) {
    $Co_id = $Co_iddata['Co_id'];
    $Check = "SELECT send_id FROM sendemail WHERE Co_id='$Co_id' AND Sendyear='$year'";
    $recheck = $conn->query($Check);
    if ($recheck->num_rows > 0) {
      continue;
    }
    $resultmail = Sendinformation($Co_id, $account);
    if ($resultmail['status'] == "success") {
      $text = $resultmail['Message'];
      $textemail = $resultmail['Email'];
      $return = Insert($text, "1");
      $Bill_id = $resultmail['bill_id'];
      if ($return['status'] == "error") {
        echo $return['Message'];
      }
      mailstatus($Co_id, "1", $Bill_id,$textemail);
    } else {
      $text = $resultmail['Message'];
      $return = Insert($text, "2");
      if ($return['status'] == "error") {
        echo $return['Message'];
      }
      $quarychek = mysqli_query($conn, "SELECT send_id FROM sendemail WHERE status='0' AND Co_id='$Co_id' AND Sendyear='$year'");
      if ($quarychek->num_rows == 0) {
        mailstatus($Co_id, "0", "NULL","NULL");
      }
    }
    $LineMass = sedmass($text, "9");
  }
} else {
  $text = "ไม่พบบัญชีที่จะใช้ในการส่ง";
  $return = Insert($text, "3");
  if ($return['status'] == "error") {
    echo $return['Message'];
  }
  $LineMass = sedmass($text, "9");
}



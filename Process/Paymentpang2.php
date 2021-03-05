<?php
include '../include/Connect.php';
if (isset($_POST['Edit'])) {
  $date = $_POST['date'];
  $Co_id = $_POST['Co_id'];
  $order_detail  = $_POST['Edit'];
  $chek = "SELECT payment_id FROM payment WHERE date='$date' AND Co_id='$Co_id'";
  $quraychek = mysqli_query($conn, $chek);
  $datafe = mysqli_fetch_array($quraychek,MYSQLI_ASSOC);
  $payment_id = $datafe['payment_id'];
  if ($quraychek->num_rows > 0) {
    $targetedfile = Preparefolder($Co_id);
    updaateP($targetedfile,$payment_id,$order_detail);
  } else {
    echo "0";
  }
  paymenttosuss($payment_id);
} elseif (isset($_POST['Add'])) {
  $date = $_POST['date'];
  $Co_id = $_POST['Co_id'];
  $chek = "SELECT payment_id FROM payment WHERE date='$date' AND Co_id='$Co_id'";
  $quraychek = mysqli_query($conn, $chek);
  if ($quraychek->num_rows > 0) {
    echo "0";
  } else {
    $sqlpay = "INSERT INTO `payment`(`date`,`Co_id`,`status`) 
    VALUES ('$date','$Co_id','0' )";
    $quraypay = mysqli_query($conn, $sqlpay) or die("Error : " . mysqli_error($conn));
    $getPid = "SELECT payment_id FROM payment WHERE date='$date' AND Co_id='$Co_id'";
    $getPidquray = mysqli_query($conn, $getPid) or die("Error : " . mysqli_error($conn));
    $fasarray = mysqli_fetch_array($getPidquray);
    $payment_id = $fasarray['payment_id'];
    $targetedfile = Preparefolder($Co_id);
    $Pamentperiod = $_POST['Pamentperiod'];
    if ($targetedfile != "0") {
      uplondimgP($targetedfile, $payment_id);
    }
    paymenttosuss($payment_id);
  }
}

function Preparefolder($Co_id)
{
  include("../include/Connect.php");
  $sql = "SELECT Company FROM customer WHERE Co_id='$Co_id'";
  $quray = mysqli_query($conn, $sql);
  if ($quray->num_rows > 0) {
    $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
    $Companyname = $row['Company'] . $Co_id;
    $chekfloder = is_dir("../customerdetail/$Companyname");
    if ($chekfloder != true) {
      mkdir("../customerdetail/$Companyname/");
    }
    $chekfloderslip = is_dir("../customerdetail/$Companyname/slip");
    if ($chekfloderslip != true) {
      mkdir("../customerdetail/$Companyname/slip/");
    }
    return "customerdetail/$Companyname/slip/";
  } else {
    return "0";
  }
}
function paymenttosuss($payment_id)
{
  include("../include/Connect.php");
  $sqlpay_detail = "SELECT Slipname FROM payment_detail WHERE payment_id='$payment_id'";
  $quarypay = mysqli_query($conn, $sqlpay_detail) or die("Error : " . mysqli_error($conn));
  $paysuss = mysqli_num_rows($quarypay); //จำนวณแถวรายละเอียดการจ่ายเงิน
  $numremain = 0; //จำนวณของรายการที่เสร็ยจสิ้น
  while ($money = mysqli_fetch_array($quarypay, MYSQLI_ASSOC)) {
    $slipname = $money['Slipname'];
    if ($slipname != '0') { //ถ้ารายการมีการอัฟโหลดจะถือว่าเสร็ยจ
      $numremain = $numremain + 1;
    }
  }
  if ($paysuss == $numremain) {
    $sqlupdate = "UPDATE payment SET status='1' WHERE payment_id='$payment_id'";
    $quarystatus = mysqli_query($conn, $sqlupdate) or die("Error : " . mysqli_error($conn));
  }
}
function uplondimgP($targeted, $payment_id)
{
  include("../include/Connect.php");
  foreach ($_FILES['slip']['name'] as $key => $val) {
    if (isset($_FILES['slip']['name'][$key]) && !empty($_FILES["slip"]["name"][$key])) {
      $date = date("Y");
      $datetime = date("Y-m-d H:i:s");
      $fileName = basename($_FILES['slip']['name'][$key]); //ได้ชื่ออย่างเดียวไม่เอาเลยkey
      $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //แยกนามสกุลไฟล์ออกมา
      $fileName = $payment_id . "_$key" . "_$date" . "." . $fileType; //เปลี่ยนชื่อไฟล์
      $targetFilePath = "../" . $targeted . $fileName; //เอาชื่อ+ที่อยู่
      // Upload file to server 
      if (move_uploaded_file($_FILES["slip"]["tmp_name"][$key], $targetFilePath)) {
        // Image db insert sql 
        $sql = "INSERT INTO payment_detail(Payment_id,Slipname,dateuplond)
        VALUES ('$payment_id','$fileName','$datetime')";
        $result = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    } else {
      $sql = "INSERT INTO payment_detail(Payment_id,Slipname,dateuplond)
      VALUES ('$payment_id','0','0000-00-00 00:00:00')";
      $result = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
    }
  }
}
function updaateP($targeted, $payment_id, $order_detail)
{
  include("../include/Connect.php");
  // $sql = "SELECT Slipname FROM payment_detail WHERE order_detail='$order_detail'";
  $quary = mysqli_query($conn, "SELECT Slipname FROM payment_detail WHERE order_detail='$order_detail'") or die("Error : " . mysqli_error($conn));
  $alldata = mysqli_fetch_array($quary, MYSQLI_ASSOC);
  $filename = $alldata['Slipname'];
  if ($filename != "0") {
    $targetFilePath = "../" . $targeted . $filename; //เอาชื่อ+ที่อยู่
    $chek = file_exists($targetFilePath);
    if ($chek == true) {
      unlink($targetFilePath);
    }
  } else {
    $fileName = basename($_FILES['slip']['name']); //ได้ชื่ออย่างเดียวไม่เอาเลยkey
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //แยกนามสกุลไฟล์ออกมา
    $date = date("Y");
    $filename = $payment_id . "_" . $order_detail . "_$date" . "." . $fileType; //เปลี่ยนชื่อไฟล์
    $targetFilePath = "../" . $targeted . $filename; //เอาชื่อ+ที่อยู่
  }
  // $targetFilePath = "../" . $targeted . $filename; //เอาชื่อ+ที่อยู่
  $datetime = date("Y-m-d H:i:s");
  if (move_uploaded_file($_FILES["slip"]["tmp_name"], $targetFilePath)) {
    // Image db insert sql 
    $sql = "UPDATE payment_detail SET Slipname='$filename',
    dateuplond='$datetime' WHERE order_detail='$order_detail'";
    $result = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
  } else {
    $update = "UPDATE payment_detail SET Slipname='0',
    dateuplond='0000-00-00 00:00:00' WHERE order_detail='$order_detail'";
    $result = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
    echo "Sorry, there was an error uploading your file.";
  }
}

<?php
include '../include/Connect.php';
$Company = mysqli_real_escape_string($conn,$_POST['Company']);
$Domain = mysqli_real_escape_string($conn,$_POST['Domain']);
$Rates = mysqli_real_escape_string($conn,$_POST['Rates']);
$Duedate = mysqli_real_escape_string($conn,$_POST['Duedate']);
$Address = mysqli_real_escape_string($conn,$_POST['Address']);
$Email = mysqli_real_escape_string($conn,$_POST['Email']);
$Co_number = mysqli_real_escape_string($conn,$_POST['Co_number']);
$Line = mysqli_real_escape_string($conn,$_POST['Line']);
$Details = mysqli_real_escape_string($conn,$_POST['Details']);
$key = $_POST['key'];
if ($key == "add") {
  $Stardate = date("Y-m-d");
  $Mailfrom_id = "0";
  $sql = "INSERT INTO customer(Company,Domain,Rates,Stardate,Duedate,Co_number,Email,Address,Line,Details,Mailfrom_id)
    VALUE ('$Company','$Domain','$Rates','$Stardate','$Duedate','$Co_number','$Email','$Address','$Line','$Details','$Mailfrom_id')";
  if ($conn->query($sql) === TRUE) {
    if (isset($_POST['Offerprice_id'])) {
      $Offerprice_id = $_POST['Offerprice_id'];
      $clearOfferprice = clearOfferprice($Offerprice_id);
      if ($clearOfferprice['status'] != "success") {
        $return = array(
          "status" => "error",
          "Message" => "ที่ตั้งไฟล์ของผู้ใช้ผิดพลาด"
        );
        $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
        echo $jsonreturn;
      }
    }
    if ($_FILES['files']['size'] != 0 && $_FILES['files']['error'] != 0) {
      $last_id = mysqli_insert_id($conn);
      $loco = Preparefolder($last_id);
      if ($loco != "0") {
        $upfile = uplode($loco, $last_id);
        if ($upfile['status'] == "success") {
          $fileName = $upfile['filename'];
          $update = "UPDATE customer SET Profile='$fileName' WHERE Co_id='$last_id'";
          if ($conn->query($update) === TRUE) {
            $return = array(
              "status" => "success",
              "Message" => "บันทึกข้อมูลเสร็ยจสิ้น"
            );
          } else {
            $return = array(
              "status" => "error",
              "Message" => $conn->error
            );
          }
        } else {
          $return = array(
            "status" => "error",
            "Message" => $upfile['status']
          );
        }
      } else {
        $return = array(
          "status" => "error",
          "Message" => "ไม่มารถตรวจหาที่ตั้งไฟล์ได้"
        );
      }
    } else {
      $return = array(
        "status" => "success",
        "Message" => "บันทึกข้อมูลเสร็ยจสิ้น"
      );
    }
  } else {
    $return = array(
      "status" => "error",
      "Message" => "Error: " . $sql . "<br>" . $conn->error
    );
  }
  $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
} elseif ($key == "edit") {
  $Co_id = $_POST['Edit'];
  $sql = "UPDATE customer SET Company = '$Company',
  Company = '$Company',
  Domain = '$Domain',
  Rates = '$Rates',
  Duedate = '$Duedate',
  Address = '$Address',
  Email = '$Email',
  Co_number = '$Co_number',
  Line = '$Line',
  Details = '$Details'  
  WHERE `customer`.`Co_id` = '$Co_id'";
  if ($conn->query($sql) === TRUE) {
    echo "2";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
// header("Location: ../Customerlist.php");

function Preparefolder($Co_id)
{
  include("../include/Connect.php");
  $sql = "SELECT Company FROM customer WHERE Co_id ='$Co_id'";
  $quray = mysqli_query($conn, $sql);
  if ($quray->num_rows > 0) {
    $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
    $Company = $row['Company'] . $Co_id;
    $chekfloder = is_dir("../customerdetail/$Company");
    if ($chekfloder != true) {
      mkdir("../customerdetail/$Company/");
    }
    return "customerdetail/$Company/";
  } else {
    return "0";
  }
}
function uplode($loca, $ID)
{
  include("../include/Connect.php");
  $sql = "SELECT Company,Profile FROM customer WHERE Co_id ='$ID'";
  $quray = mysqli_query($conn, $sql);
  if ($quray->num_rows > 0) {
    $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
    $company = $row['Company'];
    $fileName = basename($_FILES['files']['name']); //ได้ชื่ออย่างเดียวไม่เอาเลยkey
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //แยกนามสกุลไฟล์ออกมา
    $fileName = $ID . "_$company" . "." . $fileType; //เปลี่ยนชื่อไฟล์
    $floderpro = "../" . $loca . "profile/";

    $chekfloder = is_dir($floderpro);
    if ($chekfloder != true) {
      mkdir($floderpro);
    }
    if ($row['Profile'] != "NULL") {
      $oddfile = $row['Profile'];
      $targetFilePath = $floderpro . $oddfile; //เอาชื่อ+ที่อยู่
      $chek = file_exists($targetFilePath);
      if ($chek == true) {
        unlink($targetFilePath);
      }
    }

    $targetFilePath = $floderpro . $fileName; //เอาชื่อ+ที่อยู่
    if (move_uploaded_file($_FILES["files"]["tmp_name"], $targetFilePath)) {
      $status = "success";
      $mass = $fileName;
    } else {
      $status = "error";
      $mass = "พบข้อผิดพลาดขณะกำลังบันทึกรูปภาพ";
    }
    $status = array(
      "status" => $status,
      "filename" => $mass
    );
    return $status;
  }
}
function clearOfferprice($Offerprice_id)
{
  include("../include/Connect.php");
  $sql = "SELECT Offerprice_id FROM customer_offerprice WHERE Offerprice_id ='$Offerprice_id'";
  $quray = mysqli_query($conn, $sql);
  if ($quray->num_rows > 0) {
    $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
    $Offerprice_id = $row['Offerprice_id'] . "Floder";
    $chekfloder = is_dir("../offerprice/$Offerprice_id");
    if ($chekfloder == true) {
      $files = glob("../offerprice/$Offerprice_id/*");
      foreach ($files as $file) { // iterate files
        if (is_file($file)) {
          unlink($file); // delete file
        }
      }
      rmdir("../offerprice/$Offerprice_id/");
      $return = array(
        "status" => "success",
        "Messen" => "ลบข้อมูลต้กค้างเสร็ยจสิ้น"
      );
      $sql = "DELETE FROM quotation WHERE Offerprice_id='$Offerprice_id'";
      if ($conn->query($sql) === TRUE) {
        // $sql = "DELETE FROM customer_offerprice WHERE Offerprice_id='$Offerprice_id'";
        mysqli_query($conn,"DELETE FROM customer_offerprice WHERE Offerprice_id='$Offerprice_id'") or die("Error : " . mysqli_error($conn));
        $return = array(
          "status" => "success",
          "Messen" => "ลบข้อมูลทั้งหมดต้กค้างเสร็ยจสิ้น"
        );
      } else {
        $return = array(
          "status" => "error",
          "Messen" => "ข้อผิดพลาด".$conn->error
        );
      }      
      return $return;
    } else {
      $return = array(
        "status" => "error",
        "Messen" => "ที่ตั้งไฟล์ของผู้ใช้ผิดพลาด"
      );
      return $return;
    }
  }
}

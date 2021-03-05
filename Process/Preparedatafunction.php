<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../helper/PHPMailer/src/Exception.php';
require '../helper/PHPMailer/src/PHPMailer.php';
require '../helper/PHPMailer/src/SMTP.php';
function Sendinformation($Co_id, $account)
{
  include("../include/Connect.php");
  $customer = mysqli_query($conn, "SELECT Co_id,Company,Domain,Rates,Duedate,Email,Mailfrom_id FROM customer WHERE Co_id='$Co_id'");
  $detail_customer = mysqli_fetch_array($customer, MYSQLI_ASSOC);
  $year = date("Y");
  $bil = "SELECT * FROM billing WHERE Co_id='$Co_id' AND Billdate='$year'";
  $billre = $conn->query($bil);
  if ($billre->num_rows > 0) {
    $row = mysqli_fetch_array($billre, MYSQLI_ASSOC);
    $billlocation = "../customerdetail/" . $detail_customer['Company'] . $Co_id . "/bill" . "/" . $row['Bill_file'];
    $chek = file_exists($billlocation);
    if ($chek == true) {
      $emailform = $detail_customer['Mailfrom_id'];
      $result_email = mysqli_query($conn, "SELECT * FROM email WHERE Mailfrom_id ='$emailform'");
      if ($result_email->num_rows > 0) {
        $datamail = mysqli_fetch_array($result_email, MYSQLI_ASSOC);
        $Email = $datamail["Mail_detail"];
        if ($emailform == "0") {
          $block = array("&lt;div&gt;ชื่อของบริษัท&lt;/div&gt;", "&lt;div&gt;ปีนี้&lt;/div&gt;", "&lt;div&gt;โดเมนเนม&lt;/div&gt;", "&lt;div&gt;รอบการชำระเงิน&lt;/div&gt;");
          $Duedate = DateThai($detail_customer['Duedate']);
          $YEAR_B_E = $year + 543;
          $replace = array($detail_customer['Company'], $YEAR_B_E, $detail_customer['Domain'], $Duedate);
          $Email = str_replace($block, $replace, $Email);
        }
        $Topic = "ใบแจ้งชำระค่าบริการเว็บไซต์รายปี";
        $filename = "ใบวางบิล บริษัท" . $detail_customer['Company'] . "ประจำปี" . "$YEAR_B_E" . ".pdf";
        $customerEmail = $detail_customer['Email'];
        $resultmail = sedemail($Topic, $Email, $customerEmail, $account['Username'], $account['Password'], $billlocation, $filename);
        if ($resultmail['status'] == "success") {
          $text = "ทำการส่งอีเมลย์แจ้งเตือนการชำระเงินให้ บริษัท" . $detail_customer['Company'] . "โดเมน" . $detail_customer['Domain'] . "สำเหร็ยจ";
          $return = array(
            "status" => "success",
            "bill_id" => $row['Bill_id'],
            "Email"=>$Email,
            "Message" => $text
          );
        } else {
          $text = $resultmail['Message'];
          $return = array(
            "status" => "error",
            "Message" => $text
          );
        }
      } else {
        $mass = "เกิดปัญหาในการพยายามเข้าถึงรูปแบบอีเมลย์ของ บริษัท: " . $detail_customer['Company'] . "โดเมน :" . $detail_customer['Domain'];
        $return = array(
          "status" => "error",
          "Message" => $mass
        );
      }
    } else {
      $mass = "ไม่พบไฟล์ใน" . $billlocation . "โปรดทำการอัพโหลดไฟล์ใหม่อีกครั้ง";
      $return = array(
        "status" => "error",
        "Message" => $mass
      );
    }
  } else {
    $mass = "ไม่สามารถส่งอีเมลย์ให้กับโดเมน " . $detail_customer['Domain'] . "ไม่ได้เนื่องจากยังไม่ได้ทำการอัปโหลดใบวางบิลให้กับอีเมลย์ระบบจะทำการส่งใหม่ในวันต่อไปกรุณาทำการอัปโหลดให้เรียบร้อย";
    $return = array(
      "status" => "error",
      "Message" => $mass
    );
  }
  return $return;
}

function DateThai($strDate)
{
    $year = date("Y");
    $strYear = date("Y", strtotime($year)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}
function sedemail($Topic, $Email, $customerEmail, $username, $Password, $fill, $filename)
{
    // Load Composer's autoloader
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output ถ้าจะเปิด SMTP::DEBUG_SERVER
        $mail->CharSet = "utf-8";
        $mail->setLanguage('th', '../helper/PHPMailer/language/');
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $username;                     // SMTP username
        $mail->Password   = $Password;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('aiydwijitr@gmail.com', '4_9_10_Theerapet Aiydwijitr');
        $mail->addAddress("$customerEmail");     // เพิ่มผู้รับ

        $mail->addAttachment("$fill", "$filename");
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "$Topic";
        $mail->Body    = "$Email";

        $mail->send();
        $return = array(
            "status" => "success",
            "Message" => "ส่งอีเมลย์สำเหร็ยจ"
          );
    } catch (Exception $e) {
        $return = array(
            "status" => "error",
            "Message" => $mail->ErrorInfo
          );
    }
    return $return;
}
function sedmass($sMessage, $Level)
{
    include("../include/Connect.php");
    $sql = "SELECT name,TokenLine FROM user WHERE Level='$Level' AND Statusnotify!='0'";
    $TokenLine = mysqli_query($conn, $sql);
    if (mysqli_num_rows($TokenLine) > 0) {
        while ($row = mysqli_fetch_array($TokenLine, MYSQLI_ASSOC)) {
            $sToken = $row['TokenLine'];
            if (!$sToken == "0") {
                $name = $row['name'];
                $LineMass = notify($sMessage, $sToken);
                $st = $LineMass['status'];
                if ($st == "success") {

                } else {
                    $Message = $LineMass['Message'];
                    $ero = 'ไม่สามารถส่งการแจ้งเตือนให้กับ' . $name . "เนื่องจาก " . $Message;
                    Insert($ero, "2");
                }
            }
        }
    }
}
function notify($sMessage, $sToken)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    date_default_timezone_set("Asia/Bangkok");
    $chOne = curl_init();
    curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($chOne, CURLOPT_POST, 1);
    curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=$sMessage");
    $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken . '',);
    curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($chOne);

    //Result error 
    if (curl_error($chOne)) {
        $return = array(
            "status" => "error",
            "Message" => 'error:' . curl_error($chOne)
          );
        return $return;
    } else {
        $result_ = json_decode($result, true);
        $return = array(
            "status" => "success",
            "Message" => $result_
          );
        return $return;
    }
    curl_close($chOne);
}
//Level 1 = แจ้งเตือนแค่ในLine
//Level 2 = แจ้งเตือนแค่หน้าเว็ป
//level 3 = แจ้งเตือนทุกช่องทาง
function Insert($Detail, $level)
{
    date_default_timezone_set("Asia/Bangkok");
    include("../include/Connect.php");
    $time = date("Y-m-d H:i:s");
    $status = "1";
    if ($level == "2" || $level == "3") {
        $status = "2";
    }
    $sql = "INSERT INTO notification(Detail,Not_date,Level,status)
    VALUE ('$Detail','$time','$level','$status')";
    if ($conn->query($sql) === TRUE) {
        $return = array(
            "status" => "success",
            "Message" => "ส่งอีเมลย์สำเหร็ยจ"
          );
    } else {
        $return = array(
            "status" => "error",
            "Message" => mysqli_error($conn)
          );
    }
    $conn->close();
    return $return;
}
function mailstatus($Co_id, $status,$Bill_id,$textemail)
{
    include("../include/Connect.php");
    $year = date("Y");
    $sql = "INSERT INTO sendemail(Co_id,Sendyear,status,Bill_id,Textemail)
        VALUE ('$Co_id','$year','$status','$Bill_id','$textemail')";
    if ($conn->query($sql) === TRUE) {
        // echo "New record created successfully";
    } else {
        $text =  "ไม่สามารถบันทึกข้อมูลจากคำสั่ง" . $sql . "ได้เนื่องจาก" . $conn->error;
        Insert($text, "2");
    }
}

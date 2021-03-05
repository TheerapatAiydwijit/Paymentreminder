<?php
include("Preparedatafunction.php");
include("../include/Connect.php");
$key = $_POST['key'];
if ($key == "1") {
    $data = json_decode(stripslashes($_POST['jsonString']));
    // print_r($data);
    $turn = array();
    foreach ($data as $key => $value) {
        $return = compelmailstatus($value, "1");
        $stats = array(
            "Co_id" => $value,
            "status" => $return
        );
        array_push($turn, $stats);
    }
    $jsonreturn = json_encode($turn, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($key == "2") {
    $data = json_decode(stripslashes($_POST['jsonString']));
    $emailstatus = "SELECT Username,Password FROM account WHERE typeacc='1' AND status='1' LIMIT 1";
    $quarystatus = mysqli_query($conn, $emailstatus);
    $fecthquarystatus = mysqli_fetch_array($quarystatus, MYSQLI_ASSOC);
    $account = array(
        "Username" => $fecthquarystatus['Username'],
        "Password" => $fecthquarystatus['Password']
    );
    if ($quarystatus->num_rows > 0) {
        foreach ($data as $key => $value) {
            $coumer = "SELECT Co_id,Company,Domain,Rates,Duedate,Email,Mailfrom_id FROM customer WHERE Co_id='$value'";
            $customer = mysqli_query($conn, $coumer) or die("Error : " . mysqli_error($conn));;
            $Co_iddata = mysqli_fetch_array($customer, MYSQLI_ASSOC);
            $Co_id = $Co_iddata['Co_id'];
            // $Check = "SELECT send_id FROM sendemail WHERE Co_id='$Co_id' AND Sendyear='$year'";
            // $recheck = $conn->query($Check);
            // if ($recheck->num_rows > 0) {
            //   continue;
            // }
            $resultmail = Sendinformation($Co_id, $account);
            if ($resultmail['status'] == "success") {
                $text = $resultmail['Message'];
                $textemail = $resultmail['Email'];
                $return = Insert($text, "1");
                $Bill_id = $resultmail['bill_id'];
                if ($return['status'] == "error") {
                    echo $return['Message'];
                }
                mailstatus($Co_id, "1", $Bill_id, $textemail);
            } else {
                $text = $resultmail['Message'];
                $return = Insert($text, "2");
                if ($return['status'] == "error") {
                    echo $return['Message'];
                }
                $quarychek = mysqli_query($conn, "SELECT send_id FROM sendemail WHERE status='0' AND Co_id='$Co_id' AND Sendyear='$year'");
                if ($quarychek->num_rows == 0) {
                    mailstatus($Co_id, "0", "NULL", "NULL");
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
}
function compeldata($customer)
{
    include("../include/Connect.php");
    while ($company = mysqli_fetch_array($customer)) {
        $Co_id = $company['Co_id'];
        $year = date("Y");
        $YEAR = date("Y") + 543;
        $bil = "SELECT * FROM billing WHERE Co_id='$Co_id' AND Billdate LIKE '%$year%'";
        $billre = $conn->query($bil);
        $billlocation = "";
        if ($billre->num_rows > 0) {
            $row = mysqli_fetch_array($billre, MYSQLI_ASSOC);
            $billlocation = "../customerdetail/" . $company['Company'] . $company['Co_id '] . "/bill" . "/" . $row['Bill_file'];
            $Duedate = DateThai($company['Duedate']);
            $emailform = $company['Mailfrom_id'];
            $sql = "SELECT * FROM email WHERE Mailfrom_id ='$emailform'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $Email = $row["Mail_detail"];
                if ($emailform == "0") {
                    $block = array("&lt;div&gt;ชื่อของบริษัท&lt;/div&gt;", "&lt;div&gt;ปีนี้&lt;/div&gt;", "&lt;div&gt;โดเมนเนม&lt;/div&gt;", "&lt;div&gt;รอบการชำระเงิน&lt;/div&gt;");
                    $replace = array($company['Company'], $YEAR, $company['Domain'], $Duedate);
                    $Email = str_replace($block, $replace, $Email);
                }
                $Topic = "ใบแจ้งชำระค่าบริการเว็บไซต์รายปี";
                $email = "SELECT Username,Password FROM account WHERE typeacc='1' LIMIT 1;";
                $email = mysqli_query($conn, $email);
                $account = mysqli_fetch_array($email, MYSQLI_ASSOC);
                $username = $account['Username'];
                $password = $account['Password'];
                $customerEmail = $company['Email'];
                $filename = "ใบวางบิล บริษัท" . $company['Company'] . "ประจำปี" . "$YEAR" . ".pdf";
                $resultmail = sedemail($Co_id, $company['Domain'], $Topic, $Email, $customerEmail, $username, $password, $billlocation, $filename);
                $insertemail = Insert($resultmail, "1");
                $LineMass = sedmass($resultmail, "9");
            } else {
                //แจ้งเตือนถ้าค้นหารายการformEmailในดาต้าเบสไม่เจอ
            }
        } else {
            $mass = "ไม่สามารถส่งอีเมลย์ให้กับโดเมน " . $company['Domain'] . "ได้เนื่องจากยังไม่ได้ทำการอัปโหลดใบวางบิลให้กับอีเมลย์ระบบจะทำการส่งใหม่ในวันต่อไปกรุณาทำการอัปโหลดให้เรียบร้อย";
            sedmass($mass, "9");
            Insert($mass, "3");
            // mailstatus($Co_id, "0","0");
        }
        // break; //ทำให้ส่งแค่ครั้งเดียว
    }
}
function compelmailstatus($Co_id, $status)
{
    include("../include/Connect.php");
    $year = date("Y");
    $chek = "SELECT send_id FROM sendemail WHERE Co_id='$Co_id' AND Sendyear='$year'";
    $recheck = $conn->query($chek);
    if ($recheck->num_rows > 0) {
        return "2";
    }
    $sql = "INSERT INTO sendemail(Co_id,Sendyear,status)
        VALUE ('$Co_id','$year','$status')";
    if ($conn->query($sql) === TRUE) {
        return "1";
    } else {
        $text =  "ไม่สามารถบันทึกข้อมูลจากคำสั่ง" . $sql . "ได้เนื่องจาก" . $conn->error;
        $tun = Insert($text, "2");
        return $tun;
    }
}

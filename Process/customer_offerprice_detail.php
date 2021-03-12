<?php
include_once("Preparedatafunction.php");
include("../include/Connect.php");
// include("../include/Frequently.php");
$key = $_POST['key'];
date_default_timezone_set("Asia/Bangkok");
if ($key == "all") {
    $Offerprice_id  = $_POST['Offerprice_id'];
    $offerprice = "SELECT * FROM customer_offerprice WHERE Offerprice_id='$Offerprice_id'";
    $dataquery = mysqli_query($conn, $offerprice) or die("Error : " . mysqli_error($conn));
    $data = mysqli_fetch_array($dataquery, MYSQLI_ASSOC);
    $Offerprice_id = (!empty($data['Offerprice_id']) ? $data['Offerprice_id'] : 'ไม่มีข้อมูล');
    $Company = (!empty($data['Company']) ? $data['Company'] : 'ไม่มีข้อมูล');
    $Domain = (!empty($data['Domain']) ? $data['Domain'] : 'ไม่มีข้อมูล');
    $Rates = (!empty($data['Rates']) ? $data['Rates'] : 'ไม่มีข้อมูล');
    $Stardate = (!empty($data['Stardate']) ? $data['Stardate'] : 'ไม่มีข้อมูล');
    $Duedate = (!empty($data['Duedate']) ? $data['Duedate'] : 'ไม่มีข้อมูล');
    $Co_number = (!empty($data['Co_number']) ? $data['Co_number'] : 'ไม่มีข้อมูล');
    $Email = (!empty($data['Email']) ? $data['Email'] : 'ไม่มีข้อมูล');
    $Address = (!empty($data['Address']) ? $data['Address'] : 'ไม่มีข้อมูล');
    $Line = (!empty($data['Line']) ? $data['Line'] : 'ไม่มีข้อมูล');
    $Details = (!empty($data['Details']) ? $data['Details'] : 'ไม่มีข้อมูล');
    $Mailfrom_id = (!empty($data['Mailfrom_id']) ? $data['Mailfrom_id'] : 'ไม่มีข้อมูล');
    $array = array(
        "Offerprice_id" => $Offerprice_id,
        "Company" => $Company,
        "Domain" => $Domain,
        "Rates" => $Rates,
        "Stardate" => $Stardate,
        "Duedate" => $Duedate,
        "Co_number" => $Co_number,
        "Email" => $Email,
        "Address" => $Address,
        "Line" => $Line,
        "Details" => $Details,
        "Mailfrom_id" => $Mailfrom_id
    );
    $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($key == "UPDATE") {
    $columnname = mysqli_real_escape_string($conn,$_POST['column']);
    $value = mysqli_real_escape_string($conn,$_POST['value']);
    if ($value == "ไม่มีข้อมูล") {
        $value = "";
    }
    $Offerprice_id  = $_POST['Offerprice_id'];
    $sqlUPDATE = "UPDATE customer_offerprice SET $columnname='$value' WHERE Offerprice_id='$Offerprice_id'";
    $quary = mysqli_query($conn, $sqlUPDATE) or die("Error : " . mysqli_error($conn));
    echo "1";
} elseif ($key == "Quotation") {
    $Offerprice_id  = mysqli_real_escape_string($conn,$_POST['Offerprice_id']);
    $quotationsql = "SELECT * FROM quotation WHERE Offerprice_id='$Offerprice_id'";
    $dataquery = mysqli_query($conn, $quotationsql) or die("Error : " . mysqli_error($conn));
    $array = array();
    $i = 1;
    while ($data = mysqli_fetch_array($dataquery, MYSQLI_ASSOC)) {
        if ($data['Senddate'] == "") {
            $datesend = "error";
        } elseif ($data['Senddate'] == "0000-00-00 00:00:00") {
            $datesend = "Draft";
        } else {
            $datesend = $data['Senddate'];
        }
        $data = array(
            "Order" => $i,
            "Order_id " => $data['Order_id'],
            "Offerprice_id" => $data['Offerprice_id'],
            "Date" => DateThai($data['Date']),
            "Toppic" => $data['Toppic'],
            "Sendto" => $data['Sendto'],
            "Message" => $data['Message'],
            "Filename" => $data['Filename'],
            "Senddate" => $datesend
        );
        array_push($array, $data);
        $i++;
    }
    $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($key == "recordemail" || $key == "sendemail") {
    $Offerprice_id  = $_POST['Offerprice_id'];
    $message = $_POST['message'];
    $Toppic = $_POST['Toppic'];
    $Sendto = $_POST['Sendto'];
    
    $loca = Preparefolder($Offerprice_id);
    $datetime = date("Y-m-d H:i:s");
    if ($key == "recordemail") {
        $upond  = array(
            "status" => "success",
            "filename" => "แบบร่างไม่มีไฟล์"
        );
    } else {
        $upond = uplode($loca, $Offerprice_id);
    }
    if ($upond['status'] == "success") {
        $fileName = $upond['filename'];
        $insert = "INSERT INTO quotation(Offerprice_id,Date,Toppic,Message,Sendto,Filename)
                   VALUE ('$Offerprice_id','$datetime','$Toppic','$message','$Sendto','$fileName')";

                   
        if ($conn->query($insert) === TRUE) {
            $last_id = mysqli_insert_id($conn);
            $return = array(
                "status" => "success",
                "Order_id" => $last_id,
                "Message" => "บันทึกแบบร่างเสร็ยจสิ้น"
            );
            if ($key == "sendemail") {
                // ถ้ากดส่งเมลย์จะเข้าฟังชั้นนี้
                $email = "SELECT Username,Password FROM account WHERE typeacc='1' LIMIT 1;";
                $email = mysqli_query($conn, $email);
                $account = mysqli_fetch_array($email, MYSQLI_ASSOC);
                $username = $account['Username'];
                $password = $account['Password'];
                $billlocation = "../" . $loca . $fileName;
                $filename = "ใบเสนอราคา" . date("Y-m-d");
                $resultmail = sedemail($Toppic, $message, $Sendto, $username, $password, $billlocation, $filename);
                if ($resultmail['status'] == "success") {
                    $Detail = "ทำการส่งใบเสนอราคาให้กับ" . $Sendto . "สำเหร็ยจ";
                    $notification = Insert($Detail, "1");
                    sedmass($Detail, "9");
                    $return = array(
                        "status" => "success",
                        "Order_id" => $last_id,
                        "Message" => "ส่งอีเมลย์ใบเสนอราคาเสร็ยจสิ้น"
                    );
                    $datetime = date("Y-m-d H:i:s");
                    $sqlupdatestatus = "UPDATE quotation SET Senddate='$datetime' WHERE Order_id='$last_id'";
                    $dataquery = mysqli_query($conn, $sqlupdatestatus) or die("Error : " . mysqli_error($conn));
                } else {
                    $return = array(
                        "status" => "error",
                        "Order_id" => $last_id,
                        "Message" => $resultmail
                    );
                    $Message = $resultmail['Message'];
                    $Detail = "ไม่สามารถทำการส่งใบเสนอราคาให้กับ" . $Sendto . "ได้ เนื่องจาก" . $Message;
                    $notification = Insert($Detail, "3");
                    sedmass($Detail, "9");
                }
            } elseif ($key == "recordemail") {
                $datetime = "0000-00-00 00:00:00";
                $sqlupdatestatus = "UPDATE quotation SET Senddate='$datetime' WHERE Order_id='$last_id'";
                $dataquery = mysqli_query($conn, $sqlupdatestatus) or die("Error : " . mysqli_error($conn));
            }
        } else {
            $return = array(
                "status" => "error",
                "Order_id" => "NULL",
                "Message" => $conn->error
            );
        }
    } else {
        $return = array(
            "status" => "error",
            "Order_id" => "NULL",
            "Message" => $upond['filename']
        );
    }
    $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
}

function Preparefolder($Offerprice_id)
{
    include("../include/Connect.php");
    $sql = "SELECT Offerprice_id FROM customer_offerprice WHERE Offerprice_id ='$Offerprice_id'";
    $quray = mysqli_query($conn, $sql);
    if ($quray->num_rows > 0) {
        $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
        $Offerprice_id = $row['Offerprice_id'] . "Floder";
        $chekfloder = is_dir("../offerprice/$Offerprice_id");
        if ($chekfloder != true) {
            mkdir("../offerprice/$Offerprice_id/");
        }
        return "offerprice/$Offerprice_id/";
    } else {
        return "0";
    }
}
function uplode($loca, $ID)
{
    include("../include/Connect.php");
    $newdate = date("Y_m_d_H_i_s");
    $fileName = basename($_FILES['files']['name']); //ได้ชื่ออย่างเดียวไม่เอาเลยkey
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //แยกนามสกุลไฟล์ออกมา
    $fileName = $ID . "_$newdate" . "." . $fileType; //เปลี่ยนชื่อไฟล์
    $targetFilePath = "../" . $loca . $fileName; //เอาชื่อ+ที่อยู่
    $uploadOk = 1;
    if ($fileType != "pdf") {
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        $status = "error";
        $mass = "ขออภัยอนุญาตเฉพาะไฟล์ pdf เท่านั้น";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["files"]["tmp_name"], $targetFilePath)) {
            $status = "success";
            $mass = $fileName;
        } else {
            $status = "error";
            $mass = "ขออภัยอนุญาตเฉพาะไฟล์ pdf เท่านั้น";
        }
    }
    $status = array(
        "status" => $status,
        "filename" => $mass
    );
    return $status;
}

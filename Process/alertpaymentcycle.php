<?php
include("../include/Connect.php");
$keyfunction = mysqli_real_escape_string($conn,$_POST['key']);
//key 1 ไว้สำหรับการดึงข้อมูลมาเพื่อสร้างช่องว่าใหกล้ถึงรอบจ่ายมีโดเมนอะไรบ้าง
//key 2 ไว้ดึงข้อมูลรายละเอียดของบริษัทนั้นไปแสดง
if ($keyfunction == "1") { //ตรวจคีว่าต้องการจะทำอะไร
    $month = $_POST['month'];
    $sqllike = "-" . $month . "-";
    $sql = "SELECT * FROM customer WHERE Duedate LIKE '%$sqllike%' AND Sdelete='0' ORDER BY Duedate ";
    $quray = mysqli_query($conn, $sql);
    echo getdata($keyfunction, $quray);
} elseif ($keyfunction == "3") {
    $Co_id = $_POST['Co_id'];
    // echo $Co_id;
    $loca = Preparefolder($Co_id);
    if ($loca == "0") {
        echo $Co_id;
    } else {
        $date = date('Y-m-01');
        $year = date("Y", strtotime($date));
        $newyear = $_POST['newyear'];
        if ($newyear == "true") {
            $year = date("Y", strtotime($date . "+1 year"));
        }
       $uplondfile = uplode($loca, $Co_id, $year);
       $jsonreturn = json_encode($uplondfile, JSON_UNESCAPED_UNICODE);
       echo $jsonreturn;
    }
}

function getdata($key, $quray)
{
    $returnarray = array();
    if (mysqli_num_rows($quray) > 0) {
        while ($data = mysqli_fetch_array($quray, MYSQLI_ASSOC)) {
            $Co_id = $data['Co_id'];
            $Company = $data['Company'];
            $Domain = $data['Domain'];
            $Rates = $data['Rates'];
            // $Stardate = $data['Stardate'];
            $Duedate = $data['Duedate'];
            // $Co_number = $data['Co_number'];
            $Email = $data['Email'];
            // $Address = $data['Address'];
            // $Line = $data['Line'];
            // $Details = $data['Details'];
            // $Mailfrom_id = $data['Mailfrom_id'];
            $bill = getbill($Co_id);
            $stbill = "รอการอัปโหลด";
            if ($bill['Bill_id'] != "ไม่มีข้อมูล") {
                $stbill = $bill;
            }
            $Coarray = array(
                "Co_id" => $Co_id,
                "Company" => $Company,
                "Domain" => $Domain,
                "Rates" => $Rates,
                "Duedate" => DateThai($Duedate),
                "Email" => $Email,
                "stbill" => $stbill,
            );
            array_push($returnarray, $Coarray);
        }
        $jsonreturn = json_encode($returnarray, JSON_UNESCAPED_UNICODE);
        return $jsonreturn;
    }
}
//แปลงวันเดือนปีเปนแบบไทย
function DateThai($strDate)
{
    $date = date('Y-m-01');
    $current = date("m", strtotime($date));
    $strYear = date("Y") + 543;
    if ($current == "12") {
        $strYear = $strYear + 1;
    }
    // $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}
function getbill($Co_id)
{
    include("../include/Connect.php");
    $date = date('Y-m-01');
    $year = date("Y", strtotime($date));
    $newyear = $_POST['newyear'];
    if ($newyear == "true") {
        $year = date("Y", strtotime($date . "+1 year"));
    }
    $bil = "SELECT * FROM billing WHERE Co_id='$Co_id' AND Billdate LIKE '%$year%'";
    $billre = $conn->query($bil);
    if ($billre->num_rows > 0) {
        // output data of each row
        while ($row = mysqli_fetch_array($billre, MYSQLI_ASSOC)) {
            $retun = array(
                "Bill_id" => $row['Bill_id'],
                "Date" => $row['Billdate'],
                "Co_id" => $row['Co_id'],
                "Bill_file" => $row['Bill_file']
            );
            return $retun;
        }
    } else {
        $retun = array(
            "Bill_id" => "ไม่มีข้อมูล",
            "Date" => "ไม่มีข้อมูล",
            "Co_id" => "ไม่มีข้อมูล",
            "Bill_file" => "ไม่มีข้อมูล"
        );
        return $retun;
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
        $chekfloderslip = is_dir("../customerdetail/$Companyname/bill");
        if ($chekfloderslip != true) {
            mkdir("../customerdetail/$Companyname/bill/");
        }
        return "customerdetail/$Companyname/bill/";
    } else {
        return "0";
    }
}
function uplode($loca, $Co_id, $year)
{
    include("../include/Connect.php");
    $fileName = basename($_FILES['files']['name']); //ได้ชื่ออย่างเดียวไม่เอาเลยkey
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //แยกนามสกุลไฟล์ออกมา
    // $sql =  "SELECT * FROM billing WHERE Billdate='$year' AND Co_id	='$Co_id'";
    $query = mysqli_query($conn, "SELECT Bill_file FROM billing WHERE Billdate='$year' AND Co_id='$Co_id'") or die("Error : " . mysqli_error($conn));
    $num_rows = "0";
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_array($query, MYSQLI_ASSOC);
        $fileName = $data['Bill_file'];
        $num_rows = "1";
    } else {
        $fileName = $Co_id . "_$year" . "." . $fileType; //เปลี่ยนชื่อไฟล์
    }
    $targetFilePath = "../" . $loca . $fileName; //เอาชื่อ+ที่อยู่
    $uploadOk = 1;
    if ($fileType != "pdf") {
        // echo "ขออภัยอนุญาตเฉพาะไฟล์ pdf เท่านั้น";
        $uploadOk = 0;
    }
    if (file_exists($targetFilePath)) {
        unlink("$targetFilePath");
        // $delete = "DELETE FROM billing WHERE Billdate='$year' AND Co_id='$Co_id'";
        // mysqli_query($conn, $delete);
    }
    if ($uploadOk == 0) {
        echo "1";
        $return = array(
            "status" => "error",
            "Message" => "อัปโหลดไฟล์ .PDF เท่านั้น"
        );
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["files"]["tmp_name"], $targetFilePath)) {
            $return = array(
                "status" => "success",
                "Message" => "อัปโหลดใบเรียกเก็บเงินสำเหร็ยจ"
            );
            // echo "อัปโหลดไฟล์ " . htmlspecialchars(basename($_FILES["files"]["name"])) . " เสร็ยจสมบูรณ์";
            if ($num_rows == "0") {
                $insert = "INSERT INTO billing(Billdate,Co_id,Bill_file)
                VALUE ('$year','$Co_id','$fileName')";
                if ($conn->query($insert) === TRUE) {
                    // echo "New record created successfully";
                } else {
                    $return = array(
                        "status" => "error",
                        "Message" => $conn->error
                    );
                }
            }
        } else {
            $return = array(
                "status" => "error",
                "Message" => "อัปโหลดไฟล์ไม่สำเหร็ยจ"
            );
        }
    }
    return $return;
}

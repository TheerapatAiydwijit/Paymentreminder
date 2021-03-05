<?php
include("../include/Connect.php");
$keyfunction = $_POST['key'];
//key 1 ไว้สำหรับการดึงข้อมูลมาเพื่อสร้างช่องว่าใหกล้ถึงรอบจ่ายมีโดเมนอะไรบ้าง
//key 2 ไว้ดึงข้อมูลรายละเอียดของบริษัทนั้นไปแสดง
if ($keyfunction == "1") { //ตรวจคีว่าต้องการจะทำอะไร
    $month = $_POST['month'];
    $sqllike = "-" . $month . "-";
    $sql = "SELECT * FROM customer WHERE Duedate LIKE '%$sqllike%' AND Sdelete='0' ORDER BY Duedate ";
    $quray = mysqli_query($conn, $sql);
    echo getdata($keyfunction, $quray);
} elseif ($keyfunction == "2") { //ตรวจkey
    $Co_id = $_POST['Co_id'];
    $sql = "SELECT * FROM customer WHERE Co_id='$Co_id'";
    $quray = mysqli_query($conn, $sql);
    echo getdata($keyfunction, $quray);
} elseif ($keyfunction == "3") {
    $Co_id = $_POST['Co_id'];
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
        uplode($loca, $Co_id, $year);
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
            $Stardate = $data['Stardate'];
            $Duedate = $data['Duedate'];
            $Co_number = $data['Co_number'];
            $Email = $data['Email'];
            $Address = $data['Address'];
            $Line = $data['Line'];
            $Details = $data['Details'];
            $Mailfrom_id = $data['Mailfrom_id'];
            $bill = getbill($Co_id);
            if ($key == "1") {
                $stbill = "รอการอัปโหลดใบเรียกเก็บเงิน";
                if ($bill['Bill_id'] != "ไม่มีข้อมูล") {
                    $stbill = "อัปโหลดใบเก็บเงินเรียบร้อย";
                }
                $html = '<div class="card mt-3 dataC" id=' . "$Co_id" . ' onclick=' . "showdata($Co_id)" . '>
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="align-self-center">
                                <h1 class="mr-2">$' . "$Rates" . '</h1>
                            </div>
                            <div class="media-body">
                                <h4>' . "$Domain" . '</h4>
                                <span>' . DateThai($Duedate) . '</span>
                            </div>
                            <div class="align-self-center">
                                <h4>' . "$stbill" . '</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
                array_push($returnarray, $html);
            } //ปิด $key == 1
            elseif ($key == "2") {
                $Coarray = array(
                    "Co_id" => $Co_id,
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
                $returnarray = array(
                    "Co" => $Coarray,
                    "Bill" => $bill
                );
            } //ปิด $key == 2

        } //ปิดwhile
    } else {
        if ($key == "1") {
            $html = '<div class="card">
            <div class="card-content">
                <div class="card-body cleartfix">
                    <div class="media align-items-stretch">
                        <div class="align-self-center">
                            <h1 class="mr-2">$' . "00000" . '</h1>
                        </div>
                        <div class="media-body">
                            <h4>' . "ไม่มีรายการจ่ายเงินในเดือนนี้" . '</h4>
                            <span>' . "------" . '</span>
                        </div>
                        <div class="align-self-center">
                            <img src="images/icons/wallet.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>';
            array_push($returnarray, $html);
        } elseif ($key == "2") {
            $returnarray = array(
                "Co_id" => "ไม่มีข้อมูล",
                "Company" => "ไม่มีข้อมูล",
                "Domain" => "ไม่มีข้อมูล",
                "Rates" => "ไม่มีข้อมูล",
                "Stardate" => "ไม่มีข้อมูล",
                "Duedate" => "ไม่มีข้อมูล",
                "Co_number" => "ไม่มีข้อมูล",
                "Email" => "ไม่มีข้อมูล",
                "Address" => "ไม่มีข้อมูล",
                "Line" => "ไม่มีข้อมูล",
                "Details" => "ไม่มีข้อมูล",
                "Mailfrom_id" => "ไม่มีข้อมูล"
            );
        }
    }
    $jsonreturn = json_encode($returnarray, JSON_UNESCAPED_UNICODE);
    return $jsonreturn;
}
//แปลงวันเดือนปีเปนแบบไทย
function DateThai($strDate)
{
    $date = date('Y-m-01');
    $current = date("m", strtotime($date));
    $strYear = date("Y") + 543;
    if ($current == "12") {
        $strYear = $strYear+1;
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
        $Companyname = $row['Company'].$Co_id;
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
    $fileName = $Co_id . "_$year" . "." . $fileType; //เปลี่ยนชื่อไฟล์
    $targetFilePath = "../" . $loca . $fileName; //เอาชื่อ+ที่อยู่
    $uploadOk = 1;
    if ($fileType != "pdf") {
        // echo "ขออภัยอนุญาตเฉพาะไฟล์ pdf เท่านั้น";
        $uploadOk = 0;
    }
    if (file_exists($targetFilePath)) {
        unlink("$targetFilePath");
        $delete = "DELETE FROM billing WHERE Billdate='$year' AND Co_id='$Co_id'";
        mysqli_query($conn, $delete);
    }
    if ($uploadOk == 0) {
        echo "1";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["files"]["tmp_name"], $targetFilePath)) {
            echo "2";
            // echo "อัปโหลดไฟล์ " . htmlspecialchars(basename($_FILES["files"]["name"])) . " เสร็ยจสมบูรณ์";
            $insert = "INSERT INTO billing(Billdate,Co_id,Bill_file)
                VALUE ('$year','$Co_id','$fileName')";
            if ($conn->query($insert) === TRUE) {
                // echo "New record created successfully";
            } else {
                echo "3";
                // echo $conn->error;
            }
        } else {
            echo "4";
        }
    }
}

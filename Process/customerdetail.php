<?php
include("../include/Connect.php");
include("../include/Frequently.php");
$key = $_POST['key'];
if ($key == "loaddata") {
  $Co_id = $_POST['Co_id'];
  $sqlselect = "SELECT * FROM customer WHERE Co_id='$Co_id'";
  $quary = mysqli_query($conn, $sqlselect);
  $data = mysqli_fetch_array($quary, MYSQLI_ASSOC);
  $Co_id = (!empty($data['Co_id']) ? $data['Co_id'] : 'ไม่มีข้อมูล');
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
  $Profile = (!empty($data['Profile']) ? $data['Profile'] : 'ไม่มีข้อมูล');
  $Mailfrom_id = (!empty($data['Mailfrom_id']) ? $data['Mailfrom_id'] : 'ไม่มีข้อมูล');
  $array = array(
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
    "Profile" => $Profile,
    "Mailfrom_id" => $Mailfrom_id
  );
  $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
} elseif ($key == "UPDATE") {
  $columnname = $_POST['column'];
  $value =  mysqli_real_escape_string($conn,$_POST['value']);
  $Co_id  =  mysqli_real_escape_string($conn,$_POST['Co_id']);
  if ($value == "ไม่มีข้อมูล") {
    $value = "";
  }
  if ($columnname == "Company") {
    // $sql = "SELECT Company FROM customer WHERE Co_id='$Co_id'";
    $quray = mysqli_query($conn, "SELECT Company FROM customer WHERE Co_id='$Co_id'") or die("Error : " . mysqli_error($conn));
    if ($quray->num_rows > 0) {
      $row = mysqli_fetch_array($quray, MYSQLI_ASSOC);
      $Companyname = $row['Company'] . $Co_id;
      $NewCompanyname = $value . $Co_id;
      $chekfloder = is_dir("../customerdetail/$Companyname");
      if ($chekfloder == true) {
        // mkdir("../customerdetail/$Companyname/");
        $rename = rename("../customerdetail/$Companyname", "../customerdetail/$NewCompanyname");
        if ($rename != true) {
          echo "renameerror";
        } else {
          $sqlUPDATE = "UPDATE customer SET $columnname='$value' WHERE Co_id='$Co_id'";
          $quary = mysqli_query($conn, $sqlUPDATE) or die("Error : " . mysqli_error($conn));
          echo "1";
        }
      }
    } else {
      $return = array(
        "status" => "error",
        "message" => mysqli_error($conn)
      );
      print_r($return);
    }
  } else {
    $sqlUPDATE = "UPDATE customer SET $columnname='$value' WHERE Co_id='$Co_id'";
    $quary = mysqli_query($conn, $sqlUPDATE) or die("Error : " . mysqli_error($conn));
    echo "1";
  }
} elseif ($key == "Quotation") {
  $Co_id  = $_POST['Co_id'];
  // $quotationsql = "SELECT sendemail.send_id,sendemail.Sendyear,sendemail.status,sendemail.Bill_id,billing.Bill_file FROM sendemail INNER JOIN billing ON sendemail.Bill_id=billing.Bill_id WHERE sendemail.Co_id='$Co_id'";
  $dataquery = mysqli_query($conn, "SELECT sendemail.send_id,sendemail.Sendyear,sendemail.status,sendemail.Bill_id,billing.Bill_file FROM sendemail INNER JOIN billing ON sendemail.Bill_id=billing.Bill_id WHERE sendemail.Co_id='$Co_id'") or die("Error : " . mysqli_error($conn));
  $array = array();
  $i = 1;
  while ($data = mysqli_fetch_array($dataquery, MYSQLI_ASSOC)) {
    $data = array(
      "Order" => $i,
      "send_id" => $data['send_id'],
      "Sendyear" => $data['Sendyear'],
      "status" => $data['status'],
      "bill_id" => $data['Bill_id']
    );
    array_push($array, $data);
    $i++;
  }
  $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
} elseif ($key == "londpayment") {
  $Co_id =  mysqli_real_escape_string($conn,$_POST['Co_id']);
  // $sql ="SELECT * FROM payment WHERE Co_id='$Co_id' ORDER BY date";
  $query = mysqli_query($conn, "SELECT * FROM payment WHERE Co_id='$Co_id' ORDER BY date") or die("Error : " . mysqli_error($conn));
  $array = array();
  while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $status = ($row['status'] == "1") ? "เสร็ยจสิ้น" : "กำลังดำเนินการ";
    $return = array(
      "payment_id" => $row['payment_id'],
      "date" => DateThai($row['date']),
      "Co_id" => $row['Co_id'],
      "status" => $status
    );
    array_push($array, $return);
  }
  $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
} elseif ($key == "londimg") {
  $payment_id =  mysqli_real_escape_string($conn,$_POST['payment_id']);
  // $sql ="SELECT Co_id FROM payment WHERE payment_id='$payment_id'";
  $queryCo_id = mysqli_query($conn, "SELECT Co_id,status FROM payment WHERE payment_id='$payment_id'") or die("Error : " . mysqli_error($conn));
  $alldata = mysqli_fetch_array($queryCo_id, MYSQLI_ASSOC);
  $Co_id = $alldata['Co_id'];
  $status = ($alldata['status'] == "1") ? "เสร็ยจสิ้น" : "กำลังดำเนินการ";
  $locations = Preparefolder($Co_id);
  // $sql = "SELECT * FROM payment_detail WHERE payment_id='$payment_id' ORDER BY dateuplond";
  $query = mysqli_query($conn, "SELECT * FROM payment_detail WHERE payment_id='$payment_id' ORDER BY order_detail") or die("Error : " . mysqli_error($conn));
  $array = array();
  $num_rows = mysqli_num_rows($query);
  $i = 1;
  while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {

    if ($row["Slipname"] != "0") {
      $Slipname = $locations . $row["Slipname"];
    } else {
      $url = "https://placeholder.pics/svg/200x300/DEDEDE/555555/" . "งวดที่ " . $i;
      $Slipname = $url;
    }
    $return = array(
      "order_detail" => $row['order_detail'],
      "payment_id" => $row["payment_id"],
      "Slipname" => $Slipname,
      "dateuplond" => $row["dateuplond"],
      "num_rows" => $num_rows,
      "status" => $status
    );
    array_push($array, $return);
    $i++;
  }
  $jsonreturn = json_encode($array, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
} elseif ($key == "showemail") {
  $send_id =  mysqli_real_escape_string($conn,$_POST['send_id']);
  // $sql = "SELECT * FROM sendemail WHERE send_id ='$send_id'";
  $quary = mysqli_query($conn, "SELECT * FROM sendemail WHERE send_id='$send_id'") or die("Error : " . mysqli_error($conn));
  $alldata = mysqli_fetch_array($quary, MYSQLI_ASSOC);
  $Co_id = $alldata['Co_id'];
  $Bill_id = $alldata['Bill_id'];
  // $sql = "SELECT Bill_file FROM billing WHERE Bill_id='$Bill_id'";
  $bill = mysqli_query($conn, "SELECT Bill_file FROM billing WHERE Bill_id='$Bill_id'") or die("Error : " . mysqli_error($conn));
  $Bill_file = mysqli_fetch_array($bill, MYSQLI_ASSOC);
  $Bill_file = $Bill_file['Bill_file'];
  $locations = Preparefolderbill($Co_id);
  $bill_locations = $locations . $Bill_file;
  $array = array(
    "status" => $alldata['status'],
    "Bill_id" => $alldata['Bill_id'],
    "Bill_locations" => $bill_locations,
    "Bill_name" => $Bill_file,
    "Textemail" => $alldata['Textemail']
  );
  $return = json_encode($array, JSON_UNESCAPED_UNICODE);
  echo $return;
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
function Preparefolderbill($Co_id)
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

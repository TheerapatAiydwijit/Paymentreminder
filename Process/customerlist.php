<?php
include('../include/Connect.php');
$key = $_POST['key'];
if($key == "Customer"){
  $query = mysqli_query($conn, "SELECT * FROM customer ORDER BY Co_id DESC");
  $return = array();
  while ($value = mysqli_fetch_array($query)) {
    if ($value['Sdelete'] == "1") {
      $Duedate = "ไม่ได้ทำการต่ออายุ";
    } else {
      $strDay = date("j", strtotime($value['Duedate']));
      $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
      $strMonth = date("n", strtotime($value['Duedate']));
      $strMonthThai = $strMonthCut[$strMonth];
      // $current = date("m-d", strtotime($value['Duedate']));
      $Duedate = $strDay . "  " . $strMonthThai;
    }
    $arraydata = array(
      "Co_id" => $value['Co_id'],
      "Company" => $value['Company'],
      "Domain" => $value['Domain'],
      "Duedate" => $Duedate,
      'Sd' => array(
        "Co_id" => $value['Co_id'],
        "Sdelete" => $value['Sdelete']
      )
    );
    array_push($return, $arraydata);
  }
  $returnfull = array(
    "data" => $return
  );
  $jsonreturn = json_encode($returnfull, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
}elseif($key == "offerprice"){
  $query = mysqli_query($conn, "SELECT * FROM customer_offerprice ORDER BY Offerprice_id DESC");
  $return = array();
  while ($value = mysqli_fetch_array($query)) {
    if ($value['Sdelete'] == "1") {
      $Duedate = "ไม่ได้ทำการต่ออายุ";
    } else {
      $strDay = date("j", strtotime($value['Duedate']));
      $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
      $strMonth = date("n", strtotime($value['Duedate']));
      $strMonthThai = $strMonthCut[$strMonth];
      // $current = date("m-d", strtotime($value['Duedate']));
      $Duedate = $strDay . "  " . $strMonthThai;
    }
    $arraydata = array(
      "Offerprice_id" => $value['Offerprice_id'],
      "Company" => $value['Company'],
      "Domain" => $value['Domain'],
      "Duedate" => $Duedate,
      'Sd' => array(
        "Offerprice_id" => $value['Offerprice_id'],
        "Sdelete" => $value['Sdelete']
      )
    );
    array_push($return, $arraydata);
  }
  $returnfull = array(
    "data" => $return
  );
  $jsonreturn = json_encode($returnfull, JSON_UNESCAPED_UNICODE);
  echo $jsonreturn;
}


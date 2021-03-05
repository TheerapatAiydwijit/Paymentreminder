<?php
include '../include/Connect.php';
$Company = (isset($_POST['Company']) ? $_POST['Company'] :"");
$Domain =(isset($_POST['Domain']) ? $_POST['Domain'] :"");
$Rates =(isset($_POST['Rates']) ? $_POST['Rates'] :"");
$Duedate =(isset($_POST['Duedate']) ? $_POST['Duedate'] :"");
$Address =(isset($_POST['Address']) ? $_POST['Address'] :"");
$Email =(isset($_POST['Email']) ? $_POST['Email'] :"");
$Co_number =(isset($_POST['Co_number']) ? $_POST['Co_number'] :"");
$Line =(isset($_POST['Line']) ? $_POST['Line'] :"");
$Details =(isset($_POST['Details']) ? $_POST['Details'] :"");
if (isset($_POST['Add'])) {
  $Stardate = date("Y-m-d");
  $Mailfrom_id = "0";
  $sql = "INSERT INTO customer_offerprice(Company,Domain,Rates,Stardate,Duedate,Co_number,Email,Address,Line,Details,Mailfrom_id)
    VALUE ('$Company','$Domain','$Rates','$Stardate','$Duedate','$Co_number','$Email','$Address','$Line','$Details','$Mailfrom_id')";
  if ($conn->query($sql) === TRUE) {
    echo "1";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
} elseif (isset($_POST['Edit'])) {
  $Co_id = $_POST['Edit'];
  $sql ="UPDATE customer_offerprice SET Company = '$Company',
  Company = '$Company',
  Domain = '$Domain',
  Rates = '$Rates',
  Duedate = '$Duedate',
  Address = '$Address',
  Email = '$Email',
  Co_number = '$Co_number',
  Line = '$Line',
  Details = '$Details'  
  WHERE `Co_id` = '$Co_id'";
   if ($conn->query($sql) === TRUE) {
    echo "2";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
      
}
// header("Location: ../Customerlist.php");

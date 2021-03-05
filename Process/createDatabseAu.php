<?php
include("../include/Connect.php");
$Unit = $_POST['Unit'];
$userid = $_POST['userid'];
// $sqlD = "DELETE FROM reminder WHERE userid='$userid'";
// if ($conn->query($sqlD) === TRUE) {
//   echo "Record deleted successfully";
// } else {
//   echo "Error deleting record: " . $conn->error;
// }
$sql = "INSERT INTO reminder
        VALUE ('$userid','$Unit',' ')";
if ($conn->query($sql) === TRUE) {
  echo "1";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

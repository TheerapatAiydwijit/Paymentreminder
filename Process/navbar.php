<?php
    include("../include/Connect.php");
    $Not_id = $_POST['Not_id'];
    $sql ="UPDATE notification SET status='1' WHERE Not_id ='$Not_id'";
    if (mysqli_query($conn, $sql)) {
       echo "Record updated successfully";
    } else {
      echo "Error updating record: " . mysqli_error($conn);
  }
    mysqli_close($conn);

<?php 
include('../include/Connect.php');
if (isset($_POST['argument'])) {
    $Co_id = $_POST['argument'];
    $sqldele = "UPDATE customer SET Sdelete='1' WHERE Co_id='$Co_id'";
    // $query = mysqli_query($conn, $sqldele);
    // header("Location: Customerlist.php");
    if ($conn->query($sqldele) === TRUE) {
        echo "1";
    }else{
        echo "$conn->error";
    }
  }elseif(isset($_POST['renewcus'])){
    $Co_id = $_POST['renewcus'];
    $sqldele = "UPDATE customer SET Sdelete='0' WHERE Co_id='$Co_id'";
    // $query = mysqli_query($conn, $sqldele);
    // header("Location: Customerlist.php");
    if ($conn->query($sqldele) === TRUE) {
        echo "1";
    }else{
        echo "$conn->error";
    }
  }
?>
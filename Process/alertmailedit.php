<?php
    include_once("../include/Connect.php");
    $detail = $_POST['detail'];
    $Co_id = $_POST['Co_id'];
    if($Co_id == "0"){
      $sql = "UPDATE email SET Mail_detail='$detail' WHERE Mailfrom_id ='0'";
    }else{
      $cke = "SELECT Mailfrom_id FROM email";
      $result = mysqli_query($conn, $cke);
      $numrow =mysqli_num_rows($result);
      $numrow = $numrow + 1;
      $Name_email = $_POST['Name_email'];
      $sql = "INSERT INTO email
      VALUE('$numrow','$Name_email','$detail')";
    }
    if ($conn->query($sql) === TRUE) {
          if(!$Co_id == "0"){
            $update = "UPDATE customer SET Mailfrom_id='$numrow' WHERE Co_id='$Co_id'";
            if (!$conn->query($update) === TRUE) {
              echo "Error: " . $sql . "<br>" . $conn->error;
            } 
          }
          echo "1";
      } else {
        echo "Error updating record: " . $conn->error;
      }
?>
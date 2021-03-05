<?php
include("../include/Connect.php");
$userid = $_POST['userid'];
$state = $_POST['Unit'];
$sql = "SELECT code FROM reminder WHERE userid='$userid' AND state='$state'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
    // output data of each row
    $row = mysqli_fetch_assoc($result);
    $code = $row['code'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://notify-bot.line.me/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=authorization_code&code=' . $code . '&redirect_uri=https://reminderthaiweb.000webhostapp.com/Process/subscription.php&client_id=1Z7fhAlcpFRdHoZFsQDVQv&client_secret=8AAIMekpaIlKd1wY90W4fsrTys2I5ecnhUqzY0Qq3Tg',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $response = curl_exec($curl);
    $jsonde = json_decode($response, true);
    $tokenL = $jsonde['access_token'];
    // var_dump($jsonde);
    // $name =$_SESSION['NAME'];
    $sqlU = "UPDATE user SET TokenLine='$tokenL' WHERE userid='$userid'";
    mysqli_query($conn, $sqlU);
    curl_close($curl);
} else {
    echo "0 results";
}
$sqlD = "DELETE FROM reminder WHERE userid='$userid'";
if ($conn->query($sqlD) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  }
?>

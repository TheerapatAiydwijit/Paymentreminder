<?php
include("../include/Connect.php");
session_start();
$user = $_POST['user'];
$Password = $_POST['pass'];
$selectcheck = "SELECT * FROM user WHERE UserName='$user' AND Password ='$Password'";
$result = mysqli_query($conn, $selectcheck);
if ($result->num_rows > 0) {
    $resultS = mysqli_fetch_array($result);
    $userid = $resultS['userid'];
    $_SESSION['USERID'] = $userid;
    if ($_POST['cookie'] == "1") {
        setcookie('USERID',$userid, time() + (86400 * 30), "/");
    }
    echo "1";
} else {
    echo "0";
}

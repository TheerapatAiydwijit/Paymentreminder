<?php
include("../include/Connect.php");
session_start();
$user = mysqli_real_escape_string($conn, $_POST['user']);
$Password = mysqli_real_escape_string($conn, $_POST['pass']);
// $selectcheck = "SELECT * FROM user WHERE UserName='$user' AND Password ='$Password'";
$result = mysqli_query($conn, "SELECT * FROM user WHERE Username='$user'") or die("Error : " . mysqli_error($conn));
if ($result->num_rows > 0) {
    while ($allpass = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $pass = $allpass['Password'];
        if (password_verify($Password,$pass)) {
            $userid = $allpass['userid'];
            $_SESSION['USERID'] = $userid;
            if ($_POST['cookie'] == "1") {
                setcookie('USERID', $userid, time() + (86400 * 30), "/");
            }
            $return = array(
                "status" => "success",
                "Message" => "ล็อกอินเสร็ยจสิ้น"
            );
        }
    }
    if (!(isset($_SESSION['USERID']))) {
        $return = array(
            "status" => "error",
            "Message" => $Password
        );
    }
} else {
    $return = array(
        "status" => "error",
        "Message" => "ไม่พบผู้ใช้ในระบบ"
    );
}
$jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
echo $jsonreturn;

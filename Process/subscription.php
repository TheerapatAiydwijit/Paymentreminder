<?php
if (isset($_POST['code'])) {
    include("../include/Connect.php");
    $state = $_POST['state'];
    $code = $_POST['code'];
    $useridsql = "SELECT userid FROM reminder WHERE state='$state'";
    $useridquery = mysqli_query($conn, $useridsql);
    if ($useridquery) {
        $alldata = mysqli_fetch_array($useridquery);
        $userid = $alldata['userid'];
        gettoken($userid, $code);
    } else {
        echo "Error updating record: " . $conn->error;
    }
    // $sql = "UPDATE reminder SET code='$code' WHERE state='$state'";
    // mysqli_query($conn, $sql);
}
function gettoken($userid, $code)
{
    include("../include/Connect.php");
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
        CURLOPT_POSTFIELDS => 'grant_type=authorization_code&code=' . $code . '&redirect_uri=https://reminderthaiweb2.000webhostapp.com/Process/subscription.php&client_id=1Z7fhAlcpFRdHoZFsQDVQv&client_secret=8AAIMekpaIlKd1wY90W4fsrTys2I5ecnhUqzY0Qq3Tg',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $response = curl_exec($curl);
    if (curl_error($curl)) {
        echo 'error:' . curl_error($curl);
    } else {
        $jsonde = json_decode($response, true);
        $tokenL = $jsonde['access_token'];
        $sqlU = "UPDATE user SET TokenLine='$tokenL' WHERE userid='$userid'";
        if (mysqli_query($conn, $sqlU)) {
            curl_close($curl);
            $sqlD = "DELETE FROM reminder WHERE userid='$userid'";
            if ($conn->query($sqlD) === TRUE) {
                cared();
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }
}
function cared()
{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
        <style>
            body {
                text-align: center;
                padding: 40px 0;
                background: #EBF0F5;
            }

            h1 {
                color: #88B04B;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
            }

            p {
                color: #404F5E;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size: 20px;
                margin: 0;
            }

            i {
                color: #9ABC66;
                font-size: 100px;
                line-height: 200px;
                margin-left: -15px;
            }

            .card {
                background: white;
                padding: 60px;
                border-radius: 4px;
                box-shadow: 0 2px 3px #C8D0D8;
                display: inline-block;
                margin: 0 auto;
            }
        </style>
    </head>

    <body>
        <div class="card">
            <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
                <i class="checkmark">✓</i>
            </div>
            <h1>เชื่อมต่อสำเหร็ยจ</h1>
            <p>ยินดีต้อนรับสู่ระบบการแจ้งเตือนของ<br /> Paymentreminder<p>
        </div>
    </body>

    </html>
<?php } ?>
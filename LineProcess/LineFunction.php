<?php
// ต้งค่าข้อความที่จะส่ง
function getFormatTextMessage($text, $quickReply)
{
    $datas = [];
    $datas['type'] = 'text';
    $datas['text'] = $text;
    return $datas;
}
// เพิมQuickReplyให้กับข้อความ
function QuickReply()
{
    $datas = [];
    $datas['items'][0]['type'] = "action";
    $datas['items'][0]['imageUrl'] = "https://cdn1.iconfinder.com/data/icons/mix-color-3/502/Untitled-1-512.png";
    $datas['items'][0]['action'] = [
        "type" => "message",
        "label" => "Message",
        "text" => "ทดสอบQuickReply"
    ];
    return $datas;
}
// setflex message returned
function flexmessage($customer_all)
{ 
   $contents = contents($customer_all);
   $month_year = date('Y-m-01');
   $strYear = date("Y", strtotime($month_year)) + 543;
   $strMonth = date("n", strtotime($month_year . "+1 month"));
   $strMonthCut = array("","มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
   $strMonthThai = $strMonthCut[$strMonth];
   $month_year = "$strMonthThai $strYear";
   $datetime = date("Y-m-d H:i:s");
    $jayParsedAry = [
        "type" => "bubble",
        "body" => [
            "type" => "box",
            "layout" => "vertical",
            "contents" => [
                [
                    "type" => "text",
                    "text" => "รายชื่อลูกค้า",
                    "weight" => "bold",
                    "size" => "xxl",
                    "margin" => "md"
                ],
                [
                    "type" => "text",
                    "text" => $month_year,
                    "size" => "xs",
                    "color" => "#aaaaaa",
                    "wrap" => true
                ],
                [
                    "type" => "separator",
                    "margin" => "xxl"
                ],
                [
                    "type" => "box",
                    "layout" => "vertical",
                    "spacing" => "sm",
                    "contents" => $contents
                ],
                [
                    "type" => "box",
                    "layout" => "horizontal",
                    "margin" => "md",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "ลงวันที่",
                            "size" => "xs",
                            "color" => "#aaaaaa",
                            "flex" => 0
                        ],
                        [
                            "type" => "text",
                            "text" => $datetime,
                            "color" => "#aaaaaa",
                            "size" => "xs",
                            "align" => "end"
                        ]
                    ]
                ]
            ]
        ],
        "styles" => [
            "footer" => [
                "separator" => true
            ]
        ]
    ];
    $datas = [];
    $datas['type'] = 'flex';
    $datas['altText'] ="This is a Flex Message";
    $datas['contents'] = $jayParsedAry;
    return $datas;
}
// ตั้งค่าเนื้อหาให้กับ flex massage
function contents($customer_all){
    $return = [];
    foreach ($customer_all as $key => $val){
        $array = [
            "type" => "box",
            "layout" => "vertical",
            "margin" => "xxl",
            "spacing" => "sm",
            "contents" => [
                [
                    "type" => "box",
                    "layout" => "horizontal",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "วันที่: ".$val['Duedate'],
                            "size" => "sm",
                            "color" => "#555555",
                            "flex" => 0
                        ]
                    ]
                ],
                [
                    "type" => "box",
                    "layout" => "horizontal",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "โดเมน: ".$val['Domain'],
                            "size" => "sm",
                            "color" => "#555555",
                            "flex" => 0
                        ]
                    ]
                ],
                [
                    "type" => "box",
                    "layout" => "horizontal",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "บริษัท: ".$val['Company'],
                            "size" => "sm",
                            "color" => "#555555",
                            "flex" => 0
                        ]
                    ]
                ],
                [
                    "type" => "box",
                    "layout" => "horizontal",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "ยอดเงินที่ต้องชำระ",
                            "size" => "sm",
                            "color" => "#555555",
                            "flex" => 0
                        ],
                        [
                            "type" => "text",
                            "text" => $val['Rates']."฿",
                            "size" => "sm",
                            "color" => "#111111",
                            "align" => "end"
                        ]
                    ]
                ],
                [
                    "type" => "separator",
                    "margin" => "xxl"
                ]
            ]
                ];
                array_push($return,$array);
    }
    return $return;
}
// ส่งข้อความตอบกลับ
function responsemanage($Headers, $Body)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $Headers['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $Body,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "cache-control: no-cache",
            'Authorization: Bearer ' . $Headers['Authorization']
        ),
    ));

    $response = curl_exec($curl);
    file_put_contents('log.txt', $response . PHP_EOL, FILE_APPEND);
    if (curl_error($curl)) {
        file_put_contents('log.txt', curl_error($curl) . PHP_EOL, FILE_APPEND);
    } else {
        
    }
    curl_close($curl);
}
// ตรวจสอบการเข้าสู่ระบบ
function chekloginlines($userId)
{
    include("../include/Connect.php");
    // $sql = "SELECT * FROM user_line_control WHERE userLineID='$userId'";
    $querychek = mysqli_query($conn, "SELECT * FROM user_line_control WHERE userLineID='$userId'") or die("Error : " . mysqli_error($conn));
    if (mysqli_num_rows($querychek) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}
// ดึงข้อมูลที่จะใช้สำหรับmessaging-api
function getcurldata()
{
    include("../include/Connect.php");
    $emailstatus = "SELECT Username,Password FROM account WHERE account_order='3'";
    if ($quarystatus = mysqli_query($conn, $emailstatus)) {
        $fecthquarystatus = mysqli_fetch_array($quarystatus, MYSQLI_ASSOC);
        $return = array(
            "status" => "success",
            "Username" => $fecthquarystatus['Username'],
            "Password" => $fecthquarystatus['Password']
        );
    } else {
        $return = array(
            "status" => "error",
            "Message" => "ไม่พบข้อมูล messaging-apiสำหรับทำรายการ"
        );
    }
    return $return;
}

// ตั้งค่าRichMenuให้ผู้ใช้
function setrichmenu($curldata, $userId)
{
    include("../include/Connect.php");

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.line.me/v2/bot/richmenu/bulk/link',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
    "richMenuId":"' . $curldata['Password'] . '",
    "userIds":["' . $userId . '"]
  }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $curldata['Username'],
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    file_put_contents('log.txt', $curldata . PHP_EOL, FILE_APPEND);

    if (curl_error($curl)) {
        $return = array(
            "status" => "error",
            "Message" => "เกิดปัญหาเนื่องจาก" . curl_error($curl)
        );
    } else {
        $return = array(
            "status" => "success",
            "Message" => "เพิ่มrichenuให้ผู้ควบคุมเรียบร้อย"
        );
    }
    curl_close($curl);
    echo $response;
    return $return;
}
// ตรวจสอบชื่อผู้ใช้กับรหัสผ่าน
function cheklogin($username, $password)
{
    include("../include/Connect.php");
    // $sql = "SELECT userid,Username,Password FROM user WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username'") or die("Error : " . mysqli_error($conn));
    $chekloginnum = "0"; 
    if ($result->num_rows > 0) {
    while ($allpass = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $pass = $allpass['Password'];
        if (password_verify($Password,$pass)) {
            $userid = $alldata['userid'];
            $chekloginnum = "1"; 
            $return = array(
                "status" => "success",
                "data" => $userid,
                "Message" => "ชื่อผู้ใช้และรหัสผ่านถูกต้อง"
            );
        }
    }
    if ($chekloginnum == "0") {
        $return = array(
            "status" => "error",
            "Message" => "ชื่อผู้ใช้และรหัสผ่านไม่ถูกต้อง"
        );
    }
} else {
    $return = array(
        "status" => "error",
        "Message" => "ชื่อผู้ใช้และรหัสผ่านไม่ถูกต้อง"
    );
}
    return $return;
}
// เพิ่มข้อมูลไลย์ในดาต้าเบส
function insert_line_control($userid, $user_line_Id)
{
    include("../include/Connect.php");
    $sql = "INSERT INTO user_line_control(userid,userLineID)
    VALUE ('$userid','$user_line_Id')";
    // $qurey = mysqli_query($conn,"INSERT INTO user_line_control(userid,userLineID)VALUE('$userid','$user_line_Id')")or die("Error : " . mysqli_error($conn));
    if (mysqli_query($conn, $sql)) {
        $return = array(
            "status" => "success",
            "Message" => "เพิ่มไลย์ผู้ควบคุมเรียบร้อย"
        );
    } else {
        $return = array(
            "status" => "error",
            "Message" => mysqli_error($conn)
        );
    }
    return $return;
}

<?php
include("../include/Connect.php");
if ($_POST['key'] == "0") {
    $sql = "SELECT Co_id FROM customer";
    $qurey = mysqli_query($conn, $sql);
    $numcustomer = mysqli_num_rows($qurey);

    $month = date("Y-m-01",strtotime('+1 month'));
    $month = "-".onlymonth($month)."-";
    $sqlnumpay = "SELECT Co_id FROM customer WHERE Duedate LIKE '%$month%'";
    $qureynumpay = mysqli_query($conn, $sqlnumpay);
    $sqlnumpay = mysqli_num_rows($qureynumpay);

    $sqlpaymented = "SELECT status FROM payment";
    $qureypaymented = mysqli_query($conn, $sqlpaymented);
    $paymensuccess = 0;
    $paynosuccess = 0;
    while ($alltata = mysqli_fetch_array($qureypaymented, MYSQLI_ASSOC)) {
        if ($alltata['status'] == "0") {
            $paynosuccess = $paynosuccess + 1;
        } else {
            $paymensuccess = $paymensuccess + 1;
        }
    }
    $return = array(
        "numcustomer" => $numcustomer,
        "paymen" => $sqlnumpay,
        "paymensuccess" => $paymensuccess,
        "paynosuccess" => $paynosuccess
    );
    $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($_POST['key'] == "1") {
    $sql = "SELECT Co_id,Duedate FROM customer";
    $qurey = mysqli_query($conn, $sql);
    $m1 = 0;
    $m2 = 0;
    $m3 = 0;
    $m4 = 0;
    $m5 = 0;
    $m6 = 0;
    $m7 = 0;
    $m8 = 0;
    $m9 = 0;
    $m10 = 0;
    $m11 = 0;
    $m12 = 0;
    while ($allcus = mysqli_fetch_array($qurey, MYSQLI_ASSOC)) {
        $mont = onlymonth($allcus['Duedate']);
        switch ($mont) {
            case "01":
                $m1 += 1;
                break;
            case "02":
                $m2 += 1;
                break;
            case "03":
                $m3 += 1;
                break;
            case "04":
                $m4 += 1;
                break;
            case "05":
                $m5 += 1;
                break;
            case "06":
                $m6 += 1;
                break;
            case "07":
                $m7 += 1;
                break;
            case "08":
                $m8 += 1;
                break;
            case "09":
                $m9 += 1;
                break;
            case "10":
                $m10 += 1;
                break;
            case "11":
                $m11 += 1;
                break;
            case "12":
                $m12 += 1;
                break;
        }
    }
    $rows[] =  [
        'c' =>
        [
            ['v' => "ม.ค"],
            ['v' => $m1]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ก.พ"],
            ['v' => $m2]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "มี.ค"],
            ['v' => $m3]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "เม.ย"],
            ['v' => $m4]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "พ.ค"],
            ['v' => $m5]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "มิ.ย"],
            ['v' => $m6]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ก.ค"],
            ['v' => $m7]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ส.ค"],
            ['v' => $m8]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ก.ย"],
            ['v' => $m9]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ต.ค"],
            ['v' => $m10]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "พ.ย"],
            ['v' => $m11]
        ]
    ];
    $rows[] =  [
        'c' =>
        [
            ['v' => "ธ.ค"],
            ['v' => $m12]
        ]
    ];
    $data = [
        'cols' => [
            ['label' => "เดือน"],
            ['label' => "จำนวณลูกค้า", 'type' => 'number']
        ],
        'rows' => $rows
    ];
    $jsonreturn = json_encode($data, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($_POST['key'] == "2") { //ตัดการเชื่อมต่อกับLINE Notify
    $userid = $_POST['userid'];
    $sqltoken = "SELECT TokenLine FROM user WHERE userid='$userid'";
    $qureytoken = mysqli_query($conn, $sqltoken);
    $alldatatoken = mysqli_fetch_array($qureytoken);
    $linetoken = $alldatatoken['TokenLine'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://notify-api.line.me/api/revoke',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $linetoken
        ),
    ));
    $response = curl_exec($curl);
    if (curl_error($curl)) {
        echo 'error:' . curl_error($curl);
    } else {
        $uptokenline = "UPDATE user SET TokenLine='0' WHERE userid='$userid'";
        if ($conn->query($uptokenline) === TRUE) {
            echo "1";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    curl_close($curl);
    // echo $response;
} elseif ($_POST['key'] == "3") {
    $userid = $_POST['userid'];
    $Statusnotify = $_POST['status'];
    $statusset = "UPDATE user SET Statusnotify='$Statusnotify' WHERE userid='$userid'";
    if ($conn->query($statusset) === TRUE) {
        echo "1";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} elseif ($_POST['key'] == "4") {
    $status = $_POST['status'];
    $statusaccount = "UPDATE account SET status='$status' WHERE Typeacc='1'";
    if ($conn->query($statusaccount) === TRUE) {
        echo "1";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
function onlymonth($strDate)
{
    $strMonth = date("m", strtotime($strDate));
    return "$strMonth";
}

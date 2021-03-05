<?php
// session_start();
//     $month = $_POST['month'];
//     $_SESSION['month']=$month;
//     echo $_SESSION['month'];
$key = $_POST['key'];
include("../include/Frequently.php");
include("../include/Connect.php");
if ($key == "0") {
    $date = date('Y-m-01');
    $current = date("m", strtotime($date));
    $newyear = "false";
    if ($current == "12") {
        $newyear = "true";
    }
    $month = date("m", strtotime($date . "+1 month"));
    $monyhsc = "-" . $month . "-";

    $sqlsele = "SELECT customer.Co_id, customer.Domain, customer.Company, customer.Duedate, email.Mailfrom_id,email.Name_email, email.Mail_detail FROM customer INNER JOIN email ON customer.Mailfrom_id = email.Mailfrom_id WHERE Duedate LIKE '%$monyhsc%'";
    $result = mysqli_query($conn, $sqlsele) or die("Error : " . mysqli_error($conn));
    // $year = date("Y");
    if ($current == "12") {
        $year = date("Y", strtotime($date . "+1 year"));
    }else{
        $year = date("Y", strtotime($date));
    }
    $datare = array();
    while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Co_id = $data['Co_id'];
        $Company = $data['Company'];
        $Mailfrom_id = "จะทำการส่งโดยใช้" . $data['Name_email'];
        $strMonth = date("m", strtotime($data['Duedate']));

        $Check = "SELECT send_id FROM sendemail WHERE Co_id='$Co_id' AND Sendyear='$year' AND status='1'";
        $recheck = $conn->query($Check);
        $numbersedemail = mysqli_num_rows($recheck);
        $datenow = date("Y-m-d");
        $strYear = date("Y");
        if($current == "12"){
            $year = date("Y", strtotime($date . "+1 year"));
            $strYear = $year;
        }
        $strDay = date("d", strtotime($data['Duedate']));
        $Duedate = $strYear . "-" . $strMonth . "-" . $strDay;

        // $diff=date_diff($datenow,$Duedate);
        // $tfar = $diff->format("%R%a");
        // if ($tfar < 0) {
        //     $Duedate = difference($Duedate);
        // } else {
        //     $Duedate = DateThai($Duedate);
        // }
        $bil = "SELECT * FROM billing WHERE Co_id='$Co_id' AND Billdate LIKE '%$year%'";
        $billre = $conn->query($bil);
        $numbill = mysqli_num_rows($billre);

        $dataarray = array(
            "Co_id" => $Co_id,
            "sedemailst" => $numbersedemail,
            "detail" => array(
                "Co_id" => $Co_id,
                "Domain" => $data['Domain'],
                "Company" => $Company,
                "mailfrom" => $Mailfrom_id
            ),
            "Readiness" => array(
                "Duedate" => $Duedate,
                "numbill" => $numbill
            )
        );
        array_push($datare, $dataarray);
    }
    $return = array(
        "data" => $datare
    );
    $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($key == "1") {
    $month = "-" . $_POST['mont'] . "-";
    $sqlsele = "SELECT customer.Co_id, customer.Domain, customer.Company, customer.Duedate, email.Mailfrom_id,email.Name_email, email.Mail_detail FROM customer INNER JOIN email ON customer.Mailfrom_id = email.Mailfrom_id WHERE Duedate LIKE '%$month%'";
    $result = mysqli_query($conn, $sqlsele) or die("Error : " . mysqli_error($conn));
    $year = date("Y");
    $datare = array();

    while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Co_id = $data['Co_id'];
        $Company = $data['Company'];
        $Mailfrom_id = "จะทำการส่งโดยใช้" . $data['Name_email'];
        $strMonth = date("m", strtotime($data['Duedate']));

        $Check = "SELECT send_id FROM sendemail WHERE Co_id='$Co_id' AND Sendyear='$year' AND status='1'";
        $recheck = $conn->query($Check);
        $numbersedemail = mysqli_num_rows($recheck);
        $datenow = date("Y-m-d");
        $strYear = date("Y");
        $strDay = date("d", strtotime($data['Duedate']));
        $Duedate = $strYear . "-" . $strMonth . "-" . $strDay;
        if ($Duedate > $datenow) {
            $Duedate = difference($Duedate);
        } else {
            $Duedate = DateThai($Duedate);
        }
        $bil = "SELECT * FROM billing WHERE Co_id='$Co_id' AND Billdate LIKE '%$year%'";
        $billre = $conn->query($bil);
        $numbill = mysqli_num_rows($billre);

        $dataarray = array(
            "Co_id" => $Co_id,
            "sedemailst" => $numbersedemail,
            "detail" => array(
                "Co_id" => $Co_id,
                "Domain" => $data['Domain'],
                "Company" => $Company,
                "mailfrom" => $Mailfrom_id
            ),
            "Readiness" => array(
                "Duedate" => $Duedate,
                "numbill" => $numbill
            )
        );
        array_push($datare, $dataarray);
    }
    $return = array(
        "data" => $datare
    );
    $jsonreturn = json_encode($return, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
}

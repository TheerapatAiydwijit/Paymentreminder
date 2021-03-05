<?php
include '../include/Connect.php';
include("../include/Frequently.php");
if ($_POST['key'] == "0") {
    $month = "-" . $_POST['month'] . "-";
    $year = $_POST['year'];
    $MY = $year . $month;
    $sql = "SELECT * FROM payment WHERE date LIKE '%$MY%' ORDER BY date";
    $payment = mysqli_query($conn, $sql);
    $return = array();
    while ($datapayment = mysqli_fetch_array($payment, MYSQLI_ASSOC)) {
        // $statuspayment = "1";//ค่าที่จะส่งกลับ
        $payment_id = $datapayment['payment_id'];
        $Co_id = $datapayment['Co_id'];
        $status = $datapayment['status'];

        $sqlCo_id = "SELECT * FROM customer WHERE Co_id='$Co_id'";
        $sqlCo_idq = mysqli_query($conn, $sqlCo_id);
        $customer = mysqli_fetch_array($sqlCo_idq, MYSQLI_ASSOC); //ข้อมูลของบริษัท
        $Rates = $customer['Rates']; //จำนวณเงินที่ต้องชำระ

        $sqldetail = "SELECT * FROM payment_detail WHERE payment_id='$payment_id'";
        $sqldetailq = mysqli_query($conn, $sqldetail);
        $paysuss = mysqli_num_rows($sqldetailq); //จำนวณแถวรายละเอียดการจ่ายเงิน
        $Ratesperiod = $Rates / $paysuss; //ยอดเงินที่ต้องชำระต่องวด
        $numremain = 0;
        while ($money = mysqli_fetch_array($sqldetailq, MYSQLI_ASSOC)) {
            $slipname = $money['Slipname'];
            if ($slipname == '0') {
                $numremain = $numremain + 1;
                //    $statuspayment = "0";
            }
        }
        $remain = $Rates - ($numremain * $Ratesperiod); //จำนวณที่ชำระเสร็ยจสิ้น
        $arrayre = array(
            "Co_id" => $Co_id,
            "Company" => $customer['Company'],
            "Domain" => $customer['Domain'],
            "Rates" => $Rates,
            "remain" => $remain,
            "payment_id" => $payment_id,
            "payment_date" => DateThai($datapayment['date']),
            "paymentstatus" => $status
        );
        array_push($return, $arrayre);
    }
    $returnfull = array(
        "data" => $return
    );
    $jsonreturn = json_encode($returnfull, JSON_UNESCAPED_UNICODE);
    echo $jsonreturn;
} elseif ($_POST['key'] == "1") {
    $payment_id = $_POST['pay_id'];
    $sqlcusid = "SELECT payment.Co_id,customer.Company FROM payment INNER JOIN customer ON payment.Co_id = customer.Co_id";
    $quary = mysqli_query($conn,$sqlcusid)or die("Error : " . mysqli_error($conn));
    $data = mysqli_fetch_array($quary,MYSQLI_ASSOC);
    $Company = $data['Company'];
    $sqlimg = "SELECT * FROM payment_detail WHERE payment_id='$payment_id'";
    $resultIMG = mysqli_query($conn, $sqlimg) or die("Error : " . mysqli_error($conn));
    while ($row = $resultIMG->fetch_array()) {
        $imgname = $row['Slipname'];
        $base = "../slip/$Company/$imgname";
        $chek = file_exists($base);
        if($chek == true){
           echo unlink($base);
        }
    }
    $sqldelete_detail = "DELETE FROM payment_detail WHERE payment_id='$payment_id'";
    $sqldelete = "DELETE FROM payment WHERE payment_id='$payment_id'";
    $resultIMG = mysqli_query($conn, $sqldelete_detail) or die("Error : " . mysqli_error($conn));
    $resultIMG = mysqli_query($conn, $sqldelete) or die("Error : " . mysqli_error($conn));
    echo "1";
    // rmdir("../uploads/$FGname/");
}

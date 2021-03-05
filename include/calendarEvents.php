<?php 
    include("include/Connect.php");
    $getAlertdate = "SELECT * FROM customer";
    $quregetAlertdate = mysqli_query($conn,$getAlertdate);
    $arraycustomer = array();
    while($datacustomer = mysqli_fetch_array($quregetAlertdate,MYSQLI_ASSOC)){
        // $Co_id = $datacustomer['Co_id'];
        // $Company =$datacustomer['Company'];
        // $Domain = $datacustomer['Domain'];
        // $Rates = $datacustomer['Rates'];
        // $Stardate = $datacustomer['Stardate'];
        // $Duedate = $datacustomer['Duedate'];
        // $Co_number = $datacustomer['Co_number'];
        // $Email = $datacustomer['Email'];
        // $Address = $datacustomer['Address'];
        // $Line = $datacustomer['Line'];
        // $Details = $datacustomer['Details'];
        // $Mailfrom_id =$datacustomer['Mailfrom_id'];
        $customer = array("id"=>$datacustomer['Co_id'],
                               "name"=>$datacustomer['Company'],
                               "description"=>"โดเมน ".$datacustomer['Domain']."จำนวณเงิน ".$datacustomer['Rates']."บาท",
                               "date"=>$datacustomer['Duedate'],
                               "type"=>"holiday",
                               "color"=>"#922B21",
                               "everyYear"=>"TRUE");
        array_push($arraycustomer,$customer);
    }
    $jsonreturn = json_encode($arraycustomer, JSON_UNESCAPED_UNICODE);
    // echo $jsonreturn;
?>

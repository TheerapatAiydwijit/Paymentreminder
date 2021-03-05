<?php
    include("../include/Connect.php");
    if(isset($_GET['term'])){
        $term = $_GET['term'];
        $sql = "SELECT * FROM customer WHERE Domain LIKE '%".$term."%' OR Company LIKE '%".$term."%'";
        $quary = mysqli_query($conn,$sql) or die("Error : ". mysqli_error($conn));
        $pagination = array(
                            "more"=>"false"
        );
        $results = array();
        $i = 0;
        $numrow = mysqli_num_rows($quary);
        $montyear = date("Y-");
        while($data = mysqli_fetch_array($quary,MYSQLI_ASSOC)){
            $Co_id = $data['Co_id'];
            $sql = "SELECT payment_id FROM payment WHERE Co_id='$Co_id' AND date LIKE '%$montyear%'";
            $quarychek = mysqli_query($conn,$sql) or die("Error : ". mysqli_error($conn));
            if($quarychek ->num_rows > 0 ){
                continue;
            }
            $strYear = date("Y")-1;
            $montyear =$strYear."-";
            $sql = "SELECT payment_id FROM payment WHERE Co_id='$Co_id' AND date LIKE '%$montyear%'";
            $quarychek = mysqli_query($conn,$sql) or die("Error : ". mysqli_error($conn));
            $Lyear = 0;
            if($quarychek ->num_rows > 0 ){
                $paymentdetail = mysqli_fetch_array($quarychek,MYSQLI_ASSOC);
                $payment_id = $paymentdetail['payment_id'];
                $sqlorder = "SELECT order_detail FROM payment_detail WHERE payment_id='$payment_id'";
                $order = mysqli_query($conn,$sqlorder) or die("Error : ". mysqli_error($conn));
                $Lyear = mysqli_num_rows($order);
            }
            // if($montyear == $numrow){
            //     break;
            // }
            $results[$i] = array(
                                'id'=>$Co_id,
                                'text'=>$data['Domain'],
                                'comname'=>$data['Company'],
                                'Rates'=>$data['Rates'],
                                'Duedate'=>cutyear($data['Duedate']),
                                'Lyear'=>$Lyear
            );
            $i++;
            // print_r($results);
        }
        $returnarray =array(
                            "results"=>$results,
                            "pagination"=>$pagination
        );
        // array_push($returnarray, $results,$pagination);
        $jsonreturn = json_encode($returnarray, JSON_UNESCAPED_UNICODE);
        echo $jsonreturn;
    }
    function cutyear($strDate)
{
    $date = date('Y-m-01');
    $current = date("m", strtotime($date));
    $strYear = date("Y");
    if ($current == "12") {
        $strYear = $strYear+1;
    }
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    return "$strYear-$strMonth-$strDay";
}
// function onlyyear($strDate)
// {
//     $strYear = date("Y");
//     $strMonth = date("m", strtotime($strDate));
//     $strDay = date("d", strtotime($strDate));
//     return "$strYear-";
// }
?>
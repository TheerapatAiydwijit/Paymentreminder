<?php
include("../include/Connect.php");
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");
	$sql = "SELECT TokenLine FROM user WHERE Level='9'";
	$TokenLine = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($TokenLine,MYSQLI_ASSOC)){
		$sToken = $row['TokenLine'];
	}
	$sMessage ="ทดสอบ";
	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$sMessage&stickerPackageId=2&stickerId=24"); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Beare '.$sToken.'', );
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 

	//Result error 
	if(curl_error($chOne)) 
	{ 
		echo 'error:' . curl_error($chOne); 
	} 
	else { 
		$result_ = json_decode($result, true); 
		// echo $result;
		echo $result_['message'];
		// echo "status : ".$result_['status']; echo "message : ". $result_['message'];
	} 
	curl_close( $chOne );   
	print_r($a);
?>